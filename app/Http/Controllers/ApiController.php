<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Layanan;
use App\Models\QueueEntry;
use App\Models\DataBukuTamu;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getPersonalStatus(Request $request)
    {
        $idBuku = $request->input('id_buku');
        $DURASI_STANDAR = 15;

        // Cari data antrean pengguna hari ini
        $entry = DataBukuTamu::where('id_buku', $idBuku)
            ->whereDate('tanggal', Carbon::today())
            ->first();

        if (!$entry) {
            return response()->json(['status' => 'error', 'message' => 'Antrean tidak ditemukan atau sudah kadaluarsa.']);
        }

        $statusSaatIni = $entry->status_antrean;
        $antrianSaya = $entry->antrian;

        // Cari nomor antrean terakhir yang SELESAI atau DIPANGGIL di Layanan yang SAMA
        $lastCalled = DataBukuTamu::where('id_layanan', $entry->id_layanan)
            ->where('tanggal', Carbon::today())
            ->whereIn('status_antrean', ['DIPANGGIL', 'SELESAI'])
            ->max('antrian');

        $antrianDipanggil = $lastCalled ?? 0;

        $posisiDiDepan = 0;
        $estimasiMenit = 0;
        $waktuDilayani = 'MEMUAT';

        if ($statusSaatIni === 'MENUNGGU') {
            // Hitung posisi: (Nomor Saya) - (Nomor Terakhir yang Diproses) - 1 (Petugas sedang melayani nomor itu)
            $posisiDiDepan = max(0, $antrianSaya - $antrianDipanggil - 1);
            $estimasiMenit = $posisiDiDepan * $DURASI_STANDAR;
            $waktuDilayani = Carbon::now()->addMinutes($estimasiMenit)->format('H:i');
        }

        return response()->json([
            'status' => 'success',
            'antrian_dipanggil' => $antrianDipanggil,
            'posisi_di_depan' => $posisiDiDepan,
            'estimasi_menit' => $estimasiMenit,
            'waktu_dilayani' => $waktuDilayani,
            'status_saat_ini' => $statusSaatIni,
        ]);
    }
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

    public function getEntryDetails($id)
    {
        // Cari QueueEntry dengan relasi yang diperlukan
        $entry = DataBukuTamu::where('id_buku', $id)
            ->with(['tamu', 'layananDetail.layanan']) // Load relasi Tamu, Layanan Detail, dan Layanan Master
            ->first();

        if (!$entry || $entry->status_antrean !== 'SELESAI') {
            // Kita hanya perlu detail jika status sudah SELESAI, jika tidak, tolak.
            return response()->json(['status' => 'error', 'message' => 'Antrean tidak valid atau belum selesai.'], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $entry->id_buku,
                'nama' => $entry->tamu->nama ?? 'N/A',
                'no_hp' => $entry->tamu->no_hp ?? 'N/A',
                'layanan' => $entry->layananDetail->layanan->nama_layanan ?? 'N/A',
                'layanan_detail' => $entry->layananDetail->nama_layanan_detail ?? 'N/A',
            ]
        ]);
    }

    public function getGridServiceStatus()
    {
        $today = Carbon::today()->toDateString();
        $statusGrid = [];

        // Ambil semua Layanan utama (KI, AHU, FPHD, dll.)
        $services = Layanan::all();

        foreach ($services as $service) {
            $lastEntry = DataBukuTamu::where('tanggal', $today)
                ->where('id_layanan', $service->id_layanan)
                ->whereIn('status_antrean', ['DIPANGGIL', 'SELESAI'])
                ->select('nomor_lengkap')
                ->orderBy('antrian', 'desc')
                ->first();

            $nomorTerakhir = $lastEntry ? $lastEntry->nomor_lengkap : ($service->kode_layanan . '-000');

            $statusGrid[] = [
                'kode' => $service->kode_layanan,
                'nama' => $service->nama_layanan,
                'nomor_terakhir' => $nomorTerakhir
            ];
        }

        return response()->json([
            'status' => 'success',
            'grid_data' => $statusGrid
        ]);
    }
}
