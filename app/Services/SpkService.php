<?php

namespace App\Services;

use App\Models\AhpHasilBobot;
use App\Models\AhpResponden;
use App\Models\KuisionerPenilaian;
use App\Models\PaketBundling;
use App\Models\PaketPenilaian;

class SpkService
{
    public const RI_N3 = 0.58;
    public const CR_THRESHOLD = 0.10;
    public const NEUTRAL_BASELINE = 0.5000;

    /**
     * Definisi baku 4 kategori matriks AHP (dikunci konsisten antara template Excel, DB, dan UI).
     */
    public const CATEGORIES = [
        'kriteria' => [
            'label' => 'Kriteria Utama',
            'badge' => 'Bobot Kriteria',
            'items' => ['Harga', 'Kualitas', 'Fasilitas'],
            'pairs' => ['Harga vs Kualitas', 'Harga vs Fasilitas', 'Kualitas vs Fasilitas'],
            'keys'  => ['harga', 'kualitas', 'fasilitas'],
        ],
        'sub_harga' => [
            'label' => 'Subkriteria Harga',
            'badge' => 'Skala Harga',
            'items' => ['Murah', 'Terjangkau', 'Mahal'],
            'pairs' => ['Murah vs Terjangkau', 'Murah vs Mahal', 'Terjangkau vs Mahal'],
            'keys'  => ['murah', 'terjangkau', 'mahal'],
        ],
        'sub_kualitas' => [
            'label' => 'Subkriteria Kualitas',
            'badge' => 'Skala Kualitas',
            'items' => ['Sangat Baik', 'Baik', 'Cukup Baik'],
            'pairs' => ['Sangat Baik vs Baik', 'Sangat Baik vs Cukup Baik', 'Baik vs Cukup Baik'],
            'keys'  => ['sangat_baik', 'baik', 'cukup_baik'],
        ],
        'sub_fasilitas' => [
            'label' => 'Subkriteria Fasilitas',
            'badge' => 'Skala Fasilitas',
            'items' => ['Sangat Lengkap', 'Lengkap', 'Cukup Lengkap'],
            'pairs' => ['Sangat Lengkap vs Lengkap', 'Sangat Lengkap vs Cukup Lengkap', 'Lengkap vs Cukup Lengkap'],
            'keys'  => ['sangat_lengkap', 'lengkap', 'cukup_lengkap'],
        ],
    ];

    /**
     * Hitung Geometric Mean secara aman terhadap overflow (menggunakan exp(avg(ln(x)))).
     *
     * @param array<int, float|int> $values
     */
    public static function geometricMean(array $values): float
    {
        if (empty($values)) {
            return 1.0;
        }

        $logSum = 0.0;
        $count = 0;
        foreach ($values as $v) {
            $num = (float) $v;
            if ($num > 0) {
                $logSum += log($num);
                $count++;
            }
        }

        return $count > 0 ? exp($logSum / $count) : 1.0;
    }

    /**
     * Hitung matriks perbandingan berpasangan 3x3 AHP, normalisasi, bobot prioritas,
     * ideal mode, lambda_max, CI, dan CR.
     */
    public static function computeAhp3x3(float $v12, float $v13, float $v23): array
    {
        $matrix = [
            [1.0,       $v12,       $v13],
            [1.0 / $v12, 1.0,       $v23],
            [1.0 / $v13, 1.0 / $v23, 1.0],
        ];

        $colSums = [];
        for ($c = 0; $c < 3; $c++) {
            $colSums[$c] = $matrix[0][$c] + $matrix[1][$c] + $matrix[2][$c];
        }

        $normalized = [];
        $weights = [];
        for ($r = 0; $r < 3; $r++) {
            $rowSum = 0.0;
            for ($c = 0; $c < 3; $c++) {
                $val = $colSums[$c] > 0 ? ($matrix[$r][$c] / $colSums[$c]) : 0.0;
                $normalized[$r][$c] = $val;
                $rowSum += $val;
            }
            $weights[$r] = $rowSum / 3.0;
        }

        $maxWeight = max($weights) ?: 1.0;
        $ideal = array_map(fn (float $w) => $w / $maxWeight, $weights);

        $aw = [];
        $lambdaRatios = [];
        for ($r = 0; $r < 3; $r++) {
            $sumAw = 0.0;
            for ($c = 0; $c < 3; $c++) {
                $sumAw += $matrix[$r][$c] * $weights[$c];
            }
            $aw[$r] = $sumAw;
            $lambdaRatios[$r] = $weights[$r] > 0 ? ($sumAw / $weights[$r]) : 3.0;
        }

        $lambdaMax = array_sum($lambdaRatios) / 3.0;
        $ci = ($lambdaMax - 3.0) / 2.0;
        $cr = abs($ci) < 1e-9 ? 0.0 : ($ci / self::RI_N3);
        $cr = max(0.0, $cr);

        return [
            'gm'            => [$v12, $v13, $v23],
            'matrix'        => $matrix,
            'col_sums'      => $colSums,
            'normalized'    => $normalized,
            'weights'       => $weights,
            'ideal'         => $ideal,
            'aw'            => $aw,
            'lambda_ratios' => $lambdaRatios,
            'lambda_max'    => $lambdaMax,
            'ci'            => max(0.0, $ci),
            'cr'            => $cr,
            'is_consistent' => $cr <= self::CR_THRESHOLD,
        ];
    }

    /**
     * Hitung ulang seluruh hasil bobot AHP (4 kategori) dari tabel ahp_respondens
     * lalu simpan hasilnya ke ahp_hasil_bobots.
     *
     * @return array<string, AhpHasilBobot>
     */
    public static function calculateAhpFromRespondents(): array
    {
        $results = [];

        foreach (array_keys(self::CATEGORIES) as $kategori) {
            $rows = AhpResponden::where('kategori', $kategori)->get(['nilai_12', 'nilai_13', 'nilai_23']);
            if ($rows->isEmpty()) {
                continue;
            }

            $gm12 = self::geometricMean($rows->pluck('nilai_12')->all());
            $gm13 = self::geometricMean($rows->pluck('nilai_13')->all());
            $gm23 = self::geometricMean($rows->pluck('nilai_23')->all());

            $calc = self::computeAhp3x3($gm12, $gm13, $gm23);

            $record = AhpHasilBobot::updateOrCreate(
                ['kategori' => $kategori],
                [
                    'jumlah_responden' => $rows->count(),
                    'gm_12'            => round($gm12, 4),
                    'gm_13'            => round($gm13, 4),
                    'gm_23'            => round($gm23, 4),
                    'bobot_1'          => round($calc['weights'][0], 4),
                    'bobot_2'          => round($calc['weights'][1], 4),
                    'bobot_3'          => round($calc['weights'][2], 4),
                    'ideal_1'          => round($calc['ideal'][0], 4),
                    'ideal_2'          => round($calc['ideal'][1], 4),
                    'ideal_3'          => round($calc['ideal'][2], 4),
                    'lambda_max'       => round($calc['lambda_max'], 4),
                    'ci'               => round($calc['ci'], 4),
                    'cr'               => round($calc['cr'], 4),
                    'is_consistent'    => $calc['is_consistent'],
                ]
            );

            $results[$kategori] = $record;
        }

        return $results;
    }

    /**
     * Ambil bobot kriteria utama aktif langsung dari database (ahp_hasil_bobots).
     *
     * @return array{harga: float, kualitas: float, fasilitas: float, jumlah_responden: int, cr: float, is_consistent: bool}
     */
    public static function getActiveCriteriaWeights(): array
    {
        $row = AhpHasilBobot::where('kategori', 'kriteria')->first();
        if (!$row && AhpResponden::where('kategori', 'kriteria')->exists()) {
            self::calculateAhpFromRespondents();
            $row = AhpHasilBobot::where('kategori', 'kriteria')->first();
        }

        if (!$row) {
            return [
                'harga'            => 0.3333,
                'kualitas'         => 0.3333,
                'fasilitas'        => 0.3334,
                'jumlah_responden' => 0,
                'cr'               => 0.0,
                'is_consistent'    => true,
            ];
        }

        return [
            'harga'            => (float) $row->bobot_1,
            'kualitas'         => (float) $row->bobot_2,
            'fasilitas'        => (float) $row->bobot_3,
            'jumlah_responden' => (int) $row->jumlah_responden,
            'cr'               => (float) $row->cr,
            'is_consistent'    => (bool) $row->is_consistent,
        ];
    }

    /**
     * Ambil skala nilai Ideal Mode subkriteria aktif langsung dari database (ahp_hasil_bobots).
     *
     * @return array<string, array<string, float>>
     */
    public static function getActiveSubcriteriaMapping(): array
    {
        $tabMap = [
            'harga'     => 'sub_harga',
            'kualitas'  => 'sub_kualitas',
            'fasilitas' => 'sub_fasilitas',
        ];

        $stored = AhpHasilBobot::whereIn('kategori', array_values($tabMap))
            ->get()
            ->keyBy('kategori');

        if ($stored->count() < 3 && AhpResponden::whereIn('kategori', array_values($tabMap))->exists()) {
            self::calculateAhpFromRespondents();
            $stored = AhpHasilBobot::whereIn('kategori', array_values($tabMap))
                ->get()
                ->keyBy('kategori');
        }

        $mapping = [];
        foreach ($tabMap as $criterion => $kategori) {
            $keys = self::CATEGORIES[$kategori]['keys'];
            $row = $stored->get($kategori);

            if ($row) {
                $mapping[$criterion] = [
                    $keys[0] => (float) $row->ideal_1,
                    $keys[1] => (float) $row->ideal_2,
                    $keys[2] => (float) $row->ideal_3,
                ];
            } else {
                // Fallback netral sebelum data responden AHP tersedia di database
                $mapping[$criterion] = [
                    $keys[0] => 1.0000,
                    $keys[1] => 0.5000,
                    $keys[2] => 0.2500,
                ];
            }
        }

        return $mapping;
    }

    /**
     * Ambil detail lengkap matriks 3x3 & metrik konsistensi untuk seluruh kategori (tampilan dashboard admin).
     *
     * @return array<string, array<string, mixed>>
     */
    public static function getAllMatrixBreakdowns(): array
    {
        $stored = AhpHasilBobot::all()->keyBy('kategori');
        $breakdowns = [];

        foreach (self::CATEGORIES as $kategori => $meta) {
            $row = $stored->get($kategori);
            if ($row) {
                $calc = self::computeAhp3x3((float) $row->gm_12, (float) $row->gm_13, (float) $row->gm_23);
                $count = (int) $row->jumlah_responden;
            } else {
                $calc = self::computeAhp3x3(1.0, 1.0, 1.0);
                $count = 0;
            }

            $breakdowns[$kategori] = [
                'meta'             => $meta,
                'jumlah_responden' => $count,
                'calc'             => $calc,
                'stored'           => $row,
            ];
        }

        return $breakdowns;
    }

    /**
     * Hitung ulang skor SPK AHP dengan bobot kriteria & skala subkriteria dinamis dari DB.
     * Jika respon pelanggan menyimpan label opsi asli (opsi_*), nilai numeriknya otomatis
     * disinkronkan terlebih dahulu mengikuti skala subkriteria AHP terbaru.
     */
    public static function recalculateFromKuisioner(): void
    {
        $pakets = PaketBundling::all();
        if ($pakets->isEmpty()) {
            return;
        }

        $weights = self::getActiveCriteriaWeights();
        $mapping = self::getActiveSubcriteriaMapping();

        // TAHAP 1: Kumpulkan nilai mentah (rata-rata kuisioner atau baseline netral jika belum ada kuisioner)
        $rawScores = [];
        foreach ($pakets as $paket) {
            $kuisioners = KuisionerPenilaian::where('paket_id', $paket->id)->get();

            if ($kuisioners->isNotEmpty()) {
                foreach ($kuisioners as $k) {
                    $dirty = false;
                    foreach (['harga', 'kualitas', 'fasilitas'] as $crit) {
                        $opsiField = 'opsi_' . $crit;
                        $nilaiField = 'nilai_' . $crit;
                        if (!empty($k->$opsiField) && isset($mapping[$crit][$k->$opsiField])) {
                            $newVal = $mapping[$crit][$k->$opsiField];
                            if (abs((float) $k->$nilaiField - $newVal) > 0.00005) {
                                $k->$nilaiField = $newVal;
                                $dirty = true;
                            }
                        }
                    }
                    if ($dirty) {
                        $k->save();
                    }
                }

                $rawScores[$paket->id] = [
                    'harga'     => (float) $kuisioners->avg('nilai_harga'),
                    'fasilitas' => (float) $kuisioners->avg('nilai_fasilitas'),
                    'kualitas'  => (float) $kuisioners->avg('nilai_kualitas'),
                ];
            } else {
                // Baseline netral agar tidak bias terhadap ID paket ketika belum ada penilaian pelanggan
                $rawScores[$paket->id] = [
                    'harga'     => self::NEUTRAL_BASELINE,
                    'fasilitas' => self::NEUTRAL_BASELINE,
                    'kualitas'  => self::NEUTRAL_BASELINE,
                ];
            }
        }

        // TAHAP 2: Jumlah total kolom tiap kriteria
        $sumHarga = array_sum(array_column($rawScores, 'harga')) ?: 1.0;
        $sumFasilitas = array_sum(array_column($rawScores, 'fasilitas')) ?: 1.0;
        $sumKualitas = array_sum(array_column($rawScores, 'kualitas')) ?: 1.0;

        // TAHAP 3: Normalisasi kolom & pembobotan memakai bobot dinamis DB
        foreach ($pakets as $paket) {
            $raw = $rawScores[$paket->id];

            $normHarga = round($raw['harga'] / $sumHarga, 4);
            $normFasilitas = round($raw['fasilitas'] / $sumFasilitas, 4);
            $normKualitas = round($raw['kualitas'] / $sumKualitas, 4);

            $score = ($normHarga * $weights['harga']) +
                     ($normKualitas * $weights['kualitas']) +
                     ($normFasilitas * $weights['fasilitas']);

            $penilaian = PaketPenilaian::firstOrNew(['paket_id' => $paket->id]);
            $penilaian->nilai_harga = $normHarga;
            $penilaian->nilai_fasilitas = $normFasilitas;
            $penilaian->nilai_kualitas = $normKualitas;
            $penilaian->skor_akhir = round($score, 4);
            $penilaian->save();
        }

        // TAHAP 4: Penentuan ranking AHP
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
