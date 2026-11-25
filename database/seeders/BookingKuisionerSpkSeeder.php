<?php

namespace Database\Seeders;

use App\Models\Acara;
use App\Models\Booking;
use App\Models\BookingLayanan;
use App\Models\DetailBooking;
use App\Models\KuisionerPenilaian;
use App\Models\PaketBundling;
use App\Models\Review;
use App\Models\User;
use App\Services\SpkService;
use Illuminate\Database\Seeder;

class BookingKuisionerSpkSeeder extends Seeder
{
    public function run(): void
    {
        $userDemo = User::where('email', 'user@melodi.com')->first();
        $silver = PaketBundling::where('nama', 'Silver Package')->first();
        $gold = PaketBundling::where('nama', 'Gold Package')->first();
        $platinum = PaketBundling::where('nama', 'Platinum Package')->first();

        // Seed hanya jika tabel bookings masih kosong agar tidak duplikat saat db:seed dijalankan ulang
        if (Booking::count() === 0 && $userDemo && $silver && $gold && $platinum) {
            $dekorWedding = Acara::where('nama', 'Dekorasi Elegant Wedding')->first();
            $cateringBasic = Acara::where('nama', 'Paket Catering Basic')->first();

            // 1-2. Contoh pemesanan layanan custom (non-bundling)
            $bCustom1 = Booking::create([
                'id_users' => $userDemo->id,
                'nama_lengkap' => 'Sari Kusuma',
                'no_whatsapp' => '08789828282',
                'jenis_acara' => 'Wedding',
                'biaya_venue' => 19000000,
                'jumlah_tamu' => 1500,
                'acara_mulai' => '08:00',
                'acara_selesai' => '18:00',
                'tanggal' => now()->addMonths(1)->format('Y-m-d'),
                'subtotal' => 64500000,
                'metode_pembayaran' => 'dp_30',
                'bank' => 'BNI - Transfer',
                'catatan' => 'Tambahkan dekorasi dengan nuansa warna pink pastel',
            ]);
            DetailBooking::create(['id_booking' => $bCustom1->id, 'status' => 'pending']);
            if ($dekorWedding) {
                BookingLayanan::create(['id_booking' => $bCustom1->id, 'id_acara' => $dekorWedding->id, 'harga' => 8000000, 'qty' => 1]);
            }
            if ($cateringBasic) {
                BookingLayanan::create(['id_booking' => $bCustom1->id, 'id_acara' => $cateringBasic->id, 'harga' => 37500000, 'qty' => 1500]);
            }

            $bCustom2 = Booking::create([
                'id_users' => $userDemo->id,
                'nama_lengkap' => 'Zikri Hakim',
                'no_whatsapp' => '08718181900',
                'jenis_acara' => 'Lamaran',
                'biaya_venue' => 17000000,
                'jumlah_tamu' => 300,
                'acara_mulai' => '09:00',
                'acara_selesai' => '14:00',
                'tanggal' => now()->addDays(25)->format('Y-m-d'),
                'subtotal' => 25000000,
                'metode_pembayaran' => 'full_payment',
                'bank' => 'BNI - Transfer',
                'catatan' => 'Mohon siapkan backdrop bunga segar',
            ]);
            DetailBooking::create(['id_booking' => $bCustom2->id, 'status' => 'selesai']);
            if ($dekorWedding) {
                BookingLayanan::create(['id_booking' => $bCustom2->id, 'id_acara' => $dekorWedding->id, 'harga' => 8000000, 'qty' => 1]);
            }

            // 3-5. Contoh booking bundling milik User Demo (bisa dilihat langsung saat login user@melodi.com)
            $demoBundlings = [
                [
                    'nama_lengkap' => 'Chairunnisa Hanafi',
                    'no_whatsapp' => '085270314523',
                    'jenis_acara' => 'Wedding',
                    'jumlah_tamu' => 500,
                    'paket' => $silver,
                    'status' => 'di booking',
                    'days' => 18,
                    'kuisioner' => null,
                ],
                [
                    'nama_lengkap' => 'Nisa & Fajar',
                    'no_whatsapp' => '081574779615',
                    'jenis_acara' => 'Wedding',
                    'jumlah_tamu' => 500,
                    'paket' => $silver,
                    'status' => 'selesai',
                    'days' => -20,
                    'kuisioner' => [
                        'fasilitas' => 0.61,
                        'harga' => 0.56,
                        'kualitas' => 0.71,
                        'komentar' => 'Layanan sangat memuaskan, tim EO sangat profesional!',
                    ],
                ],
                [
                    'nama_lengkap' => 'User Demo - Resepsi',
                    'no_whatsapp' => '08987654321',
                    'jenis_acara' => 'Wedding',
                    'jumlah_tamu' => 500,
                    'paket' => $silver,
                    'status' => 'selesai',
                    'days' => -14,
                    'kuisioner' => [
                        'fasilitas' => 0.61,
                        'harga' => 0.56,
                        'kualitas' => 0.71,
                        'komentar' => 'Dekorasi dan alur acara rapi sekali, sangat sesuai ekspektasi kami.',
                    ],
                ],
                [
                    'nama_lengkap' => 'User Demo - Lamaran',
                    'no_whatsapp' => '08987654321',
                    'jenis_acara' => 'Lamaran',
                    'jumlah_tamu' => 800,
                    'paket' => $gold,
                    'status' => 'selesai',
                    'days' => -10,
                    'kuisioner' => [
                        'fasilitas' => 0.61,
                        'harga' => 0.56,
                        'kualitas' => 0.71,
                        'komentar' => 'Paket Gold sangat worth it dan berkesan untuk acara keluarga kami.',
                    ],
                ],
            ];

            foreach ($demoBundlings as $dbItem) {
                $booking = Booking::create([
                    'id_users' => $userDemo->id,
                    'nama_lengkap' => $dbItem['nama_lengkap'],
                    'no_whatsapp' => $dbItem['no_whatsapp'],
                    'jenis_acara' => $dbItem['jenis_acara'],
                    'biaya_venue' => 15000000,
                    'jumlah_tamu' => $dbItem['jumlah_tamu'],
                    'acara_mulai' => '08:00',
                    'acara_selesai' => '16:00',
                    'id_paket_bundling' => $dbItem['paket']->id,
                    'tanggal' => now()->addDays($dbItem['days'])->format('Y-m-d'),
                    'subtotal' => $dbItem['paket']->harga,
                    'metode_pembayaran' => 'full_payment',
                    'bank' => 'BCA - Transfer',
                ]);
                DetailBooking::create(['id_booking' => $booking->id, 'status' => $dbItem['status']]);

                if ($dbItem['kuisioner']) {
                    KuisionerPenilaian::create([
                        'id_booking' => $booking->id,
                        'id_users' => $userDemo->id,
                        'paket_id' => $dbItem['paket']->id,
                        'nilai_fasilitas' => $dbItem['kuisioner']['fasilitas'],
                        'nilai_harga' => $dbItem['kuisioner']['harga'],
                        'nilai_kualitas' => $dbItem['kuisioner']['kualitas'],
                        'komentar' => $dbItem['kuisioner']['komentar'],
                    ]);
                }
            }

            // 6-15. 10 Pelanggan + Booking + Kuisioner SPK (Silver 42.1%, Gold 36.2%, Platinum 21.7%)
            $respondents = [
                ['email' => 'amanda.putri@gmail.com',   'jenis' => 'Wedding',           'paket' => $silver,   'fas' => 0.61, 'hrg' => 0.56, 'kua' => 0.71, 'komentar' => 'Dekorasi dan catering sangat lezat, tamu-tamu kami sangat puas!'],
                ['email' => 'rizky.ramadhan@gmail.com', 'jenis' => 'Lamaran',           'paket' => $silver,   'fas' => 0.61, 'hrg' => 0.56, 'kua' => 0.71, 'komentar' => 'Harga sangat terjangkau untuk paket selengkap ini, sangat direkomendasikan.'],
                ['email' => 'dimas.pratama@gmail.com',  'jenis' => 'Wedding',           'paket' => $silver,   'fas' => 0.30, 'hrg' => 0.56, 'kua' => 0.23, 'komentar' => 'Semua acara lancar dan rapi, pelayanan ramah.'],
                ['email' => 'bella.shofie@gmail.com',   'jenis' => 'Wedding',           'paket' => $silver,   'fas' => 0.61, 'hrg' => 0.56, 'kua' => 0.71, 'komentar' => 'MUA dan gaun pengantinnya cantik sekali, pas di hati.'],
                ['email' => 'kevin.sanjaya@gmail.com',  'jenis' => 'Wedding',           'paket' => $gold,     'fas' => 0.61, 'hrg' => 0.36, 'kua' => 0.71, 'komentar' => 'Paket Gold sangat pas untuk pesta 1000 porsi, tamu terlayani dengan sangat baik.'],
                ['email' => 'citra.kirana@gmail.com',   'jenis' => 'Wedding',           'paket' => $gold,     'fas' => 0.61, 'hrg' => 0.36, 'kua' => 0.71, 'komentar' => 'Sound system dan live band-nya sangat memeriahkan suasana resepsi.'],
                ['email' => 'farhan.m@gmail.com',       'jenis' => 'Gathering',         'paket' => $gold,     'fas' => 0.30, 'hrg' => 0.56, 'kua' => 0.71, 'komentar' => 'Koordinasi panitia EO sangat gesit dan komunikatif.'],
                ['email' => 'tiara.andini@gmail.com',   'jenis' => 'Wedding',           'paket' => $gold,     'fas' => 0.61, 'hrg' => 0.36, 'kua' => 0.23, 'komentar' => 'Fasilitas sangat lengkap, dekorasi pelaminan megah!'],
                ['email' => 'aditya.nugraha@gmail.com', 'jenis' => 'Wedding Exclusive', 'paket' => $platinum, 'fas' => 0.61, 'hrg' => 0.08, 'kua' => 0.71, 'komentar' => 'Paket Platinum benar-benar sultan, sangat mewah dan prestisius!'],
                ['email' => 'maya.anggraini@gmail.com', 'jenis' => 'Grand Wedding',     'paket' => $platinum, 'fas' => 0.61, 'hrg' => 0.08, 'kua' => 0.71, 'komentar' => 'Semua tamu VIP terkesan dengan tata panggung dan sajian menunya.'],
            ];

            foreach ($respondents as $idx => $r) {
                $user = User::where('email', $r['email'])->first();
                if (!$user) {
                    continue;
                }

                $booking = Booking::create([
                    'id_users' => $user->id,
                    'nama_lengkap' => $user->nama,
                    'no_whatsapp' => $user->telepon,
                    'jenis_acara' => $r['jenis'],
                    'biaya_venue' => 15000000,
                    'jumlah_tamu' => 500,
                    'acara_mulai' => '09:00',
                    'acara_selesai' => '17:00',
                    'id_paket_bundling' => $r['paket']->id,
                    'tanggal' => now()->subDays(12 - $idx)->format('Y-m-d'),
                    'subtotal' => $r['paket']->harga,
                    'metode_pembayaran' => 'full_payment',
                    'bank' => 'BCA - Transfer',
                ]);

                DetailBooking::create(['id_booking' => $booking->id, 'status' => 'selesai']);

                KuisionerPenilaian::create([
                    'id_booking' => $booking->id,
                    'id_users' => $user->id,
                    'paket_id' => $r['paket']->id,
                    'nilai_fasilitas' => $r['fas'],
                    'nilai_harga' => $r['hrg'],
                    'nilai_kualitas' => $r['kua'],
                    'komentar' => $r['komentar'],
                ]);
            }
        }

        // Seed ulasan/testimoni beranda bila masih kosong
        if (Review::count() === 0 && $userDemo) {
            $reviews = [
                [
                    'email' => 'amanda.putri@gmail.com',
                    'nama' => 'Amanda Putri',
                    'rating' => 5,
                    'komentar' => 'Pelayanannya luar biasa! Dari dekorasi sampai dokumentasi semuanya sangat rapi dan sesuai dengan yang kami impikan. Timnya juga ramah dan profesional.',
                ],
                [
                    'email' => 'kevin.sanjaya@gmail.com',
                    'nama' => 'Kevin & Citra',
                    'rating' => 5,
                    'komentar' => 'Sangat puas dengan servis dari MELODI! Mereka sangat detail dan sabar menghadapi revisi dari kami. Kateringnya juara, banyak tamu yang memuji rasanya.',
                ],
                [
                    'email' => 'maya.anggraini@gmail.com',
                    'nama' => 'Maya Anggraini',
                    'rating' => 5,
                    'komentar' => 'Venue yang disediakan sangat elegan dan sesuai budget kami. Acara berjalan mulus tanpa hambatan berarti berkat koordinasi tim di hari H yang sangat sigap.',
                ],
                [
                    'email' => 'rizky.ramadhan@gmail.com',
                    'nama' => 'Rizky Ramadhan',
                    'rating' => 4,
                    'komentar' => 'Respons cepat dan solutif. Dekorasi lamaran dan fotografernya keren banget, sukses selalu untuk MELODI Event Organizer!',
                ],
            ];

            foreach ($reviews as $rev) {
                $u = User::where('email', $rev['email'])->first() ?? $userDemo;
                Review::create([
                    'id_users' => $u->id,
                    'nama' => $rev['nama'],
                    'rating' => $rev['rating'],
                    'komentar' => $rev['komentar'],
                ]);
            }
        }

        // Hitung ulang skor & ranking SPK AHP otomatis berdasarkan data kuisioner
        SpkService::recalculateFromKuisioner();
    }
}
