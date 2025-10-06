<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisplayQueue extends Model
{
    // Nama tabel di database
    protected $table = 'display_queue';

    // Kolom Primary Key (jika bukan 'id')
    protected $primaryKey = 'id';

    // Nonaktifkan timestamps karena tabel Anda tidak punya created_at/updated_at
    public $timestamps = false;

    // Kolom yang diizinkan untuk diisi massal
    protected $fillable = [
        'id_buku',
        'loket_tujuan',
        'status_panggil',
        'waktu_request'
    ];
}
