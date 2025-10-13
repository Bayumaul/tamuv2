<?php

namespace App\Http\Controllers;

use App\Models\DataBukuTamu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Layanan;
use Carbon\Carbon;

class AdminController extends Controller
{

    public function showRecapForm()
    {
        // Untuk tampilan filter/opsi
        $currentDate = Carbon::now()->isoFormat('D MMMM YYYY');
        return view('admin.notifications.send_report', compact('currentDate'));
    }


    /**
     * API: Menghitung rekapitulasi dan mengirimkannya ke nomor tujuan.
     */
    public function sendRecapReport(Request $request)
    {
        $request->validate([
            'periode' => 'required|in:daily,monthly',
            'target_phone' => ['required', 'regex:/^08\d{8,11}$/'],
        ]);

        $periode = $request->periode;
        $targetPhone = formatNomorWA($request->target_phone);

        $startDate = ($periode === 'daily') ? Carbon::today() : Carbon::now()->startOfMonth();
        $endDate = ($periode === 'daily') ? Carbon::today()->endOfDay() : Carbon::now()->endOfMonth();

        $periodeText = ($periode === 'daily')
            ? "Laporan Harian Tanggal " . $startDate->isoFormat('D MMMM YYYY')
            : "Laporan Bulanan Periode " . $startDate->isoFormat('MMMM YYYY');

        // --- TENTUKAN ID LAYANAN KRITIS (Asumsi ID 1=AHU, 2=KI) ---
        // Sebaiknya cari dari database Master Layanan jika nama kodenya 'AHU' dan 'KI'
        $idAhu = Layanan::where('kode_layanan', 'AHU')->value('id_layanan');
        $idKi = Layanan::where('kode_layanan', 'KI')->value('id_layanan');

        $dateRange = [$startDate->toDateString(), $endDate->toDateString()];


        // --- 1. PENGAMBILAN DATA REKAP TOTAL (Sudah ada di jawaban sebelumnya) ---
        $stats = DataBukuTamu::select(
            DB::raw('COUNT(*) as total_pendaftaran'),
            DB::raw('SUM(CASE WHEN status_antrean = "SELESAI" THEN 1 ELSE 0 END) as selesai'),
            DB::raw('SUM(CASE WHEN tipe_layanan = "Online" THEN 1 ELSE 0 END) as online'),
            DB::raw('SUM(CASE WHEN tipe_layanan = "Offline" THEN 1 ELSE 0 END) as offline')
        )
            ->whereBetween('tanggal', $dateRange)
            ->first();

        $totalPendaftaran = $stats->total_pendaftaran;
        $tingkatKehadiran = ($totalPendaftaran > 0)
            ? round(($stats->selesai / $totalPendaftaran) * 100, 1)
            : 0;


        // --- 2. PENGAMBILAN DATA SPESIFIK AHU dan KI ---
        $layananStats = DataBukuTamu::select(
            'id_layanan',
            DB::raw('COUNT(*) as count')
        )
            ->whereBetween('tanggal', $dateRange)
            ->whereIn('id_layanan', [$idAhu, $idKi])
            ->groupBy('id_layanan')
            ->pluck('count', 'id_layanan'); // Mengubah hasil menjadi [id_layanan => count]

        $countAhu = $layananStats[$idAhu] ?? 0;
        $countKi = $layananStats[$idKi] ?? 0;


        // --- 3. FORMAT PESAN WHATSAPP (MODIFIKASI) ---
        $message = "*REKAP PELAYANAN KANWIL KEMENKUM DIY*\n";
        $message .= "==============================\n";
        $message .= "*$periodeText*\n\n";

        $message .= "• Total Pendaftaran: *$totalPendaftaran*\n";
        $message .= "• Layanan Selesai: *{$stats->selesai}* \n";
        $message .= "• Tingkat Kehadiran: *{$tingkatKehadiran}%*\n\n";

        $message .= "*Rincian Layanan Utama:*\n";
        $message .= "• Administrasi Hukum Umum (AHU): *$countAhu*\n";
        $message .= "• Kekayaan Intelektual (KI): *$countKi*\n\n";

        $message .= "Rincian Tipe Pendaftaran:\n";
        $message .= "• Online: *{$stats->online}*\n";
        $message .= "• Offline: *{$stats->offline}*\n\n";

        $message .= "Laporan detail dapat dilihat di Dashboard Admin.\n";
        $message .= "Terima kasih.\n_Sistem Otomatis_";


        // --- 4. KIRIM VIA WA ---
        Kirimfonnte($targetPhone, $message);


        return redirect()->back()->with('success', "Laporan {$periodeText} berhasil dikirim ke nomor {$request->target_phone}.");
    }
}
