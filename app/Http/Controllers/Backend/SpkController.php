<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AhpHasilBobot;
use App\Models\AhpResponden;
use App\Models\KuisionerPenilaian;
use App\Models\PaketBundling;
use App\Models\PaketPenilaian;
use App\Services\AhpExcelService;
use App\Services\SpkService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SpkController extends Controller
{
    // ==========================================
    // MENU 1: BOBOT & RESPONDEN AHP (4 TAB EXCEL)
    // ==========================================
    public function bobotIndex(Request $request)
    {
        $ahpWeights = SpkService::getActiveCriteriaWeights();
        $ahpStatuses = AhpHasilBobot::all()->keyBy('kategori');
        $categories = SpkService::CATEGORIES;
        $matrixBreakdowns = SpkService::getAllMatrixBreakdowns();

        $activeTab = $request->query('tab', 'kriteria');
        if (!array_key_exists($activeTab, $categories)) {
            $activeTab = 'kriteria';
        }

        $respondens = AhpResponden::where('kategori', $activeTab)
            ->orderBy('id')
            ->paginate(15)
            ->appends(['tab' => $activeTab]);

        return view('backend.spk.bobot', compact(
            'ahpWeights',
            'ahpStatuses',
            'categories',
            'matrixBreakdowns',
            'activeTab',
            'respondens'
        ));
    }

    // ==========================================
    // MENU 2: PENILAIAN PAKET JASA & KUISIONER CUSTOMER
    // ==========================================
    public function penilaianIndex(Request $request)
    {
        $pakets = PaketBundling::all();

        $penilaians = PaketPenilaian::with('paketBundling')->orderBy('ranking', 'asc')->get();
        $topPaket = $penilaians->where('ranking', 1)->first() ?? $penilaians->first();

        $totalKuisioner = KuisionerPenilaian::count();
        $kuisioners = KuisionerPenilaian::with(['user', 'booking', 'paketBundling'])->latest()->paginate(10);

        $ahpWeights = SpkService::getActiveCriteriaWeights();
        $ahpStatuses = AhpHasilBobot::all()->keyBy('kategori');
        $categories = SpkService::CATEGORIES;

        return view('backend.spk.penilaian', compact(
            'pakets',
            'penilaians',
            'totalKuisioner',
            'kuisioners',
            'topPaket',
            'ahpWeights',
            'ahpStatuses',
            'categories'
        ));
    }

    public function penilaianStore(Request $request)
    {
        $request->validate([
            'paket_id' => 'required|exists:paket_bundlings,id',
            'nilai_fasilitas' => 'required|numeric',
            'nilai_harga' => 'required|numeric',
            'nilai_kualitas' => 'required|numeric',
        ]);

        $penilaian = PaketPenilaian::firstOrNew(['paket_id' => $request->paket_id]);
        $penilaian->nilai_fasilitas = $request->nilai_fasilitas;
        $penilaian->nilai_harga = $request->nilai_harga;
        $penilaian->nilai_kualitas = $request->nilai_kualitas;
        $penilaian->save();

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
        if ($penilaians->isEmpty()) {
            return;
        }

        $weights = SpkService::getActiveCriteriaWeights();

        foreach ($penilaians as $penilaian) {
            $score = ($penilaian->nilai_harga * $weights['harga']) +
                     ($penilaian->nilai_kualitas * $weights['kualitas']) +
                     ($penilaian->nilai_fasilitas * $weights['fasilitas']);
            $penilaian->skor_akhir = round($score, 4);
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
    // DOWNLOAD TEMPLATE & IMPORT 4 TAB EXCEL (.xlsx)
    // ==========================================
    public function downloadAhpTemplate()
    {
        $tempPath = AhpExcelService::tempFilePath('ahp_tpl_', 'xlsx');
        AhpExcelService::writeWorkbook($tempPath, false);

        return response()
            ->download($tempPath, 'Template_Responden_AHP_MELODI.xlsx')
            ->deleteFileAfterSend(true);
    }

    public function exportActiveAhp()
    {
        $tempPath = AhpExcelService::tempFilePath('ahp_act_', 'xlsx');
        AhpExcelService::writeWorkbook($tempPath, true);

        return response()
            ->download($tempPath, 'Data_Responden_AHP_Aktif_MELODI.xlsx')
            ->deleteFileAfterSend(true);
    }

    public function previewAhpRespondents(Request $request)
    {
        AhpExcelService::cleanupExpiredPreviews();

        $validator = Validator::make($request->all(), [
            'file_excel' => 'required|file|mimes:xlsx|max:10240',
        ], [
            'file_excel.required' => 'Pilih file Excel (.xlsx) 4 tab terlebih dahulu sebelum melakukan preview.',
            'file_excel.file'     => 'File yang diunggah tidak valid atau gagal diterima server.',
            'file_excel.mimes'    => 'Format file ditolak! Wajib menggunakan file Excel (.xlsx).',
            'file_excel.max'      => 'Ukuran file terlalu besar (maksimal 10 MB).',
        ]);

        if ($validator->fails()) {
            $msgs = $validator->errors()->all();
            return response()->json([
                'ok'     => false,
                'error'  => $msgs[0] ?? 'Validasi file gagal.',
                'errors' => $msgs,
            ], 422);
        }

        $file = $request->file('file_excel');
        $parsed = AhpExcelService::parseAndValidate($file->getRealPath());

        if (!$parsed['ok']) {
            return response()->json([
                'ok'     => false,
                'error'  => $parsed['error'],
                'errors' => $parsed['errors'] ?? [$parsed['error']],
            ], 422);
        }

        $token = bin2hex(random_bytes(16));
        $previewPath = AhpExcelService::tempFolder() . DIRECTORY_SEPARATOR . "ahp_preview_{$token}.json";
        file_put_contents($previewPath, json_encode($parsed['data'], JSON_UNESCAPED_UNICODE));
        session(['ahp_preview_token' => $token]);

        return response()->json([
            'ok'        => true,
            'token'     => $token,
            'filename'  => $file->getClientOriginalName(),
            'totalRows' => array_sum(array_map('count', $parsed['data'])),
            'tabs'      => AhpExcelService::buildPreviewSummary($parsed['data']),
        ]);
    }

    public function importAhpRespondents(Request $request)
    {
        AhpExcelService::cleanupExpiredPreviews();

        $parsedData = null;
        $previewFileToCleanup = null;

        if ($request->filled('preview_token')) {
            $token = (string) $request->input('preview_token');
            $sessionToken = (string) session('ahp_preview_token', '');

            if (!preg_match('/^[a-f0-9]{32}$/', $token) || $sessionToken === '' || !hash_equals($sessionToken, $token)) {
                return redirect()
                    ->back()
                    ->with('error', 'Sesi preview tidak valid atau sudah kedaluwarsa. Silakan pilih ulang file Excel (.xlsx) Anda.')
                    ->with('import_errors', ['Sesi preview tidak valid atau sudah kedaluwarsa. Silakan lakukan Preview ulang sebelum menyimpan.']);
            }

            $previewFile = AhpExcelService::tempFolder() . DIRECTORY_SEPARATOR . "ahp_preview_{$token}.json";
            if (!is_file($previewFile)) {
                return redirect()
                    ->back()
                    ->with('error', 'Data preview sementara tidak ditemukan atau sudah habis masa berlakunya. Silakan preview ulang file Excel Anda.')
                    ->with('import_errors', ['Data preview sementara sudah tidak tersedia. Silakan unggah ulang file Excel (.xlsx) Anda.']);
            }

            $decoded = json_decode((string) file_get_contents($previewFile), true);
            if (!is_array($decoded) || empty($decoded)) {
                @unlink($previewFile);
                return redirect()
                    ->back()
                    ->with('error', 'Data preview rusak. Silakan lakukan Preview ulang.')
                    ->with('import_errors', ['Format file preview sementara tidak valid.']);
            }

            $parsedData = $decoded;
            $previewFileToCleanup = $previewFile;
        } else {
            $request->validate([
                'file_excel' => 'required|file|mimes:xlsx|max:10240',
            ], [
                'file_excel.required' => 'Pilih file Excel (.xlsx) 4 tab terlebih dahulu sebelum mengunggah.',
                'file_excel.file'     => 'File yang diunggah tidak valid atau gagal diterima server.',
                'file_excel.mimes'    => 'Format file ditolak! Wajib menggunakan file Excel (.xlsx).',
                'file_excel.max'      => 'Ukuran file terlalu besar (maksimal 10 MB).',
            ]);

            $parsed = AhpExcelService::parseAndValidate($request->file('file_excel')->getRealPath());

            if (!$parsed['ok']) {
                return redirect()
                    ->back()
                    ->with('error', $parsed['error'])
                    ->with('import_errors', $parsed['errors'] ?? [$parsed['error']]);
            }

            $parsedData = $parsed['data'];
        }

        try {
            $totalInserted = $this->commitParsedAhpData($parsedData);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kegagalan saat menyimpan data ke database (data lama tetap aman): ' . $e->getMessage())
                ->with('import_errors', ['Database error: ' . $e->getMessage()]);
        }

        if ($previewFileToCleanup && is_file($previewFileToCleanup)) {
            @unlink($previewFileToCleanup);
        }
        session()->forget('ahp_preview_token');

        $firstTabCount = count(reset($parsedData) ?: []);

        return redirect()
            ->route('backend.spk.bobot')
            ->with('success', "Berhasil mengimpor 4 sheet ({$firstTabCount} responden/tab, total {$totalInserted} baris). Bobot kriteria, skala subkriteria, dan ranking paket telah dihitung ulang otomatis!");
    }

    /**
     * Simpan 4 tab responden AHP secara atomik (hapus data lama & insert baru dalam satu transaksi)
     * lalu hitung ulang seluruh bobot & ranking.
     *
     * @param array<string, list<array{nama_responden: ?string, nilai_12: float, nilai_13: float, nilai_23: float}>> $parsedData
     */
    private function commitParsedAhpData(array $parsedData): int
    {
        $now = now();
        $totalInserted = 0;

        DB::transaction(function () use ($parsedData, $now, &$totalInserted) {
            AhpResponden::query()->delete();

            foreach ($parsedData as $kategori => $rows) {
                $chunks = [];
                foreach ($rows as $r) {
                    $chunks[] = [
                        'kategori'       => $kategori,
                        'nama_responden' => $r['nama_responden'],
                        'nilai_12'       => $r['nilai_12'],
                        'nilai_13'       => $r['nilai_13'],
                        'nilai_23'       => $r['nilai_23'],
                        'created_at'     => $now,
                        'updated_at'     => $now,
                    ];
                    $totalInserted++;
                }

                foreach (array_chunk($chunks, 200) as $batch) {
                    AhpResponden::insert($batch);
                }
            }

            SpkService::calculateAhpFromRespondents();
            SpkService::recalculateFromKuisioner();
        });

        return $totalInserted;
    }

    // ==========================================
    // MENU 3: PERHITUNGAN AHP/SPK (HASIL RANKING & GRAFIK)
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

        $ahpWeights = SpkService::getActiveCriteriaWeights();

        return view('backend.spk.perhitungan', compact(
            'penilaians',
            'recommendedPakets',
            'scoreDistribution',
            'ahpWeights'
        ));
    }

    public function perhitunganCalculate()
    {
        SpkService::calculateAhpFromRespondents();
        SpkService::recalculateFromKuisioner();

        return redirect()->back()->with('success', 'Perhitungan bobot AHP responden & ranking kuisioner pelanggan berhasil disinkronkan.');
    }

    public function exportPdf()
    {
        $pakets = PaketPenilaian::with('paketBundling')
            ->whereNotNull('ranking')
            ->orderBy('ranking', 'asc')
            ->get();

        $ahpWeights = SpkService::getActiveCriteriaWeights();

        $pdf = Pdf::loadView('backend.spk.pdf', compact('pakets', 'ahpWeights'));
        return $pdf->download('Laporan_Data_Pemilihan_Paket_Jasa.pdf');
    }
}
