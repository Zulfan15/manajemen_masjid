

<?php $__env->startSection('content'); ?>
<div class="p-6">

    <h1 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
        <i class="fas fa-bullhorn text-green-600 mr-2"></i>
        Manajemen Informasi Masjid
    </h1>

    <!-- Tabs -->
    <div class="flex space-x-3 mb-6">
        <a href="?tab=pengumuman"
           class="px-4 py-2 rounded-lg transition <?php echo e($tab=='pengumuman' ? 'bg-green-600 text-white shadow' : 'bg-gray-200 hover:bg-gray-300'); ?>">
            Pengumuman
        </a>

        <a href="?tab=berita"
           class="px-4 py-2 rounded-lg transition <?php echo e($tab=='berita' ? 'bg-green-600 text-white shadow' : 'bg-gray-200 hover:bg-gray-300'); ?>">
            Berita
        </a>

        <a href="?tab=artikel"
           class="px-4 py-2 rounded-lg transition <?php echo e($tab=='artikel' ? 'bg-green-600 text-white shadow' : 'bg-gray-200 hover:bg-gray-300'); ?>">
            Artikel
        </a>
    </div>

    <!-- Dropdown Tambah -->
    <div class="mb-4 relative inline-block">
        <button onclick="toggleMenu()"
            class="bg-green-600 text-white px-5 py-2.5 rounded-lg shadow hover:bg-green-700 flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Informasi
            <i class="fas fa-chevron-down ml-2 text-sm"></i>
        </button>

        <div id="menuTambah"
             class="hidden absolute mt-2 w-48 bg-white shadow-lg rounded-lg border z-10">

            <a href="<?php echo e(route('informasi.pengumuman.create')); ?>"
               class="block px-4 py-2 hover:bg-gray-100">
                📢 Tambah Pengumuman
            </a>

            <a href="<?php echo e(route('informasi.berita.create')); ?>"
               class="block px-4 py-2 hover:bg-gray-100">
                📰 Tambah Berita
            </a>

            <a href="<?php echo e(route('informasi.artikel.create')); ?>"
               class="block px-4 py-2 hover:bg-gray-100">
                📝 Tambah Artikel
            </a>
        </div>
    </div>

    <!-- TABLE CONTENT -->
    <?php if($tab == 'pengumuman'): ?>
        <?php echo $__env->make('modules.informasi.partials.table_pengumuman', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php elseif($tab == 'berita'): ?>
        <?php echo $__env->make('modules.informasi.partials.table_berita', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php else: ?>
        <?php echo $__env->make('modules.informasi.partials.table_artikel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

</div>

<script>
function toggleMenu() {
    document.getElementById('menuTambah').classList.toggle('hidden');
}

document.addEventListener('click', function(e) {
    let menu = document.getElementById('menuTambah');
    if (!menu.contains(e.target) && !e.target.closest('button')) {
        menu.classList.add('hidden');
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ACER\Downloads\Manpro Masjid\resources\views/modules/informasi/index.blade.php ENDPATH**/ ?>