<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AhpResponden extends Model
{
    protected $table = 'ahp_respondens';

    protected $fillable = [
        'kategori',
        'nama_responden',
        'nilai_12',
        'nilai_13',
        'nilai_23',
    ];

    protected $casts = [
        'nilai_12' => 'float',
        'nilai_13' => 'float',
        'nilai_23' => 'float',
    ];
}
