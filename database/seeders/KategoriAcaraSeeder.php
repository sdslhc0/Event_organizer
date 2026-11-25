<?php

namespace Database\Seeders;

use App\Models\Acara;
use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class KategoriAcaraSeeder extends Seeder
{
    public function run(): void
    {
        $kategorisData = [
            ['tipe' => 'Dekorasi',        'nama' => 'Wedding'],
            ['tipe' => 'Dekorasi',        'nama' => 'Birthday'],
            ['tipe' => 'Dekorasi',        'nama' => 'Engagement'],
            ['tipe' => 'Dekorasi',        'nama' => 'Gathering'],
            ['tipe' => 'Dekorasi',        'nama' => 'Syukuran'],
            ['tipe' => 'Dekorasi',        'nama' => 'Umum'],
            ['tipe' => 'Catering',        'nama' => 'Basic'],
            ['tipe' => 'Catering',        'nama' => 'Standar'],
            ['tipe' => 'Catering',        'nama' => 'Premium'],
            ['tipe' => 'Fotografer',      'nama' => 'Basic'],
            ['tipe' => 'Fotografer',      'nama' => 'Standar'],
            ['tipe' => 'Fotografer',      'nama' => 'Premium'],
            ['tipe' => 'Rias dan Busana', 'nama' => 'Tradisional'],
            ['tipe' => 'Rias dan Busana', 'nama' => 'Umum'],
            ['tipe' => 'Musik',           'nama' => 'Band'],
            ['tipe' => 'Musik',           'nama' => 'Akustik'],
            ['tipe' => 'Musik',           'nama' => 'Organ Tunggal'],
            ['tipe' => 'Musik',           'nama' => 'Tradisional & Adat'],
            ['tipe' => 'Buku Undangan',   'nama' => 'Digital'],
            ['tipe' => 'Buku Undangan',   'nama' => 'Cetak Eksklusif'],
        ];

        $kategoriMap = [];
        foreach ($kategorisData as $item) {
            $kat = Kategori::firstOrCreate($item);
            $kategoriMap[$item['tipe'] . '|' . $item['nama']] = $kat->id;
        }

        $acarasData = [
            [
                'kategori' => 'Dekorasi|Wedding',
                'nama' => 'Dekorasi Elegant Wedding',
                'harga' => 8000000,
                'foto' => 'acara/iYvRyiBuasRF2pCn6PU22cp2xHM0FKNWrxHrrBMl.jpg',
                'deskripsi' => 'Dekorasi pelaminan lengkap tema elegant.',
            ],
            [
                'kategori' => 'Dekorasi|Birthday',
                'nama' => 'Dekorasi Birthday Rustic',
                'harga' => 10000000,
                'foto' => 'acara/eDsudxCAiflkbLHh7gnfau9bD6I3ys070fooRwaP.jpg',
                'deskripsi' => 'Dekorasi ulang tahun tema rustic kekinian.',
            ],
            [
                'kategori' => 'Dekorasi|Engagement',
                'nama' => 'Romatic Floral Elegance',
                'harga' => 2500000,
                'foto' => 'acara/0jJBZ74aFBgfLVuOQ7rNvdRVdpeGstBpf1e0WYkS.jpg',
                'deskripsi' => 'Rayakan awal dari perjalanan indah bersama dengan dekorasi elegan dan romantis yang di rancang untuk membuat momen spesialmu tak terlupakan',
            ],
            [
                'kategori' => 'Dekorasi|Umum',
                'nama' => 'Grandeur Signature Decor',
                'harga' => 4000000,
                'foto' => 'acara/0qIc5Hy2x3aSaObv9bIczRo1XwEIAo2j1Zop7aJA.jpg',
                'deskripsi' => 'Dekorasi serbaguna dengan tampilan elegan dan hangat yang dapat disesuaikan dengan tema acara apapun — dari pernikahan, ulang tahun, lamaran, syukuran, gathering, hingga perayaan lainnya.',
            ],
            [
                'kategori' => 'Catering|Basic',
                'nama' => 'Paket Catering Basic',
                'harga' => 25000,
                'foto' => 'acara/yEkb4cbfbY4POB19Lso6sWqQQDx45Hgq0DM8UnbG.jpg',
                'deskripsi' => 'Paket prasmanan sederhana berisi 1 pilihan nasi (nasi putih atau nasi kuning), 2 lauk utama (misalnya ayam goreng dan telur balado atau ayam kecap dan ikan goreng), 1 menu sayur (seperti tumis kangkung atau capcay sederhana), serta air mineral. Cocok untuk acara kasual seperti syukuran, arisan, atau gathering kecil.',
            ],
            [
                'kategori' => 'Catering|Standar',
                'nama' => 'Paket Catering Standar',
                'harga' => 50000,
                'foto' => 'acara/4uWfDiXwSvbes4qFPVkpwEDKBdlDNwy8TtkBxGhU.jpg',
                'deskripsi' => 'Paket prasmanan lengkap berisi 1 pilihan nasi (nasi putih atau nasi goreng), 3 lauk utama yang terdiri dari kombinasi ayam, daging sapi, dan seafood (misalnya ayam bakar, rendang sapi, dan udang saus tiram), 2 menu sayuran (capcay dan tumis buncis), 1 sup (sup ayam atau sop kimlo), lengkap dengan dessert (puding atau buah segar) serta minuman (es teh atau air mineral). Cocok untuk acara ulang tahun, lamaran, hingga acara keluarga.',
            ],
            [
                'kategori' => 'Catering|Premium',
                'nama' => 'Paket Catering Premium',
                'harga' => 95000,
                'foto' => 'acara/7Bu4eJXs6D59Edfgzb82hv9EuyzlE3xI8PJ0xXh8.jpg',
                'deskripsi' => 'Paket prasmanan mewah full course yang terdiri dari pilihan karbohidrat (nasi putih/nasi kuning atau pasta seperti spaghetti/penne), 4 lauk utama premium yang mencakup daging sapi (misalnya beef blackpepper), ayam (ayam panggang herb), seafood (udang atau ikan saus mentega), dan menu vegetarian (tumis sayuran premium atau tofu steak), 2 menu sayur (capcay dan asparagus/cauliflower sauté), sup krim (mushroom cream soup atau chicken cream soup), aneka dessert (cake slice, pudding, dan buah segar), serta free flow minuman (soft drink, infused water, dan teh). Cocok untuk wedding reception, anniversary, maupun corporate dinner formal.',
            ],
            [
                'kategori' => 'Fotografer|Basic',
                'nama' => 'Basic Package',
                'harga' => 1500000,
                'foto' => 'acara/J8bYJXmTGKKHt8gPUDC1DJz1VenbWHyh7dmBRDX1.jpg',
                'deskripsi' => 'Sesi foto profesional selama 4 jam bersama 1 fotografer berpengalaman. Menghasilkan 100+ foto edited siap pakai yang dikirimkan langsung via Google Drive. Termasuk free konsultasi konsep sebelum acara.',
            ],
            [
                'kategori' => 'Fotografer|Standar',
                'nama' => 'Standard Package',
                'harga' => 3000000,
                'foto' => 'acara/Wxn8TjxPIVYdOFERM4KewBe8kfSVUlHYzOAExmSB.jpg',
                'deskripsi' => 'Sesi foto dan video selama 8 jam dengan 1 fotografer dan 1 asisten. Menghasilkan 200+ foto edited, highlight video 1–2 menit, dan album digital. Dilengkapi free konsultasi dan diskusi konsep untuk hasil terbaik.',
            ],
            [
                'kategori' => 'Fotografer|Premium',
                'nama' => 'Premium Package',
                'harga' => 5500000,
                'foto' => 'acara/bmDMGoJkI38LpRfRQEQm5xaPNFc5vpX5sYkSA6PP.jpg',
                'deskripsi' => 'Layanan full day selama 12 jam dengan 2 fotografer profesional. Menghasilkan 300+ foto edited dengan premium retouch, cinematic video 3–5 menit, album cetak eksklusif, semua file tersimpan di harddisk, dan prioritas editing lebih cepat.',
            ],
            [
                'kategori' => 'Rias dan Busana|Tradisional',
                'nama' => 'Adat Jawa',
                'harga' => 7000000,
                'foto' => 'acara/RYivsJDMEME7TzM7Bx8jr0l5pKOJiuULjsmL0QBi.jpg',
                'deskripsi' => 'Tampil anggun dengan balutan busana Jawa yang megah, dilengkapi riasan paes tradisional, rangkaian melati segar, aksesori lengkap, dan makeup profesional yang memesona.',
            ],
            [
                'kategori' => 'Rias dan Busana|Tradisional',
                'nama' => 'Adat Minang',
                'harga' => 6000000,
                'foto' => 'acara/16g7FadF3TP206M3BgdzxeGP0kVUrVhmPfJVP9wg.jpg',
                'deskripsi' => 'Tampil memukau dengan Suntiang megah khas Minangkabau, busana pengantin lengkap, aksesori tradisional, dan riasan profesional yang kaya warna dan detail.',
            ],
            [
                'kategori' => 'Rias dan Busana|Tradisional',
                'nama' => 'Adat Batak',
                'harga' => 5500000,
                'foto' => 'acara/0Na1xGZwEYcuX11D6GZ1iNHpCniYjny3zEXn5Bdn.jpg',
                'deskripsi' => 'Tampil berwibawa dengan Sortali emas dan kain Ulos pilihan, busana adat lengkap, serta riasan profesional yang sarat makna budaya Batak',
            ],
            [
                'kategori' => 'Rias dan Busana|Tradisional',
                'nama' => 'Adat Sunda',
                'harga' => 6500000,
                'foto' => 'acara/Jrnel4SKc5w50z8TXY3i5xWVTWVhRSICD8ZxOD6o.jpg',
                'deskripsi' => 'Tampil cantik dan anggun dengan busana kebaya Sunda yang elegan, siger emas khas Sunda, aksesori tradisional lengkap, dan riasan profesional bernuansa lembut dan natural.',
            ],
            [
                'kategori' => 'Rias dan Busana|Umum',
                'nama' => 'Grandeur Beauty & Attire',
                'harga' => 1500000,
                'foto' => 'acara/NC7mrj3CGiTx3mTfOn51WxZs2b9g154P6VIjvlHK.jpg',
                'deskripsi' => 'Tampil memukau di setiap momen spesialmu dengan riasan profesional dan busana pilihan yang dapat disesuaikan dengan tema acara — dari pernikahan, lamaran, ulang tahun, wisuda, hingga acara formal lainnya. Setiap detail dirancang untuk membuatmu tampil percaya diri dan tak terlupakan.',
            ],
            [
                'kategori' => 'Musik|Band',
                'nama' => 'Live Band',
                'harga' => 4000000,
                'foto' => 'acara/8urNzdpL1SNGUBzGZMnCnp2pVjI7QeMvfH0WO8Jz.jpg',
                'deskripsi' => 'Penampilan live band 4-5 orang dengan sound system lengkap. Membawakan berbagai genre musik sesuai permintaan dan suasana acara. Cocok untuk segala jenis perayaan yang ingin terasa meriah dan berkesan.',
            ],
            [
                'kategori' => 'Musik|Akustik',
                'nama' => 'Akustik Santai',
                'harga' => 1500000,
                'foto' => 'acara/sq7WfFubuPbfuICcEq6JDsxFqpOCqf4s1MQvaEVP.jpg',
                'deskripsi' => 'Penampilan duo akustik (gitar & vokal) dengan repertoar lagu pop Indonesia dan internasional. Suasana hangat dan intim, cocok untuk berbagai acara seperti birthday, syukuran, lamaran, maupun gathering keluarga.',
            ],
            [
                'kategori' => 'Musik|Organ Tunggal',
                'nama' => 'Music Organ Tunggal',
                'harga' => 2499999,
                'foto' => 'acara/LK5f79pMLKGfr2incsZEWJHuChRoJ4ohjCa0hJy5.jpg',
                'deskripsi' => 'Hiburan musik serbaguna dengan organ tunggal dan MC yang interaktif. Bisa membawakan lagu dangdut, pop, hingga religi sesuai permintaan tamu. Pas untuk berbagai acara dari yang formal hingga santai.',
            ],
            [
                'kategori' => 'Musik|Tradisional & Adat',
                'nama' => 'Music Tradisonal Dan Adat',
                'harga' => 2000000,
                'foto' => 'acara/sgWjJCGYIJ9ceUV4jfEFtR9ySPGw1DDtrXoWgQf4.jpg',
                'deskripsi' => 'Penampilan musik tradisional dengan pilihan gamelan, rebana, atau hadrah. Membawakan lagu-lagu daerah, religi, maupun iringan prosesi adat sesuai kebutuhan acara. Cocok untuk pernikahan adat, pengajian, syukuran, maupun acara kebudayaan lainnya.',
            ],
            [
                'kategori' => 'Buku Undangan|Digital',
                'nama' => 'Undangan Digital Interaktif',
                'harga' => 150000,
                'foto' => 'acara/nniJkf0qTXMju7ktE56ph5WmSbvKkgFWdY1Dpk4j.jpg',
                'deskripsi' => 'Undangan berbasis digital dalam format video animasi atau website undangan eksklusif, lengkap dengan informasi acara, lokasi, dan RSVP online. Praktis, modern, dan bisa disebarkan langsung via WhatsApp atau media sosial.',
            ],
            [
                'kategori' => 'Buku Undangan|Cetak Eksklusif',
                'nama' => 'Undangan Cetak Eksklusif',
                'harga' => 5000,
                'foto' => 'acara/JfpDCiRCJ8Ew7so3pMESyZo8a0GmhhBiCR0lGpu5.jpg',
                'deskripsi' => 'Undangan fisik dengan desain elegan dan bahan kertas premium, tersedia dalam berbagai pilihan motif sesuai tema acara. Dilengkapi amplop dan finishing laminasi untuk kesan yang lebih mewah.',
            ],
        ];

        foreach ($acarasData as $item) {
            $this->copySeedAsset($item['foto']);

            $idKategori = $kategoriMap[$item['kategori']];
            Acara::firstOrCreate(
                [
                    'id_kategori' => $idKategori,
                    'nama' => $item['nama'],
                ],
                [
                    'harga' => $item['harga'],
                    'foto' => $item['foto'],
                    'deskripsi' => $item['deskripsi'],
                ]
            );
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
