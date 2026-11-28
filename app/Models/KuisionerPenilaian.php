<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KuisionerPenilaian extends Model
{
    use HasFactory;

    protected $table = 'kuisioner_penilaians';

    protected $fillable = [
        'id_booking',
        'id_users',
        'paket_id',
        'opsi_fasilitas',
        'opsi_harga',
        'opsi_kualitas',
        'nilai_fasilitas',
        'nilai_harga',
        'nilai_kualitas',
        'komentar',
    ];

    protected $casts = [
        'nilai_fasilitas' => 'float',
        'nilai_harga' => 'float',
        'nilai_kualitas' => 'float',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'id_booking');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function paketBundling()
    {
        return $this->belongsTo(PaketBundling::class, 'paket_id');
    }
}
