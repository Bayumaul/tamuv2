<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'layanan';
    protected $primaryKey = 'id_layanan';
    public $timestamps = false;

    public function details()
    {
        return $this->hasMany(LayananDetail::class, 'id_layanan', 'id_layanan');
    }
}
