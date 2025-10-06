<?php

namespace App\Http\Controllers;

use App\Models\DataBukuTamu;
use Illuminate\Http\Request;
use App\Models\DisplayQueue;
use App\Models\QueueEntry;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MonitorController extends Controller
{
    /**
     * Menampilkan View Blade untuk Monitor TV
     */
    public function showDisplay()
    {
        return view('monitor.monitor_display');
    }
    public function showDisplayPublic()
    {
        return view('monitor.public_monitor');
    }

    /**
     * API: Mengambil panggilan antrean NEW dan mengembalikannya ke View (AJAX Polling)
     */
    public function processDisplay(Request $request)
    {
        // Jika request adalah POST, ini adalah sinyal dari client untuk menandai panggilan selesai
        if ($request->isMethod('post')) {
            $action = $request->input('action');
            $queueId = $request->input('queue_id');

            if ($action === 'mark_announced' && $queueId) {
                // Tandai panggilan sebagai ANNOUNCED
                DisplayQueue::where('id', $queueId)->update(['status_panggil' => 'ANNOUNCED']);
                return response()->json(['status' => 'success', 'message' => 'Announced']);
            }
        }

        // --- LOGIKA GET (POLLING) ---

        // Mencari panggilan paling awal yang statusnya 'NEW'
        $callData = DisplayQueue::where('status_panggil', 'NEW')
            ->orderBy('waktu_request', 'asc')
            ->select('id', 'id_buku', 'loket_tujuan')
            ->first();
        // return $callData;
        if ($callData) {
            // Ambil data lengkap nomor dan kode layanan dari QueueEntry
            $entry = DataBukuTamu::where('id_buku', $callData->id_buku)
                ->with('layanan:id_layanan,kode_layanan') // Ambil kode layanan saja
                ->first(['id_layanan', 'nomor_lengkap']);

            if ($entry) {
                return response()->json([
                    'status' => 'new_call',
                    'data' => [
                        'id' => $callData->id, // ID dari display_queue
                        'id_buku' => $callData->id_buku,
                        'loket_pemanggil' => $callData->loket_tujuan,
                        'nomor_lengkap' => $entry->nomor_lengkap,
                        'kode_layanan' => $entry->layanan->kode_layanan,
                    ]
                ]);
            }
        }

        return response()->json(['status' => 'no_call']);
    }
}
