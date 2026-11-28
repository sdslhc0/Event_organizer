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
        $admin = User::where('email', 'admin@melodi.com')->first();
        $userDemo = User::where('email', 'user@melodi.com')->first();
        $silver = PaketBundling::where('nama', 'Silver Package')->first();
        $gold = PaketBundling::where('nama', 'Gold Package')->first();
        $platinum = PaketBundling::where('nama', 'Platinum Package')->first();
        $diamond = PaketBundling::where('nama', 'Diamond Package')->first();

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
                        'opsi_fasilitas' => 'sangat_lengkap',
                        'opsi_harga'     => 'murah',
                        'opsi_kualitas'  => 'sangat_baik',
                        'komentar'       => 'Layanan sangat memuaskan, tim EO sangat profesional!',
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
                        'opsi_fasilitas' => 'sangat_lengkap',
                        'opsi_harga'     => 'murah',
                        'opsi_kualitas'  => 'sangat_baik',
                        'komentar'       => 'Dekorasi dan alur acara rapi sekali, sangat sesuai ekspektasi kami.',
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
                        'opsi_fasilitas' => 'sangat_lengkap',
                        'opsi_harga'     => 'murah',
                        'opsi_kualitas'  => 'sangat_baik',
                        'komentar'       => 'Paket Gold sangat worth it dan berkesan untuk acara keluarga kami.',
                    ],
                ],
            ];

            $mapping = SpkService::getActiveSubcriteriaMapping();

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
                    $q = $dbItem['kuisioner'];
                    KuisionerPenilaian::create([
                        'id_booking'      => $booking->id,
                        'id_users'        => $userDemo->id,
                        'paket_id'        => $dbItem['paket']->id,
                        'opsi_fasilitas'  => $q['opsi_fasilitas'],
                        'opsi_harga'      => $q['opsi_harga'],
                        'opsi_kualitas'   => $q['opsi_kualitas'],
                        'nilai_fasilitas' => $mapping['fasilitas'][$q['opsi_fasilitas']],
                        'nilai_harga'     => $mapping['harga'][$q['opsi_harga']],
                        'nilai_kualitas'  => $mapping['kualitas'][$q['opsi_kualitas']],
                        'komentar'        => $q['komentar'],
                    ]);
                }
            }

            // 6-15. 10 Pelanggan + Booking + Kuisioner SPK (nilai dihitung dinamis dari AhpRespondenSeeder)
            $respondents = [
                ['email' => 'amanda.putri@gmail.com',   'jenis' => 'Wedding',           'paket' => $silver,   'fas' => 'sangat_lengkap', 'hrg' => 'murah',      'kua' => 'sangat_baik', 'komentar' => 'Dekorasi dan catering sangat lezat, tamu-tamu kami sangat puas!'],
                ['email' => 'rizky.ramadhan@gmail.com', 'jenis' => 'Lamaran',           'paket' => $silver,   'fas' => 'sangat_lengkap', 'hrg' => 'murah',      'kua' => 'sangat_baik', 'komentar' => 'Harga sangat terjangkau untuk paket selengkap ini, sangat direkomendasikan.'],
                ['email' => 'dimas.pratama@gmail.com',  'jenis' => 'Wedding',           'paket' => $silver,   'fas' => 'lengkap',        'hrg' => 'murah',      'kua' => 'baik',        'komentar' => 'Semua acara lancar dan rapi, pelayanan ramah.'],
                ['email' => 'bella.shofie@gmail.com',   'jenis' => 'Wedding',           'paket' => $silver,   'fas' => 'sangat_lengkap', 'hrg' => 'murah',      'kua' => 'sangat_baik', 'komentar' => 'MUA dan gaun pengantinnya cantik sekali, pas di hati.'],
                ['email' => 'kevin.sanjaya@gmail.com',  'jenis' => 'Wedding',           'paket' => $gold,     'fas' => 'sangat_lengkap', 'hrg' => 'terjangkau', 'kua' => 'sangat_baik', 'komentar' => 'Paket Gold sangat pas untuk pesta 1000 porsi, tamu terlayani dengan sangat baik.'],
                ['email' => 'citra.kirana@gmail.com',   'jenis' => 'Wedding',           'paket' => $gold,     'fas' => 'sangat_lengkap', 'hrg' => 'terjangkau', 'kua' => 'sangat_baik', 'komentar' => 'Sound system dan live band-nya sangat memeriahkan suasana resepsi.'],
                ['email' => 'farhan.m@gmail.com',       'jenis' => 'Gathering',         'paket' => $gold,     'fas' => 'lengkap',        'hrg' => 'murah',      'kua' => 'sangat_baik', 'komentar' => 'Koordinasi panitia EO sangat gesit dan komunikatif.'],
                ['email' => 'tiara.andini@gmail.com',   'jenis' => 'Wedding',           'paket' => $gold,     'fas' => 'sangat_lengkap', 'hrg' => 'terjangkau', 'kua' => 'baik',        'komentar' => 'Fasilitas sangat lengkap, dekorasi pelaminan megah!'],
                ['email' => 'aditya.nugraha@gmail.com', 'jenis' => 'Wedding Exclusive', 'paket' => $platinum, 'fas' => 'sangat_lengkap', 'hrg' => 'mahal',      'kua' => 'sangat_baik', 'komentar' => 'Paket Platinum benar-benar sultan, sangat mewah dan prestisius!'],
                ['email' => 'maya.anggraini@gmail.com', 'jenis' => 'Grand Wedding',     'paket' => $platinum, 'fas' => 'sangat_lengkap', 'hrg' => 'mahal',      'kua' => 'sangat_baik', 'komentar' => 'Semua tamu VIP terkesan dengan tata panggung dan sajian menunya.'],
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
                    'id_booking'      => $booking->id,
                    'id_users'        => $user->id,
                    'paket_id'        => $r['paket']->id,
                    'opsi_fasilitas'  => $r['fas'],
                    'opsi_harga'      => $r['hrg'],
                    'opsi_kualitas'   => $r['kua'],
                    'nilai_fasilitas' => $mapping['fasilitas'][$r['fas']],
                    'nilai_harga'     => $mapping['harga'][$r['hrg']],
                    'nilai_kualitas'  => $mapping['kualitas'][$r['kua']],
                    'komentar'        => $r['komentar'],
                ]);
            }

            // 17-21. Data booking & kuisioner tambahan (termasuk Diamond Package)
            $extraBookings = [
                [
                    'nama_lengkap' => 'mm',
                    'no_whatsapp' => '08991919',
                    'jenis_acara' => 'Lamaran',
                    'jumlah_tamu' => 900,
                    'selesai' => '11:14',
                    'paket' => $gold,
                    'tanggal' => '2026-12-17',
                    'catatan' => 'mmm',
                    'kuisioner' => ['fas' => 'sangat_lengkap', 'hrg' => 'murah', 'kua' => 'sangat_baik'],
                ],
                [
                    'nama_lengkap' => 'mm',
                    'no_whatsapp' => '90',
                    'jenis_acara' => 'Lamaran',
                    'jumlah_tamu' => 88,
                    'selesai' => '11:15',
                    'paket' => $gold,
                    'tanggal' => '2026-12-02',
                    'catatan' => 'n',
                    'kuisioner' => ['fas' => 'sangat_lengkap', 'hrg' => 'murah', 'kua' => 'sangat_baik'],
                ],
                [
                    'nama_lengkap' => 'mm',
                    'no_whatsapp' => '9',
                    'jenis_acara' => 'Birthday',
                    'jumlah_tamu' => 9,
                    'selesai' => '11:39',
                    'paket' => $diamond,
                    'tanggal' => '2026-12-09',
                    'catatan' => null,
                    'kuisioner' => null,
                ],
                [
                    'nama_lengkap' => 'jamal',
                    'no_whatsapp' => '869583',
                    'jenis_acara' => 'Corporate',
                    'jumlah_tamu' => 889,
                    'selesai' => '11:47',
                    'paket' => $diamond,
                    'tanggal' => '2026-12-13',
                    'catatan' => null,
                    'kuisioner' => ['fas' => 'sangat_lengkap', 'hrg' => 'mahal', 'kua' => 'sangat_baik'],
                ],
                [
                    'nama_lengkap' => 'dika',
                    'no_whatsapp' => '888',
                    'jenis_acara' => 'Corporate',
                    'jumlah_tamu' => 87,
                    'selesai' => '11:48',
                    'paket' => $platinum,
                    'tanggal' => '2026-12-19',
                    'catatan' => '900',
                    'kuisioner' => ['fas' => 'sangat_lengkap', 'hrg' => 'murah', 'kua' => 'sangat_baik'],
                ],
            ];

            $actorId = $admin ? $admin->id : $userDemo->id;
            foreach ($extraBookings as $ex) {
                if (!$ex['paket']) {
                    continue;
                }

                $booking = Booking::create([
                    'id_users' => $actorId,
                    'nama_lengkap' => $ex['nama_lengkap'],
                    'no_whatsapp' => $ex['no_whatsapp'],
                    'jenis_acara' => $ex['jenis_acara'],
                    'biaya_venue' => $ex['paket']->harga,
                    'jumlah_tamu' => $ex['jumlah_tamu'],
                    'acara_mulai' => '08:00',
                    'acara_selesai' => $ex['selesai'],
                    'id_paket_bundling' => $ex['paket']->id,
                    'tanggal' => $ex['tanggal'],
                    'subtotal' => $ex['paket']->harga,
                    'metode_pembayaran' => 'full_payment',
                    'bank' => 'BCA - Transfer',
                    'catatan' => $ex['catatan'],
                ]);

                DetailBooking::create(['id_booking' => $booking->id, 'status' => 'pending']);

                if ($ex['kuisioner']) {
                    $q = $ex['kuisioner'];
                    KuisionerPenilaian::create([
                        'id_booking'      => $booking->id,
                        'id_users'        => $actorId,
                        'paket_id'        => $ex['paket']->id,
                        'opsi_fasilitas'  => $q['fas'],
                        'opsi_harga'      => $q['hrg'],
                        'opsi_kualitas'   => $q['kua'],
                        'nilai_fasilitas' => $mapping['fasilitas'][$q['fas']],
                        'nilai_harga'     => $mapping['harga'][$q['hrg']],
                        'nilai_kualitas'  => $mapping['kualitas'][$q['kua']],
                        'komentar'        => null,
                    ]);
                }
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
