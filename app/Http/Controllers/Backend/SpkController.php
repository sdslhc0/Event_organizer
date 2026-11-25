<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PaketBundling;
use App\Models\PaketPenilaian;
use App\Models\KuisionerPenilaian;
use App\Services\SpkService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SpkController extends Controller
{
    // ==========================================
    // MENU 1: PENILAIAN PAKET JASA (Input Nilai)
    // ==========================================
    public function penilaianIndex(Request $request)
    {
        $pakets = PaketBundling::all();

        // Data penilaian paket terurut berdasar ranking
        $penilaians = PaketPenilaian::with('paketBundling')->orderBy('ranking', 'asc')->get();
        $topPaket = $penilaians->where('ranking', 1)->first() ?? $penilaians->first();

        // Data kuisioner dari pengguna (Maksimal 10 per halaman)
        $totalKuisioner = KuisionerPenilaian::count();
        $kuisioners = KuisionerPenilaian::with(['user', 'booking', 'paketBundling'])->latest()->paginate(10);

        return view('backend.spk.penilaian', compact('pakets', 'penilaians', 'totalKuisioner', 'kuisioners', 'topPaket'));
    }

    public function penilaianStore(Request $request)
    {
        $request->validate([
            'paket_id' => 'required|exists:paket_bundlings,id',
            'nilai_fasilitas' => 'required|numeric',
            'nilai_harga' => 'required|numeric',
            'nilai_kualitas' => 'required|numeric', // Di gambar tertulis Kapasitas/Waktu, tapi kita ikut Kualitas sesuai kesepakatan
        ]);

        $penilaian = PaketPenilaian::firstOrNew(['paket_id' => $request->paket_id]);
        $penilaian->nilai_fasilitas = $request->nilai_fasilitas;
        $penilaian->nilai_harga = $request->nilai_harga;
        $penilaian->nilai_kualitas = $request->nilai_kualitas;
        $penilaian->save();

        // Auto-calculate skor and ranking
        $this->hitungSpk();

        return redirect()->route('backend.spk.penilaian')->with('success', 'Data penilaian berhasil disimpan & skor otomatis dihitung.');
    }

    public function penilaianDestroy($id)
    {
        PaketPenilaian::where('paket_id', $id)->delete();
        $this->hitungSpk();
        return redirect()->route('backend.spk.penilaian')->with('success', 'Data penilaian berhasil dihapus & ranking diupdate.');
    }

    private function hitungSpk()
    {
        $penilaians = PaketPenilaian::all();
        if ($penilaians->isEmpty()) return;

        $w_harga = 0.50;
        $w_fasilitas = 0.23;
        $w_kualitas = 0.27;

        foreach ($penilaians as $penilaian) {
            $score = ($penilaian->nilai_harga * $w_harga) + 
                     ($penilaian->nilai_fasilitas * $w_fasilitas) + 
                     ($penilaian->nilai_kualitas * $w_kualitas);
            $penilaian->skor_akhir = $score;
            $penilaian->save();
        }

        $penilaians = PaketPenilaian::orderBy('skor_akhir', 'desc')->get();
        $rank = 1;
        foreach ($penilaians as $p) {
            $p->ranking = $rank++;
            $p->save();
        }
    }

    // ==========================================
    // MENU 2: PERHITUNGAN AHP/SPK (Ranking)
    // ==========================================
    public function perhitunganIndex()
    {
        $penilaians = PaketPenilaian::with('paketBundling')
            ->orderBy('skor_akhir', 'desc')
            ->get();

        $recommendedPakets = $penilaians->whereNotNull('ranking')->take(5)->values();

        $scoreDistribution = [];
        foreach ($penilaians->whereNotNull('ranking') as $p) {
            $name = $p->paketBundling->nama ?? 'Paket #' . $p->paket_id;
            $scoreDistribution[$name] = round(($p->skor_akhir ?? 0) * 100, 1);
        }

        return view('backend.spk.perhitungan', compact('penilaians', 'recommendedPakets', 'scoreDistribution'));
    }

    public function perhitunganCalculate()
    {
        SpkService::recalculateFromKuisioner();

        return redirect()->route('backend.spk.perhitungan')->with('success', 'Perhitungan SPK dan sinkronisasi kuisioner berhasil dijalankan. Ranking telah diupdate.');
    }

    public function exportPdf()
    {
        $pakets = PaketPenilaian::with('paketBundling')
            ->whereNotNull('ranking')
            ->orderBy('ranking', 'asc')
            ->get();

        $pdf = Pdf::loadView('backend.spk.pdf', compact('pakets'));
        return $pdf->download('Laporan_Data_Pemilihan_Paket_Jasa.pdf');
    }
}
