{{-- ============================================================================= --}}
{{-- FILE: auth/modal-success.blade.php --}}
{{-- HALAMAN: Modal Sukses Reset Password --}}
{{-- DESKRIPSI: Popup sukses setelah password berhasil diperbarui. --}}
{{-- ============================================================================= --}}

{{-- Modal: Sukses Reset Password --}}
<template x-teleport="body">
    <div x-show="showSuccessModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm"
         @click.self="showSuccessModal = false"
         style="display: none;">

        <div x-show="showSuccessModal"
             @click.stop
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="relative z-10 bg-white rounded-[2.5rem] p-10 max-w-sm w-full shadow-2xl text-center border border-gray-100">

            <div class="w-20 h-20 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h3 class="text-xl font-bold text-gray-900 mb-2">Berhasil!</h3>
            <p class="text-gray-500 text-sm mb-8 leading-relaxed">Password anda telah berhasil diperbarui. Silahkan login kembali dengan password baru anda.</p>

            <div class="flex flex-col gap-3">
                <button @click="goToLogin" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-2xl transition-all shadow-lg shadow-green-100 active:scale-95">
                    Ke Halaman Login
                </button>
            </div>
        </div>
    </div>
</template>
