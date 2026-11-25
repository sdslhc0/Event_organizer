<?php

namespace App\Services;

use App\Models\PaketBundling;
use App\Models\PaketPenilaian;
use App\Models\KuisionerPenilaian;

class SpkService
{
    // Bobot kriteria paten AHP
    public const BOBOT_HARGA = 0.50;
    public const BOBOT_FASILITAS = 0.23;
    public const BOBOT_KUALITAS = 0.27;

    // Nilai baku kualitatif ke kuantitatif AHP
    public const MAPPING_NILAI = [
        'fasilitas' => [
            'sangat_lengkap' => 0.61,
            'cukup_lengkap'  => 0.30,
            'kurang_lengkap' => 0.09,
        ],
        'harga' => [
            'terjangkau'     => 0.56,
            'cukup_mahal'    => 0.36,
            'sangat_mahal'   => 0.08,
        ],
        'kualitas' => [
            'sangat_bagus'   => 0.71,
            'cukup_bagus'    => 0.23,
            'kurang_bagus'   => 0.06,
        ],
    ];

    // Baseline default jika paket belum memiliki kuisioner user
    public const BASELINE_NILAI = [
        1 => ['harga' => 0.56, 'fasilitas' => 0.61, 'kualitas' => 0.71], // Silver
        2 => ['harga' => 0.36, 'fasilitas' => 0.30, 'kualitas' => 0.23], // Gold
        3 => ['harga' => 0.08, 'fasilitas' => 0.09, 'kualitas' => 0.06], // Platinum
    ];

    /**
     * Hitung ulang skor SPK AHP dengan NORMALISASI MATRIKS ALTERNATIF (Normalisasi Kolom AHP).
     * Dengan normalisasi ini, total persentase seluruh paket dijamin MATEMATIS selalu tepat 100% (1.0000).
     */
    public static function recalculateFromKuisioner(): void
    {
        $pakets = PaketBundling::all();
        if ($pakets->isEmpty()) {
            return;
        }

        // TAHAP 1: Kumpulkan nilai mentah (rata-rata kuisioner atau baseline) untuk tiap paket
        $rawScores = [];
        foreach ($pakets as $paket) {
            $kuisioners = KuisionerPenilaian::where('paket_id', $paket->id)->get();
            $default = self::BASELINE_NILAI[$paket->id] ?? ['harga' => 0.36, 'fasilitas' => 0.30, 'kualitas' => 0.23];

            if ($kuisioners->isNotEmpty()) {
                $rawScores[$paket->id] = [
                    'harga'     => (float) $kuisioners->avg('nilai_harga'),
                    'fasilitas' => (float) $kuisioners->avg('nilai_fasilitas'),
                    'kualitas'  => (float) $kuisioners->avg('nilai_kualitas'),
                ];
            } else {
                $rawScores[$paket->id] = $default;
            }
        }

        // TAHAP 2: Hitung jumlah total kolom untuk tiap kriteria (Sum of columns)
        $sumHarga = array_sum(array_column($rawScores, 'harga')) ?: 1.0;
        $sumFasilitas = array_sum(array_column($rawScores, 'fasilitas')) ?: 1.0;
        $sumKualitas = array_sum(array_column($rawScores, 'kualitas')) ?: 1.0;

        // TAHAP 3: Normalisasi matriks AHP & hitung skor akhir berbobot
        foreach ($pakets as $paket) {
            $raw = $rawScores[$paket->id];

            // Normalisasi nilai kriteria (nilai paket / total kriteria seluruh paket)
            $normHarga = round($raw['harga'] / $sumHarga, 4);
            $normFasilitas = round($raw['fasilitas'] / $sumFasilitas, 4);
            $normKualitas = round($raw['kualitas'] / $sumKualitas, 4);

            // Perhitungan skor akhir berbobot AHP:
            // (Norm_Harga * 0.50) + (Norm_Fasilitas * 0.23) + (Norm_Kualitas * 0.27)
            $score = ($normHarga * self::BOBOT_HARGA) +
                     ($normFasilitas * self::BOBOT_FASILITAS) +
                     ($normKualitas * self::BOBOT_KUALITAS);

            $penilaian = PaketPenilaian::firstOrNew(['paket_id' => $paket->id]);
            $penilaian->nilai_harga = $normHarga;
            $penilaian->nilai_fasilitas = $normFasilitas;
            $penilaian->nilai_kualitas = $normKualitas;
            $penilaian->skor_akhir = round($score, 4);
            $penilaian->save();
        }

        // TAHAP 4: Penentuan ranking AHP (Diurutkan dari skor akhir tertinggi)
        // Jika skor sama (tie), paket dengan harga lebih terjangkau diprioritaskan
        $allPenilaians = PaketPenilaian::with('paketBundling')
            ->get()
            ->sort(function ($a, $b) {
                if (abs($a->skor_akhir - $b->skor_akhir) < 0.0001) {
                    return ($a->paketBundling->harga ?? 0) <=> ($b->paketBundling->harga ?? 0);
                }
                return $b->skor_akhir <=> $a->skor_akhir;
            });

        $rank = 1;
        foreach ($allPenilaians as $p) {
            $p->ranking = $rank++;
            $p->save();
        }
    }
}
