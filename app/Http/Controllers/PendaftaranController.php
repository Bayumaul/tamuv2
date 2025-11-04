<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Layanan;
use App\Models\DataTamu;
use App\Models\Pendaftaran;
use App\Models\DataBukuTamu;
use App\Models\DisplayQueue;
use Illuminate\Http\Request;
use App\Models\LayananDetail;
use App\Models\MasterPriorityCategory;
use Illuminate\Support\Facades\DB;

class PendaftaranController extends Controller
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
     * @param  \App\Models\Pendaftaran  $pendaftaran
     * @return \Illuminate\Http\Response
     */
    public function show(Pendaftaran $pendaftaran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pendaftaran  $pendaftaran
     * @return \Illuminate\Http\Response
     */
    public function edit(Pendaftaran $pendaftaran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pendaftaran  $pendaftaran
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pendaftaran $pendaftaran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pendaftaran  $pendaftaran
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pendaftaran $pendaftaran)
    {
        //
    }

    public function validatenik(Request $request)
    {
        // Ambil input dari query string (?nik=...&kategori=...)
        $nik = $request->query('nik');
        $kategori = $request->query('kategori');

        // Cek apakah kedua parameter ada
        if (!$nik || !$kategori) {
            return response()->json([
                'status' => 'error',
                'message' => 'Parameter nik dan kategori wajib diisi.'
            ], 400);
        }

        // Query data dari tabel data_tamu
        $dataPengunjung = DataTamu::where('nik', $nik)
            ->where('kategori', $kategori)
            ->first();

        // Jika tidak ditemukan
        if (!$dataPengunjung) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'Data tamu tidak ditemukan.'
            ], 200);
        }

        // Jika ditemukan
        return response()->json($dataPengunjung);
    }

    public function online()
    {
        $layanans = Layanan::with('details')
            ->orderBy('id_layanan', 'asc')
            ->get();
        return view('registration.online', compact('layanans'));
    }

    public function onlineRegistration(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|digits:16',
            'alamat' => 'required|string',
            'no_hp' => ['required', 'regex:/^08\d{8,11}$/'],
            'kategori' => 'required',
            'kategori_pengunjung' => 'required',
            'layanan' => 'required|exists:layanan_detail,id_layanan_detail',
            'aduan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $tanggal = now()->format('Y-m-d');

            // 🔹 Cek atau buat DataTamu
            $tamu = DataTamu::firstOrNew(['nik' => $validated['nik']]);
            $tamu->fill([
                'nama' => $validated['name'],
                'no_hp' => $validated['no_hp'],
                'alamat' => $validated['alamat'],
                'kategori' => $validated['kategori_pengunjung'],
            ]);
            $tamu->save();

            // 🔹 Ambil info layanan
            $layananDetail = LayananDetail::with('layanan')
                ->where('id_layanan_detail', $validated['layanan'])
                ->first();

            // 🔹 Simpan ke data_buku_tamu
            $bukuTamu = DataBukuTamu::create([
                'id_tamu' => $tamu->id_tamu,
                'id_layanan_detail' => $layananDetail->id_layanan_detail,
                'id_layanan' => $layananDetail->id_layanan,
                'layanan_lain' => $validated['aduan'] ?? '',
                'antrian' => 0,
                'tanggal' => $tanggal,
                'keterangan' => $validated['aduan'] ?? '',
                'tipe_layanan' => 'Online',
            ]);

            // 🔹 Ambil data lengkap untuk pesan WA
            $databuku = DataBukuTamu::select(
                'data_buku_tamu.*',
                'data_tamu.nama',
                'data_tamu.no_hp',
                'layanan.kode_layanan',
                'layanan.nama_layanan',
                'layanan_detail.nama_layanan_detail'
            )
                ->join('data_tamu', 'data_tamu.id_tamu', '=', 'data_buku_tamu.id_tamu')
                ->join('layanan', 'layanan.id_layanan', '=', 'data_buku_tamu.id_layanan')
                ->join('layanan_detail', 'layanan_detail.id_layanan_detail', '=', 'data_buku_tamu.id_layanan_detail')
                ->where('data_buku_tamu.id_tamu', $tamu->id_tamu)
                ->orderByDesc('data_buku_tamu.id_buku')
                ->first();

            // 🔹 Format nomor HP
            $nomor = $this->formatNomorWA($databuku->no_hp);

            // 🔹 Waktu dan format tanggal
            $waktu = now()->locale('id')->isoFormat('dddd, D MMMM Y HH:mm') . ' WIB';

            // // 🔹 Tentukan kategori
            // $kategoriText = match ($validated['kategori']) {
            //     1 => 'Permohonan Informasi',
            //     2 => 'Konsultasi',
            //     default => 'Pengaduan',
            // };

            $namaLayananDetail = $validated['layanan'] == 9
                ? $databuku->layanan_lain
                : $databuku->nama_layanan_detail;

            // 🔹 Pesan WhatsApp
            $pesan = "Terima Kasih Bapak/Ibu *{$databuku->nama}*,\n\n";
            // $pesan .= "Anda telah terdaftar pada Layanan $kategoriText Kategori *{$databuku->nama_layanan}* - *{$namaLayananDetail}* ";
            $pesan .= "Kantor Wilayah Kementerian Hukum Daerah Istimewa Yogyakarta pada $waktu.\n\n";
            $pesan .= "Mohon ditunggu, Petugas Kami akan menghubungi Anda.\n\nTerima Kasih\n\n_Pesan ini dikirim otomatis_";

            // 🔹 Kirim WA (contoh)
            // Kirimfonnte([
            //     "target" => $nomor,
            //     "message" => $pesan,
            // ]);

            DB::commit();
            // Redirect ke kartu
            $encodedId = base64_encode($databuku->id_buku);
            return redirect()->route('online.registration.card', ['encoded_id' => $encodedId])
                ->with('status', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('status', 'error')->withErrors(['msg' => $e->getMessage()]);
        }
    }

    public function offline()
    {
        $layanans = Layanan::with('details')
            ->orderBy('id_layanan', 'asc')
            ->get();
        $priorityCategories = MasterPriorityCategory::all();
        return view('registration.offline', compact('layanans', 'priorityCategories'));
    }

    public function offlineRegistration(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|digits:16',
            'alamat' => 'required|string',
            'no_hp' => ['required', 'regex:/^08\d{8,11}$/'],
            'kategori_pengunjung' => 'required',
            'layanan' => 'required|exists:layanan_detail,id_layanan_detail',
            'priority_category_id' => 'nullable|integer',
        ]);
        // return $request->all();
        DB::beginTransaction();
        try {
            $tanggal = now()->format('Y-m-d');

            // KUNCI PERBAIKAN: Menentukan ID Kategori dan Level
            $priorityCategoryId = $request->input('priority_category_id') ?: 1; // Jika kosong, set ke ID 1 (Umum)

            // Cari data kategori dari master
            $priorityCategory = \App\Models\MasterPriorityCategory::where('id', $priorityCategoryId)->first(['id', 'priority_level']);

            if (!$priorityCategory) {
                // Safety check jika ID 1 terhapus (seharusnya tidak terjadi)
                throw new \Exception("Kategori prioritas tidak ditemukan.");
            }
            $idPriority = $priorityCategory->id;
            $levelSnapshot = $priorityCategory->priority_level;
            // 🔹 Cek atau buat DataTamu
            $tamu = DataTamu::firstOrNew(['nik' => $validated['nik']]);
            $tamu->fill([
                'nama' => $validated['name'],
                'no_hp' => $validated['no_hp'],
                'alamat' => $validated['alamat'],
                'kategori' => $validated['kategori_pengunjung'],
            ]);
            $tamu->save();

            // 🔹 Ambil info layanan
            $layananDetail = LayananDetail::with('layanan')
                ->where('id_layanan_detail', $validated['layanan'])
                ->first();

            $idLayanan = $layananDetail->id_layanan;
            $idLoket = $layananDetail->id_loket_tujuan;

            // 🔹 Hitung nomor antrian hari ini untuk layanan tsb
            $jumlahAntrian = DataBukuTamu::where('tanggal', $tanggal)
                ->where('id_layanan', $idLayanan)
                ->count();

            $antrian = $jumlahAntrian + 1;

            $kodeLayanan = $layananDetail->layanan->kode_layanan;
            $nomorLengkap = $kodeLayanan . '-' . str_pad($antrian, 3, '0', STR_PAD_LEFT);

            // 🔹 Simpan ke data_buku_tamu
            $bukuTamu = DataBukuTamu::create([
                'id_tamu' => $tamu->id_tamu,
                'id_layanan_detail' => $layananDetail->id_layanan_detail,
                'id_layanan' => $layananDetail->id_layanan,
                'antrian' => $antrian,
                'tanggal' => $tanggal,
                'nomor_lengkap' => $nomorLengkap,
                'tipe_layanan' => 'Offline',
                'id_loket' => $idLoket,
                'id_priority_category' => $idPriority,
                'priority_level_snapshot' => $levelSnapshot,
            ]);

            // 🔹 Ambil data lengkap untuk pesan WA
            $databuku = DataBukuTamu::select(
                'data_buku_tamu.*',
                'data_tamu.nama',
                'data_tamu.no_hp',
                'layanan.kode_layanan',
                'layanan.nama_layanan',
                'layanan_detail.nama_layanan_detail'
            )
                ->join('data_tamu', 'data_tamu.id_tamu', '=', 'data_buku_tamu.id_tamu')
                ->join('layanan', 'layanan.id_layanan', '=', 'data_buku_tamu.id_layanan')
                ->join('layanan_detail', 'layanan_detail.id_layanan_detail', '=', 'data_buku_tamu.id_layanan_detail')
                ->where('data_buku_tamu.id_tamu', $tamu->id_tamu)
                ->orderByDesc('data_buku_tamu.id_buku')
                ->first();

            // 🔹 Format nomor HP
            $nomor = $this->formatNomorWA($databuku->no_hp);

            // 🔹 Waktu dan format tanggal
            $waktu = now()->locale('id')->isoFormat('dddd, D MMMM Y HH:mm') . ' WIB';


            $namaLayananDetail = $validated['layanan'] == 9
                ? $databuku->layanan_lain
                : $databuku->nama_layanan_detail;

            // 🔹 Pesan WhatsApp
            $pesan = "Terima Kasih Bapak/Ibu *{$databuku->nama}*,\n\n";
            // $pesan .= "Anda telah terdaftar pada Layanan $kategoriText Kategori *{$databuku->nama_layanan}* - *{$namaLayananDetail}* ";
            $pesan .= "Kantor Wilayah Kementerian Hukum Daerah Istimewa Yogyakarta pada $waktu.\n\n";
            $pesan .= "Mohon ditunggu, Petugas Kami akan menghubungi Anda.\n\nTerima Kasih\n\n_Pesan ini dikirim otomatis_";

            // 🔹 Kirim WA 

            $encodedId = base64_encode($databuku->id_buku);
            $cardUrl = route('offline.registration.card', ['encoded_id' => $encodedId]); // Menggunakan route Laravel

            $nomorWA = $this->formatNomorWA($validated['no_hp']);
            $waktu = Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y HH:mm') . ' WIB';
            $namaLayanan = $layananDetail->nama_layanan_detail;

            // SUSUNAN PESAN LENGKAP (sesuai format native Anda)
            $pesan = "Terima Kasih Bapak/Ibu *{$validated['name']}*,\n\n";
            $pesan .= "Anda telah terdaftar pada Layanan Offline Kategori *$namaLayanan* ";
            $pesan .= "Kantor Wilayah Kementerian Hukum Daerah Istimewa Yogyakarta pada $waktu.\n\n";
            $pesan .= "Sekarang Anda berada pada Nomor Antrean *$nomorLengkap*. ";
            $pesan .= "Silahkan Download Kartu Antrian pada link berikut:\n$cardUrl";
            $pesan .= "\n\nSilakan tunjukkan pesan ini ke petugas kami saat anda berada di pelayanan secara tatap muka (Offline).";
            $pesan .= "\nTerima Kasih";
            $pesan .= "\n\n_Pesan ini dikirim otomatis_";

            // KIRIM VIA HELPER
            kirimFonnte($nomorWA, $pesan);

            DB::commit();
            // Redirect ke kartu
            return redirect()->route('offline.registration.card', ['encoded_id' => $encodedId])
                ->with('status', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('status', 'error')->withErrors(['msg' => $e->getMessage()]);
        }
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

    public function showCard($encoded_id)
    {
        require_once app_path('Libraries/phpqrcode/qrlib.php');
        $decodedId = base64_decode($encoded_id);

        if (!ctype_digit($decodedId)) {
            abort(404, 'ID tidak valid.');
        }

        $data = DataBukuTamu::select(
            'data_buku_tamu.*',
            'data_tamu.nama',
            'data_tamu.no_hp',
            'data_tamu.alamat',
            'layanan.kode_layanan',
            'layanan.nama_layanan',
            'layanan_detail.nama_layanan_detail'
        )
            ->join('data_tamu', 'data_tamu.id_tamu', '=', 'data_buku_tamu.id_tamu')
            ->join('layanan', 'layanan.id_layanan', '=', 'data_buku_tamu.id_layanan')
            ->join('layanan_detail', 'layanan_detail.id_layanan_detail', '=', 'data_buku_tamu.id_layanan_detail')
            ->where('data_buku_tamu.id_buku', $decodedId)
            ->firstOrFail();

        $kode = $data->kode_layanan ?? 'LAIN';
        $mapping = [
            'KI' => 'Layanan Kekayaan Intelektual',
            'AHU' => 'Layanan Administrasi Hukum Umum',
            'FPHD' => 'Layanan Fasilitasi Produk Hukum Daerah',
            'JDIH' => 'Layanan JDIH',
            'HKM' => 'Layanan Bantuan Hukum',
            'ADM' => 'Layanan Fasilitatif Administratif',
        ];

        $layanan = isset($mapping[$kode]) ? $mapping[$kode] : 'Lainnya';


        $url = 'kemenkumjogja.id';

        $no_antrian = str_pad($data->antrian, 3, '0', STR_PAD_LEFT);
        // folder simpan hasil QR
        $tempDir = public_path('img/qrcode/');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        // path file
        $filename = $decodedId . '.png';
        $filePath = $tempDir . $filename;

        // generate QR code jika belum ada
        if (!file_exists($filePath)) {
            \QRcode::png($decodedId, $filePath, QR_ECLEVEL_L, 6);
        }

        // kirim path ke view
        $qrPath = asset('img/qrcode/' . $filename);
        $tgl = $data->created_at->isoFormat('D MMMM YYYY, HH:mm') . ' WIB';
        return view('registration.card', compact('data', 'layanan', 'url', 'no_antrian', 'tgl', 'qrPath'));
    }
}
