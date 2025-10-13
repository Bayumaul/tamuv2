<!DOCTYPE html>
<html>

<head>
    <title>{{ $judul }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
        }

        h4 {
            text-align: center;
            margin-bottom: 5px;
        }

        .info {
            text-align: center;
            margin-bottom: 20px;
            font-size: 11pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .footer {
            font-size: 8pt;
            text-align: center;
            margin-top: 30px;
        }

        .badge-success {
            color: green;
        }

        .badge-danger {
            color: red;
        }
    </style>
</head>

<body>
    <h4>{{ $judul }}</h4>
    <div class="info">
        <strong>{{ $periode }}</strong> <br>
        Dicetak pada: {{ now()->isoFormat('dddd, D MMMM YYYY HH:mm') }} WIB
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Tgl Kunjungan</th>
                <th>Nama Pengunjung</th>
                <th>Layanan Diminta</th>
                <th>Nomor Antrean</th>
                <th>Loket</th>
                <th>Tipe</th>
                <th>Status</th>
                <th>Waktu Panggil</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $item->formatted_tanggal }}</td>
                    <td>{{ $item->tamu->nama ?? 'N/A' }}</td>
                    <td>{{ $item->layananDetail->nama_layanan_detail ?? 'N/A' }}</td>
                    <td style="text-align: center;">{{ $item->nomor_lengkap }}</td>
                    <td style="text-align: center;">{{ $loketMaster[$item->id_loket] ?? 'N/A' }}</td>
                    <td style="text-align: center;">{{ $item->tipe_layanan }}</td>
                    <td>
                        {{-- Logika status sederhana untuk PDF --}}
                        @if ($item->status_antrean == 'SELESAI')
                            <span class="badge-success">SELESAI</span>
                        @elseif ($item->status_antrean == 'LEWAT')
                            <span class="badge-danger">LEWAT</span>
                        @else
                            {{ $item->status_antrean }}
                        @endif
                    </td>
                    <td>{{ $item->waktu_panggil_format }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Laporan ini dihasilkan otomatis oleh Sistem Antrean Kanwil Kementerian Hukum D.I. Yogyakarta.
    </div>
</body>

</html>
