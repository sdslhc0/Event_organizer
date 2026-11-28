<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AhpHasilBobot extends Model
{
    protected $table = 'ahp_hasil_bobots';

    protected $fillable = [
        'kategori',
        'jumlah_responden',
        'gm_12',
        'gm_13',
        'gm_23',
        'bobot_1',
        'bobot_2',
        'bobot_3',
        'ideal_1',
        'ideal_2',
        'ideal_3',
        'lambda_max',
        'ci',
        'cr',
        'is_consistent',
    ];

    protected $casts = [
        'jumlah_responden' => 'integer',
        'gm_12' => 'float',
        'gm_13' => 'float',
        'gm_23' => 'float',
        'bobot_1' => 'float',
        'bobot_2' => 'float',
        'bobot_3' => 'float',
        'ideal_1' => 'float',
        'ideal_2' => 'float',
        'ideal_3' => 'float',
        'lambda_max' => 'float',
        'ci' => 'float',
        'cr' => 'float',
        'is_consistent' => 'boolean',
    ];
}
