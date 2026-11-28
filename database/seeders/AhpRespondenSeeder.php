<?php

namespace Database\Seeders;

use App\Models\AhpResponden;
use App\Services\SpkService;
use Illuminate\Database\Seeder;

class AhpRespondenSeeder extends Seeder
{
    public function run(): void
    {
        if (AhpResponden::count() > 0) {
            SpkService::calculateAhpFromRespondents();
            return;
        }

        // Pola 23 responden dasar per tab (diulang 5x = 115 responden per tab, total 460 baris)
        // Catatan: Tab sub_kualitas & sub_fasilitas menggunakan urutan kolom baku (1 vs 2 | 1 vs 3 | 2 vs 3)
        $patterns = [
            'kriteria' => [
                [3,3,1],[2,4,2],[3,6,2],[4,4,1],[2,6,3],[3,6,2],[2,4,2],[3,3,1],
                [3,6,2],[2,4,2],[4,4,1],[3,6,2],[2,6,3],[3,3,1],[1,5,5],[3,6,2],
                [4,4,1],[2,4,2],[3,6,2],[2,6,3],[3,3,1],[4,4,1],[2,4,2],
            ],
            'sub_harga' => [
                [2,4,2],[3,6,2],[2,4,2],[2,4,2],[3,6,2],[2,4,2],[3,6,2],[2,4,2],
                [2,4,2],[3,6,2],[2,4,2],[2,4,2],[3,6,2],[2,4,2],[2,4,2],[3,6,2],
                [2,4,2],[2,4,2],[2,4,2],[3,6,2],[2,4,2],[2,4,2],[3,6,2],
            ],
            'sub_kualitas' => [
                [3,6,2],[2,4,2],[2,6,3],[2,4,2],[3,6,2],[2,6,3],[2,4,2],[3,6,2],
                [2,6,3],[2,4,2],[3,6,2],[2,6,3],[2,4,2],[3,6,2],[2,6,3],[2,4,2],
                [3,6,2],[2,6,3],[2,4,2],[3,6,2],[2,6,3],[2,4,2],[3,6,2],
            ],
            'sub_fasilitas' => [
                [2,4,2],[3,6,2],[2,6,3],[2,4,2],[2,4,2],[2,6,3],[3,6,2],[2,4,2],
                [2,6,3],[2,4,2],[3,6,2],[2,6,3],[2,4,2],[3,6,2],[2,6,3],[2,4,2],
                [3,6,2],[2,6,3],[2,4,2],[3,6,2],[2,6,3],[2,4,2],[3,6,2],
            ],
        ];

        $now = now();
        $rows = [];
        foreach ($patterns as $kategori => $base23) {
            for ($rep = 0; $rep < 5; $rep++) {
                foreach ($base23 as $vals) {
                    $rows[] = [
                        'kategori'       => $kategori,
                        'nama_responden' => null,
                        'nilai_12'       => $vals[0],
                        'nilai_13'       => $vals[1],
                        'nilai_23'       => $vals[2],
                        'created_at'     => $now,
                        'updated_at'     => $now,
                    ];
                }
            }
        }

        foreach (array_chunk($rows, 200) as $batch) {
            AhpResponden::insert($batch);
        }

        SpkService::calculateAhpFromRespondents();
    }
}
