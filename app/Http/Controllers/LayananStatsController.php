<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DataBukuTamu;
use Illuminate\Http\Request;
use App\Models\Layanan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LayananStatsController extends Controller
{
    // ID Layanan AHU dan KI (Asumsikan sudah diketahui dari tabel 'layanan')
    // Ganti ini dengan ID yang sebenarnya dari database Anda!
    const ID_AHU = 1;
    const ID_KI = 2;

    public function index()
    {
        return view('dashboard.layanan_stats');
    }

    /**
     * API: Mengambil semua data statistik yang diperlukan untuk grafik KI dan AHU.
     * Route: api.stats.layanan
     */
    public function getLayananData(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);

        $stats = [];

        // --- 1. Tren Bulanan AHU vs KI (Bar Chart) ---
        // Jumlah antrean per bulan untuk AHU dan KI di tahun ini
        $monthlyTrend = DataBukuTamu::select(
            DB::raw('MONTH(tanggal) as month'),
            DB::raw('SUM(CASE WHEN id_layanan = ' . self::ID_AHU . ' THEN 1 ELSE 0 END) as ahu_count'),
            DB::raw('SUM(CASE WHEN id_layanan = ' . self::ID_KI . ' THEN 1 ELSE 0 END) as ki_count')
        )
            ->whereYear('tanggal', $year)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $stats['monthly_trend'] = $monthlyTrend;

        // --- 2. Distribusi Status Antrean Hari Ini (Donut Chart) ---
        // Status: SELESAI, DIPANGGIL, MENUNGGU untuk AHU dan KI hari ini
        $dailyStatus = DataBukuTamu::select('status_antrean', DB::raw('COUNT(*) as count'))
            ->whereDate('tanggal', Carbon::today())
            ->whereIn('id_layanan', [self::ID_AHU, self::ID_KI])
            ->groupBy('status_antrean')
            ->get();

        $stats['daily_status'] = $dailyStatus;

        // --- 3. Perbandingan Tipe Layanan AHU vs KI (Pie Chart) ---
        // Perbandingan Online vs Offline
        $typeComparison = DataBukuTamu::select('tipe_layanan', 'id_layanan', DB::raw('COUNT(*) as count'))
            ->whereYear('tanggal', $year)
            ->whereIn('id_layanan', [self::ID_AHU, self::ID_KI])
            ->groupBy('tipe_layanan', 'id_layanan')
            ->get();

        $stats['type_comparison'] = $typeComparison;

        // ... (Tambahkan metrik lain di sini jika dibutuhkan) ...

        return response()->json(['status' => 'success', 'data' => $stats]);
    }
}
