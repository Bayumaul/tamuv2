<?php

namespace App\Http\Controllers;

use App\Models\DataBukuTamu;
use Illuminate\Http\Request;
use App\Models\QueueEntry;
use Carbon\Carbon;

class ApiController extends Controller
{
    // ... (metode getPersonalStatus jika Anda menempatkannya di sini)

    /**
     * API: Mengambil nomor antrean yang sedang DIPANGGIL di setiap loket (1-4).
     */
    public function getLoketStatus()
    {
        $today = Carbon::today()->toDateString();
        $loketStatus = [];

        // Ambil semua antrean yang sedang DIPANGGIL hari ini
        $activeCalls = DataBukuTamu::where('tanggal', $today)
            ->where('status_antrean', 'DIPANGGIL')
            ->orderBy('id_buku', 'desc')
            ->get(['id_loket', 'nomor_lengkap']);

        // Inisiasi status IDLE
        for ($i = 1; $i <= 4; $i++) {
            $loketStatus[$i] = 'IDLE';
        }

        // Overwrite status dengan nomor antrean aktif
        // Menggunakan foreach memastikan kita mengambil yang terbaru (walau sudah di-order)
        foreach ($activeCalls as $call) {
            // Karena kita hanya butuh 1 per loket, yang terbaru akan disimpan
            if ($loketStatus[$call->id_loket] === 'IDLE') {
                $loketStatus[$call->id_loket] = $call->nomor_lengkap;
            }
        }

        return response()->json($loketStatus);
    }

    public function getLastActiveCall()
    {
        $today = Carbon::today()->toDateString();

        $lastCall = DataBukuTamu::where('tanggal', $today)
            // Cari yang statusnya sudah diproses (DIPANGGIL atau SELESAI)
            ->whereIn('status_antrean', ['DIPANGGIL', 'SELESAI'])
            ->select('nomor_lengkap', 'id_loket')
            ->orderBy('antrian', 'desc') // Ambil nomor urut tertinggi
            ->first();

        if ($lastCall) {
            return response()->json([
                'status' => 'success',
                'nomor' => $lastCall->nomor_lengkap,
                'loket' => $lastCall->id_loket,
            ]);
        }

        return response()->json(['status' => 'none', 'nomor' => '---']);
    }
}
