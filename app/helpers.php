<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

if (!function_exists('loket_user')) {
    function loket_user()
    {
        return Auth::check() ? Auth::user()->id_loket : '0';
    }
}
function formatNomorWA($nomor)
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


if (!function_exists('kirimFonnte')) {
    function kirimFonnte($no, $message)
    {
        $token = env('FONNTE_TOKEN');

        $response = Http::withHeaders([
            'Authorization' => $token,
        ])->asForm()->post('https://api.fonnte.com/send', [
            'target' => $no,
            'message' => $message,
        ]);

        return $response->json();
    }
}

function formatNomorIndo($nomor)
{
    // Hilangkan spasi, tanda plus, strip, dan titik
    $nomor = preg_replace('/[^0-9]/', '', $nomor);

    // Jika nomor diawali dengan 0 → ubah jadi 62
    if (substr($nomor, 0, 1) === '0') {
        $nomor = '62' . substr($nomor, 1);
    }

    // Jika sudah diawali 62 → biarkan
    else if (substr($nomor, 0, 2) === '62') {
        $nomor = $nomor;
    }

    // Jika tanpa awalan apa pun (misal 812xxxx) → tambahkan 62
    else if (!preg_match('/^62/', $nomor)) {
        $nomor = '62' . $nomor;
    }

    return $nomor;
}
