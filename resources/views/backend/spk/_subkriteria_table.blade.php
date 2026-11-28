{{-- Tabel Ringkasan Skala Subkriteria (Format Baris Label + Nilai seperti Excel) --}}
<div class="bg-dark-100 rounded-xl border border-brown-800/30 overflow-hidden shadow-lg">
    <div class="px-5 py-3.5 border-b border-brown-800/30 bg-dark-100/60 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div>
            <h3 class="font-serif text-base text-gold-400">Tabel Acuan Nilai Subkriteria (Ideal Mode)</h3>
            <p class="text-[11px] text-brown-400 mt-0.5">Ringkasan skala konversi aktif yang dipakai saat pelanggan mengisi kuisioner penilaian paket.</p>
        </div>
        <span class="text-[11px] text-brown-400">Otomatis diperbarui dari hasil perhitungan AHP</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-center text-xs border-collapse">
            <tbody>
                @foreach([
                    ['key' => 'sub_harga', 'title' => 'Harga', 'weight' => $ahpWeights['harga'] ?? 0],
                    ['key' => 'sub_kualitas', 'title' => 'Kualitas', 'weight' => $ahpWeights['kualitas'] ?? 0],
                    ['key' => 'sub_fasilitas', 'title' => 'Fasilitas', 'weight' => $ahpWeights['fasilitas'] ?? 0],
                ] as $subRow)
                    @php
                        $subMeta = $categories[$subRow['key']];
                        $subStat = $ahpStatuses->get($subRow['key']);
                        $subIdeals = $subStat
                            ? [$subStat->ideal_1, $subStat->ideal_2, $subStat->ideal_3]
                            : [1.0, 0.5, 0.25];
                    @endphp
                    <tr class="bg-dark-200/70 text-brown-300 border-t border-brown-800/40">
                        <td class="px-5 py-2 w-44"></td>
                        @foreach($subMeta['items'] as $lbl)
                            <td class="px-5 py-2 font-serif font-semibold text-gold-300/90 tracking-wide">{{ $lbl }}</td>
                        @endforeach
                    </tr>
                    <tr class="bg-dark-100 border-b border-brown-800/40 hover:bg-dark-200/30 transition-colors">
                        <td class="px-5 py-3 font-serif font-semibold text-brown-100 text-sm">
                            {{ $subRow['title'] }}
                            <span class="block text-[10px] font-mono font-normal text-brown-400">Bobot: {{ number_format($subRow['weight'], 4) }}</span>
                        </td>
                        @foreach($subIdeals as $iIdx => $val)
                            <td class="px-5 py-3 font-mono text-sm {{ $iIdx === 0 ? 'font-bold text-gold-400' : 'font-semibold text-brown-200' }}">
                                {{ $iIdx === 0 && abs($val - 1.0) < 0.0001 ? '1' : number_format($val, 4) }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
