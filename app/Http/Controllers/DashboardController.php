<?php

namespace App\Http\Controllers;

use Log;
use Carbon\Carbon;
use App\Models\DataBukuTamu;
use App\Models\DisplayQueue;
use Illuminate\Http\Request;
use App\Models\LayananDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function call()
    {
        $loketId = loket_user();
        $namaLoket = 'Loket ' . $loketId;

        return view('dashboard.call', compact('loketId', 'namaLoket'));
    }


    // --- API Status Real-time (Untuk AJAX Polling di Dashboard) ---
    /**
     * API: Mengambil status antrean real-time untuk loket yang sedang login.
     */
    public function getQueueStatus(Request $request)
    {
        // Mengambil ID Loket dari sesi pengguna yang login
        $loketId = loket_user();
        $today = Carbon::today()->toDateString();
        // 1. Ambil Antrean Sedang DIPANGGIL
        $currentCall = DataBukuTamu::where('tanggal', $today)
            ->where('id_loket', $loketId)
            ->where('status_antrean', 'DIPANGGIL')
            ->orderBy('id_buku', 'desc')
            ->first();

        // 2. Ambil 5 Antrean MENUNGGU Berikutnya
        $waitingList = DataBukuTamu::where('tanggal', $today)
            ->where('id_loket', $loketId)
            ->where('status_antrean', 'MENUNGGU')
            ->orWhere('status_antrean', 'LEWAT')
            ->orderBy('antrian', 'asc')
            ->limit(5)
            ->get();
        // return $waitingList;

        return response()->json([
            'status' => 'success',
            'current' => $currentCall,
            'waiting' => $waitingList,
        ]);
    }

    // --- Aksi Panggilan Kritis (Tombol "Panggil Berikutnya") ---
    /**
     * Aksi: Memanggil antrean berikutnya dan memasukkannya ke Display Queue.
     */
    public function callNext(Request $request)
    {
        $loketId = loket_user();
        $today = Carbon::today()->toDateString();

        DB::beginTransaction();
        try {
        // 1. CARI & KUNCI ANTREAM BERIKUTNYA (MENUNGGU)
        // Menggunakan lockForUpdate() untuk mencegah bentrok/perebutan antrean
        $nextEntry = DataBukuTamu::where('id_loket', $loketId)
            ->where('status_antrean', 'MENUNGGU')
            ->where('tanggal', $today)
            ->orderBy('antrian', 'asc')
            ->lockForUpdate()
            ->first();

        if (!$nextEntry) {
            DB::rollBack();
            return response()->json(['status' => 'no_queue', 'message' => 'Tidak ada antrean menunggu untuk dipanggil.']);
        }

        // 2. UPDATE STATUS ANTREAM menjadi 'DIPANGGIL'
        $nextEntry->status_antrean = 'DIPANGGIL';
        // $nextEntry->waktu_panggil = Carbon::now();
        $nextEntry->save();

        // 3. MASUKKAN ke ANTREAM PANGGILAN PUSAT (display_queue)
        DisplayQueue::create([
            'id_buku' => $nextEntry->id_buku,
            'loket_tujuan' => $loketId,
            'status_panggil' => 'NEW',
            'waktu_request' => Carbon::now(),
        ]);

        DB::commit();

        return response()->json([
            'status' => 'success',
            'nomor' => $nextEntry->nomor_lengkap,
            'id_buku' => $nextEntry->id
        ]);
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error("Call Next Gagal: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal memproses panggilan karena error server.']);
        }
    }

    // --- Aksi Menyelesaikan Layanan ---
    /**
     * Aksi: Menyelesaikan layanan antrean yang sedang aktif.
     */
    public function completeService(Request $request)
    {
        $loketId = auth()->user()->id_loket;
        $idBuku = $request->input('id_buku_saat_ini');

        // Pastikan hanya antrean yang berstatus DIPANGGIL yang bisa diselesaikan
        $updated = DataBukuTamu::where('id_buku', $idBuku)
            ->where('id_loket', $loketId)
            ->where('status_antrean', 'DIPANGGIL')
            ->update(['status_antrean' => 'SELESAI']);

        if ($updated) {
            return response()->json(['status' => 'success', 'message' => 'Layanan telah diselesaikan.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Antrean tidak aktif atau sudah selesai.']);
        }
    }

    // --- Aksi Memanggil Ulang ---
    /**
     * Aksi: Memasukkan kembali antrean yang sedang aktif ke Display Queue (untuk suara).
     */
    public function reissueCall(Request $request)
    {
        $loketId = 4;
        $idBuku = $request->input('id_buku_saat_ini');

        // Hanya masukkan kembali ke display_queue (tidak mengubah status utama)
        DisplayQueue::create([
            'id_buku' => $idBuku,
            'loket_tujuan' => $loketId,
            'status_panggil' => 'NEW',
            'waktu_request' => Carbon::now(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Panggilan ulang berhasil dimasukkan ke antrean.']);
    }

    // --- Aksi Lewati Antrean ---
    /**
     * Aksi: Mengubah status antrean aktif menjadi LEWAT.
     */
    public function skipCall(Request $request)
    {
        $loketId = 4;
        $idBuku = $request->input('id_buku_saat_ini');

        $updated = DataBukuTamu::where('id_buku', $idBuku)
            ->where('id_loket', $loketId)
            ->where('status_antrean', 'DIPANGGIL')
            ->update(['status_antrean' => 'LEWAT']);

        if ($updated) {
            return response()->json(['status' => 'success', 'message' => 'Antrean telah dilewati.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Antrean tidak aktif atau sudah diproses.']);
        }
    }

    // --- Fungsi Pendukung (Hanya untuk contoh) ---
    protected function getLoketName($id)
    {
        // Implementasikan lookup dari database Master Loket Anda (jika ada)
        $map = [1 => 'Admin Hukum', 2 => 'Pendaftaran Merek', 3 => 'Paten/Cipta', 4 => 'Umum/Info'];
        return $map[$id] ?? 'Loket Tidak Dikenal';
    }

    public function getDailyStats(Request $request)
    {
        $today = Carbon::today()->toDateString();

        // 1. Total Kunjungan Hari Ini
        $totalKunjungan = DataBukuTamu::whereDate('tanggal', $today)->count();

        // 2. Antrean Aktif (DIPANGGIL) dan Menunggu
        $antreanAktif = DataBukuTamu::whereDate('tanggal', $today)
            ->where('status_antrean', 'DIPANGGIL')
            ->count();

        $antreanMenunggu = DataBukuTamu::whereDate('tanggal', $today)
            ->where('status_antrean', 'MENUNGGU')
            ->count();

        // 3. Waktu Layanan Rata-rata (Hanya dari yang sudah SELESAI)
        $finishedEntries = DataBukuTamu::whereDate('tanggal', $today)
            ->where('status_antrean', 'SELESAI')
            // ->whereNotNull('waktu_panggil')
            ->get();

        $avgServiceTime = 0;
        if ($finishedEntries->count() > 0) {
            $totalDuration = 0;
            foreach ($finishedEntries as $entry) {
                // Pastikan updated_at dan waktu_panggil ada
                if ($entry->updated_at && $entry->waktu_panggil) {
                    $start = Carbon::parse($entry->waktu_panggil);
                    $end = Carbon::parse($entry->updated_at); // Waktu diupdate saat SELESAI
                    $totalDuration += $end->diffInMinutes($start);
                }
            }
            // Rata-rata dibulatkan ke 1 desimal
            $avgServiceTime = round($totalDuration / $finishedEntries->count(), 1);
        }

        // 4. Tingkat Kehadiran (Selesai + Dipanggil) / Total
        $totalProses = $finishedEntries->count() + $antreanAktif;
        $tingkatKehadiran = ($totalKunjungan > 0)
            ? round(($totalProses / $totalKunjungan) * 100, 1)
            : 0;

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_kunjungan' => $totalKunjungan,
                'antrean_aktif' => $antreanAktif,
                'antrean_menunggu' => $antreanMenunggu,
                'avg_service_time' => $avgServiceTime,
                'tingkat_kehadiran' => $tingkatKehadiran,
            ]
        ]);
    }


    /**
     * API: Menghitung tren kunjungan mingguan (Grafik Line/Bar).
     * Route: api.admin.stats.weekly_trend
     */
    public function getWeeklyTrend(Request $request)
    {
        $startDate = Carbon::today()->subDays(6)->toDateString(); // 7 hari terakhir
        $endDate = Carbon::today()->toDateString();

        $entries = DataBukuTamu::select(
            DB::raw('DATE(tanggal) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $dates = [];
        $counts = [];

        // Inisiasi 7 hari dengan count 0
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::today()->subDays(6)->addDays($i);
            $dates[$date->toDateString()] = 0;
        }

        // Isi count yang sebenarnya
        foreach ($entries as $entry) {
            $dates[$entry->date] = $entry->count;
        }

        // Format label dan array count untuk chart
        $labels = collect($dates)->keys()->map(function ($date) {
            return Carbon::parse($date)->isoFormat('ddd'); // Contoh: Sen, Sel
        })->toArray();
        $counts = collect($dates)->values()->toArray();

        return response()->json([
            'status' => 'success',
            'data' => [
                'labels' => $labels,
                'counts' => $counts,
            ]
        ]);
    }

    /**
     * API: Menghitung distribusi antrean per Loket (Grafik Donut).
     * Route: api.admin.stats.loket_dist
     */
    public function getServiceDistribution(Request $request)
    {
        $today = Carbon::today()->toDateString();

        $distribution = DataBukuTamu::select('id_loket', DB::raw('COUNT(*) as count'))
            ->whereDate('tanggal', $today)
            ->groupBy('id_loket')
            ->get();

        $totalAntrean = $distribution->sum('count');

        $labels = [];
        $percentages = [];

        foreach ($distribution as $item) {
            $labels[] = $this->getLoketName($item->id_loket);
            $percentages[] = round(($item->count / $totalAntrean) * 100, 1);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'labels' => $labels,
                'percentages' => $percentages,
                'total' => $totalAntrean,
            ]
        ]);
    }

    /**
     * API: Top 5 Layanan Paling Diminati (List).
     * Route: api.admin.stats.top_services
     */
    public function getTopServices(Request $request)
    {
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $monthName = Carbon::now()->isoFormat('MMMM YYYY');

        $topServices = DataBukuTamu::select('id_layanan_detail', DB::raw('COUNT(*) as total'))
            ->whereDate('tanggal', '>=', $startOfMonth)
            ->groupBy('id_layanan_detail')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Ambil nama detail layanan
        $serviceDetails = LayananDetail::whereIn('id_layanan_detail', $topServices->pluck('id_layanan_detail'))
            ->pluck('nama_layanan_detail', 'id_layanan_detail');

        $data = $topServices->map(function ($service) use ($serviceDetails) {
            return [
                'nama_layanan_detail' => $serviceDetails[$service->id_layanan_detail] ?? 'Layanan Tak Dikenal',
                'total' => $service->total
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'services' => $data,
                'month' => $monthName,
            ]
        ]);
    }
}
