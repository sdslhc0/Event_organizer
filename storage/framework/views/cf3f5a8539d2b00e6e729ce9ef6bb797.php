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
     <?php $__env->slot('header', null, []); ?> Tambah User <?php $__env->endSlot(); ?>

    <div class="max-w-2xl">
        <div class="bg-dark-100 rounded-xl border border-brown-800/30 p-6">
            <form action="<?php echo e(route('backend.user.store')); ?>" method="POST" class="space-y-6">
                <?php echo csrf_field(); ?>
                
                <div>
                    <label class="block text-sm font-medium text-brown-300 mb-2">Nama</label>
                    <input type="text" name="nama" value="<?php echo e(old('nama')); ?>" required
                           class="w-full bg-dark-200 border-brown-800/50 rounded-lg px-4 py-2.5 text-brown-100 focus:ring-gold-500 focus:border-gold-500">
                    <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-brown-300 mb-2">Email</label>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" required
                           class="w-full bg-dark-200 border-brown-800/50 rounded-lg px-4 py-2.5 text-brown-100 focus:ring-gold-500 focus:border-gold-500">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-brown-300 mb-2">Telepon</label>
                    <input type="text" name="telepon" value="<?php echo e(old('telepon')); ?>" required
                           class="w-full bg-dark-200 border-brown-800/50 rounded-lg px-4 py-2.5 text-brown-100 focus:ring-gold-500 focus:border-gold-500">
                    <?php $__errorArgs = ['telepon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-brown-300 mb-2">Password</label>
                    <input type="password" name="password" required autocomplete="new-password"
                           class="w-full bg-dark-200 border-brown-800/50 rounded-lg px-4 py-2.5 text-brown-100 focus:ring-gold-500 focus:border-gold-500">
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-brown-300 mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required autocomplete="new-password"
                           class="w-full bg-dark-200 border-brown-800/50 rounded-lg px-4 py-2.5 text-brown-100 focus:ring-gold-500 focus:border-gold-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-brown-300 mb-2">Role</label>
                    <select name="role" required class="w-full bg-dark-200 border-brown-800/50 rounded-lg px-4 py-2.5 text-brown-100 focus:ring-gold-500 focus:border-gold-500">
                        <option value="0" <?php echo e(old('role') == '0' ? 'selected' : ''); ?>>User</option>
                        <option value="1" <?php echo e(old('role') == '1' ? 'selected' : ''); ?>>Admin</option>
                    </select>
                    <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="flex gap-4 pt-4 border-t border-brown-800/30">
                    <button type="submit" class="btn-gold">
                        Simpan
                    </button>
                    <a href="<?php echo e(route('backend.user.index')); ?>" class="px-6 py-2.5 rounded-lg border border-brown-700 text-brown-300 hover:bg-brown-800/30 transition-colors">
                        Batal
                    </a>
                </div>
            </form>
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
<?php /**PATH C:\0704\2704\event_organizer\resources\views/backend/user/create.blade.php ENDPATH**/ ?>