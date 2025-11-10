<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\SurveyLink;
use App\Models\DataBukuTamu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;

class ReportController extends Controller
{
    /**
     * Menampilkan halaman indeks laporan kunjungan.
     */
    public function index()
    {
        // Data yang dibutuhkan untuk filter di View
        $layananMaster = \App\Models\Layanan::all();
        $loketMaster = [1 => 'Loket 1', 2 => 'Loket 2'];

        $SurveyLinks = SurveyLink::where('is_active', true)->get();

        return view('admin.reports.visits_index', compact('layananMaster', 'loketMaster', 'SurveyLinks'));
    }

    /**
     * API: Mengambil data kunjungan untuk Datatables (termasuk semua filter).
     */
    public function getVisitsData(Request $request)
    {
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

        $data = $query->latest('tanggal')->get();
        $dataTableData = $data->map(function (DataBukuTamu $entry) {
            // === Ganti match status_antrean ===
            switch ($entry->status_antrean) {
                case 'SELESAI':
                    $statusBadge = '<span class="badge bg-success">Selesai</span>';
                    break;
                case 'DIPANGGIL':
                    $statusBadge = '<span class="badge bg-primary">Aktif</span>';
                    break;
                case 'MENUNGGU':
                    $statusBadge = '<span class="badge bg-info">Menunggu</span>';
                    break;
                case 'LEWAT':
                    $statusBadge = '<span class="badge bg-danger">Lewat</span>';
                    break;
                default:
                    $statusBadge = '<span class="badge bg-secondary">Baru</span>';
                    break;
            }

            // === Ganti match tipe_layanan ===
            switch ($entry->tipe_layanan) {
                case 'Online':
                    $tipeBadge = '<span class="badge bg-info">Online</span>';
                    break;
                case 'Offline':
                    $tipeBadge = '<span class="badge bg-primary">Offline</span>';
                    break;
                default:
                    $tipeBadge = '<span class="badge bg-secondary">--</span>';
                    break;
            }

            $buttonText = ($entry->status_antrean === 'SELESAI') ? 'Kirim Survei' : 'Antrean Aktif';

            return [
                'id' => $entry->id_buku,
                'tanggal' => Carbon::parse($entry->tanggal)->isoFormat('D MMM YYYY'),
                'nama' => $entry->tamu->nama ?? 'N/A',
                'layanan' => $entry->layananDetail->nama_layanan_detail ?? 'N/A',
                'nomor_antrean' => $entry->nomor_lengkap,
                'loket' => $entry->id_loket,
                'tipe_layanan' => $tipeBadge,
                'status' => $statusBadge,
                'aksi' => '<button class="btn btn-sm btn-label-success send-survey-btn" data-id="' . $entry->id_buku . '" ' . ($entry->status_antrean !== 'SELESAI' ? 'disabled' : '') . '>' . $buttonText . '</button>'
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
    public function sendSurvey(Request $request, $id)
    {
        $templateId = $request->input('template_id'); // KUNCI: ID template dari Modal

        // Menggunakan Model QueueEntry (Pengganti DataBukuTamu)
        $entry = DataBukuTamu::with('tamu')->where('id_buku', $id)->first();
        $template = SurveyLink::find($templateId);

        // --- 1. VALIDASI STATUS DAN TEMPLATE ---
        if (!$entry || $entry->status_antrean !== 'SELESAI') {
            return response()->json(['status' => 'error', 'message' => 'Survei hanya bisa dikirim setelah layanan Selesai.'], 403);
        }
        if (!$template || !$template->is_active) {
            return response()->json(['status' => 'error', 'message' => 'Template survei tidak valid atau tidak aktif.'], 400);
        }

        // --- 2. PERSIAPAN DATA PESAN ---
        $surveyLink = $template->link_url; // Ambil URL survei dari template
        $nomorWA = $this->formatNomorWA($entry->tamu->no_hp);
        $namaTamu = $entry->tamu->nama;

        // --- 4. KIRIM VIA WA ---
        Kirimfonnte($nomorWA, $template->caption);

        return response()->json([
            'status' => 'success',
            'message' => "Survei '{$template->name}' berhasil dikirim via WhatsApp."
        ]);
    }


    private function formatNomorWA($nomor)
    {
        $nomor = trim($nomor);
        if (str_starts_with($nomor, '0')) {
            return '62' . substr($nomor, 1);
        }
        if (str_starts_with($nomor, '+62')) {
            return substr($nomor, 1);
        }
        return $nomor;
    }

    public function generatePdfReport(Request $request)
    {
        // 1. Ambil Data Filter
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $loketId = $request->input('loket');

        $query = DataBukuTamu::with(['tamu', 'layananDetail.layanan', 'lastDisplayCall'])
            ->whereNotNull('nomor_lengkap');

        // --- Terapkan Filter ---
        if ($startDate) {
            $query->whereDate('tanggal', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('tanggal', '<=', $endDate);
        }
        if ($loketId) {
            $query->where('id_loket', $loketId);
        }
        // ... (Tambahkan filter lain sesuai kebutuhan) ...

        $reports = $query->orderBy('tanggal', 'asc')->get();

        // 2. Persiapan Data untuk View
        $judul = "Rekap Kunjungan Kanwil Kemenkum DIY";
        $periode = "Periode: " . ($startDate ? Carbon::parse($startDate)->isoFormat('D MMM YYYY') : 'Awal') .
            " s.d. " . ($endDate ? Carbon::parse($endDate)->isoFormat('D MMM YYYY') : 'Sekarang');

        $loketMaster = [1 => 'Loket 1', 2 => 'Loket 2', 3 => 'Loket 3', 4 => 'Loket 4'];


        $reports = $reports->map(function ($item) {
            // KUNCI: Lakukan semua format Carbon di Controller!
            $item->formatted_tanggal = Carbon::parse($item->tanggal)->format('d/m/Y');
            $item->waktu_panggil_format = ($item->lastDisplayCall && $item->lastDisplayCall->waktu_request)
                ? Carbon::parse($item->lastDisplayCall->waktu_request)->format('H:i:s')
                : '-';
            return $item;
        });

        // 3. Muat View dan Generate PDF
        $pdf = PDF::loadView('admin.reports.pdf_template', compact('reports', 'judul', 'periode', 'loketMaster'))
            ->setPaper('A4', 'portrait'); // Menggunakan kertas A4 portrait
        // return $reports;
        $filename = 'Laporan_Kunjungan_' . Carbon::now()->format('Ymd_His') . '.pdf';

        return $pdf->stream($filename);
    }
}
