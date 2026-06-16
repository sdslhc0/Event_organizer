<x-backend-layout>
    <x-slot name="title">Perhitungan AHP/SPK - Admin MELODI</x-slot>
    <x-slot name="header">Perhitungan AHP / SPK</x-slot>

    <x-slot name="headerActions">
        <form action="{{ route('backend.spk.perhitungan.calculate') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="btn-gold flex items-center gap-2 shadow-lg transform hover:-translate-y-0.5 transition-all">
                <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Hitung Ulang SPK
            </button>
        </form>
    </x-slot>

    <div class="space-y-6">
        <!-- Table Section -->
        <div class="bg-dark-100 rounded-xl border border-brown-800/30 overflow-hidden flex flex-col">
            <div class="p-6 border-b border-brown-800/30 flex justify-between items-center bg-dark-100/50">
                <h3 class="font-serif text-lg text-brown-100">Hasil Perhitungan & Ranking</h3>
                <a href="{{ route('backend.spk.export_pdf') }}" class="bg-dark-300 hover:bg-dark-200 text-gold-500 hover:text-gold-400 border border-gold-500/30 hover:border-gold-500 font-medium py-2 px-5 rounded-lg shadow-sm transition-all flex items-center gap-2 group">
                    <svg class="w-5 h-5 group-hover:-translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Cetak Laporan PDF
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-dark-200/50 text-brown-300 font-serif border-b border-brown-800/30">
                        <tr>
                            <th class="px-6 py-4 font-medium text-center w-20">Rank</th>
                            <th class="px-6 py-4 font-medium">Nama Paket</th>
                            <th class="px-6 py-4 font-medium text-center">N. Harga (0.50)</th>
                            <th class="px-6 py-4 font-medium text-center">N. Kualitas (0.27)</th>
                            <th class="px-6 py-4 font-medium text-center">N. Fasilitas (0.23)</th>
                            <th class="px-6 py-4 font-medium text-right">Skor Akhir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brown-800/30">
                        @forelse($penilaians as $p)
                            <tr class="hover:bg-dark-200/30 transition-colors {{ $p->ranking == 1 ? 'bg-gold-500/5' : '' }}">
                                <td class="px-6 py-4 text-center">
                                    @if($p->ranking)
                                        @if($p->ranking == 1)
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gold-500 text-black font-bold text-xs shadow-[0_0_10px_rgba(201,168,76,0.4)]">1</span>
                                        @else
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-dark-300 text-brown-300 font-medium text-xs border border-brown-800">{{ $p->ranking }}</span>
                                        @endif
                                    @else
                                        <span class="text-brown-600 italic">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-brown-100 font-medium">{{ $p->paketBundling->nama }}</td>
                                <td class="px-6 py-4 text-center text-brown-300">{{ $p->nilai_harga ?? '-' }}</td>
                                <td class="px-6 py-4 text-center text-brown-300">{{ $p->nilai_kualitas ?? '-' }}</td>
                                <td class="px-6 py-4 text-center text-brown-300">{{ $p->nilai_fasilitas ?? '-' }}</td>
                                <td class="px-6 py-4 text-right">
                                    @if($p->skor_akhir)
                                        <span class="font-medium text-gold-400">{{ number_format($p->skor_akhir, 4) }}</span>
                                    @else
                                        <span class="text-brown-600 italic">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-brown-400 italic">Data perhitungan belum ada. Pastikan sudah input penilaian paket.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Analytics Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-dark-100 rounded-xl border border-brown-800/30 p-6">
                <h3 class="text-lg font-serif text-gold-500 mb-4">Top 5 Paket Direkomendasikan</h3>
                <canvas id="recommendedChart" height="200"></canvas>
            </div>
            <div class="bg-dark-100 rounded-xl border border-brown-800/30 p-6">
                <h3 class="text-lg font-serif text-gold-500 mb-4">Distribusi Skor Paket</h3>
                <canvas id="distributionChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart.js Setup -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Data for Recommended Chart
            const recommendedData = {
                labels: {!! json_encode($recommendedPakets->map(function($item) { return $item->paketBundling->nama; })->values()) !!},
                datasets: [{
                    label: 'Skor SPK',
                    data: {!! json_encode($recommendedPakets->pluck('skor_akhir')->values()) !!},
                    backgroundColor: 'rgba(201, 168, 76, 0.2)',
                    borderColor: 'rgba(201, 168, 76, 1)',
                    borderWidth: 1
                }]
            };

            const configRecommended = {
                type: 'bar',
                data: recommendedData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 1,
                            grid: { color: 'rgba(125, 113, 97, 0.1)' },
                            ticks: { color: 'rgba(168, 152, 128, 0.8)' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: 'rgba(168, 152, 128, 0.8)' }
                        }
                    }
                }
            };
            
            if(document.getElementById('recommendedChart')) {
                new Chart(document.getElementById('recommendedChart'), configRecommended);
            }

            // Data for Distribution Chart
            const distributionData = {
                labels: {!! json_encode(array_keys($scoreDistribution)) !!},
                datasets: [{
                    data: {!! json_encode(array_values($scoreDistribution)) !!},
                    backgroundColor: [
                        'rgba(201, 168, 76, 0.8)',
                        'rgba(168, 152, 128, 0.8)',
                        'rgba(125, 113, 97, 0.8)',
                        'rgba(74, 66, 57, 0.8)'
                    ],
                    borderWidth: 0
                }]
            };

            const configDistribution = {
                type: 'doughnut',
                data: distributionData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: { color: 'rgba(168, 152, 128, 0.8)' }
                        }
                    }
                }
            };
            
            if(document.getElementById('distributionChart')) {
                new Chart(document.getElementById('distributionChart'), configDistribution);
            }
        });
    </script>
</x-backend-layout>
