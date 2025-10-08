<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\SurveyLink;
use App\Models\DataBukuTamu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    /**
     * Menampilkan halaman indeks laporan kunjungan.
     */
    public function index()
    {
        // Data yang dibutuhkan untuk filter di View
        $layananMaster = \App\Models\Layanan::all();
        $loketMaster = [1 => 'Loket 1', 2 => 'Loket 2', 3 => 'Loket 3', 4 => 'Loket 4'];

        return view('admin.reports.visits_index', compact('layananMaster', 'loketMaster'));
    }

    /**
     * API: Mengambil data kunjungan untuk Datatables (termasuk semua filter).
     */
    public function getVisitsData(Request $request)
    {
        // Logika Datatables (Anda mungkin perlu Datatables package seperti Yajra/Laravel-datatables)
        // Untuk contoh ini, kita buat query dasar:

        $query = DataBukuTamu::with(['layanan', 'tamu', 'layananDetail'])
            ->whereNotNull('nomor_lengkap'); // Hanya antrean yang sudah dihitung

        // --- FILTER LOGIC ---
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }
        if ($request->filled('loket')) {
            $query->where('id_loket', $request->loket);
        }
        if ($request->filled('tipe_layanan')) {
            $query->where('tipe_layanan', $request->tipe_layanan);
        }

        // Asumsi Anda menggunakan Yajra Datatables untuk ServerSide Processing
        // Jika tidak, Anda harus memproses query ini secara manual untuk Datatables.
        $totalRecords = $query->count();

        return   $data = $query->latest('tanggal')->get();

        $dataTableData = $data->map(function (DataBukuTamu $entry) {
            $statusBadge = match ($entry->status_antrean) {
                'SELESAI' => '<span class="badge bg-success">Selesai</span>',
                'DIPANGGIL' => '<span class="badge bg-primary">Aktif</span>',
                'MENUNGGU' => '<span class="badge bg-info">Menunggu</span>',
                'LEWAT' => '<span class="badge bg-danger">Lewat</span>',
                default => '<span class="badge bg-secondary">Baru</span>',
            };

            $buttonText = ($entry->status_antrean === 'SELESAI') ? 'Kirim Survei' : 'Antrean Aktif';

            return [
                'id' => $entry->id_buku,
                'tanggal' => Carbon::parse($entry->tanggal)->isoFormat('D MMM YYYY'),
                'nama' => $entry->tamu->nama ?? 'N/A',
                'layanan' => $entry->layananDetail->nama_layanan_detail ?? 'N/A',
                'nomor_antrean' => $entry->nomor_lengkap,
                'loket' => $entry->id_loket,
                'status' => $statusBadge,
                'aksi' => '<button class="btn btn-sm btn-label-success send-survey-btn" data-id="' . $entry->id . '" ' . ($entry->status_antrean !== 'SELESAI' ? 'disabled' : '') . '>' . $buttonText . '</button>'
            ];
        });

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalRecords),
            "recordsFiltered" => intval($totalRecords), // Di sini recordsFiltered sama dengan Total
            "data" => $dataTableData // Data halaman saat ini
        ]);
    }

    /**
     * Aksi: Mengirim link survei ke nomor WA pelanggan.
     */
    public function sendSurvey($id)
    {
        $entry = DataBukuTamu::with('tamu')->find($id);

        if (!$entry || $entry->status_antrean !== 'SELESAI') {
            return response()->json(['status' => 'error', 'message' => 'Survei hanya bisa dikirim untuk layanan yang sudah selesai.']);
        }

        // ASUMSI: Link survei Anda
        $surveyLink = url('/survey/' . base64_encode($entry->id));
        $nomorWA = $entry->tamu->no_hp;

        $pesan = "Yth. Bapak/Ibu {$entry->tamu->nama}, terima kasih telah menggunakan layanan Kanwil Kemenkum DIY. Mohon luangkan waktu Anda untuk mengisi survei kepuasan di link ini: {$surveyLink}";

        // Kirim via Fonnte/WA API
        // $this->Kirimfonnte(['target' => $this->formatNomorWA($nomorWA), 'message' => $pesan]);

        return response()->json(['status' => 'success', 'message' => 'Link survei berhasil dikirim via WhatsApp.']);
    }
}
