<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketPenilaian extends Model
{
    protected $fillable = [
        'paket_id',
        'nilai_harga',
        'nilai_kualitas',
        'nilai_fasilitas',
        'skor_akhir',
        'ranking',
    ];

    public function paketBundling()
    {
        return $this->belongsTo(PaketBundling::class, 'paket_id');
    }

    public function getLabelHargaAttribute()
    {
        if ($this->nilai_harga == 1) return 'Terjangkau';
        if ($this->nilai_harga == 0.41) return 'Cukup Mahal';
        if ($this->nilai_harga == 0.17) return 'Sangat Mahal';
        return '-';
    }

    public function getLabelKualitasAttribute()
    {
        if ($this->nilai_kualitas == 1) return 'Sangat Bagus';
        if ($this->nilai_kualitas == 0.41) return 'Cukup Bagus';
        if ($this->nilai_kualitas == 0.17) return 'Kurang Bagus';
        return '-';
    }

    public function getLabelFasilitasAttribute()
    {
        if ($this->nilai_fasilitas == 1) return 'Sangat Lengkap';
        if ($this->nilai_fasilitas == 0.41) return 'Cukup Lengkap';
        if ($this->nilai_fasilitas == 0.17) return 'Kurang Lengkap';
        return '-';
    }
}
