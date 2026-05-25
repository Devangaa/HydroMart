{{-- ============================================================================= --}}
{{-- FILE: admin/transaksi/modal-resi.blade.php --}}
{{-- HALAMAN: Modal Input Resi --}}
{{-- DESKRIPSI: Form input nomor resi dan ekspedisi. --}}
{{-- ============================================================================= --}}

{{-- Modal: Input Resi --}}
<template x-teleport="body">
        <div x-show="showResiModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm"
             style="display: none;">
            
            <div @click.away="showResiModal = false" 
                 x-show="showResiModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
                 class="bg-white rounded-[2.5rem] p-8 max-w-md w-full shadow-2xl">
                
                <h3 class="text-xl font-bold text-gray-900 mb-2">Input Nomor Resi</h3>
                <p class="text-gray-500 text-sm mb-6">Status akan otomatis berubah menjadi <span class="font-bold text-purple-600">Dikirim</span> setelah resi divalidasi.</p>

                <form action="{{ route('admin.transaksi.resi', $transaksi->order_id) }}" method="POST"
                      x-data="{ 
                            courierOpen: false, 
                            courierSearch: '', 
                            selectedCourier: '{{ old('ekspedisi') }}',
                            selectedCourierName: '',
                            couriers: [
                                { id: 'jne', name: 'JNE' },
                                { id: 'pos', name: 'POS Indonesia' },
                                { id: 'jnt', name: 'J&T' },
                                { id: 'sicepat', name: 'SiCepat' },
                                { id: 'tiki', name: 'TIKI' },
                                { id: 'anteraja', name: 'Anteraja' },
                                { id: 'ninja', name: 'Ninja Xpress' },
                                { id: 'spx', name: 'Shopee Express' }
                            ],
                            get filteredCouriers() {
                                if (!this.courierSearch) return this.couriers;
                                return this.couriers.filter(c => c.name.toLowerCase().includes(this.courierSearch.toLowerCase()));
                            },
                            init() {
                                if(this.selectedCourier) {
                                    const c = this.couriers.find(i => i.id === this.selectedCourier);
                                    if(c) this.selectedCourierName = c.name;
                                }
                            },
                            clientErrorEkspedisi: false
                      }"
                      @submit="clientErrorEkspedisi = !selectedCourier; if(clientErrorEkspedisi) $event.preventDefault();">
                    @csrf
                    <div class="space-y-4">
                        <div class="relative">
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Ekspedisi</label>
                            <input type="hidden" name="ekspedisi" :value="selectedCourier" required>
                            
                            <button type="button" @click="courierOpen = !courierOpen; if(courierOpen) $nextTick(() => $refs.courierSearch.focus())"
                                    class="w-full bg-gray-50 border-2 rounded-xl px-4 py-3 text-sm font-bold text-left flex items-center justify-between transition-all focus:border-green-500 focus:outline-none"
                                    :class="(clientErrorEkspedisi || {{ $errors->has('ekspedisi') ? 'true' : 'false' }}) ? 'border-red-500' : 'border-gray-100'">
                                <span :class="selectedCourierName ? 'text-gray-900' : 'text-gray-400'" x-text="selectedCourierName || 'Pilih Ekspedisi'"></span>
                                <svg class="h-4 w-4 text-gray-400 transition-transform duration-200" :class="courierOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <p x-show="clientErrorEkspedisi" class="text-[10px] text-red-500 mt-1 font-bold" x-cloak>ekspedisi wajib diisi</p>
                            @error('ekspedisi')
                                <p class="text-[10px] text-red-500 mt-1 font-bold">{{ $message }}</p>
                            @enderror

                            <div x-show="courierOpen" x-cloak @click.away="courierOpen = false"
                                 class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-100 rounded-xl shadow-xl z-50 overflow-hidden">
                                <div class="p-2 border-b border-gray-50 bg-gray-50/50">
                                    <input type="text" x-model="courierSearch" x-ref="courierSearch" placeholder="Cari ekspedisi..."
                                           class="w-full px-3 py-2 text-xs bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-400 focus:outline-none placeholder-gray-300 font-medium">
                                </div>
                                <div class="max-h-40 overflow-y-auto">
                                    <template x-if="filteredCouriers.length === 0">
                                        <p class="px-4 py-3 text-xs text-gray-400 text-center">Tidak ditemukan</p>
                                    </template>
                                    <template x-for="courier in filteredCouriers" :key="courier.id">
                                        <button type="button" @click="selectedCourier = courier.id; selectedCourierName = courier.name; courierOpen = false; courierSearch = '';"
                                                class="w-full text-left px-4 py-3 text-xs font-bold text-gray-600 hover:bg-green-50 hover:text-green-600 transition"
                                                :class="selectedCourier == courier.id ? 'bg-green-50 text-green-600' : ''"
                                                x-text="courier.name"></button>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Nomor Resi (AWB)</label>
                            <input type="text" name="nomor_resi" value="{{ old('nomor_resi') }}" required placeholder="Contoh: JP123456789" class="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-3 text-sm focus:border-green-500 focus:ring-0 transition-colors @error('nomor_resi') border-red-500 @enderror">
                            @error('nomor_resi')
                                <p class="text-xs text-red-500 mt-2 font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 mt-8">
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-2xl transition-all shadow-lg shadow-green-100 active:scale-95">
                            Validasi & Simpan
                        </button>
                        <button @click="showResiModal = false" type="button" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-4 rounded-2xl transition-all active:scale-95">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
