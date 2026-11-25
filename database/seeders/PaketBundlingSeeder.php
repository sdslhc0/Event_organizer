<?php

namespace Database\Seeders;

use App\Models\Acara;
use App\Models\PaketBundling;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PaketBundlingSeeder extends Seeder
{
    public function run(): void
    {
        $bundlingsData = [
            [
                'nama' => 'Silver Package',
                'harga' => 65500000,
                'deskripsi' => 'Catering 500 pax dan Undangan 100 cetak',
                'gambar' => 'paket-bundling/UebKHP8PfDSnQ3oh11VGrNZaerAfOnWqQ6f9o5lB.jpg',
                'items' => [
                    'Grandeur Signature Decor',
                    'Standard Package',
                    'Grandeur Beauty & Attire',
                    'Akustik Santai',
                    'Undangan Cetak Eksklusif',
                    'Paket Catering Standar',
                ],
            ],
            [
                'nama' => 'Gold Package',
                'harga' => 95500000,
                'deskripsi' => 'Catering 1000 pax dan Undangan 500 cetak',
                'gambar' => 'paket-bundling/H0KxGhHaC564NJonGfPucWUyCDLYKuEvVaOeC1oa.jpg',
                'items' => [
                    'Grandeur Signature Decor',
                    'Standard Package',
                    'Grandeur Beauty & Attire',
                    'Music Organ Tunggal',
                    'Music Tradisonal Dan Adat',
                    'Undangan Digital Interaktif',
                    'Undangan Cetak Eksklusif',
                    'Paket Catering Standar',
                ],
            ],
            [
                'nama' => 'Platinum Package',
                'harga' => 194500000,
                'deskripsi' => 'Catering 1500 pax dan Undangan 1000 cetak',
                'gambar' => 'paket-bundling/gpxGRUyWFwr5xfDJs4rq0YTChmRlqsYvmk5OQUXm.jpg',
                'items' => [
                    'Grandeur Signature Decor',
                    'Premium Package',
                    'Grandeur Beauty & Attire',
                    'Live Band',
                    'Music Tradisonal Dan Adat',
                    'Undangan Digital Interaktif',
                    'Undangan Cetak Eksklusif',
                    'Paket Catering Premium',
                ],
            ],
        ];

        $acaraByName = Acara::pluck('id', 'nama');

        foreach ($bundlingsData as $data) {
            $this->copySeedAsset($data['gambar']);

            $paket = PaketBundling::firstOrCreate(
                ['nama' => $data['nama']],
                [
                    'harga' => $data['harga'],
                    'deskripsi' => $data['deskripsi'],
                    'gambar' => $data['gambar'],
                ]
            );

            $itemIds = collect($data['items'])
                ->map(fn($name) => $acaraByName[$name] ?? null)
                ->filter()
                ->values()
                ->all();

            if (!empty($itemIds)) {
                $paket->acaras()->syncWithoutDetaching($itemIds);
            }
        }
    }

    private function copySeedAsset(?string $relativePath): void
    {
        if (!$relativePath) {
            return;
        }

        $source = database_path('seeders/assets/' . $relativePath);
        $destination = storage_path('app/public/' . $relativePath);

        if (File::exists($source) && !File::exists($destination)) {
            File::ensureDirectoryExists(dirname($destination));
            File::copy($source, $destination);
        }
    }
}
