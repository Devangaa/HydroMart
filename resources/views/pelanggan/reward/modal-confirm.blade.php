{{-- ============================================================================= --}}
{{-- FILE: pelanggan/reward/modal-confirm.blade.php --}}
{{-- HALAMAN: Modal Konfirmasi Klaim Reward --}}
{{-- DESKRIPSI: Popup konfirmasi penukaran poin untuk reward. --}}
{{-- ============================================================================= --}}

{{-- Modal: Konfirmasi Klaim --}}
<div id="confirmModal" class="fixed inset-0 z-[9999] hidden" aria-modal="true" role="dialog">
    <div class="modal-backdrop absolute inset-0 bg-black/40 backdrop-blur-sm opacity-0 transition-opacity duration-300 ease-out" onclick="closeModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="modal-content relative z-10 bg-white rounded-[2.5rem] p-8 max-w-sm w-full shadow-2xl opacity-0 transition-opacity duration-300 ease-out pointer-events-auto">
            <div class="w-20 h-20 bg-amber-50 rounded-3xl flex items-center justify-center mx-auto mb-6 text-amber-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-black text-gray-900 text-center mb-2">Konfirmasi Klaim</h3>
            <p class="text-gray-500 text-sm text-center mb-8">
                Yakin ingin menukarkan <span id="modalPoin" class="font-black text-amber-600"></span> poin untuk reward <span id="modalReward" class="font-black text-gray-900">ini</span>?
            </p>

            <div class="flex gap-4">
                <button type="button" onclick="closeModal()" class="flex-1 py-4 bg-gray-100 text-gray-600 font-bold rounded-2xl hover:bg-gray-200 transition">
                    Batal
                </button>
                <form id="claimForm" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full py-4 bg-green-600 text-white font-bold rounded-2xl shadow-lg shadow-green-100 hover:bg-green-700 transition">
                        Ya, Klaim
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
