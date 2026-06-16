<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PaketBundling;
use App\Models\PaketPenilaian;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SpkController extends Controller
{
    // ==========================================
    // MENU 1: PENILAIAN PAKET JASA (Input Nilai)
    // ==========================================
    public function penilaianIndex(Request $request)
    {
        // For the left form
        $pakets = PaketBundling::all();
        $editPenilaian = null;

        if ($request->has('edit')) {
            $editPenilaian = PaketPenilaian::where('paket_id', $request->edit)->first();
        }

        // For the right table
        $penilaians = PaketPenilaian::with('paketBundling')->get();

        return view('backend.spk.penilaian', compact('pakets', 'penilaians', 'editPenilaian'));
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

        $recommendedPakets = $penilaians->whereNotNull('ranking')->take(5);

        $scoreDistribution = [
            'Sangat Tinggi (>0.8)' => PaketPenilaian::where('skor_akhir', '>', 0.8)->count(),
            'Tinggi (0.6 - 0.8)' => PaketPenilaian::whereBetween('skor_akhir', [0.6, 0.8])->count(),
            'Sedang (0.4 - 0.6)' => PaketPenilaian::whereBetween('skor_akhir', [0.4, 0.6])->count(),
            'Rendah (<0.4)' => PaketPenilaian::where('skor_akhir', '<', 0.4)->count(),
        ];

        return view('backend.spk.perhitungan', compact('penilaians', 'recommendedPakets', 'scoreDistribution'));
    }

    public function perhitunganCalculate()
    {
        $penilaians = PaketPenilaian::all();

        if ($penilaians->isEmpty()) {
            return redirect()->route('backend.spk.perhitungan')->with('error', 'Belum ada data nilai kriteria untuk dihitung.');
        }

        $this->hitungSpk();

        return redirect()->route('backend.spk.perhitungan')->with('success', 'Perhitungan SPK berhasil dijalankan dan ranking telah diupdate.');
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
