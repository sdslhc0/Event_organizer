<?php

namespace App\Services;

use App\Models\AhpResponden;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Reader\XLSX\Options as ReaderOptions;
use OpenSpout\Reader\XLSX\Reader;
use OpenSpout\Writer\XLSX\Options as WriterOptions;
use OpenSpout\Writer\XLSX\Writer;

class AhpExcelService
{
    private const MAX_ERRORS = 20;

    public static function tempFolder(): string
    {
        $dir = function_exists('storage_path')
            ? storage_path('app/temp')
            : dirname(__DIR__, 2) . '/storage/app/temp';

        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }

        return (is_dir($dir) && is_writable($dir)) ? $dir : sys_get_temp_dir();
    }

    public static function tempFilePath(string $prefix, string $ext = 'xlsx'): string
    {
        $cleanExt = ltrim(strtolower($ext), '.');
        return self::tempFolder() . DIRECTORY_SEPARATOR . $prefix . uniqid('', true) . '.' . $cleanExt;
    }

    /**
     * Ambil baris header & data (aktif dari DB atau 3 baris contoh) untuk satu kategori.
     *
     * @return array{headers: list<string>, rows: list<list<int|float|string>>}
     */
    private static function buildSheetRows(string $tabKey, bool $includeActiveData): array
    {
        $meta = SpkService::CATEGORIES[$tabKey];
        $headers = array_merge(['Nama Responden'], $meta['pairs']);

        $sampleRows = [
            'kriteria'      => [['Responden 1', 3, 3, 1], ['Responden 2', 2, 4, 2], ['', 3, 6, 2]],
            'sub_harga'     => [['Responden 1', 2, 4, 2], ['Responden 2', 3, 6, 2], ['', 2, 4, 2]],
            'sub_kualitas'  => [['Responden 1', 3, 6, 2], ['Responden 2', 2, 4, 2], ['', 2, 6, 3]],
            'sub_fasilitas' => [['Responden 1', 2, 4, 2], ['Responden 2', 3, 6, 2], ['', 2, 6, 3]],
        ];

        $rows = [];
        if ($includeActiveData) {
            $records = AhpResponden::where('kategori', $tabKey)
                ->orderBy('id')
                ->get(['nama_responden', 'nilai_12', 'nilai_13', 'nilai_23']);

            foreach ($records as $rec) {
                $rows[] = [
                    $rec->nama_responden ?? '',
                    self::formatExcelNumber($rec->nilai_12),
                    self::formatExcelNumber($rec->nilai_13),
                    self::formatExcelNumber($rec->nilai_23),
                ];
            }
        }

        if (empty($rows)) {
            $rows = $sampleRows[$tabKey];
        }

        return ['headers' => $headers, 'rows' => $rows];
    }

    /**
     * Buat file Excel (.xlsx) berisi 4 sheet baku AHP:
     * kriteria, sub_harga, sub_kualitas, sub_fasilitas.
     */
    public static function writeWorkbook(string $outputPath, bool $includeActiveData = false): void
    {
        $options = new WriterOptions();
        $options->setTempFolder(self::tempFolder());

        $writer = new Writer($options);
        $writer->openToFile($outputPath);

        $headerStyle = (new Style())->setFontBold();
        $firstSheet = true;

        foreach (array_keys(SpkService::CATEGORIES) as $sheetKey) {
            $sheet = $firstSheet ? $writer->getCurrentSheet() : $writer->addNewSheetAndMakeItCurrent();
            $firstSheet = false;
            $sheet->setName($sheetKey);

            $payload = self::buildSheetRows($sheetKey, $includeActiveData);
            $writer->addRow(Row::fromValues($payload['headers'], $headerStyle));
            foreach ($payload['rows'] as $row) {
                $writer->addRow(Row::fromValues($row));
            }
        }

        $writer->close();
    }

    /**
     * Baca dan validasi ketat file Excel (.xlsx) hasil upload admin:
     * 1. Wajib memiliki 4 sheet: kriteria, sub_harga, sub_kualitas, sub_fasilitas
     * 2. Urutan header per sheet harus sesuai template baku
     * 3. Semua baris terisi angka skala Saaty valid (1/9 .. 9) & tidak kosong sebagian
     * 4. Wajib konsisten per sheet (CR <= 0.10)
     *
     * @return array{ok: bool, error?: string, errors?: list<string>, data?: array<string, list<array{nama_responden: ?string, nilai_12: float, nilai_13: float, nilai_23: float}>>}
     */
    public static function parseAndValidate(string $filePath): array
    {
        $readerOptions = new ReaderOptions();
        $readerOptions->setTempFolder(self::tempFolder());

        $reader = new Reader($readerOptions);
        try {
            $reader->open($filePath);
        } catch (\Throwable $e) {
            $msg = 'File Excel (.xlsx) rusak atau tidak dapat dibaca: ' . $e->getMessage();
            return ['ok' => false, 'error' => $msg, 'errors' => [$msg]];
        }

        $sheets = [];
        $foundTabNames = [];
        try {
            foreach ($reader->getSheetIterator() as $sheet) {
                $rawName = trim($sheet->getName());
                $foundTabNames[] = $rawName;
                $sheetKey = strtolower($rawName);
                $rows = [];
                foreach ($sheet->getRowIterator() as $row) {
                    if ($row->isEmpty()) {
                        continue;
                    }
                    $cells = $row->getCells();
                    $values = [];
                    foreach ($cells as $colIdx => $cell) {
                        $values[$colIdx] = $cell->getValue();
                    }
                    $rows[] = $values;
                }
                $sheets[$sheetKey] = $rows;
            }
        } finally {
            $reader->close();
        }

        $expectedTabs = array_keys(SpkService::CATEGORIES);
        $missingTabs = array_diff($expectedTabs, array_keys($sheets));
        if (!empty($missingTabs)) {
            $foundStr = !empty($foundTabNames) ? implode(', ', $foundTabNames) : 'tidak ada';
            $errors = [
                'Sheet wajib belum lengkap. Sheet yang tidak ditemukan: ' . implode(', ', $missingTabs) . '.',
                'File wajib memiliki 4 sheet bernama: ' . implode(', ', $expectedTabs) . " (Sheet terbaca di file Anda: {$foundStr}).",
            ];
            return ['ok' => false, 'error' => $errors[0], 'errors' => $errors];
        }

        $errors = [];
        $parsedData = [];

        foreach ($expectedTabs as $tab) {
            $meta = SpkService::CATEGORIES[$tab];
            $rows = $sheets[$tab];

            if (count($rows) < 2) {
                $errors[] = "Sheet '{$tab}': Tidak ada baris data responden di bawah baris header.";
                continue;
            }

            $header = $rows[0];
            $colMode = self::detectHeaderMode($header, $meta['pairs']);
            if ($colMode === null) {
                $foundHeaders = array_values(array_map(fn ($v) => trim((string) $v), array_slice($header, 0, 4)));
                $errors[] = "Sheet '{$tab}' baris 1 (Header): Judul/urutan kolom tidak sesuai template baku. Wajib urut: [" . implode(' | ', $meta['pairs']) . '], sedangkan di file terbaca: [' . implode(' | ', $foundHeaders) . '].';
                continue;
            }

            $dataLines = [];
            for ($i = 1; $i < count($rows); $i++) {
                $dataLines[] = ['line' => $i + 1, 'cells' => $rows[$i]];
            }

            $validated = self::validateTabRows($tab, $dataLines, $colMode, $errors);
            if ($validated !== null) {
                $parsedData[$tab] = $validated;
            }
        }

        if (!empty($errors)) {
            return ['ok' => false, 'error' => $errors[0], 'errors' => $errors];
        }

        return ['ok' => true, 'data' => $parsedData];
    }

    /**
     * Validasi seluruh baris & uji konsistensi CR satu tab Excel.
     *
     * @param  list<array{line: int, cells: array<int, mixed>}> $dataLines
     * @param  list<string>                                     $errors
     * @return ?list<array{nama_responden: ?string, nilai_12: float, nilai_13: float, nilai_23: float}>
     */
    private static function validateTabRows(string $tab, array $dataLines, string $colMode, array &$errors): ?array
    {
        $meta = SpkService::CATEGORIES[$tab];
        $tabRows = [];

        foreach ($dataLines as $entry) {
            $excelLine = $entry['line'];
            $r = $entry['cells'];

            if ($colMode === 'with_name') {
                $rawName = trim((string) ($r[0] ?? ''));
                $rawVals = [$r[1] ?? null, $r[2] ?? null, $r[3] ?? null];
            } else {
                $rawName = '';
                $rawVals = [$r[0] ?? null, $r[1] ?? null, $r[2] ?? null];
            }

            if (($rawVals[0] === null || trim((string) $rawVals[0]) === '')
                && ($rawVals[1] === null || trim((string) $rawVals[1]) === '')
                && ($rawVals[2] === null || trim((string) $rawVals[2]) === '')) {
                continue;
            }

            $parsedVals = [];
            $rowHasError = false;
            for ($c = 0; $c < 3; $c++) {
                $rawCell = $rawVals[$c];
                $pairLabel = $meta['pairs'][$c];

                if ($rawCell === null || trim((string) $rawCell) === '') {
                    $rowHasError = true;
                    if (count($errors) < self::MAX_ERRORS) {
                        $errors[] = "Sheet '{$tab}' baris ke-{$excelLine}, kolom '{$pairLabel}': Sel masih kosong (wajib diisi angka skala Saaty 1/9 sampai 9).";
                    }
                    continue;
                }

                $num = self::parseSaatyValue($rawCell);
                if ($num === null) {
                    $rowHasError = true;
                    if (count($errors) < self::MAX_ERRORS) {
                        $preview = is_scalar($rawCell) ? trim((string) $rawCell) : gettype($rawCell);
                        $errors[] = "Sheet '{$tab}' baris ke-{$excelLine}, kolom '{$pairLabel}': Nilai '{$preview}' tidak valid (harus berupa angka/pecahan skala Saaty antara 1/9 [0.1111] sampai 9).";
                    }
                    continue;
                }

                $parsedVals[$c] = $num;
            }

            if (!$rowHasError) {
                $tabRows[] = [
                    'nama_responden' => $rawName !== '' ? mb_substr($rawName, 0, 120) : null,
                    'nilai_12'       => round($parsedVals[0], 4),
                    'nilai_13'       => round($parsedVals[1], 4),
                    'nilai_23'       => round($parsedVals[2], 4),
                ];
            }
        }

        if (empty($tabRows)) {
            if (count($errors) < self::MAX_ERRORS) {
                $errors[] = "Sheet '{$tab}': Tidak memiliki satu pun baris angka yang valid.";
            }
            return null;
        }

        $gm12 = SpkService::geometricMean(array_column($tabRows, 'nilai_12'));
        $gm13 = SpkService::geometricMean(array_column($tabRows, 'nilai_13'));
        $gm23 = SpkService::geometricMean(array_column($tabRows, 'nilai_23'));
        $check = SpkService::computeAhp3x3($gm12, $gm13, $gm23);

        if (!$check['is_consistent']) {
            $crFormatted = number_format($check['cr'], 4);
            $gmText = number_format($gm12, 4) . ' | ' . number_format($gm13, 4) . ' | ' . number_format($gm23, 4);
            $errors[] = "Sheet '{$tab}': Data TIDAK KONSISTEN (CR = {$crFormatted} > batas maksimal 0.1000; Geometric Mean = {$gmText}). Periksa kembali arah perbandingan atau angka yang ekstrem/bertabrakan.";
        }

        return $tabRows;
    }

    /**
     * Deteksi apakah header berisi 4 kolom (Nama + 3 pasangan) atau 3 kolom angka langsung.
     *
     * @param array<int, mixed> $header
     * @param list<string>      $expectedPairs
     */
    private static function detectHeaderMode(array $header, array $expectedPairs): ?string
    {
        $norm = fn ($val): string => strtolower(preg_replace('/\s+/', ' ', trim((string) $val)));

        // Mode 4 kolom: Nama Responden | pair1 | pair2 | pair3
        $h1 = $norm($header[1] ?? '');
        $h2 = $norm($header[2] ?? '');
        $h3 = $norm($header[3] ?? '');
        if ($h1 === $norm($expectedPairs[0]) && $h2 === $norm($expectedPairs[1]) && $h3 === $norm($expectedPairs[2])) {
            return 'with_name';
        }

        // Mode 3 kolom langsung tanpa kolom Nama Responden: pair1 | pair2 | pair3
        $c0 = $norm($header[0] ?? '');
        if ($c0 === $norm($expectedPairs[0]) && $h1 === $norm($expectedPairs[1]) && $h2 === $norm($expectedPairs[2])) {
            return 'numeric_only';
        }

        return null;
    }

    /**
     * Parse input angka Saaty (mendukung integer, desimal koma/titik, maupun pecahan '1/3').
     */
    private static function parseSaatyValue(mixed $raw): ?float
    {
        if ($raw === null || $raw === '') {
            return null;
        }

        if (is_int($raw) || is_float($raw)) {
            $val = (float) $raw;
        } else {
            $str = str_replace(',', '.', trim((string) $raw));
            if (preg_match('/^(\d+(?:\.\d+)?)\s*\/\s*(\d+(?:\.\d+)?)$/', $str, $m)) {
                $den = (float) $m[2];
                if ($den <= 0) {
                    return null;
                }
                $val = ((float) $m[1]) / $den;
            } elseif (is_numeric($str)) {
                $val = (float) $str;
            } else {
                return null;
            }
        }

        if ($val < 0.1110 || $val > 9.0001) {
            return null;
        }

        return $val;
    }

    private static function formatExcelNumber(float|int|string $val): float|int
    {
        $num = (float) $val;
        return abs($num - round($num)) < 0.00001 ? (int) round($num) : $num;
    }

    /**
     * Bangun ringkasan bobot & daftar responden per tab untuk preview sebelum disimpan ke DB.
     *
     * @param  array<string, list<array{nama_responden: ?string, nilai_12: float, nilai_13: float, nilai_23: float}>> $parsedData
     * @return array<string, array<string, mixed>>
     */
    public static function buildPreviewSummary(array $parsedData): array
    {
        $summary = [];
        foreach (SpkService::CATEGORIES as $tabKey => $meta) {
            $tabRows = $parsedData[$tabKey] ?? [];
            $gm12 = SpkService::geometricMean(array_column($tabRows, 'nilai_12'));
            $gm13 = SpkService::geometricMean(array_column($tabRows, 'nilai_13'));
            $gm23 = SpkService::geometricMean(array_column($tabRows, 'nilai_23'));
            $calc = SpkService::computeAhp3x3($gm12, $gm13, $gm23);

            $summary[$tabKey] = [
                'key'           => $tabKey,
                'label'         => $meta['label'],
                'badge'         => $meta['badge'],
                'items'         => $meta['items'],
                'pairs'         => $meta['pairs'],
                'count'         => count($tabRows),
                'gm'            => [round($gm12, 4), round($gm13, 4), round($gm23, 4)],
                'weights'       => array_map(fn (float $v) => round($v, 4), $calc['weights']),
                'ideal'         => array_map(fn (float $v) => round($v, 4), $calc['ideal']),
                'cr'            => round($calc['cr'], 4),
                'is_consistent' => $calc['is_consistent'],
                'rows'          => array_map(fn (array $r) => [
                    'nama' => $r['nama_responden'],
                    'v12'  => $r['nilai_12'],
                    'v13'  => $r['nilai_13'],
                    'v23'  => $r['nilai_23'],
                ], $tabRows),
            ];
        }

        return $summary;
    }

    /**
     * Bersihkan file preview sementara yang lebih lama dari 2 jam.
     */
    public static function cleanupExpiredPreviews(): void
    {
        $pattern = self::tempFolder() . DIRECTORY_SEPARATOR . 'ahp_preview_*.json';
        $cutoff = time() - 7200;
        foreach (glob($pattern) ?: [] as $file) {
            if (is_file($file) && (@filemtime($file) ?: time()) < $cutoff) {
                @unlink($file);
            }
        }
    }
}
