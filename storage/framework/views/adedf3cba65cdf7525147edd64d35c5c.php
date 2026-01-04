<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Manajemen Masjid'); ?></title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
        
        /* Toast Notification Styles */
        .toast {
            animation: slideInRight 0.3s ease-out;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .toast-exit {
            animation: slideOutRight 0.3s ease-in;
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        /* Modal Styles */
        .modal-backdrop {
            backdrop-filter: blur(2px);
        }
    </style>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-gray-100">
    <?php if(auth()->guard()->check()): ?>
        <?php echo $__env->make('layouts.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        
        <div class="flex">
            <?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            
            <main class="flex-1 p-6 md:ml-64 mt-16">
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    <?php else: ?>
        <main>
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    <?php endif; ?>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-20 right-4 z-50 space-y-2" x-data="toastManager()">
        <template x-for="toast in toasts" :key="toast.id">
            <div 
                x-show="toast.show" 
                x-transition:enter="toast"
                x-transition:leave="toast-exit"
                :class="{
                    'bg-green-500': toast.type === 'success',
                    'bg-red-500': toast.type === 'error',
                    'bg-blue-500': toast.type === 'info',
                    'bg-yellow-500': toast.type === 'warning'
                }"
                class="flex items-center gap-3 text-white px-6 py-4 rounded-lg shadow-lg min-w-[300px] max-w-md"
                role="alert">
                <div class="flex-shrink-0">
                    <template x-if="toast.type === 'success'">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </template>
                    <template x-if="toast.type === 'error'">
                        <i class="fas fa-times-circle text-2xl"></i>
                    </template>
                    <template x-if="toast.type === 'info'">
                        <i class="fas fa-info-circle text-2xl"></i>
                    </template>
                    <template x-if="toast.type === 'warning'">
                        <i class="fas fa-exclamation-triangle text-2xl"></i>
                    </template>
                </div>
                <div class="flex-1">
                    <p class="font-medium" x-text="toast.message"></p>
                </div>
                <button @click="removeToast(toast.id)" class="flex-shrink-0 hover:bg-white hover:bg-opacity-20 rounded p-1 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </template>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Toast Manager Script -->
    <script>
        function toastManager() {
            return {
                toasts: [],
                nextId: 1,
                
                init() {
                    // Show Laravel session messages
                    <?php if(session('success')): ?>
                        this.showToast('success', '<?php echo e(session('success')); ?>');
                    <?php endif; ?>
                    
                    <?php if(session('error')): ?>
                        this.showToast('error', '<?php echo e(session('error')); ?>');
                    <?php endif; ?>
                    
                    <?php if(session('info')): ?>
                        this.showToast('info', '<?php echo e(session('info')); ?>');
                    <?php endif; ?>
                    
                    <?php if(session('warning')): ?>
                        this.showToast('warning', '<?php echo e(session('warning')); ?>');
                    <?php endif; ?>
                    
                    // Listen for custom toast events
                    window.addEventListener('show-toast', (event) => {
                        this.showToast(event.detail.type, event.detail.message);
                    });
                },
                
                showToast(type, message, duration = 5000) {
                    const id = this.nextId++;
                    const toast = { id, type, message, show: true };
                    this.toasts.push(toast);
                    
                    setTimeout(() => {
                        this.removeToast(id);
                    }, duration);
                },
                
                removeToast(id) {
                    const index = this.toasts.findIndex(t => t.id === id);
                    if (index > -1) {
                        this.toasts[index].show = false;
                        setTimeout(() => {
                            this.toasts.splice(index, 1);
                        }, 300);
                    }
                }
            };
        }
        
        // Global toast function
        window.showToast = function(type, message, duration = 5000) {
            window.dispatchEvent(new CustomEvent('show-toast', {
                detail: { type, message }
            }));
        };
        
        // Confirm delete modal
        function confirmDelete(message = 'Apakah Anda yakin ingin menghapus data ini?') {
            return confirm(message);
        }
    </script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\ACER\Downloads\Manpro Masjid\resources\views/layouts/app.blade.php ENDPATH**/ ?>