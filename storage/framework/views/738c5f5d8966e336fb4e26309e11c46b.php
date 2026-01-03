

<?php $__env->startSection('content'); ?>
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">
        Manajemen Keuangan Masjid
    </h2>

    <div class="flex border-b border-gray-200 mb-6">
        <button class="py-2 px-4 text-gray-500 hover:text-green-600 focus:outline-none">
            Pemasukan (Coming Soon)
        </button>
        <button class="py-2 px-4 border-b-2 border-green-600 text-green-600 font-semibold focus:outline-none">
            Pengeluaran
        </button>
        <a href="<?php echo e(route('kategori-pengeluaran.index')); ?>" class="py-2 px-4 text-gray-500 hover:text-green-600 focus:outline-none">
            Kategori
        </a>
    </div>

    <div class="grid gap-6 mb-8 md:grid-cols-2">
        
        <div class="grid gap-6 md:grid-cols-1">
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="p-3 mr-4 text-red-500 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Pengeluaran Bulan Ini (<?php echo e(date('F Y')); ?>)</p>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">Rp <?php echo e(number_format($totalBulanIni, 0, ',', '.')); ?></p>
                </div>
            </div>
            
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full dark:text-orange-100 dark:bg-orange-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Total Semua Pengeluaran</p>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">Rp <?php echo e(number_format($totalSemua, 0, ',', '.')); ?></p>
                </div>
            </div>
        </div>

        <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
            <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
                Komposisi Pengeluaran
            </h4>
            <div class="relative h-48"> <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>

    <?php if(session('success')): ?>
    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
        <span class="font-medium">Berhasil!</span> <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
        <ul class="list-disc pl-5">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <div x-data="{ 
        openModal: false, 
        isEdit: false, 
        actionUrl: '<?php echo e(route('pengeluaran.store')); ?>',
        data: { id: '', judul: '', kategori_id: '', jumlah: '', tanggal: '', deskripsi: '' } 
    }">
        
        <div class="flex flex-col sm:flex-row justify-between mb-4 gap-4">
            <h4 class="text-lg font-semibold text-gray-600 self-center">Daftar Pengeluaran</h4>
            
            <div class="flex gap-2">
                <form action="<?php echo e(route('pengeluaran.cetak')); ?>" method="GET" target="_blank" class="flex gap-2">
                    <select name="bulan" class="text-sm border-gray-300 rounded-md focus:border-green-400 focus:shadow-outline-green">
                        <?php for($i=1; $i<=12; $i++): ?>
                            <option value="<?php echo e($i); ?>" <?php echo e(date('n') == $i ? 'selected' : ''); ?>><?php echo e(date('F', mktime(0, 0, 0, $i, 10))); ?></option>
                        <?php endfor; ?>
                    </select>
                    <select name="tahun" class="text-sm border-gray-300 rounded-md focus:border-green-400 focus:shadow-outline-green">
                        <option value="<?php echo e(date('Y')); ?>"><?php echo e(date('Y')); ?></option>
                        <option value="<?php echo e(date('Y')-1); ?>"><?php echo e(date('Y')-1); ?></option>
                    </select>
                    <button type="submit" class="px-3 py-2 text-sm font-medium leading-5 text-white bg-gray-600 border border-transparent rounded-lg hover:bg-gray-700 focus:outline-none">
                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        PDF
                    </button>
                </form>

                <button 
                    @click="
                        openModal = true; 
                        isEdit = false; 
                        actionUrl = '<?php echo e(route('pengeluaran.store')); ?>';
                        data = { id: '', judul: '', kategori_id: '', jumlah: '', tanggal: '<?php echo e(date('Y-m-d')); ?>', deskripsi: '' };
                    "
                    class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-purple">
                    + Tambah
                </button>
            </div>
        </div>

        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Judul</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Jumlah</th>
                            <th class="px-4 py-3">Bukti</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                        <?php $__empty_1 = true; $__currentLoopData = $pengeluaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="text-gray-700">
                            <td class="px-4 py-3 text-sm">
                                <?php echo e(\Carbon\Carbon::parse($item->tanggal)->format('d M Y')); ?>

                            </td>
                            <td class="px-4 py-3">
                                <p class="font-semibold"><?php echo e($item->judul_pengeluaran); ?></p>
                                <p class="text-xs text-gray-600 truncate w-40"><?php echo e($item->deskripsi); ?></p>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">
                                    <?php echo e($item->kategori->nama_kategori); ?>

                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm font-bold text-red-600">
                                Rp <?php echo e(number_format($item->jumlah, 0, ',', '.')); ?>

                            </td>
                            <td class="px-4 py-3 text-sm">
                                <?php if($item->bukti_transaksi): ?>
                                    <a href="<?php echo e(asset('storage/'.$item->bukti_transaksi)); ?>" target="_blank" class="text-blue-600 underline text-xs">Lihat</a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-2 text-sm">
                                    <button 
                                        @click="
                                            openModal = true; 
                                            isEdit = true;
                                            actionUrl = '<?php echo e(url('pengeluaran')); ?>/<?php echo e($item->id); ?>';
                                            data = {
                                                id: '<?php echo e($item->id); ?>',
                                                judul: '<?php echo e(addslashes($item->judul_pengeluaran)); ?>',
                                                kategori_id: '<?php echo e($item->kategori_id); ?>',
                                                jumlah: '<?php echo e($item->jumlah); ?>',
                                                tanggal: '<?php echo e($item->tanggal); ?>',
                                                deskripsi: '<?php echo e(addslashes($item->deskripsi)); ?>'
                                            }
                                        "
                                        class="px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg focus:outline-none focus:shadow-outline-gray" 
                                        aria-label="Edit">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                                    </button>

                                    <form action="<?php echo e(route('pengeluaran.destroy', $item->id)); ?>" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button class="px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
                                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 000-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-3 text-center text-gray-500">Belum ada data.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div
            x-show="openModal"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            style="display: none;"
            class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
        >
            <div 
                x-show="openModal"
                class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg sm:rounded-lg sm:m-4 sm:max-w-xl"
                @click.away="openModal = false"
            >
                <header class="flex justify-between mb-4">
                    <p class="text-lg font-semibold text-gray-700" x-text="isEdit ? 'Edit Pengeluaran' : 'Tambah Pengeluaran'"></p>
                    <button class="text-gray-400 hover:text-gray-700" @click="openModal = false">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L10 8.586 5.707 4.293a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path></svg>
                    </button>
                </header>

                <form :action="actionUrl" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="mt-4 mb-4">
                        <label class="block text-sm">
                            <span class="text-gray-700">Judul Pengeluaran</span>
                            <input x-model="data.judul" name="judul_pengeluaran" class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-green-400 form-input" required />
                        </label>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <label class="block text-sm">
                            <span class="text-gray-700">Kategori</span>
                            <select x-model="data.kategori_id" name="kategori_id" class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-green-400 form-select" required>
                                <option value="">Pilih...</option>
                                <?php $__currentLoopData = $kategori; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->nama_kategori); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </label>
                        
                        <label class="block text-sm">
                            <span class="text-gray-700">Tanggal</span>
                            <input x-model="data.tanggal" type="date" name="tanggal" class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-green-400 form-input" required />
                        </label>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm">
                            <span class="text-gray-700">Jumlah (Rp)</span>
                            <input x-model="data.jumlah" type="number" name="jumlah" class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-green-400 form-input" required />
                        </label>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm">
                            <span class="text-gray-700">Deskripsi</span>
                            <textarea x-model="data.deskripsi" name="deskripsi" class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-green-400 form-textarea" rows="2"></textarea>
                        </label>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm">
                            <span class="text-gray-700">Bukti Transaksi <span x-show="isEdit" class="text-xs text-orange-500">(Biarkan kosong jika tidak ingin mengubah foto)</span></span>
                            <input type="file" name="bukti_transaksi" class="block w-full mt-1 text-sm text-gray-500"/>
                        </label>
                    </div>

                    <footer class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50">
                        <button @click="openModal = false" type="button" class="w-full px-5 py-3 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg sm:w-auto hover:border-gray-500">
                            Batal
                        </button>
                        <button type="submit" class="w-full px-5 py-3 text-sm font-medium text-white bg-green-600 rounded-lg sm:w-auto hover:bg-green-700">
                            Simpan Data
                        </button>
                    </footer>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('pieChart').getContext('2d');
        
        // Ambil data dari PHP Controller yang dikirim via compact
        const labels = <?php echo json_encode($labels); ?>;
        const data = <?php echo json_encode($dataset); ?>;

        // Konfigurasi Chart
        const myPieChart = new Chart(ctx, {
            type: 'doughnut', // Bisa ganti 'pie' kalau mau full bulat
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: [
                        '#10B981', // Hijau (Tailwind Green-500)
                        '#3B82F6', // Biru (Blue-500)
                        '#F59E0B', // Kuning (Amber-500)
                        '#EF4444', // Merah (Red-500)
                        '#8B5CF6', // Ungu (Purple-500)
                        '#EC4899', // Pink (Pink-500)
                        '#6B7280', // Abu (Gray-500)
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right', // Legend di sebelah kanan
                        labels: {
                            boxWidth: 10
                        }
                    }
                }
            }
        });
    });
</script>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\manajemen_masjid\resources\views/modules/keuangan/pengeluaran/index.blade.php ENDPATH**/ ?>