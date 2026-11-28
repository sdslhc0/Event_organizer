<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\KuisionerPenilaian;
use App\Services\SpkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KuisionerController extends Controller
{
    /**
     * Simpan jawaban kuisioner pelanggan dan perbarui skor SPK secara dinamis.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_booking' => 'required|exists:bookings,id',
            'paket_id' => 'required|exists:paket_bundlings,id',
            'fasilitas' => 'required|in:sangat_lengkap,lengkap,cukup_lengkap',
            'harga' => 'required|in:murah,terjangkau,mahal',
            'kualitas' => 'required|in:sangat_baik,baik,cukup_baik',
            'komentar' => 'nullable|string|max:1000',
        ]);

        $booking = Booking::findOrFail($request->id_booking);

        // Validasi kepemilikan booking
        if ($booking->id_users !== Auth::id() && !Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk menilai booking ini.');
        }

        // Cek apakah booking sudah dinilai sebelumnya
        $alreadyRated = KuisionerPenilaian::where('id_booking', $booking->id)->exists();
        if ($alreadyRated) {
            return redirect()->back()->with('error', 'Anda sudah memberikan penilaian untuk pesanan paket ini.');
        }

        // Konversi opsi kualitatif ke nilai Ideal Mode dinamis AHP dari database
        $mapping = SpkService::getActiveSubcriteriaMapping();
        $nilaiFasilitas = $mapping['fasilitas'][$request->fasilitas];
        $nilaiHarga = $mapping['harga'][$request->harga];
        $nilaiKualitas = $mapping['kualitas'][$request->kualitas];

        // Simpan respon kuisioner (beserta label opsi asli agar dinamis mengikuti bobot AHP terbaru)
        KuisionerPenilaian::create([
            'id_booking'      => $booking->id,
            'id_users'        => Auth::id(),
            'paket_id'        => $request->paket_id,
            'opsi_fasilitas'  => $request->fasilitas,
            'opsi_harga'      => $request->harga,
            'opsi_kualitas'   => $request->kualitas,
            'nilai_fasilitas' => $nilaiFasilitas,
            'nilai_harga'     => $nilaiHarga,
            'nilai_kualitas'  => $nilaiKualitas,
            'komentar'        => $request->komentar,
        ]);

        // Hitung ulang agregat SPK secara real-time
        SpkService::recalculateFromKuisioner();

        return redirect()->back()->with('success', 'Terima kasih atas penilaian Anda! Skor rekomendasi paket telah berhasil diperbarui.');
    }
}
