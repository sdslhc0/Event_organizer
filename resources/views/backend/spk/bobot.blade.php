<x-backend-layout>
    <x-slot name="title">Bobot &amp; Responden AHP - Admin MELODI</x-slot>
    <x-slot name="header">Bobot &amp; Responden AHP</x-slot>

    <x-slot name="headerActions">
        <form action="{{ route('backend.spk.perhitungan.calculate') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="btn-gold flex items-center gap-2 text-xs py-2 px-4 shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>Hitung Ulang Bobot</span>
            </button>
        </form>
    </x-slot>

    <div class="space-y-6" x-data="ahpPreviewManager('{{ route('backend.spk.preview_ahp') }}')">
        @if(session('success'))
            <div class="p-4 rounded-xl bg-green-950/60 border border-green-700/50 text-green-300 flex items-center gap-3 shadow-lg">
                <svg class="w-5 h-5 flex-shrink-0 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error') || session('import_errors'))
            @php
                $importErrList = session('import_errors', session('error') ? [session('error')] : []);
            @endphp
            <div class="rounded-xl shadow-lg text-red-200" style="background-color: rgba(127, 29, 29, 0.28); border: 1px solid rgba(248, 113, 113, 0.45); padding: 16px 20px;">
                <div class="flex items-center gap-2.5 text-red-300 font-semibold text-sm">
                    <svg class="w-5 h-5 flex-shrink-0 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Upload Excel Dibatalkan &mdash; Data Aktif Sebelumnya Tetap Aman</span>
                </div>
                <ul class="text-xs text-red-200/95 leading-relaxed" style="margin: 10px 0 0 0; padding: 0; list-style: none; display: flex; flex-direction: column; gap: 6px;">
                    @foreach($importErrList as $errMsg)
                        <li style="display: flex; align-items: flex-start; gap: 8px;">
                            <span style="color: #f87171; flex-shrink: 0;">&bull;</span>
                            <span>{{ $errMsg }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-xl shadow-lg text-red-200 text-xs" style="background-color: rgba(127, 29, 29, 0.28); border: 1px solid rgba(248, 113, 113, 0.45); padding: 16px 20px;">
                <div class="font-semibold text-sm text-red-300">Validasi File Gagal:</div>
                <ul class="leading-relaxed" style="margin: 8px 0 0 0; padding: 0; list-style: none; display: flex; flex-direction: column; gap: 6px;">
                    @foreach($errors->all() as $errMsg)
                        <li style="display: flex; align-items: flex-start; gap: 8px;">
                            <span style="color: #f87171; flex-shrink: 0;">&bull;</span>
                            <span>{{ $errMsg }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Error Alert dari AJAX Preview Excel --}}
        <div x-show="ajaxErrors.length > 0" x-cloak class="rounded-xl shadow-lg text-red-200" style="background-color: rgba(127, 29, 29, 0.28); border: 1px solid rgba(248, 113, 113, 0.45); padding: 16px 20px;">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5 text-red-300 font-semibold text-sm">
                    <svg class="w-5 h-5 flex-shrink-0 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Preview Excel Dibatalkan &mdash; Data Aktif Sebelumnya Tetap Aman</span>
                </div>
                <button type="button" @click="ajaxErrors = []" class="text-xs px-2.5 py-1 rounded-md text-red-200 hover:text-white" style="background-color: rgba(127, 29, 29, 0.45); border: 1px solid rgba(248, 113, 113, 0.35);">
                    &times; Tutup
                </button>
            </div>
            <ul class="text-xs text-red-200/95 leading-relaxed" style="margin: 10px 0 0 0; padding: 0; list-style: none; display: flex; flex-direction: column; gap: 6px;">
                <template x-for="(err, idx) in ajaxErrors" :key="idx">
                    <li style="display: flex; align-items: flex-start; gap: 8px;">
                        <span style="color: #f87171; flex-shrink: 0;">&bull;</span>
                        <span x-text="err"></span>
                    </li>
                </template>
            </ul>
        </div>

        {{-- Upload Excel 4 Tab --}}
        <div class="bg-dark-100 rounded-xl border border-gold-500/25 overflow-hidden shadow-lg">
            <div class="p-5 border-b border-brown-800/30 bg-dark-100/60 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <div class="flex flex-wrap items-center gap-2.5">
                        <h3 class="font-serif text-lg text-gold-400">Import &amp; Pembobotan AHP Otomatis (4 Tab Excel)</h3>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-gold-500/15 text-gold-300 border border-gold-500/30">
                            Data Aktif · {{ $ahpWeights['jumlah_responden'] ?? 0 }} Responden
                        </span>
                    </div>
                    <p class="text-xs text-brown-400 mt-1">
                        Unduh template resmi 4 sheet (<code>kriteria</code>, <code>sub_harga</code>, <code>sub_kualitas</code>, <code>sub_fasilitas</code>), isi angka perbandingan responden (kolom nama opsional/boleh kosong), lalu klik Preview untuk memeriksa seluruh data sebelum disimpan.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2.5" style="flex-shrink: 0;">
                    <a href="{{ route('backend.spk.template') }}" class="btn-outline flex items-center gap-1.5 text-xs py-2 px-3.5 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Download Template (.xlsx)</span>
                    </a>
                    <a href="{{ route('backend.spk.export_ahp') }}" class="btn-outline flex items-center gap-1.5 text-xs py-2 px-3.5 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Download Data Aktif (.xlsx)</span>
                    </a>
                </div>
            </div>

            <div class="p-5">
                <form action="{{ route('backend.spk.import_ahp') }}" method="POST" enctype="multipart/form-data" class="bg-dark-200/50 border border-brown-800/40 rounded-xl p-4 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                    @csrf
                    <div class="flex-1 min-w-0">
                        <label class="block text-xs font-semibold text-brown-200 uppercase tracking-wider mb-1.5">
                            Upload File Responden AHP (.xlsx)
                        </label>
                        <input x-ref="fileInput" type="file" name="file_excel" accept=".xlsx" required class="block w-full text-xs text-brown-300 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border file:border-gold-500/40 file:text-xs file:font-semibold file:bg-gold-500/15 file:text-gold-300 hover:file:bg-gold-500/25 cursor-pointer bg-dark-100 border border-brown-800/50 rounded-lg p-1.5">
                        <span class="block text-[11px] text-brown-400 mt-1.5">Validasi otomatis: wajib 4 sheet baku &amp; CR &le; 0.10 (maks. 10 MB)</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-2.5 xl:pt-1">
                        <button type="button" @click="runPreview()" :disabled="isLoading" class="btn-outline text-xs py-2.5 px-4 whitespace-nowrap flex items-center gap-1.5 disabled:opacity-60">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span x-show="!isLoading">Preview Data</span>
                            <span x-show="isLoading" x-cloak>Memvalidasi...</span>
                        </button>
                        <button type="submit" :disabled="isLoading" class="btn-gold text-xs py-2.5 px-5 whitespace-nowrap flex items-center gap-1.5 disabled:opacity-60">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            <span>Upload &amp; Hitung Ulang</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Ringkasan Bobot & Skala Subkriteria dengan Tombol Switch (Utama: Tabel | Alternatif: Kartu) --}}
        <div x-data="{ viewMode: 'tabel' }" class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-dark-100 rounded-xl border border-brown-800/30 px-5 py-3.5 shadow-md">
                <div class="text-xs text-brown-300">
                    <span class="font-serif text-sm text-gold-400 font-semibold mr-2">Ringkasan Hasil Pembobotan:</span>
                    <span class="font-mono text-brown-200">
                        Harga {{ number_format($ahpWeights['harga'] ?? 0, 4) }} &middot;
                        Kualitas {{ number_format($ahpWeights['kualitas'] ?? 0, 4) }} &middot;
                        Fasilitas {{ number_format($ahpWeights['fasilitas'] ?? 0, 4) }}
                    </span>
                </div>
                <div class="inline-flex items-center p-1 rounded-lg bg-dark-200 border border-brown-800/50 self-start sm:self-auto">
                    <button type="button"
                            @click="viewMode = 'tabel'"
                            :class="viewMode === 'tabel' ? 'bg-gold-500 text-black font-bold shadow' : 'text-brown-300 hover:text-gold-300'"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <span>Tampilan Tabel</span>
                    </button>
                    <button type="button"
                            @click="viewMode = 'kartu'"
                            :class="viewMode === 'kartu' ? 'bg-gold-500 text-black font-bold shadow' : 'text-brown-300 hover:text-gold-300'"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        <span>Tampilan Kartu</span>
                    </button>
                </div>
            </div>

            {{-- Mode Utama: Tabel Acuan Nilai Subkriteria --}}
            <div x-show="viewMode === 'tabel'">
                @include('backend.spk._subkriteria_table')
            </div>

            {{-- Mode Alternatif: 4 Kartu Ringkasan per Kategori --}}
            <div x-show="viewMode === 'kartu'" x-cloak class="bg-dark-100 rounded-xl border border-brown-800/30 p-5 shadow-lg">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                    @foreach($categories as $catKey => $meta)
                        @php
                            $st = $ahpStatuses->get($catKey);
                            $crVal = $st ? $st->cr : 0;
                            $ok = $st ? $st->is_consistent : true;
                            $isSelected = ($catKey === $activeTab);
                            $vals = $st
                                ? ($catKey === 'kriteria'
                                    ? [$st->bobot_1, $st->bobot_2, $st->bobot_3]
                                    : [$st->ideal_1, $st->ideal_2, $st->ideal_3])
                                : null;
                        @endphp
                        <a href="{{ route('backend.spk.bobot', ['tab' => $catKey]) }}#tabel-ahp"
                           class="block rounded-xl p-4 transition-all border {{ $isSelected ? 'bg-dark-200 border-gold-500/70 ring-1 ring-gold-500/30 shadow-md' : 'bg-dark-200/40 hover:bg-dark-200/80 border-brown-800/40 hover:border-gold-500/40' }}">
                            <div class="flex items-start justify-between gap-2 pb-2.5 mb-3 border-b border-brown-800/40">
                                <div>
                                    <span class="text-xs font-serif font-semibold text-gold-400 block">{{ $meta['label'] }}</span>
                                    <span class="text-[11px] text-brown-400">
                                        {{ $st->jumlah_responden ?? 0 }} Responden &middot; {{ $catKey === 'kriteria' ? 'Bobot Prioritas' : 'Skala Ideal Mode' }}
                                    </span>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold whitespace-nowrap {{ $ok ? 'bg-green-950/70 text-green-300 border border-green-700/50' : 'bg-red-950/70 text-red-300 border border-red-700/50' }}">
                                    CR {{ number_format($crVal, 4) }}
                                </span>
                            </div>

                            @if($vals)
                                <div class="space-y-2">
                                    @foreach($meta['items'] as $idx => $itemName)
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="text-brown-200 font-medium">{{ $itemName }}</span>
                                            <span class="font-mono font-semibold {{ $idx === 0 ? 'text-gold-300' : 'text-brown-100' }}">
                                                {{ number_format($vals[$idx], 4) }}
                                                @if($catKey === 'kriteria')
                                                    <span class="text-[10px] text-brown-400 font-sans">({{ number_format($vals[$idx] * 100, 1) }}%)</span>
                                                @endif
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-xs text-brown-500 italic py-3 text-center">Belum ada data</div>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Rincian Matriks & Data Responden 4 Tab --}}
        @php
            $activeBreakdown = $matrixBreakdowns[$activeTab];
            $activeMeta = $activeBreakdown['meta'];
            $calc = $activeBreakdown['calc'];
            $respCount = $activeBreakdown['jumlah_responden'];
            $fmtClean = fn ($v) => rtrim(rtrim(number_format((float) $v, 4, '.', ''), '0'), '.');
        @endphp

        <div id="tabel-ahp" class="bg-dark-100 rounded-xl border border-brown-800/30 overflow-hidden shadow-lg">
            {{-- 4 Tab Switcher --}}
            <div class="px-6 pt-4 border-b border-brown-800/30 bg-dark-200/30 flex flex-wrap gap-2">
                @foreach($matrixBreakdowns as $tabKey => $tb)
                    @php
                        $isTabActive = ($tabKey === $activeTab);
                    @endphp
                    <a href="{{ route('backend.spk.bobot', ['tab' => $tabKey]) }}#tabel-ahp"
                       class="px-4 py-2.5 rounded-t-lg text-xs font-semibold transition-all border-t border-l border-r {{ $isTabActive ? 'bg-dark-100 text-gold-400 border-gold-500/40 -mb-px pb-3' : 'bg-dark-200/40 text-brown-400 border-transparent hover:text-brown-200' }}">
                        <span>{{ $tb['meta']['label'] }}</span>
                        <span class="ml-1.5 px-1.5 py-0.5 rounded text-[10px] {{ $isTabActive ? 'bg-gold-500/20 text-gold-300' : 'bg-dark-300 text-brown-400' }}">
                            {{ $tb['jumlah_responden'] }}
                        </span>
                    </a>
                @endforeach
            </div>

            <div class="p-6 space-y-6">
                {{-- Matriks 3x3 & Normalisasi / CR --}}
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                    {{-- Matriks Perbandingan Berpasangan --}}
                    <div class="bg-dark-200/40 rounded-xl border border-brown-800/40 overflow-hidden">
                        <div class="px-4 py-3 border-b border-brown-800/40 flex items-center justify-between">
                            <span class="font-serif text-sm text-brown-100">Matriks Perbandingan Berpasangan (Geometric Mean)</span>
                            <span class="text-[11px] text-brown-400">{{ $respCount }} Responden</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-center">
                                <thead class="bg-dark-200/70 text-brown-300 border-b border-brown-800/40">
                                    <tr>
                                        <th class="px-3 py-2.5 text-left">{{ $activeMeta['badge'] }}</th>
                                        @foreach($activeMeta['items'] as $it)
                                            <th class="px-3 py-2.5">{{ $it }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-brown-800/30 font-mono">
                                    @foreach($activeMeta['items'] as $rIdx => $it)
                                        <tr>
                                            <td class="px-3 py-2.5 text-left font-sans font-medium text-brown-200">{{ $it }}</td>
                                            @for($cIdx = 0; $cIdx < 3; $cIdx++)
                                                <td class="px-3 py-2.5 {{ $rIdx === $cIdx ? 'text-gold-400 font-semibold' : 'text-brown-300' }}">
                                                    {{ number_format($calc['matrix'][$rIdx][$cIdx], 4) }}
                                                </td>
                                            @endfor
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-dark-200/80 border-t border-gold-500/25 font-mono font-semibold text-gold-300">
                                    <tr>
                                        <td class="px-3 py-2.5 text-left font-sans">Jumlah Kolom</td>
                                        @foreach($calc['col_sums'] as $cs)
                                            <td class="px-3 py-2.5">{{ number_format($cs, 4) }}</td>
                                        @endforeach
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    {{-- Matriks Normalisasi, Bobot Prioritas & Uji CR --}}
                    <div class="bg-dark-200/40 rounded-xl border border-brown-800/40 overflow-hidden flex flex-col justify-between">
                        <div class="px-4 py-3 border-b border-brown-800/40 flex items-center justify-between">
                            <span class="font-serif text-sm text-brown-100">Normalisasi, Bobot Prioritas &amp; Ideal Mode</span>
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $calc['is_consistent'] ? 'bg-green-950/70 text-green-300 border border-green-700/50' : 'bg-red-950/70 text-red-300 border border-red-700/50' }}">
                                {{ $calc['is_consistent'] ? 'KONSISTEN' : 'TIDAK KONSISTEN' }} (CR = {{ number_format($calc['cr'], 4) }})
                            </span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-center">
                                <thead class="bg-dark-200/70 text-brown-300 border-b border-brown-800/40">
                                    <tr>
                                        <th class="px-3 py-2.5 text-left">Item</th>
                                        <th class="px-3 py-2.5">Norm. K1</th>
                                        <th class="px-3 py-2.5">Norm. K2</th>
                                        <th class="px-3 py-2.5">Norm. K3</th>
                                        <th class="px-3 py-2.5 text-gold-400">Bobot Lokal</th>
                                        <th class="px-3 py-2.5 text-gold-300">Ideal Mode</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-brown-800/30 font-mono">
                                    @foreach($activeMeta['items'] as $rIdx => $it)
                                        <tr>
                                            <td class="px-3 py-2.5 text-left font-sans font-medium text-brown-200">{{ $it }}</td>
                                            @for($cIdx = 0; $cIdx < 3; $cIdx++)
                                                <td class="px-3 py-2.5 text-brown-400">{{ number_format($calc['normalized'][$rIdx][$cIdx], 4) }}</td>
                                            @endfor
                                            <td class="px-3 py-2.5 font-bold text-gold-400">{{ number_format($calc['weights'][$rIdx], 4) }}</td>
                                            <td class="px-3 py-2.5 font-bold text-gold-300">{{ number_format($calc['ideal'][$rIdx], 4) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-4 py-2.5 bg-dark-200/70 border-t border-brown-800/40 flex flex-wrap items-center justify-between gap-2 text-[11px] font-mono text-brown-300">
                            <span>&lambda;<sub>max</sub> = <strong class="text-brown-100">{{ number_format($calc['lambda_max'], 4) }}</strong></span>
                            <span>CI = <strong class="text-brown-100">{{ number_format($calc['ci'], 4) }}</strong></span>
                            <span>RI (n=3) = <strong class="text-brown-100">0.5800</strong></span>
                            <span>CR = <strong class="text-gold-400">{{ number_format($calc['cr'], 4) }}</strong> (&le; 0.10)</span>
                        </div>
                    </div>
                </div>

                {{-- Daftar Responden Hasil Upload / Database --}}
                <div class="bg-dark-200/30 rounded-xl border border-brown-800/40 overflow-hidden">
                    <div class="px-4 py-3 border-b border-brown-800/40 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <span class="font-serif text-sm text-brown-100">Data Responden Tersimpan &mdash; {{ $activeMeta['label'] }}</span>
                        <span class="text-xs text-brown-400">Menampilkan {{ $respondens->firstItem() ?? 0 }}&ndash;{{ $respondens->lastItem() ?? 0 }} dari total {{ $respondens->total() }} responden</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left whitespace-nowrap">
                            <thead class="bg-dark-200/60 text-brown-300 font-serif border-b border-brown-800/40">
                                <tr>
                                    <th class="px-4 py-3 text-center w-16">No</th>
                                    <th class="px-4 py-3">Nama Responden</th>
                                    <th class="px-4 py-3 text-center">{{ $activeMeta['pairs'][0] }}</th>
                                    <th class="px-4 py-3 text-center">{{ $activeMeta['pairs'][1] }}</th>
                                    <th class="px-4 py-3 text-center">{{ $activeMeta['pairs'][2] }}</th>
                                    <th class="px-4 py-3 text-right">Tanggal Input</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-brown-800/30">
                                @forelse($respondens as $idx => $resp)
                                    @php
                                        $noUrut = ($respondens->firstItem() ?? 1) + $idx;
                                    @endphp
                                    <tr class="hover:bg-dark-200/40 transition-colors">
                                        <td class="px-4 py-2.5 text-center text-brown-400 font-mono">{{ $noUrut }}</td>
                                        <td class="px-4 py-2.5 font-medium {{ $resp->nama_responden ? 'text-brown-100' : 'text-brown-400 italic' }}">
                                            {{ $resp->nama_responden ?: 'Responden #' . $noUrut }}
                                        </td>
                                        <td class="px-4 py-2.5 text-center font-mono text-brown-200">{{ $fmtClean($resp->nilai_12) }}</td>
                                        <td class="px-4 py-2.5 text-center font-mono text-brown-200">{{ $fmtClean($resp->nilai_13) }}</td>
                                        <td class="px-4 py-2.5 text-center font-mono text-brown-200">{{ $fmtClean($resp->nilai_23) }}</td>
                                        <td class="px-4 py-2.5 text-right text-brown-400">{{ $resp->created_at?->format('d M Y, H:i') ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-brown-400 italic">Belum ada data responden untuk tab ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($respCount > 0)
                                <tfoot class="bg-gold-500/10 border-t-2 border-gold-500/30 font-mono text-gold-300 font-bold">
                                    <tr>
                                        <td class="px-4 py-3 text-center">-</td>
                                        <td class="px-4 py-3 font-sans">TOTAL (Geometric Mean {{ $respCount }} Responden)</td>
                                        <td class="px-4 py-3 text-center">{{ number_format($calc['gm'][0], 4) }}</td>
                                        <td class="px-4 py-3 text-center">{{ number_format($calc['gm'][1], 4) }}</td>
                                        <td class="px-4 py-3 text-center">{{ number_format($calc['gm'][2], 4) }}</td>
                                        <td class="px-4 py-3 text-right font-sans text-[11px]">CR = {{ number_format($calc['cr'], 4) }}</td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>

                    @if($respondens->hasPages())
                        <div class="px-5 py-3.5 border-t border-brown-800/40 bg-dark-100/70 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                            <span class="text-brown-400">Halaman {{ $respondens->currentPage() }} dari {{ $respondens->lastPage() }}</span>
                            <div class="flex flex-wrap items-center gap-1.5">
                                @if($respondens->onFirstPage())
                                    <span class="px-3 py-1.5 rounded-lg bg-dark-200/40 text-brown-600 border border-brown-800/30 cursor-not-allowed">Sebelumnya</span>
                                @else
                                    <a href="{{ $respondens->previousPageUrl() }}#tabel-ahp" class="px-3 py-1.5 rounded-lg bg-dark-200 text-brown-200 border border-brown-800 hover:border-gold-500 hover:text-gold-300 transition-colors">Sebelumnya</a>
                                @endif

                                @foreach($respondens->getUrlRange(1, $respondens->lastPage()) as $pg => $pgUrl)
                                    @if($pg == $respondens->currentPage())
                                        <span class="px-3 py-1.5 rounded-lg bg-gold-500 text-black font-bold">{{ $pg }}</span>
                                    @else
                                        <a href="{{ $pgUrl }}#tabel-ahp" class="px-3 py-1.5 rounded-lg bg-dark-200 text-brown-300 border border-brown-800 hover:border-gold-500 hover:text-gold-300 transition-colors">{{ $pg }}</a>
                                    @endif
                                @endforeach

                                @if($respondens->hasMorePages())
                                    <a href="{{ $respondens->nextPageUrl() }}#tabel-ahp" class="px-3 py-1.5 rounded-lg bg-dark-200 text-brown-200 border border-brown-800 hover:border-gold-500 hover:text-gold-300 transition-colors">Selanjutnya</a>
                                @else
                                    <span class="px-3 py-1.5 rounded-lg bg-dark-200/40 text-brown-600 border border-brown-800/30 cursor-not-allowed">Selanjutnya</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Modal Popup Preview Angka Data Responden Excel (Hanya Tabel Data) --}}
        <style>[x-cloak] { display: none !important; }</style>
        <div x-show="previewOpen" x-cloak
             @keydown.escape.window="closePreview()"
             @click="closePreview()"
             style="position: fixed; inset: 0; z-index: 9999; background-color: rgba(0, 0, 0, 0.82); backdrop-filter: blur(4px); padding: 20px; overflow-y: auto;">
            <div style="min-height: 100%; display: flex; align-items: center; justify-content: center;">
                <div @click.stop
                     class="bg-dark-100 rounded-2xl shadow-2xl"
                     style="width: 100%; max-width: 1020px; max-height: 88vh; display: flex; flex-direction: column; overflow: hidden; border: 1px solid rgba(212, 168, 67, 0.45);">
                    {{-- Header Modal --}}
                    <div class="px-6 py-4 border-b border-brown-800/40 bg-dark-200/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3" style="flex-shrink: 0;">
                        <div>
                            <h3 class="font-serif text-lg text-gold-400">Preview Data Responden Excel</h3>
                            <p class="text-xs text-brown-300 mt-1">
                                File: <strong class="text-brown-100" x-text="previewData?.filename || '-'"></strong>
                                &middot; Total terbaca: <strong class="text-gold-300" x-text="(previewData?.totalRows || 0) + ' baris data'"></strong>
                            </p>
                        </div>
                        <button type="button" @click="closePreview()" class="self-end sm:self-auto rounded-lg bg-dark-300 hover:bg-dark-200 text-brown-300 hover:text-white text-xs border border-brown-800/60" style="padding: 7px 14px;">
                            &times; Tutup
                        </button>
                    </div>

                    {{-- Body Modal (Hanya 4 Tab + Tabel Angka Responden) --}}
                    <div style="flex: 1 1 auto; min-height: 0; display: flex; flex-direction: column; gap: 16px; padding: 20px;">
                        {{-- 4 Tab Switcher Rapi Merata --}}
                        <div style="display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 8px; padding: 6px; background-color: #100904; border: 1px solid rgba(139, 94, 60, 0.35); border-radius: 12px; flex-shrink: 0;">
                            <template x-for="(tabInfo, tabKey) in (previewData?.tabs || {})" :key="tabKey">
                                <button type="button" @click="modalTab = tabKey"
                                        :style="(modalTab === tabKey
                                            ? 'background: linear-gradient(135deg, #f5d565 0%, #d4a843 100%); color: #140d07; font-weight: 700; border: 1px solid #f5d565; box-shadow: 0 2px 10px rgba(212, 168, 67, 0.25);'
                                            : 'background-color: #1e140c; color: #d4b896; font-weight: 500; border: 1px solid rgba(74, 52, 35, 0.7);')
                                            + ' display: flex; align-items: center; justify-content: center; gap: 8px; padding: 10px 12px; border-radius: 8px; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.15s ease;'">
                                    <span x-text="shortTabLabel(tabKey, tabInfo.label)"></span>
                                    <span :style="(modalTab === tabKey
                                            ? 'background-color: rgba(20, 13, 7, 0.2); color: #140d07;'
                                            : 'background-color: rgba(212, 168, 67, 0.14); color: #f5d565;')
                                            + ' padding: 2px 8px; border-radius: 999px; font-size: 11px; font-weight: 700; font-family: monospace; line-height: 1.3; flex-shrink: 0;'"
                                          x-text="tabInfo.count"></span>
                                </button>
                            </template>
                        </div>

                        {{-- Tabel Preview Angka Responden --}}
                        <div class="bg-dark-200/35 rounded-xl border border-brown-800/40 overflow-hidden" style="flex: 1 1 auto; min-height: 0;">
                            <div style="max-height: 52vh; overflow-y: auto; overflow-x: auto;">
                                <table class="w-full text-xs text-left whitespace-nowrap">
                                    <thead class="text-brown-300 font-serif border-b border-brown-800/50" style="position: sticky; top: 0; z-index: 10; background-color: #1e150e;">
                                        <tr>
                                            <th class="text-center" style="padding: 12px 16px; width: 64px;">No</th>
                                            <th style="padding: 12px 16px;">Nama Responden</th>
                                            <th class="text-center" style="padding: 12px 16px;" x-text="currentTab?.pairs?.[0] || '-'"></th>
                                            <th class="text-center" style="padding: 12px 16px;" x-text="currentTab?.pairs?.[1] || '-'"></th>
                                            <th class="text-center" style="padding: 12px 16px;" x-text="currentTab?.pairs?.[2] || '-'"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-brown-800/30">
                                        <template x-for="(item, idx) in (currentTab?.rows || [])" :key="idx">
                                            <tr class="hover:bg-dark-200/50">
                                                <td class="text-center font-mono text-brown-400" style="padding: 10px 16px;" x-text="idx + 1"></td>
                                                <td class="font-medium" style="padding: 10px 16px;"
                                                    :class="item.nama ? 'text-brown-100' : 'text-brown-400 italic'"
                                                    x-text="item.nama || ('Responden #' + (idx + 1))"></td>
                                                <td class="text-center font-mono text-brown-200" style="padding: 10px 16px;" x-text="fmtClean(item.v12)"></td>
                                                <td class="text-center font-mono text-brown-200" style="padding: 10px 16px;" x-text="fmtClean(item.v13)"></td>
                                                <td class="text-center font-mono text-brown-200" style="padding: 10px 16px;" x-text="fmtClean(item.v23)"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Modal --}}
                    <div class="px-6 py-4 border-t border-brown-800/40 bg-dark-200/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3" style="flex-shrink: 0;">
                        <span class="text-[11px] text-brown-300">
                            Data belum disimpan ke database. Klik simpan jika angka responden di atas sudah sesuai.
                        </span>
                        <div class="flex items-center justify-end gap-2.5">
                            <button type="button" @click="closePreview()" class="btn-outline text-xs py-2 px-4">
                                Batal
                            </button>
                            <form action="{{ route('backend.spk.import_ahp') }}" method="POST">
                                @csrf
                                <input type="hidden" name="preview_token" :value="previewToken">
                                <button type="submit" class="btn-gold text-xs py-2 px-5 whitespace-nowrap">
                                    Simpan &amp; Hitung Ulang
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function ahpPreviewManager(previewUrl) {
            return {
                isLoading: false,
                previewOpen: false,
                previewToken: '',
                previewData: null,
                modalTab: 'kriteria',
                ajaxErrors: [],

                get currentTab() {
                    if (!this.previewData || !this.previewData.tabs) return null;
                    return this.previewData.tabs[this.modalTab] || null;
                },

                fmtClean(val) {
                    const num = Number(val);
                    if (!Number.isFinite(num)) return val;
                    return Number.isInteger(num) ? String(num) : String(parseFloat(num.toFixed(4)));
                },

                shortTabLabel(tabKey, fallback) {
                    const labels = {
                        kriteria: 'Kriteria Utama',
                        sub_harga: 'Sub Harga',
                        sub_kualitas: 'Sub Kualitas',
                        sub_fasilitas: 'Sub Fasilitas',
                    };
                    return labels[tabKey] || fallback;
                },

                async runPreview() {
                    const input = this.$refs.fileInput;
                    if (!input || !input.files || !input.files[0]) {
                        this.ajaxErrors = ['Pilih file Excel (.xlsx) 4 tab terlebih dahulu sebelum melakukan preview.'];
                        return;
                    }

                    this.isLoading = true;
                    this.ajaxErrors = [];

                    const formData = new FormData();
                    formData.append('file_excel', input.files[0]);

                    try {
                        const res = await fetch(previewUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                            body: formData,
                        });
                        const data = await res.json();

                        if (!res.ok || !data.ok) {
                            this.ajaxErrors = Array.isArray(data.errors) && data.errors.length
                                ? data.errors
                                : [data.error || 'Gagal membaca file Excel.'];
                            return;
                        }

                        this.previewData = data;
                        this.previewToken = data.token;
                        this.modalTab = 'kriteria';
                        this.previewOpen = true;
                    } catch (e) {
                        this.ajaxErrors = ['Gagal menghubungi server untuk membaca file Excel: ' + e.message];
                    } finally {
                        this.isLoading = false;
                    }
                },

                closePreview() {
                    this.previewOpen = false;
                },
            };
        }
    </script>
</x-backend-layout>
