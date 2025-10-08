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

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan');
    }
    public function tamu()
    {
        return $this->belongsTo(DataTamu::class, 'id_tamu', 'id_tamu');
    }
    public function layananDetail()
    {
        return $this->belongsTo(LayananDetail::class, 'id_layanan_detail', 'id_layanan_detail');
    }
}
