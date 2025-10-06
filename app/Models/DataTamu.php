<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataTamu extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'data_tamu';
    protected $primaryKey = 'id_tamu';
    public $timestamps = false;
}
