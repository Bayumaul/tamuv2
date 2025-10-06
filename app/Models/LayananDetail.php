<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayananDetail extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'layanan_detail';
    protected $primaryKey = 'id_layanan_detail';
    public $timestamps = false;

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan');
    }
}
