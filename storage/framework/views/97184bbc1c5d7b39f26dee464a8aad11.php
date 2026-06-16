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
     <?php $__env->slot('title', null, []); ?> Penilaian Paket Jasa - Admin MELODI <?php $__env->endSlot(); ?>
     <?php $__env->slot('header', null, []); ?> Penilaian Paket Jasa <?php $__env->endSlot(); ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Kiri -->
        <div class="lg:col-span-1 bg-dark-100 rounded-xl border border-brown-800/30 overflow-hidden flex flex-col h-fit">
            <div class="p-6 border-b border-brown-800/30">
                <h3 class="font-serif text-lg text-gold-500">Form Penilaian</h3>
            </div>
            <div class="p-6">
                <form action="<?php echo e(route('backend.spk.penilaian.store')); ?>" method="POST" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    
                    <div>
                        <label class="label-melodi">Paket Jasa</label>
                        <select name="paket_id" id="paket_id" required class="select-melodi">
                            <option value="">-- Pilih Paket Jasa --</option>
                            <?php $__currentLoopData = $pakets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($paket->id); ?>" <?php echo e((isset($editPenilaian) && $editPenilaian->paket_id == $paket->id) ? 'selected' : ''); ?>>
                                    <?php echo e($paket->id); ?> - <?php echo e($paket->nama); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="label-melodi">Fasilitas</label>
                        <select name="nilai_fasilitas" required class="select-melodi">
                            <option value="">-- Pilih Fasilitas --</option>
                            <option value="0.61" <?php echo e((isset($editPenilaian) && $editPenilaian->nilai_fasilitas == 0.61) ? 'selected' : ''); ?>>0.61 (Sangat Lengkap)</option>
                            <option value="0.30" <?php echo e((isset($editPenilaian) && $editPenilaian->nilai_fasilitas == 0.30) ? 'selected' : ''); ?>>0.30 (Cukup Lengkap)</option>
                            <option value="0.09" <?php echo e((isset($editPenilaian) && $editPenilaian->nilai_fasilitas == 0.09) ? 'selected' : ''); ?>>0.09 (Kurang Lengkap)</option>
                        </select>
                    </div>

                    <div>
                        <label class="label-melodi">Harga Sewa</label>
                        <select name="nilai_harga" required class="select-melodi">
                            <option value="">-- Pilih Harga Sewa --</option>
                            <option value="0.56" <?php echo e((isset($editPenilaian) && $editPenilaian->nilai_harga == 0.56) ? 'selected' : ''); ?>>0.56 (Terjangkau)</option>
                            <option value="0.36" <?php echo e((isset($editPenilaian) && $editPenilaian->nilai_harga == 0.36) ? 'selected' : ''); ?>>0.36 (Cukup Mahal)</option>
                            <option value="0.08" <?php echo e((isset($editPenilaian) && $editPenilaian->nilai_harga == 0.08) ? 'selected' : ''); ?>>0.08 (Sangat Mahal)</option>
                        </select>
                    </div>

                    <div>
                        <label class="label-melodi">Kualitas</label>
                        <select name="nilai_kualitas" required class="select-melodi">
                            <option value="">-- Pilih Kualitas --</option>
                            <option value="0.71" <?php echo e((isset($editPenilaian) && $editPenilaian->nilai_kualitas == 0.71) ? 'selected' : ''); ?>>0.71 (Sangat Bagus)</option>
                            <option value="0.23" <?php echo e((isset($editPenilaian) && $editPenilaian->nilai_kualitas == 0.23) ? 'selected' : ''); ?>>0.23 (Cukup Bagus)</option>
                            <option value="0.06" <?php echo e((isset($editPenilaian) && $editPenilaian->nilai_kualitas == 0.06) ? 'selected' : ''); ?>>0.06 (Kurang Bagus)</option>
                        </select>
                    </div>

                    <div class="pt-4 flex items-center gap-3">
                        <button type="submit" class="btn-gold flex-1 h-11 flex items-center justify-center text-center">SIMPAN</button>
                        <a href="<?php echo e(route('backend.spk.penilaian')); ?>" class="btn-outline flex-1 h-11 flex items-center justify-center text-center uppercase">Batal</a>
                    </div>
                </form>
                <?php if(isset($editPenilaian)): ?>
                    <form action="<?php echo e(route('backend.spk.penilaian.destroy', $editPenilaian->paket_id)); ?>" method="POST" class="mt-2" onsubmit="return confirm('Hapus data penilaian ini?');">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn-danger w-full text-center py-2">Hapus</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tabel Kanan -->
        <div class="lg:col-span-2 bg-dark-100 rounded-xl border border-brown-800/30 overflow-hidden flex flex-col">
            <div class="p-4 border-b border-brown-800/30 bg-dark-100/50">
                <h3 class="font-serif text-lg text-brown-100">Data Penilaian Paket</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-dark-200/50 text-brown-300 font-serif border-b border-brown-800/30">
                        <tr>
                            <th class="px-4 py-3 font-medium">ID Paket</th>
                            <th class="px-4 py-3 font-medium">Nama Paket</th>
                            <th class="px-4 py-3 font-medium">Fasilitas</th>
                            <th class="px-4 py-3 font-medium">Harga Sewa</th>
                            <th class="px-4 py-3 font-medium">Kualitas</th>
                            <th class="px-4 py-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brown-800/30">
                        <?php $__empty_1 = true; $__currentLoopData = $penilaians; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-dark-200/30 transition-colors <?php echo e(isset($editPenilaian) && $editPenilaian->paket_id == $p->paket_id ? 'bg-gold-500/10' : ''); ?>">
                                <td class="px-4 py-3 text-brown-300"><?php echo e($p->paket_id); ?></td>
                                <td class="px-4 py-3 text-brown-100"><?php echo e($p->paketBundling->nama); ?></td>
                                <td class="px-4 py-3 text-brown-300"><?php echo e($p->nilai_fasilitas); ?></td>
                                <td class="px-4 py-3 text-brown-300"><?php echo e($p->nilai_harga); ?></td>
                                <td class="px-4 py-3 text-brown-300"><?php echo e($p->nilai_kualitas); ?></td>
                                <td class="px-4 py-3 text-right">
                                    <a href="<?php echo e(route('backend.spk.penilaian', ['edit' => $p->paket_id])); ?>" class="text-gold-500 hover:text-gold-400 text-sm font-medium">Ubah</a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-brown-400 italic">Belum ada data penilaian.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
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
<?php /**PATH C:\0704\2704\event_organizer\resources\views/backend/spk/penilaian.blade.php ENDPATH**/ ?>