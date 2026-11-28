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
     <?php $__env->slot('title', null, []); ?> Bobot &amp; Responden AHP - Admin MELODI <?php $__env->endSlot(); ?>
     <?php $__env->slot('header', null, []); ?> Bobot &amp; Responden AHP <?php $__env->endSlot(); ?>

     <?php $__env->slot('headerActions', null, []); ?> 
        <form action="<?php echo e(route('backend.spk.perhitungan.calculate')); ?>" method="POST" class="inline">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn-gold flex items-center gap-2 text-xs py-2 px-4 shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>Hitung Ulang Bobot</span>
            </button>
        </form>
     <?php $__env->endSlot(); ?>

    <div class="space-y-6" x-data="ahpPreviewManager('<?php echo e(route('backend.spk.preview_ahp')); ?>')">
        <?php if(session('success')): ?>
            <div class="p-4 rounded-xl bg-green-950/60 border border-green-700/50 text-green-300 flex items-center gap-3 shadow-lg">
                <svg class="w-5 h-5 flex-shrink-0 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="text-sm font-medium"><?php echo e(session('success')); ?></span>
            </div>
        <?php endif; ?>

        <?php if(session('error') || session('import_errors')): ?>
            <?php
                $importErrList = session('import_errors', session('error') ? [session('error')] : []);
            ?>
            <div class="rounded-xl shadow-lg text-red-200" style="background-color: rgba(127, 29, 29, 0.28); border: 1px solid rgba(248, 113, 113, 0.45); padding: 16px 20px;">
                <div class="flex items-center gap-2.5 text-red-300 font-semibold text-sm">
                    <svg class="w-5 h-5 flex-shrink-0 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Upload Excel Dibatalkan &mdash; Data Aktif Sebelumnya Tetap Aman</span>
                </div>
                <ul class="text-xs text-red-200/95 leading-relaxed" style="margin: 10px 0 0 0; padding: 0; list-style: none; display: flex; flex-direction: column; gap: 6px;">
                    <?php $__currentLoopData = $importErrList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $errMsg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li style="display: flex; align-items: flex-start; gap: 8px;">
                            <span style="color: #f87171; flex-shrink: 0;">&bull;</span>
                            <span><?php echo e($errMsg); ?></span>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="rounded-xl shadow-lg text-red-200 text-xs" style="background-color: rgba(127, 29, 29, 0.28); border: 1px solid rgba(248, 113, 113, 0.45); padding: 16px 20px;">
                <div class="font-semibold text-sm text-red-300">Validasi File Gagal:</div>
                <ul class="leading-relaxed" style="margin: 8px 0 0 0; padding: 0; list-style: none; display: flex; flex-direction: column; gap: 6px;">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $errMsg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li style="display: flex; align-items: flex-start; gap: 8px;">
                            <span style="color: #f87171; flex-shrink: 0;">&bull;</span>
                            <span><?php echo e($errMsg); ?></span>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        
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

        
        <div class="bg-dark-100 rounded-xl border border-gold-500/25 overflow-hidden shadow-lg">
            <div class="p-5 border-b border-brown-800/30 bg-dark-100/60 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <div class="flex flex-wrap items-center gap-2.5">
                        <h3 class="font-serif text-lg text-gold-400">Import &amp; Pembobotan AHP Otomatis (4 Tab Excel)</h3>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-gold-500/15 text-gold-300 border border-gold-500/30">
                            Data Aktif · <?php echo e($ahpWeights['jumlah_responden'] ?? 0); ?> Responden
                        </span>
                    </div>
                    <p class="text-xs text-brown-400 mt-1">
                        Unduh template resmi 4 sheet (<code>kriteria</code>, <code>sub_harga</code>, <code>sub_kualitas</code>, <code>sub_fasilitas</code>), isi angka perbandingan responden (kolom nama opsional/boleh kosong), lalu klik Preview untuk memeriksa seluruh data sebelum disimpan.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2.5" style="flex-shrink: 0;">
                    <a href="<?php echo e(route('backend.spk.template')); ?>" class="btn-outline flex items-center gap-1.5 text-xs py-2 px-3.5 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Download Template (.xlsx)</span>
                    </a>
                    <a href="<?php echo e(route('backend.spk.export_ahp')); ?>" class="btn-outline flex items-center gap-1.5 text-xs py-2 px-3.5 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Download Data Aktif (.xlsx)</span>
                    </a>
                </div>
            </div>

            <div class="p-5">
                <form action="<?php echo e(route('backend.spk.import_ahp')); ?>" method="POST" enctype="multipart/form-data" class="bg-dark-200/50 border border-brown-800/40 rounded-xl p-4 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                    <?php echo csrf_field(); ?>
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

        
        <div x-data="{ viewMode: 'tabel' }" class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-dark-100 rounded-xl border border-brown-800/30 px-5 py-3.5 shadow-md">
                <div class="text-xs text-brown-300">
                    <span class="font-serif text-sm text-gold-400 font-semibold mr-2">Ringkasan Hasil Pembobotan:</span>
                    <span class="font-mono text-brown-200">
                        Harga <?php echo e(number_format($ahpWeights['harga'] ?? 0, 4)); ?> &middot;
                        Kualitas <?php echo e(number_format($ahpWeights['kualitas'] ?? 0, 4)); ?> &middot;
                        Fasilitas <?php echo e(number_format($ahpWeights['fasilitas'] ?? 0, 4)); ?>

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

            
            <div x-show="viewMode === 'tabel'">
                <?php echo $__env->make('backend.spk._subkriteria_table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            
            <div x-show="viewMode === 'kartu'" x-cloak class="bg-dark-100 rounded-xl border border-brown-800/30 p-5 shadow-lg">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $catKey => $meta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $st = $ahpStatuses->get($catKey);
                            $crVal = $st ? $st->cr : 0;
                            $ok = $st ? $st->is_consistent : true;
                            $isSelected = ($catKey === $activeTab);
                            $vals = $st
                                ? ($catKey === 'kriteria'
                                    ? [$st->bobot_1, $st->bobot_2, $st->bobot_3]
                                    : [$st->ideal_1, $st->ideal_2, $st->ideal_3])
                                : null;
                        ?>
                        <a href="<?php echo e(route('backend.spk.bobot', ['tab' => $catKey])); ?>#tabel-ahp"
                           class="block rounded-xl p-4 transition-all border <?php echo e($isSelected ? 'bg-dark-200 border-gold-500/70 ring-1 ring-gold-500/30 shadow-md' : 'bg-dark-200/40 hover:bg-dark-200/80 border-brown-800/40 hover:border-gold-500/40'); ?>">
                            <div class="flex items-start justify-between gap-2 pb-2.5 mb-3 border-b border-brown-800/40">
                                <div>
                                    <span class="text-xs font-serif font-semibold text-gold-400 block"><?php echo e($meta['label']); ?></span>
                                    <span class="text-[11px] text-brown-400">
                                        <?php echo e($st->jumlah_responden ?? 0); ?> Responden &middot; <?php echo e($catKey === 'kriteria' ? 'Bobot Prioritas' : 'Skala Ideal Mode'); ?>

                                    </span>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold whitespace-nowrap <?php echo e($ok ? 'bg-green-950/70 text-green-300 border border-green-700/50' : 'bg-red-950/70 text-red-300 border border-red-700/50'); ?>">
                                    CR <?php echo e(number_format($crVal, 4)); ?>

                                </span>
                            </div>

                            <?php if($vals): ?>
                                <div class="space-y-2">
                                    <?php $__currentLoopData = $meta['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $itemName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="text-brown-200 font-medium"><?php echo e($itemName); ?></span>
                                            <span class="font-mono font-semibold <?php echo e($idx === 0 ? 'text-gold-300' : 'text-brown-100'); ?>">
                                                <?php echo e(number_format($vals[$idx], 4)); ?>

                                                <?php if($catKey === 'kriteria'): ?>
                                                    <span class="text-[10px] text-brown-400 font-sans">(<?php echo e(number_format($vals[$idx] * 100, 1)); ?>%)</span>
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <div class="text-xs text-brown-500 italic py-3 text-center">Belum ada data</div>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        
        <?php
            $activeBreakdown = $matrixBreakdowns[$activeTab];
            $activeMeta = $activeBreakdown['meta'];
            $calc = $activeBreakdown['calc'];
            $respCount = $activeBreakdown['jumlah_responden'];
            $fmtClean = fn ($v) => rtrim(rtrim(number_format((float) $v, 4, '.', ''), '0'), '.');
        ?>

        <div id="tabel-ahp" class="bg-dark-100 rounded-xl border border-brown-800/30 overflow-hidden shadow-lg">
            
            <div class="px-6 pt-4 border-b border-brown-800/30 bg-dark-200/30 flex flex-wrap gap-2">
                <?php $__currentLoopData = $matrixBreakdowns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tabKey => $tb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $isTabActive = ($tabKey === $activeTab);
                    ?>
                    <a href="<?php echo e(route('backend.spk.bobot', ['tab' => $tabKey])); ?>#tabel-ahp"
                       class="px-4 py-2.5 rounded-t-lg text-xs font-semibold transition-all border-t border-l border-r <?php echo e($isTabActive ? 'bg-dark-100 text-gold-400 border-gold-500/40 -mb-px pb-3' : 'bg-dark-200/40 text-brown-400 border-transparent hover:text-brown-200'); ?>">
                        <span><?php echo e($tb['meta']['label']); ?></span>
                        <span class="ml-1.5 px-1.5 py-0.5 rounded text-[10px] <?php echo e($isTabActive ? 'bg-gold-500/20 text-gold-300' : 'bg-dark-300 text-brown-400'); ?>">
                            <?php echo e($tb['jumlah_responden']); ?>

                        </span>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="p-6 space-y-6">
                
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                    
                    <div class="bg-dark-200/40 rounded-xl border border-brown-800/40 overflow-hidden">
                        <div class="px-4 py-3 border-b border-brown-800/40 flex items-center justify-between">
                            <span class="font-serif text-sm text-brown-100">Matriks Perbandingan Berpasangan (Geometric Mean)</span>
                            <span class="text-[11px] text-brown-400"><?php echo e($respCount); ?> Responden</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-center">
                                <thead class="bg-dark-200/70 text-brown-300 border-b border-brown-800/40">
                                    <tr>
                                        <th class="px-3 py-2.5 text-left"><?php echo e($activeMeta['badge']); ?></th>
                                        <?php $__currentLoopData = $activeMeta['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <th class="px-3 py-2.5"><?php echo e($it); ?></th>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-brown-800/30 font-mono">
                                    <?php $__currentLoopData = $activeMeta['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rIdx => $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="px-3 py-2.5 text-left font-sans font-medium text-brown-200"><?php echo e($it); ?></td>
                                            <?php for($cIdx = 0; $cIdx < 3; $cIdx++): ?>
                                                <td class="px-3 py-2.5 <?php echo e($rIdx === $cIdx ? 'text-gold-400 font-semibold' : 'text-brown-300'); ?>">
                                                    <?php echo e(number_format($calc['matrix'][$rIdx][$cIdx], 4)); ?>

                                                </td>
                                            <?php endfor; ?>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                                <tfoot class="bg-dark-200/80 border-t border-gold-500/25 font-mono font-semibold text-gold-300">
                                    <tr>
                                        <td class="px-3 py-2.5 text-left font-sans">Jumlah Kolom</td>
                                        <?php $__currentLoopData = $calc['col_sums']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <td class="px-3 py-2.5"><?php echo e(number_format($cs, 4)); ?></td>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    
                    <div class="bg-dark-200/40 rounded-xl border border-brown-800/40 overflow-hidden flex flex-col justify-between">
                        <div class="px-4 py-3 border-b border-brown-800/40 flex items-center justify-between">
                            <span class="font-serif text-sm text-brown-100">Normalisasi, Bobot Prioritas &amp; Ideal Mode</span>
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold <?php echo e($calc['is_consistent'] ? 'bg-green-950/70 text-green-300 border border-green-700/50' : 'bg-red-950/70 text-red-300 border border-red-700/50'); ?>">
                                <?php echo e($calc['is_consistent'] ? 'KONSISTEN' : 'TIDAK KONSISTEN'); ?> (CR = <?php echo e(number_format($calc['cr'], 4)); ?>)
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
                                    <?php $__currentLoopData = $activeMeta['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rIdx => $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="px-3 py-2.5 text-left font-sans font-medium text-brown-200"><?php echo e($it); ?></td>
                                            <?php for($cIdx = 0; $cIdx < 3; $cIdx++): ?>
                                                <td class="px-3 py-2.5 text-brown-400"><?php echo e(number_format($calc['normalized'][$rIdx][$cIdx], 4)); ?></td>
                                            <?php endfor; ?>
                                            <td class="px-3 py-2.5 font-bold text-gold-400"><?php echo e(number_format($calc['weights'][$rIdx], 4)); ?></td>
                                            <td class="px-3 py-2.5 font-bold text-gold-300"><?php echo e(number_format($calc['ideal'][$rIdx], 4)); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="px-4 py-2.5 bg-dark-200/70 border-t border-brown-800/40 flex flex-wrap items-center justify-between gap-2 text-[11px] font-mono text-brown-300">
                            <span>&lambda;<sub>max</sub> = <strong class="text-brown-100"><?php echo e(number_format($calc['lambda_max'], 4)); ?></strong></span>
                            <span>CI = <strong class="text-brown-100"><?php echo e(number_format($calc['ci'], 4)); ?></strong></span>
                            <span>RI (n=3) = <strong class="text-brown-100">0.5800</strong></span>
                            <span>CR = <strong class="text-gold-400"><?php echo e(number_format($calc['cr'], 4)); ?></strong> (&le; 0.10)</span>
                        </div>
                    </div>
                </div>

                
                <div class="bg-dark-200/30 rounded-xl border border-brown-800/40 overflow-hidden">
                    <div class="px-4 py-3 border-b border-brown-800/40 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <span class="font-serif text-sm text-brown-100">Data Responden Tersimpan &mdash; <?php echo e($activeMeta['label']); ?></span>
                        <span class="text-xs text-brown-400">Menampilkan <?php echo e($respondens->firstItem() ?? 0); ?>&ndash;<?php echo e($respondens->lastItem() ?? 0); ?> dari total <?php echo e($respondens->total()); ?> responden</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left whitespace-nowrap">
                            <thead class="bg-dark-200/60 text-brown-300 font-serif border-b border-brown-800/40">
                                <tr>
                                    <th class="px-4 py-3 text-center w-16">No</th>
                                    <th class="px-4 py-3">Nama Responden</th>
                                    <th class="px-4 py-3 text-center"><?php echo e($activeMeta['pairs'][0]); ?></th>
                                    <th class="px-4 py-3 text-center"><?php echo e($activeMeta['pairs'][1]); ?></th>
                                    <th class="px-4 py-3 text-center"><?php echo e($activeMeta['pairs'][2]); ?></th>
                                    <th class="px-4 py-3 text-right">Tanggal Input</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-brown-800/30">
                                <?php $__empty_1 = true; $__currentLoopData = $respondens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $resp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $noUrut = ($respondens->firstItem() ?? 1) + $idx;
                                    ?>
                                    <tr class="hover:bg-dark-200/40 transition-colors">
                                        <td class="px-4 py-2.5 text-center text-brown-400 font-mono"><?php echo e($noUrut); ?></td>
                                        <td class="px-4 py-2.5 font-medium <?php echo e($resp->nama_responden ? 'text-brown-100' : 'text-brown-400 italic'); ?>">
                                            <?php echo e($resp->nama_responden ?: 'Responden #' . $noUrut); ?>

                                        </td>
                                        <td class="px-4 py-2.5 text-center font-mono text-brown-200"><?php echo e($fmtClean($resp->nilai_12)); ?></td>
                                        <td class="px-4 py-2.5 text-center font-mono text-brown-200"><?php echo e($fmtClean($resp->nilai_13)); ?></td>
                                        <td class="px-4 py-2.5 text-center font-mono text-brown-200"><?php echo e($fmtClean($resp->nilai_23)); ?></td>
                                        <td class="px-4 py-2.5 text-right text-brown-400"><?php echo e($resp->created_at?->format('d M Y, H:i') ?? '-'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-brown-400 italic">Belum ada data responden untuk tab ini.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            <?php if($respCount > 0): ?>
                                <tfoot class="bg-gold-500/10 border-t-2 border-gold-500/30 font-mono text-gold-300 font-bold">
                                    <tr>
                                        <td class="px-4 py-3 text-center">-</td>
                                        <td class="px-4 py-3 font-sans">TOTAL (Geometric Mean <?php echo e($respCount); ?> Responden)</td>
                                        <td class="px-4 py-3 text-center"><?php echo e(number_format($calc['gm'][0], 4)); ?></td>
                                        <td class="px-4 py-3 text-center"><?php echo e(number_format($calc['gm'][1], 4)); ?></td>
                                        <td class="px-4 py-3 text-center"><?php echo e(number_format($calc['gm'][2], 4)); ?></td>
                                        <td class="px-4 py-3 text-right font-sans text-[11px]">CR = <?php echo e(number_format($calc['cr'], 4)); ?></td>
                                    </tr>
                                </tfoot>
                            <?php endif; ?>
                        </table>
                    </div>

                    <?php if($respondens->hasPages()): ?>
                        <div class="px-5 py-3.5 border-t border-brown-800/40 bg-dark-100/70 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                            <span class="text-brown-400">Halaman <?php echo e($respondens->currentPage()); ?> dari <?php echo e($respondens->lastPage()); ?></span>
                            <div class="flex flex-wrap items-center gap-1.5">
                                <?php if($respondens->onFirstPage()): ?>
                                    <span class="px-3 py-1.5 rounded-lg bg-dark-200/40 text-brown-600 border border-brown-800/30 cursor-not-allowed">Sebelumnya</span>
                                <?php else: ?>
                                    <a href="<?php echo e($respondens->previousPageUrl()); ?>#tabel-ahp" class="px-3 py-1.5 rounded-lg bg-dark-200 text-brown-200 border border-brown-800 hover:border-gold-500 hover:text-gold-300 transition-colors">Sebelumnya</a>
                                <?php endif; ?>

                                <?php $__currentLoopData = $respondens->getUrlRange(1, $respondens->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pg => $pgUrl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($pg == $respondens->currentPage()): ?>
                                        <span class="px-3 py-1.5 rounded-lg bg-gold-500 text-black font-bold"><?php echo e($pg); ?></span>
                                    <?php else: ?>
                                        <a href="<?php echo e($pgUrl); ?>#tabel-ahp" class="px-3 py-1.5 rounded-lg bg-dark-200 text-brown-300 border border-brown-800 hover:border-gold-500 hover:text-gold-300 transition-colors"><?php echo e($pg); ?></a>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php if($respondens->hasMorePages()): ?>
                                    <a href="<?php echo e($respondens->nextPageUrl()); ?>#tabel-ahp" class="px-3 py-1.5 rounded-lg bg-dark-200 text-brown-200 border border-brown-800 hover:border-gold-500 hover:text-gold-300 transition-colors">Selanjutnya</a>
                                <?php else: ?>
                                    <span class="px-3 py-1.5 rounded-lg bg-dark-200/40 text-brown-600 border border-brown-800/30 cursor-not-allowed">Selanjutnya</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <style>[x-cloak] { display: none !important; }</style>
        <div x-show="previewOpen" x-cloak
             @keydown.escape.window="closePreview()"
             @click="closePreview()"
             style="position: fixed; inset: 0; z-index: 9999; background-color: rgba(0, 0, 0, 0.82); backdrop-filter: blur(4px); padding: 20px; overflow-y: auto;">
            <div style="min-height: 100%; display: flex; align-items: center; justify-content: center;">
                <div @click.stop
                     class="bg-dark-100 rounded-2xl shadow-2xl"
                     style="width: 100%; max-width: 1020px; max-height: 88vh; display: flex; flex-direction: column; overflow: hidden; border: 1px solid rgba(212, 168, 67, 0.45);">
                    
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

                    
                    <div style="flex: 1 1 auto; min-height: 0; display: flex; flex-direction: column; gap: 16px; padding: 20px;">
                        
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

                    
                    <div class="px-6 py-4 border-t border-brown-800/40 bg-dark-200/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3" style="flex-shrink: 0;">
                        <span class="text-[11px] text-brown-300">
                            Data belum disimpan ke database. Klik simpan jika angka responden di atas sudah sesuai.
                        </span>
                        <div class="flex items-center justify-end gap-2.5">
                            <button type="button" @click="closePreview()" class="btn-outline text-xs py-2 px-4">
                                Batal
                            </button>
                            <form action="<?php echo e(route('backend.spk.import_ahp')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
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
<?php /**PATH C:\MyProject\Laravel\event_organizer\resources\views/backend/spk/bobot.blade.php ENDPATH**/ ?>