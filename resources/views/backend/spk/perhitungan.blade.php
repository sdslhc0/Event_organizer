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
        @if(session('success'))
            <div class="p-4 rounded-xl bg-green-950/60 border border-green-700/50 text-green-300 flex items-center gap-3 shadow-lg">
                <svg class="w-5 h-5 flex-shrink-0 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

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
                            <th class="px-6 py-4 font-medium text-center w-24">Rank</th>
                            <th class="px-6 py-4 font-medium">Nama Paket</th>
                            <th class="px-6 py-4 font-medium text-center">N. Harga (0.50)</th>
                            <th class="px-6 py-4 font-medium text-center">N. Kualitas (0.27)</th>
                            <th class="px-6 py-4 font-medium text-center">N. Fasilitas (0.23)</th>
                            <th class="px-8 py-4 font-medium text-center w-48">Skor Akhir</th>
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
                                <td class="px-6 py-4 text-center text-brown-300 font-mono">{{ isset($p->nilai_harga) ? number_format($p->nilai_harga, 4) : '-' }}</td>
                                <td class="px-6 py-4 text-center text-brown-300 font-mono">{{ isset($p->nilai_kualitas) ? number_format($p->nilai_kualitas, 4) : '-' }}</td>
                                <td class="px-6 py-4 text-center text-brown-300 font-mono">{{ isset($p->nilai_fasilitas) ? number_format($p->nilai_fasilitas, 4) : '-' }}</td>
                                <td class="px-8 py-4 text-center">
                                    @if($p->skor_akhir)
                                        <div class="inline-flex items-center justify-center gap-2.5">
                                            <span class="font-mono font-semibold {{ $p->ranking == 1 ? 'text-gold-400' : 'text-gold-500/90' }}">
                                                {{ number_format($p->skor_akhir, 4) }}
                                            </span>
                                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold {{ $p->ranking == 1 ? 'bg-gold-500/20 text-gold-300 border border-gold-500/40' : 'bg-dark-200 text-brown-300 border border-brown-800/70' }}">
                                                {{ number_format($p->skor_akhir * 100, 1) }}%
                                            </span>
                                        </div>
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
            {{-- Left Chart: Bar Chart Top Paket --}}
            <div class="bg-dark-100 rounded-xl border border-brown-800/30 p-6 flex flex-col">
                <div class="flex items-start justify-between gap-4 mb-5">
                    <div>
                        <h3 class="text-lg font-serif text-gold-500">
                            {{ $recommendedPakets->count() > 0 ? 'Top ' . $recommendedPakets->count() . ' Paket Direkomendasikan' : 'Paket Direkomendasikan' }}
                        </h3>
                        <p class="text-xs text-brown-400 mt-0.5">Perbandingan skor akhir AHP setiap paket bundling (skala persentase)</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-medium bg-gold-500/10 text-gold-400 border border-gold-500/25 whitespace-nowrap">
                        {{ $recommendedPakets->count() }} Paket
                    </span>
                </div>
                <div class="relative h-72 w-full">
                    <canvas id="recommendedChart"></canvas>
                </div>
            </div>

            {{-- Right Chart: Doughnut Distribusi Skor --}}
            <div class="bg-dark-100 rounded-xl border border-brown-800/30 p-6 flex flex-col">
                <div class="flex items-start justify-between gap-4 mb-5">
                    <div>
                        <h3 class="text-lg font-serif text-gold-500">Distribusi Skor Paket</h3>
                        <p class="text-xs text-brown-400 mt-0.5">Proporsi kontribusi rekomendasi dari total 100% bobot AHP</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-medium bg-gold-500/10 text-gold-400 border border-gold-500/25 whitespace-nowrap">
                        Total 100%
                    </span>
                </div>
                <div class="relative h-72 w-full">
                    <canvas id="distributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Setup -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const paketNames = {!! json_encode($recommendedPakets->map(fn($item) => $item->paketBundling->nama ?? 'Paket')->values()) !!};
            const paketRawScores = {!! json_encode($recommendedPakets->map(fn($item) => round((float) ($item->skor_akhir ?? 0), 4))->values()) !!};
            const paketPercentages = paketRawScores.map(val => Number((val * 100).toFixed(1)));
            const paketRanks = {!! json_encode($recommendedPakets->pluck('ranking')->values()) !!};

            const distLabels = {!! json_encode(array_keys($scoreDistribution)) !!};
            const distValues = {!! json_encode(array_values($scoreDistribution)) !!};

            const tooltipTheme = {
                backgroundColor: 'rgba(20, 13, 7, 0.96)',
                titleColor: '#f5d565',
                bodyColor: '#f5ebe4',
                borderColor: 'rgba(212, 168, 67, 0.55)',
                borderWidth: 1,
                padding: 12,
                cornerRadius: 8,
                titleFont: { family: "'Playfair Display', serif", size: 13, weight: 'bold' },
                bodyFont: { family: "'Inter', sans-serif", size: 12 },
                displayColors: true,
                boxPadding: 5
            };

            // Custom plugin: draw percentage labels above bars
            const barValueLabelsPlugin = {
                id: 'barValueLabels',
                afterDatasetsDraw(chart) {
                    const { ctx } = chart;
                    chart.data.datasets.forEach((dataset, i) => {
                        const meta = chart.getDatasetMeta(i);
                        meta.data.forEach((bar, index) => {
                            const val = dataset.data[index];
                            if (val === null || val === undefined) return;
                            ctx.save();
                            ctx.font = "600 12px 'Inter', sans-serif";
                            ctx.fillStyle = index === 0 ? '#fce588' : '#e8d5c4';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'bottom';
                            ctx.fillText(val.toFixed(1) + '%', bar.x, bar.y - 6);
                            ctx.restore();
                        });
                    });
                }
            };

            // Bar Chart
            const recCanvas = document.getElementById('recommendedChart');
            if (recCanvas && paketNames.length > 0) {
                const ctx = recCanvas.getContext('2d');

                const makeGrad = (topColor, bottomColor) => {
                    const grad = ctx.createLinearGradient(0, 0, 0, 260);
                    grad.addColorStop(0, topColor);
                    grad.addColorStop(1, bottomColor);
                    return grad;
                };

                const barBackgrounds = [
                    makeGrad('rgba(245, 213, 101, 0.92)', 'rgba(184, 146, 46, 0.28)'), // #1 Gold
                    makeGrad('rgba(212, 184, 150, 0.80)', 'rgba(166, 124, 82, 0.22)'), // #2 Champagne
                    makeGrad('rgba(166, 124, 82, 0.72)', 'rgba(107, 66, 38, 0.20)'),  // #3 Bronze
                    makeGrad('rgba(139, 94, 60, 0.65)', 'rgba(74, 44, 23, 0.20)'),
                    makeGrad('rgba(107, 66, 38, 0.60)', 'rgba(45, 24, 16, 0.20)')
                ];

                const barBorders = [
                    '#f5d565',
                    '#d4b896',
                    '#a67c52',
                    '#8b5e3c',
                    '#6b4226'
                ];

                const maxPct = Math.max(...paketPercentages, 40);
                const suggestedMax = Math.min(100, Math.ceil((maxPct + 14) / 10) * 10);

                new Chart(recCanvas, {
                    type: 'bar',
                    data: {
                        labels: paketNames.map((n, idx) => `#${paketRanks[idx] ?? (idx + 1)} ${n}`),
                        datasets: [{
                            label: 'Skor AHP',
                            data: paketPercentages,
                            backgroundColor: paketNames.map((_, i) => barBackgrounds[i % barBackgrounds.length]),
                            borderColor: paketNames.map((_, i) => barBorders[i % barBorders.length]),
                            borderWidth: 1.5,
                            borderRadius: { topLeft: 10, topRight: 10, bottomLeft: 0, bottomRight: 0 },
                            borderSkipped: false,
                            maxBarThickness: 72
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: { padding: { top: 24, right: 10, left: 4, bottom: 0 } },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                ...tooltipTheme,
                                callbacks: {
                                    label: function(context) {
                                        const idx = context.dataIndex;
                                        return [
                                            ` Persentase : ${paketPercentages[idx].toFixed(1)}%`,
                                            ` Skor AHP   : ${paketRawScores[idx].toFixed(4)}`
                                        ];
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                suggestedMax: suggestedMax,
                                border: { display: false },
                                grid: {
                                    color: 'rgba(212, 168, 67, 0.08)',
                                    tickLength: 0
                                },
                                ticks: {
                                    color: '#c19a6b',
                                    font: { family: "'Inter', sans-serif", size: 11 },
                                    padding: 8,
                                    callback: (val) => val + '%'
                                }
                            },
                            x: {
                                border: { color: 'rgba(212, 168, 67, 0.18)' },
                                grid: { display: false },
                                ticks: {
                                    color: '#f5ebe4',
                                    font: { family: "'Inter', sans-serif", size: 12, weight: '500' },
                                    padding: 8
                                }
                            }
                        }
                    },
                    plugins: [barValueLabelsPlugin]
                });
            }

            // Custom plugin: draw #1 top package summary inside doughnut center
            const doughnutCenterPlugin = {
                id: 'doughnutCenterText',
                afterDraw(chart) {
                    const { ctx, chartArea } = chart;
                    if (!chartArea || distLabels.length === 0) return;

                    const centerX = (chartArea.left + chartArea.right) / 2;
                    const centerY = (chartArea.top + chartArea.bottom) / 2;

                    ctx.save();
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';

                    ctx.font = "600 10px 'Inter', sans-serif";
                    ctx.fillStyle = '#c19a6b';
                    ctx.fillText('TERBAIK #1', centerX, centerY - 16);

                    ctx.font = "700 22px 'Playfair Display', serif";
                    ctx.fillStyle = '#f5d565';
                    ctx.fillText((distValues[0] ?? 0).toFixed(1) + '%', centerX, centerY + 6);

                    ctx.font = "500 11px 'Inter', sans-serif";
                    ctx.fillStyle = '#e8d5c4';
                    const topName = (distLabels[0] ?? '').replace(' Package', '');
                    ctx.fillText(topName, centerX, centerY + 26);

                    ctx.restore();
                }
            };

            // Doughnut Chart
            const distCanvas = document.getElementById('distributionChart');
            if (distCanvas && distLabels.length > 0) {
                const sliceColors = [
                    '#d4a843', // #1 Rich Luxury Gold
                    '#b88e52', // #2 Warm Champagne Gold
                    '#755031', // #3 Deep Bronze Espresso
                    '#4d3623',
                    '#382618'
                ];

                new Chart(distCanvas, {
                    type: 'doughnut',
                    data: {
                        labels: distLabels.map((lbl, i) => `${lbl} (${(distValues[i] ?? 0).toFixed(1)}%)`),
                        datasets: [{
                            data: distValues,
                            backgroundColor: distLabels.map((_, i) => sliceColors[i % sliceColors.length]),
                            hoverBackgroundColor: distLabels.map((_, i) => i === 0 ? '#f5d565' : sliceColors[i % sliceColors.length]),
                            borderColor: '#2d2218',
                            borderWidth: 3,
                            hoverOffset: 6,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '66%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: '#e8d5c4',
                                    padding: 18,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    boxWidth: 8,
                                    boxHeight: 8,
                                    font: { family: "'Inter', sans-serif", size: 12 }
                                }
                            },
                            tooltip: {
                                ...tooltipTheme,
                                callbacks: {
                                    title: (items) => distLabels[items[0].dataIndex] ?? '',
                                    label: function(context) {
                                        const idx = context.dataIndex;
                                        const raw = paketRawScores[idx] ?? (distValues[idx] / 100);
                                        return [
                                            ` Porsi Rekomendasi : ${distValues[idx].toFixed(1)}%`,
                                            ` Skor Akhir AHP    : ${Number(raw).toFixed(4)}`
                                        ];
                                    }
                                }
                            }
                        }
                    },
                    plugins: [doughnutCenterPlugin]
                });
            }
        });
    </script>
</x-backend-layout>
