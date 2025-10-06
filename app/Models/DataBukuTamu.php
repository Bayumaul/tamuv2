<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataBukuTamu extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'data_buku_tamu';
    protected $primaryKey = 'id_buku';
    public $timestamps = false;

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan');
    }
}
