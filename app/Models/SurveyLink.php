<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'caption',
        'is_active',
    ];

    // Helper untuk memudahkan pengambilan link aktif
    public static function getActiveLink()
    {
        return self::where('is_active', true)->first();
    }
}
