# MELODI — Wedding & Event Organizer

Aplikasi web manajemen layanan *Wedding & Event Organizer* berbasis **Laravel 12**, **Tailwind CSS**, **Alpine.js**, dan **Chart.js** yang dilengkapi sistem pemesanan multi-tahap, paket bundling, kuisioner pelanggan, serta Sistem Pendukung Keputusan (SPK) menggunakan metode **Analytical Hierarchy Process (AHP)** untuk merekomendasikan paket bundling terbaik.

---

## Prasyarat Sistem

Pastikan perangkat lokal Anda sudah terpasang:
- **PHP** `>= 8.2` (beserta ekstensi `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, dan `gd`)
- **Composer**
- **Node.js** & **NPM**
- **MySQL / MariaDB** (bisa menggunakan Laragon, XAMPP, atau Docker)

---

## Cara Instalasi di Lokal

Ikuti langkah-langkah berikut secara berurutan:

### 1. Clone / Masuk ke Folder Proyek
```bash
git clone <url-repository-anda>
cd event_organizer
```

### 2. Install Dependency Backend & Frontend
```bash
composer install
npm install
```

### 3. Konfigurasi File `.env`
Salin file `.env.example` menjadi `.env`:
```bash
# Windows (PowerShell / CMD)
copy .env.example .env

# macOS / Linux
cp .env.example .env
```

Buka file `.env` dan sesuaikan konfigurasi database MySQL Anda:
```env
APP_NAME=MELODI
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wedding_organizer
DB_USERNAME=root
DB_PASSWORD=
```
> **Catatan:** Pastikan Anda sudah membuat database kosong di MySQL/phpMyAdmin sesuai nilai `DB_DATABASE` di atas (misalnya `wedding_organizer`).

### 4. Generate Key & Jalankan Migrasi Database
```bash
php artisan key:generate
php artisan migrate
```
Jika ingin langsung mengisi data awal (*user admin, user demo, kategori, dan layanan acara*):
```bash
php artisan db:seed
```
*(Atau jalankan sekaligus dengan `php artisan migrate --seed`)*

### 5. Hubungkan Storage Publik (`storage:link`)
Agar gambar yang diunggah (layanan acara, paket bundling, bukti pembayaran, dll.) dapat diakses oleh browser:
```bash
php artisan storage:link
```

### 6. Build Asset Frontend
Untuk mem-build CSS & JS produksi:
```bash
npm run build
```
*(Atau gunakan `npm run dev` di terminal terpisah jika sedang melakukan pengembangan tampilan)*

### 7. Jalankan Aplikasi
```bash
php artisan serve
```
Buka browser dan akses: **http://127.0.0.1:8000**

*(Jika menggunakan **Laragon**, pastikan document root mengarah ke folder `public/` atau jalankan lewat `php artisan serve`)*

---

## Akun Bawaan (Jika Menjalankan Seeder)

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `admin@melodi.com` | `password` |
| **User / Pelanggan** | `user@melodi.com` | `password` |

---

## Troubleshooting: Gambar Tidak Muncul

Jika foto layanan, paket bundling, atau gambar hasil upload lainnya tidak muncul (broken image), biasanya disebabkan oleh **folder atau shortcut `public/storage` yang sudah ada sebelumnya** masih mengarah ke path komputer lama / rusak.

**Solusi:**
1. **Hapus terlebih dahulu folder `storage` yang ada di dalam folder `public/`** (`public/storage`):
   - **Cara Manual:** Buka File Explorer/Finder -> masuk ke folder `public` -> hapus folder `storage`.
   - **Via Terminal:**
     ```bash
     # Windows (CMD)
     rmdir public\storage

     # Windows (PowerShell)
     Remove-Item public\storage -Recurse -Force

     # macOS / Linux
     rm -rf public/storage
     ```
2. **Buat ulang symlink storage Laravel:**
   ```bash
   php artisan storage:link
   ```
3. **Refresh browser** dengan *hard refresh* (`Ctrl + Shift + R`).

---

## Perintah Tambahan yang Berguna

Jika terjadi perubahan pada view/konfigurasi namun tampilan belum berubah, bersihkan cache Laravel dengan perintah:
```bash
php artisan optimize:clear
```
