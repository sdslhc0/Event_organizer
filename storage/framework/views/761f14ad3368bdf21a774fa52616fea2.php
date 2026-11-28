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
     <?php $__env->slot('title', null, []); ?> Penilaian Paket & Kuisioner - Admin MELODI <?php $__env->endSlot(); ?>
     <?php $__env->slot('header', null, []); ?> Penilaian Paket & Kuisioner <?php $__env->endSlot(); ?>

     <?php $__env->slot('headerActions', null, []); ?> 
        <div class="flex items-center gap-3">
            <form action="<?php echo e(route('backend.spk.perhitungan.calculate')); ?>" method="POST" class="inline">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn-gold flex items-center gap-2 text-xs py-2 px-4 shadow-md hover:shadow-gold-500/20">
                    <svg class="w-4 h-4 animate-spin-hover" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>Sinkronkan SPK</span>
                </button>
            </form>
            <a href="<?php echo e(route('backend.spk.export_pdf')); ?>" class="btn-outline flex items-center gap-2 text-xs py-2 px-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Cetak PDF</span>
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="space-y-6">
        
        <?php if(session('success')): ?>
            <div class="p-4 rounded-xl bg-green-950/60 border border-green-700/50 text-green-300 flex items-center gap-3 shadow-lg">
                <svg class="w-5 h-5 flex-shrink-0 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="text-sm font-medium"><?php echo e(session('success')); ?></span>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="p-4 rounded-xl bg-red-950/60 border border-red-700/50 text-red-300 flex items-center gap-3 shadow-lg">
                <svg class="w-5 h-5 flex-shrink-0 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm font-medium"><?php echo e(session('error')); ?></span>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="p-4 rounded-xl bg-red-950/60 border border-red-700/50 text-red-300 shadow-lg text-sm">
                <?php echo e($errors->first()); ?>

            </div>
        <?php endif; ?>

        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            
            <div class="bg-dark-100 rounded-xl border border-brown-800/40 p-5 flex items-center justify-between shadow-lg">
                <div>
                    <span class="text-xs uppercase tracking-wider text-brown-400 font-semibold block mb-1">Total Respon Masuk</span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-serif font-bold text-gold-400"><?php echo e($totalKuisioner); ?></span>
                        <span class="text-xs text-brown-400">kuisioner customer</span>
                    </div>
                    <span class="text-[11px] text-brown-500 mt-1 block">Input otomatis dari pelanggan</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gold-500/10 border border-gold-500/20 flex items-center justify-center text-gold-400 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>

            
            <div class="bg-dark-100 rounded-xl border border-brown-800/40 p-5 flex items-center justify-between shadow-lg">
                <div>
                    <span class="text-xs uppercase tracking-wider text-brown-400 font-semibold block mb-1">Rekomendasi Teratas (#1)</span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-xl font-serif font-bold text-brown-50"><?php echo e($topPaket->paketBundling->nama ?? 'Belum ada'); ?></span>
                    </div>
                    <span class="text-xs text-gold-400 font-medium mt-1 block">
                        Skor: <?php echo e(number_format(($topPaket->skor_akhir ?? 0) * 100, 1)); ?>% (<?php echo e(number_format($topPaket->skor_akhir ?? 0, 4)); ?>)
                    </span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gold-500/15 border border-gold-500/30 flex items-center justify-center text-gold-400 flex-shrink-0">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
            </div>

            
            <div class="bg-dark-100 rounded-xl border border-brown-800/40 p-5 flex items-center justify-between shadow-lg">
                <div>
                    <span class="text-xs uppercase tracking-wider text-brown-400 font-semibold block mb-1">Model SPK Dinamis</span>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gold-500/10 text-gold-400 border border-gold-500/30">
                            Auto-Synchronized
                        </span>
                    </div>
                    <span class="text-[11px] text-brown-400 mt-1.5 block">
                        Bobot (<?php echo e($ahpWeights['jumlah_responden'] ?? 0); ?> Resp.): Harga (<?php echo e(number_format($ahpWeights['harga'] ?? 0, 4)); ?>), Kualitas (<?php echo e(number_format($ahpWeights['kualitas'] ?? 0, 4)); ?>), Fasilitas (<?php echo e(number_format($ahpWeights['fasilitas'] ?? 0, 4)); ?>)
                    </span>
                    <a href="<?php echo e(route('backend.spk.bobot')); ?>" class="inline-flex items-center gap-1 text-[11px] text-gold-400 hover:text-gold-300 mt-1.5 font-medium">
                        <span>Kelola Bobot &amp; Excel AHP</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gold-500/10 border border-gold-500/20 flex items-center justify-center text-gold-400 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
        </div>

        
        <div class="bg-dark-100 rounded-xl border border-brown-800/30 overflow-hidden shadow-lg">
            <div class="p-5 border-b border-brown-800/30 bg-dark-100/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h3 class="font-serif text-lg text-brown-100">Data Agregasi Nilai & Skor SPK Paket</h3>
                    <p class="text-xs text-brown-400 mt-0.5">Nilai kriteria dihitung otomatis dari rata-rata kuisioner pengguna yang telah memesan paket.</p>
                </div>
                <a href="<?php echo e(route('backend.spk.perhitungan')); ?>" class="btn-outline text-xs py-1.5 px-3 flex items-center gap-1.5 self-start sm:self-auto">
                    <span>Lihat Hasil &amp; Ranking</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs whitespace-nowrap">
                    <thead class="bg-dark-200/50 text-brown-300 font-serif border-b border-brown-800/30 text-xs">
                        <tr>
                            <th class="px-3 py-3.5 font-medium text-center" style="padding-left: 20px;">Rank</th>
                            <th class="px-3 py-3.5 font-medium">Nama Paket Bundling</th>
                            <th class="px-3 py-3.5 font-medium text-center">Harga (<?php echo e(number_format($ahpWeights['harga'] ?? 0, 4)); ?>)</th>
                            <th class="px-3 py-3.5 font-medium text-center">Kualitas (<?php echo e(number_format($ahpWeights['kualitas'] ?? 0, 4)); ?>)</th>
                            <th class="px-3 py-3.5 font-medium text-center">Fasilitas (<?php echo e(number_format($ahpWeights['fasilitas'] ?? 0, 4)); ?>)</th>
                            <th class="px-3 py-3.5 font-medium text-center">Skor Akhir</th>
                            <th class="px-3 py-3.5 font-medium text-center">Persentase</th>
                            <th class="px-3 py-3.5 font-medium text-right" style="padding-right: 20px;">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brown-800/30">
                        <?php $__empty_1 = true; $__currentLoopData = $penilaians; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-dark-200/30 transition-colors <?php echo e($p->ranking == 1 ? 'bg-gold-500/5' : ''); ?>">
                                <td class="px-3 py-3.5 text-center" style="padding-left: 20px;">
                                    <?php if($p->ranking == 1): ?>
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gold-500 text-black font-bold text-xs shadow-[0_0_12px_rgba(201,168,76,0.5)]">
                                            1
                                        </span>
                                    <?php elseif($p->ranking): ?>
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-dark-300 text-brown-300 font-medium text-xs border border-brown-800">
                                            <?php echo e($p->ranking); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="text-brown-600">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-3 py-3.5">
                                    <div class="font-medium text-brown-100"><?php echo e($p->paketBundling->nama); ?></div>
                                    <div class="text-[11px] text-gold-500"><?php echo e($p->paketBundling->formatted_harga); ?></div>
                                </td>
                                <td class="px-3 py-3.5 text-center text-brown-200 font-mono text-xs">
                                    <?php echo e(number_format($p->nilai_harga, 4)); ?>

                                </td>
                                <td class="px-3 py-3.5 text-center text-brown-200 font-mono text-xs">
                                    <?php echo e(number_format($p->nilai_kualitas, 4)); ?>

                                </td>
                                <td class="px-3 py-3.5 text-center text-brown-200 font-mono text-xs">
                                    <?php echo e(number_format($p->nilai_fasilitas, 4)); ?>

                                </td>
                                <td class="px-3 py-3.5 text-center font-bold text-gold-400 font-mono text-xs">
                                    <?php echo e(number_format($p->skor_akhir, 4)); ?>

                                </td>
                                <td class="px-3 py-3.5 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold <?php echo e($p->ranking == 1 ? 'bg-gold-500/20 text-gold-300 border border-gold-500/40' : 'bg-dark-200 text-brown-300 border border-brown-800'); ?>">
                                        <?php echo e(number_format($p->skor_akhir * 100, 1)); ?>%
                                    </span>
                                </td>
                                <td class="px-3 py-3.5 text-right" style="padding-right: 20px;">
                                    <?php if($p->ranking == 1): ?>
                                        <span class="text-gold-400 text-xs font-semibold">★ Rekomendasi Utama</span>
                                    <?php else: ?>
                                        <span class="text-brown-500 text-xs">Alternatif #<?php echo e($p->ranking); ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="px-5 py-8 text-center text-brown-400 italic">Belum ada data penilaian paket.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php echo $__env->make('backend.spk._subkriteria_table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <div class="bg-dark-100 rounded-xl border border-brown-800/30 overflow-hidden shadow-lg">
            <div class="p-5 border-b border-brown-800/30 bg-dark-100/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h3 class="font-serif text-lg text-brown-100">Log Respon Kuisioner Pelanggan</h3>
                    <p class="text-xs text-brown-400 mt-0.5">Daftar suara dan ulasan langsung dari pengguna yang telah memesan paket bundling.</p>
                </div>
                <span class="text-xs text-brown-400 font-medium">
                    Total: <?php echo e($totalKuisioner); ?> data
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs whitespace-nowrap">
                    <thead class="bg-dark-200/50 text-brown-300 font-serif border-b border-brown-800/30 text-xs">
                        <tr>
                            <th class="px-3 py-3.5 font-medium" style="padding-left: 20px;">Pelanggan</th>
                            <th class="px-3 py-3.5 font-medium">Paket</th>
                            <th class="px-3 py-3.5 font-medium text-center">Fasilitas (<?php echo e(number_format($ahpWeights['fasilitas'] ?? 0, 4)); ?>)</th>
                            <th class="px-3 py-3.5 font-medium text-center">Harga (<?php echo e(number_format($ahpWeights['harga'] ?? 0, 4)); ?>)</th>
                            <th class="px-3 py-3.5 font-medium text-center">Kualitas (<?php echo e(number_format($ahpWeights['kualitas'] ?? 0, 4)); ?>)</th>
                            <th class="px-3 py-3.5 font-medium">Ulasan / Masukan</th>
                            <th class="px-3 py-3.5 font-medium text-right" style="padding-right: 20px;">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brown-800/30 text-xs">
                        <?php $__empty_1 = true; $__currentLoopData = $kuisioners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-dark-200/30 transition-colors">
                                <td class="px-3 py-3 font-medium text-brown-100" style="padding-left: 20px;">
                                    <div><?php echo e($k->user->nama ?? 'Pelanggan'); ?></div>
                                    <div class="text-[11px] text-brown-500">Booking #<?php echo e($k->id_booking); ?> (<?php echo e($k->booking->jenis_acara ?? 'Acara'); ?>)</div>
                                </td>
                                <td class="px-3 py-3 font-medium text-gold-400">
                                    <?php echo e($k->paketBundling->nama ?? '-'); ?>

                                </td>
                                <td class="px-3 py-3 text-center text-brown-200 font-mono">
                                    <span class="px-2 py-0.5 bg-dark-200/70 border border-brown-800/50 rounded"><?php echo e($k->nilai_fasilitas); ?></span>
                                </td>
                                <td class="px-3 py-3 text-center text-brown-200 font-mono">
                                    <span class="px-2 py-0.5 bg-dark-200/70 border border-brown-800/50 rounded"><?php echo e($k->nilai_harga); ?></span>
                                </td>
                                <td class="px-3 py-3 text-center text-brown-200 font-mono">
                                    <span class="px-2 py-0.5 bg-dark-200/70 border border-brown-800/50 rounded"><?php echo e($k->nilai_kualitas); ?></span>
                                </td>
                                <td class="px-3 py-3 text-brown-300" title="<?php echo e($k->komentar); ?>">
                                    <div style="max-width: 170px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        <?php if($k->komentar): ?>
                                            <span class="italic text-brown-200">"<?php echo e($k->komentar); ?>"</span>
                                        <?php else: ?>
                                            <span class="text-brown-600 italic">Tidak ada catatan</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-3 py-3 text-right text-brown-400" style="padding-right: 20px;">
                                    <?php echo e($k->created_at->format('d M Y, H:i')); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-brown-400 italic">
                                    Belum ada kuisioner yang dikirim oleh pelanggan.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if(method_exists($kuisioners, 'hasPages') && $kuisioners->hasPages()): ?>
                <style>
                    .spk-pagination-btn {
                        display: inline-flex !important;
                        align-items: center !important;
                        justify-content: center !important;
                        gap: 8px !important;
                        height: 38px !important;
                        padding: 0 18px !important;
                        border-radius: 8px !important;
                        font-size: 13px !important;
                        font-weight: 500 !important;
                        letter-spacing: 0.01em !important;
                        color: #e8d5c4 !important;
                        background-color: #241a12 !important;
                        border: 1px solid #4a3423 !important;
                        transition: all 0.2s ease !important;
                        text-decoration: none !important;
                        outline: none !important;
                        user-select: none !important;
                    }
                    .spk-pagination-btn:hover {
                        background-color: #352619 !important;
                        border-color: #d4a843 !important;
                        color: #fce588 !important;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.3) !important;
                    }
                    .spk-pagination-btn-disabled {
                        display: inline-flex !important;
                        align-items: center !important;
                        justify-content: center !important;
                        gap: 8px !important;
                        height: 38px !important;
                        padding: 0 18px !important;
                        border-radius: 8px !important;
                        font-size: 13px !important;
                        font-weight: 500 !important;
                        letter-spacing: 0.01em !important;
                        color: #5a4231 !important;
                        background-color: #1a120b !important;
                        border: 1px solid #2a1c12 !important;
                        cursor: not-allowed !important;
                        user-select: none !important;
                        opacity: 0.55 !important;
                    }
                    .spk-pagination-num {
                        display: inline-flex !important;
                        align-items: center !important;
                        justify-content: center !important;
                        min-width: 38px !important;
                        height: 38px !important;
                        padding: 0 12px !important;
                        border-radius: 8px !important;
                        font-size: 13px !important;
                        font-weight: 600 !important;
                        color: #e8d5c4 !important;
                        background-color: #241a12 !important;
                        border: 1px solid #4a3423 !important;
                        transition: all 0.2s ease !important;
                        text-decoration: none !important;
                        outline: none !important;
                        user-select: none !important;
                    }
                    .spk-pagination-num:hover {
                        background-color: #352619 !important;
                        border-color: #d4a843 !important;
                        color: #fce588 !important;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.3) !important;
                    }
                    .spk-pagination-num-active {
                        display: inline-flex !important;
                        align-items: center !important;
                        justify-content: center !important;
                        min-width: 38px !important;
                        height: 38px !important;
                        padding: 0 12px !important;
                        border-radius: 8px !important;
                        font-size: 13px !important;
                        font-weight: 700 !important;
                        color: #1a1209 !important;
                        background-color: #d4a843 !important;
                        border: 1px solid #d4a843 !important;
                        box-shadow: 0 3px 12px rgba(212, 168, 67, 0.35) !important;
                        user-select: none !important;
                    }
                </style>

                <div class="px-6 py-4 border-t border-brown-800/40 bg-[#1e150e]/80 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-xs text-brown-400">
                        Menampilkan <span class="font-semibold text-gold-400"><?php echo e($kuisioners->firstItem()); ?></span> - <span class="font-semibold text-gold-400"><?php echo e($kuisioners->lastItem()); ?></span> dari total <span class="font-semibold text-gold-400"><?php echo e($kuisioners->total()); ?></span> respon kuisioner
                    </div>
                    
                    
                    <div class="flex items-center gap-2.5">
                        
                        <?php if($kuisioners->onFirstPage()): ?>
                            <span class="spk-pagination-btn-disabled">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 19l-7-7 7-7"/></svg>
                                <span>Sebelumnya</span>
                            </span>
                        <?php else: ?>
                            <a href="<?php echo e($kuisioners->previousPageUrl()); ?>" class="spk-pagination-btn">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 19l-7-7 7-7"/></svg>
                                <span>Sebelumnya</span>
                            </a>
                        <?php endif; ?>

                        
                        <div class="flex items-center gap-2">
                            <?php $__currentLoopData = $kuisioners->getUrlRange(1, $kuisioners->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pageNumber => $pageUrl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($pageNumber == $kuisioners->currentPage()): ?>
                                    <span class="spk-pagination-num-active">
                                        <?php echo e($pageNumber); ?>

                                    </span>
                                <?php else: ?>
                                    <a href="<?php echo e($pageUrl); ?>" class="spk-pagination-num">
                                        <?php echo e($pageNumber); ?>

                                    </a>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        
                        <?php if($kuisioners->hasMorePages()): ?>
                            <a href="<?php echo e($kuisioners->nextPageUrl()); ?>" class="spk-pagination-btn">
                                <span>Selanjutnya</span>
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        <?php else: ?>
                            <span class="spk-pagination-btn-disabled">
                                <span>Selanjutnya</span>
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
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
<?php /**PATH C:\MyProject\Laravel\event_organizer\resources\views/backend/spk/penilaian.blade.php ENDPATH**/ ?>