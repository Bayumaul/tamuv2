<?php

namespace App\Http\Controllers;

use Log;
use Carbon\Carbon;
use App\Models\DataBukuTamu;
use App\Models\DisplayQueue;
use Illuminate\Http\Request;
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
        //
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
        // Pastikan pengguna sudah terotentikasi dan memiliki id_loket
        $loketId = 2;

        // Logika untuk mendapatkan nama loket (Contoh: Loket 2 - Pendaftaran Merek)
        $namaLoket = 'Loket 2';

        return view('dashboard.call', compact('loketId', 'namaLoket'));
    }


    // --- API Status Real-time (Untuk AJAX Polling di Dashboard) ---
    /**
     * API: Mengambil status antrean real-time untuk loket yang sedang login.
     */
    public function getQueueStatus(Request $request)
    {
        // Mengambil ID Loket dari sesi pengguna yang login
        $loketId = 4;
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
        $loketId = 4;
        $today = Carbon::today()->toDateString();

        // DB::beginTransaction();
        // try {
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
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     // Log::error("Call Next Gagal: " . $e->getMessage());
        //     return response()->json(['status' => 'error', 'message' => 'Gagal memproses panggilan karena error server.']);
        // }
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
        $updated = DataBukuTamu::where('id', $idBuku)
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
}
