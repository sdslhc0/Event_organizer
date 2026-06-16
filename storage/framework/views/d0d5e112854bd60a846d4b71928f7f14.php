<?php if (isset($component)) { $__componentOriginala2edde5e1c82a5de5f92a34d70a8fe30 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala2edde5e1c82a5de5f92a34d70a8fe30 = $attributes; } ?>
<?php $component = App\View\Components\BackendLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('backend-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\BackendLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('title', null, []); ?> SPK Paket Bundling - Admin MELODI <?php $__env->endSlot(); ?>
     <?php $__env->slot('header', null, []); ?> SPK Paket Bundling <?php $__env->endSlot(); ?>

     <?php $__env->slot('headerActions', null, []); ?> 
        <form action="<?php echo e(route('backend.spk.calculate')); ?>" method="POST" class="inline">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Hitung Ulang SPK
            </button>
        </form>
     <?php $__env->endSlot(); ?>

    <div class="space-y-6">
        <!-- Analytics Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Recommended Chart -->
            <div class="bg-dark-100 rounded-xl border border-brown-800/30 p-6">
                <h3 class="text-lg font-serif text-gold-500 mb-4">Top 5 Paket Direkomendasikan</h3>
                <canvas id="recommendedChart" height="200"></canvas>
            </div>

            <!-- Score Distribution -->
            <div class="bg-dark-100 rounded-xl border border-brown-800/30 p-6">
                <h3 class="text-lg font-serif text-gold-500 mb-4">Distribusi Skor Paket</h3>
                <canvas id="distributionChart" height="200"></canvas>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-dark-100 rounded-xl border border-brown-800/30 overflow-hidden flex flex-col">
            <div class="p-6 border-b border-brown-800/30 flex flex-col md:flex-row gap-4 justify-between items-center bg-dark-100/50">
                <form action="<?php echo e(route('backend.spk.index')); ?>" method="GET" class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
                    <div class="relative w-full md:w-64">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari paket..." 
                               class="w-full bg-dark-200 border border-brown-800/50 rounded-lg pl-10 pr-4 py-2 text-sm text-brown-100 focus:border-gold-500/50 focus:ring-1 focus:ring-gold-500/50">
                        <svg class="w-4 h-4 text-brown-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <select name="budget" class="bg-dark-200 border border-brown-800/50 rounded-lg px-4 py-2 text-sm text-brown-100 focus:border-gold-500/50 focus:ring-1 focus:ring-gold-500/50">
                        <option value="">Semua Budget</option>
                        <option value="under_10" <?php echo e(request('budget') == 'under_10' ? 'selected' : ''); ?>>Di bawah Rp 10 Juta</option>
                        <option value="10_20" <?php echo e(request('budget') == '10_20' ? 'selected' : ''); ?>>Rp 10 Juta - Rp 20 Juta</option>
                        <option value="above_20" <?php echo e(request('budget') == 'above_20' ? 'selected' : ''); ?>>Di atas Rp 20 Juta</option>
                    </select>
                    <button type="submit" class="btn-secondary">Filter</button>
                    <?php if(request()->hasAny(['search', 'budget'])): ?>
                        <a href="<?php echo e(route('backend.spk.index')); ?>" class="btn-secondary flex justify-center items-center">Reset</a>
                    <?php endif; ?>
                </form>

                <div class="flex gap-2 w-full md:w-auto">
                    <a href="<?php echo e(route('backend.spk.export_pdf')); ?>" class="btn-secondary text-sm flex-1 md:flex-none text-center">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        PDF
                    </a>
                    <a href="<?php echo e(route('backend.spk.export_excel')); ?>" class="btn-secondary text-sm flex-1 md:flex-none text-center">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Excel
                    </a>
                    <form action="<?php echo e(route('backend.spk.delete_hasil')); ?>" method="POST" class="inline flex-1 md:flex-none" onsubmit="return confirm('Yakin ingin menghapus semua hasil perhitungan SPK?');">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full btn-danger text-sm text-center">Reset</button>
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-dark-200/50 text-brown-300 font-serif border-b border-brown-800/30">
                        <tr>
                            <th class="px-6 py-4 font-medium">Rank</th>
                            <th class="px-6 py-4 font-medium">Paket</th>
                            <th class="px-6 py-4 font-medium">Harga</th>
                            <th class="px-6 py-4 font-medium">N. Harga (0.57)</th>
                            <th class="px-6 py-4 font-medium">N. Kualitas (0.22)</th>
                            <th class="px-6 py-4 font-medium">N. Fasilitas (0.21)</th>
                            <th class="px-6 py-4 font-medium">Skor Akhir</th>
                            <th class="px-6 py-4 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brown-800/30">
                        <?php $__empty_1 = true; $__currentLoopData = $rankedPakets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-dark-200/30 transition-colors <?php echo e($paket->penilaian && $paket->penilaian->ranking == 1 ? 'bg-gold-500/5' : ''); ?>">
                                <td class="px-6 py-4">
                                    <?php if($paket->penilaian && $paket->penilaian->ranking): ?>
                                        <?php if($paket->penilaian->ranking == 1): ?>
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gold-500 text-dark font-bold text-xs">1</span>
                                            <span class="ml-2 text-xs text-gold-500 font-bold hidden md:inline">Best Choice</span>
                                        <?php elseif($paket->penilaian->ranking == 2): ?>
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-brown-400 text-dark font-bold text-xs">2</span>
                                            <span class="ml-2 text-xs text-brown-400 font-medium hidden md:inline">Recommended</span>
                                        <?php elseif($paket->penilaian->ranking == 3): ?>
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-brown-600 text-brown-100 font-bold text-xs">3</span>
                                            <span class="ml-2 text-xs text-brown-400 hidden md:inline">Alternative</span>
                                        <?php else: ?>
                                            <span class="text-brown-400 font-medium"><?php echo e($paket->penilaian->ranking); ?></span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-brown-600 italic">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded border border-brown-800/30 overflow-hidden bg-dark-200 flex-shrink-0">
                                            <?php if($paket->gambar): ?>
                                                <img src="<?php echo e(asset('storage/' . $paket->gambar)); ?>" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <div class="w-full h-full flex items-center justify-center text-brown-600">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <div class="text-brown-100 font-medium"><?php echo e($paket->nama); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-brown-300"><?php echo e($paket->formatted_harga); ?></td>
                                <td class="px-6 py-4 text-center text-brown-300"><?php echo e($paket->penilaian->nilai_harga ?? '-'); ?></td>
                                <td class="px-6 py-4 text-center text-brown-300"><?php echo e($paket->penilaian->nilai_kualitas ?? '-'); ?></td>
                                <td class="px-6 py-4 text-center text-brown-300"><?php echo e($paket->penilaian->nilai_fasilitas ?? '-'); ?></td>
                                <td class="px-6 py-4">
                                    <?php if($paket->penilaian && $paket->penilaian->skor_akhir): ?>
                                        <span class="font-medium text-gold-400"><?php echo e(number_format($paket->penilaian->skor_akhir, 4)); ?></span>
                                    <?php else: ?>
                                        <span class="text-brown-600 italic">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button onclick="openModal('modal-nilai-<?php echo e($paket->id); ?>')" class="text-gold-500 hover:text-gold-400 text-sm font-medium transition-colors">
                                        Set Nilai
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-brown-400 italic">Tidak ada paket bundling.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modals for Setting Nilai -->
    <?php $__currentLoopData = $rankedPakets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div id="modal-nilai-<?php echo e($paket->id); ?>" class="fixed inset-0 z-50 hidden flex items-center justify-center">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeModal('modal-nilai-<?php echo e($paket->id); ?>')"></div>
            <div class="bg-dark-100 rounded-xl border border-brown-800/30 w-full max-w-lg mx-4 relative z-10 shadow-2xl">
                <div class="p-6 border-b border-brown-800/30 flex justify-between items-center">
                    <h3 class="font-serif text-lg text-brown-100">Set Nilai SPK: <?php echo e($paket->nama); ?></h3>
                    <button onclick="closeModal('modal-nilai-<?php echo e($paket->id); ?>')" class="text-brown-400 hover:text-brown-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form action="<?php echo e(route('backend.spk.update_nilai', $paket->id)); ?>" method="POST" class="p-6 space-y-4">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="block text-sm font-medium text-brown-300 mb-1">Harga</label>
                        <select name="nilai_harga" required class="w-full bg-dark-200 border border-brown-800/50 rounded-lg px-4 py-2.5 text-brown-100 focus:border-gold-500/50 focus:ring-1 focus:ring-gold-500/50">
                            <option value="">Pilih Kategori Harga</option>
                            <option value="1" <?php echo e(($paket->penilaian->nilai_harga ?? '') == 1 ? 'selected' : ''); ?>>Terjangkau (1)</option>
                            <option value="0.41" <?php echo e(($paket->penilaian->nilai_harga ?? '') == 0.41 ? 'selected' : ''); ?>>Cukup Mahal (0.41)</option>
                            <option value="0.17" <?php echo e(($paket->penilaian->nilai_harga ?? '') == 0.17 ? 'selected' : ''); ?>>Sangat Mahal (0.17)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-brown-300 mb-1">Kualitas</label>
                        <select name="nilai_kualitas" required class="w-full bg-dark-200 border border-brown-800/50 rounded-lg px-4 py-2.5 text-brown-100 focus:border-gold-500/50 focus:ring-1 focus:ring-gold-500/50">
                            <option value="">Pilih Kategori Kualitas</option>
                            <option value="1" <?php echo e(($paket->penilaian->nilai_kualitas ?? '') == 1 ? 'selected' : ''); ?>>Sangat Bagus (1)</option>
                            <option value="0.41" <?php echo e(($paket->penilaian->nilai_kualitas ?? '') == 0.41 ? 'selected' : ''); ?>>Cukup Bagus (0.41)</option>
                            <option value="0.17" <?php echo e(($paket->penilaian->nilai_kualitas ?? '') == 0.17 ? 'selected' : ''); ?>>Kurang Bagus (0.17)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-brown-300 mb-1">Fasilitas</label>
                        <select name="nilai_fasilitas" required class="w-full bg-dark-200 border border-brown-800/50 rounded-lg px-4 py-2.5 text-brown-100 focus:border-gold-500/50 focus:ring-1 focus:ring-gold-500/50">
                            <option value="">Pilih Kategori Fasilitas</option>
                            <option value="1" <?php echo e(($paket->penilaian->nilai_fasilitas ?? '') == 1 ? 'selected' : ''); ?>>Sangat Lengkap (1)</option>
                            <option value="0.41" <?php echo e(($paket->penilaian->nilai_fasilitas ?? '') == 0.41 ? 'selected' : ''); ?>>Cukup Lengkap (0.41)</option>
                            <option value="0.17" <?php echo e(($paket->penilaian->nilai_fasilitas ?? '') == 0.17 ? 'selected' : ''); ?>>Kurang Lengkap (0.17)</option>
                        </select>
                    </div>
                    <div class="pt-4 flex justify-end gap-3">
                        <button type="button" onclick="closeModal('modal-nilai-<?php echo e($paket->id); ?>')" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">Simpan Nilai</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <!-- Chart.js Setup -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }
        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        document.addEventListener("DOMContentLoaded", function() {
            // Data for Recommended Chart
            const recommendedData = {
                labels: <?php echo json_encode($recommendedPakets->pluck('nama')); ?>,
                datasets: [{
                    label: 'Skor SPK',
                    data: <?php echo json_encode($recommendedPakets->pluck('penilaian.skor_akhir')); ?>,
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
                            ticks: { color: 'rgba(168, 152, 128, 0.8)', maxRotation: 45, minRotation: 45 }
                        }
                    }
                }
            };
            new Chart(document.getElementById('recommendedChart'), configRecommended);

            // Data for Distribution Chart
            const distributionData = {
                labels: <?php echo json_encode(array_keys($scoreDistribution)); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_values($scoreDistribution)); ?>,
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
            new Chart(document.getElementById('distributionChart'), configDistribution);
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala2edde5e1c82a5de5f92a34d70a8fe30)): ?>
<?php $attributes = $__attributesOriginala2edde5e1c82a5de5f92a34d70a8fe30; ?>
<?php unset($__attributesOriginala2edde5e1c82a5de5f92a34d70a8fe30); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala2edde5e1c82a5de5f92a34d70a8fe30)): ?>
<?php $component = $__componentOriginala2edde5e1c82a5de5f92a34d70a8fe30; ?>
<?php unset($__componentOriginala2edde5e1c82a5de5f92a34d70a8fe30); ?>
<?php endif; ?>
<?php /**PATH C:\0704\2704\event_organizer\resources\views/backend/spk/index.blade.php ENDPATH**/ ?>