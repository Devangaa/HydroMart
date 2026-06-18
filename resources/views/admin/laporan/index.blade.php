@extends('layouts.app')

{{-- ============================================================================= --}}
{{-- FILE: admin/laporan/index.blade.php --}}
{{-- HALAMAN: Laporan Tahunan & Bulanan --}}
{{-- DESKRIPSI: Visualisasi performa keuangan, rating, produk/layanan terlaris bulanan. --}}
{{-- ============================================================================= --}}

@section('title', 'Laporan Tahunan & Bulanan')

@section('content')
<div class="w-full" x-data="{ openTahun: false, currentTahun: {{ $tahun }}, showPemasukan: true, showPengeluaran: true, showKas: true }">
    <div class="min-h-screen bg-gray-50/50 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Bagian: Breadcrumb --}}
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold text-gray-400 hover:text-green-600 transition-colors flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali ke Dashboard
                        </a>
                    </li>
                </ol>
            </nav>

            <div data-aos="fade-right" class="relative z-20">
                {{-- Bagian: Header & Filter Dropdown --}}
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                    <div>
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full uppercase">
                            Analisis & Laporan
                        </span>
                        <h1 class="text-3xl font-extrabold text-gray-900 mt-2">Laporan Bisnis</h1>
                        <p class="text-gray-500 text-sm">Pantau tren finansial, volume penjualan, dan rating kepuasan pelanggan.</p>
                    </div>

                    {{-- Dropdown Filter Tahun --}}
                    <div class="relative w-full md:w-auto self-start md:self-auto">
                        <button type="button" @click="openTahun = !openTahun" class="w-full md:w-auto bg-white border border-gray-200 rounded-2xl px-6 py-3 text-sm font-bold text-gray-700 shadow-sm outline-none hover:bg-gray-50 transition flex items-center justify-between gap-3 min-w-[150px] cursor-pointer">
                            <span class="flex items-center gap-2 text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Tahun: <strong class="text-gray-950 font-black" x-text="currentTahun"></strong>
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 transition-transform duration-200" :class="openTahun ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div
                            x-show="openTahun"
                            x-cloak
                            @click.away="openTahun = false"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-2"
                            class="absolute right-0 top-full mt-2 bg-white border border-gray-100 rounded-2xl shadow-xl z-30 overflow-y-auto max-h-80 min-w-[160px]"
                        >
                            @foreach($years as $y)
                                <button type="button" @click="openTahun = false; currentTahun = {{ $y }}; window.location.href = '?tahun={{ $y }}';" :class="currentTahun === {{ $y }} ? 'bg-green-50 text-green-600 font-bold' : ''" class="w-full text-left px-5 py-3 text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-600 transition cursor-pointer">
                                    {{ $y }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Grafik Utama: Tren Finansial --}}
            <div data-aos="fade-up" class="mb-4">
                <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-gray-100 shadow-sm relative overflow-hidden">
                    
                    {{-- Header Grafik & Toggle Buttons --}}
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-8 gap-4 border-b border-gray-50 pb-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Grafik Keuangan: Tren Finansial</h2>
                            <p class="text-xs text-gray-400">Gunakan tombol di bawah untuk menyembunyikan/menampilkan garis pada grafik.</p>
                        </div>
                        
                        {{-- Custom Legend / Toggle Buttons --}}
                        <div class="flex flex-wrap gap-2.5">
                            {{-- Toggle Pemasukan --}}
                            <button type="button" @click="showPemasukan = !showPemasukan; toggleDataset(0, showPemasukan);"
                                    :class="showPemasukan ? 'bg-green-600 text-white border-green-600 shadow-green-100' : 'bg-transparent text-green-600 border border-green-600'"
                                    class="px-4.5 py-2.5 text-xs font-black rounded-2xl transition-all duration-200 border flex items-center gap-2 shadow-sm cursor-pointer">
                                <span class="w-2.5 h-2.5 bg-green-500 rounded-full" :class="showPemasukan ? 'bg-white' : ''"></span>
                                Pemasukan
                            </button>
                            {{-- Toggle Pengeluaran --}}
                            <button type="button" @click="showPengeluaran = !showPengeluaran; toggleDataset(1, showPengeluaran);"
                                    :class="showPengeluaran ? 'bg-red-600 text-white border-red-600 shadow-red-100' : 'bg-transparent text-red-600 border border-red-600'"
                                    class="px-4.5 py-2.5 text-xs font-black rounded-2xl transition-all duration-200 border flex items-center gap-2 shadow-sm cursor-pointer">
                                <span class="w-2.5 h-2.5 bg-red-500 rounded-full" :class="showPengeluaran ? 'bg-white' : ''"></span>
                                Pengeluaran
                            </button>
                            {{-- Toggle Kas Bulanan (Profit/Loss) --}}
                            <button type="button" @click="showKas = !showKas; toggleDataset(2, showKas);"
                                    :class="showKas ? 'bg-indigo-600 text-white border-indigo-600 shadow-indigo-100' : 'bg-transparent text-indigo-600 border border-indigo-600'"
                                    class="px-4.5 py-2.5 text-xs font-black rounded-2xl transition-all duration-200 border flex items-center gap-2 shadow-sm cursor-pointer">
                                <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full" :class="showKas ? 'bg-white' : ''"></span>
                                Kas Bulanan
                            </button>
                        </div>
                    </div>

                    {{-- Ringkasan Tahunan di Atas Grafik --}}
                    @php
                        $totalPemasukanTahunan = array_sum($pemasukanData);
                        $totalPengeluaranTahunan = array_sum($pengeluaranData);
                        $totalKasTahunan = array_sum($kasData);
                    @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                        {{-- Card Kas Tahunan (Profit/Loss) --}}
                        <div class="bg-indigo-50/40 p-6 rounded-3xl border border-indigo-100/50 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest">Kas Tahunan (Net)</span>
                                <p class="text-2xl font-black text-indigo-950 mt-1">
                                    Rp {{ number_format($totalKasTahunan, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center font-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        {{-- Card Pemasukan Tahunan --}}
                        <div class="bg-green-50/40 p-6 rounded-3xl border border-green-100/50 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] font-bold text-green-600 uppercase tracking-widest">Pemasukan Tahunan</span>
                                <p class="text-2xl font-black text-green-950 mt-1">
                                    Rp {{ number_format($totalPemasukanTahunan, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center font-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                        </div>
                        {{-- Card Pengeluaran Tahunan --}}
                        <div class="bg-red-50/40 p-6 rounded-3xl border border-red-100/50 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] font-bold text-red-600 uppercase tracking-widest">Pengeluaran Tahunan</span>
                                <p class="text-2xl font-black text-red-950 mt-1">
                                    Rp {{ number_format($totalPengeluaranTahunan, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center font-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Canvas Chart --}}
                    <div class="w-full min-h-[350px] relative">
                        <canvas id="financialChart" class="w-full h-full min-h-[350px]"></canvas>
                    </div>
                </div>
            </div>



            {{-- 3. Widget & Tabel Volume Penjualan Bulanan (Bulan Berjalan) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8" data-aos="fade-up">
                
                {{-- Kiri: Best Sellers --}}
                <div class="lg:col-span-1 flex flex-col gap-6">
                    <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-gray-100 shadow-sm flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">
                                <span class="w-2.5 h-2.5 bg-orange-500 rounded-full animate-pulse"></span>
                                Top Best Seller of the Month
                            </div>
                            <h3 class="text-sm font-bold text-gray-500 mb-6 uppercase tracking-wider">Bulan: {{ $bulanNames[$currentMonth - 1] }} {{ $tahun }}</h3>
                        </div>

                        <div class="space-y-6">
                            {{-- Top Product --}}
                            <div class="flex items-start gap-4 p-4 bg-orange-50/50 rounded-3xl border border-orange-100/50 group hover:bg-orange-50 hover:-translate-y-0.5 transition-all duration-300">
                                <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center font-black text-xl flex-shrink-0 group-hover:scale-105 transition-transform duration-300">
                                    P
                                </div>
                                <div class="flex-grow">
                                    <span class="text-[10px] font-bold text-orange-500 uppercase tracking-widest">Produk Terlaris</span>
                                    <h4 class="font-extrabold text-gray-900 text-base mt-0.5 line-clamp-1">
                                        {{ $topProduct ? $topProduct->produk?->nama_produk : '-' }}
                                    </h4>
                                    <p class="text-xs text-gray-400 mt-1">
                                        Terjual: <span class="font-bold text-gray-900">{{ $topProduct ? $topProduct->total_qty : 0 }} Qty</span>
                                    </p>
                                </div>
                            </div>

                            {{-- Top Service --}}
                            <div class="flex items-start gap-4 p-4 bg-teal-50/50 rounded-3xl border border-teal-100/50 group hover:bg-teal-50 hover:-translate-y-0.5 transition-all duration-300">
                                <div class="w-12 h-12 bg-teal-100 text-teal-600 rounded-2xl flex items-center justify-center font-black text-xl flex-shrink-0 group-hover:scale-105 transition-transform duration-300">
                                    L
                                </div>
                                <div class="flex-grow">
                                    <span class="text-[10px] font-bold text-teal-500 uppercase tracking-widest">Layanan Terlaris</span>
                                    <h4 class="font-extrabold text-gray-900 text-base mt-0.5 line-clamp-1">
                                        {{ $topLayanan ? $topLayanan->layanan?->nama_layanan : '-' }}
                                    </h4>
                                    <p class="text-xs text-gray-400 mt-1">
                                        Dipesan: <span class="font-bold text-gray-900">{{ $topLayanan ? $topLayanan->total_count : 0 }} Kali</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kanan: Tabel Penjualan Bulanan --}}
                <div class="lg:col-span-2 flex flex-col">
                    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden flex-1 flex flex-col">
                        <div class="p-6 md:p-8 border-b border-gray-50 flex items-center justify-between">
                            <div>
                                <h3 class="font-bold text-gray-900">Volume Penjualan Bulanan</h3>
                                <p class="text-xs text-gray-400 mt-1">Penjualan per produk & layanan pada bulan {{ $bulanNames[$currentMonth - 1] }} {{ $tahun }}.</p>
                            </div>
                        </div>

                        <div class="overflow-x-auto flex-grow max-h-[300px] overflow-y-auto">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-gray-50/50 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 bg-gray-50/50">Item</th>
                                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 bg-gray-50/50">Tipe</th>
                                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 bg-gray-50/50">Total Qty</th>
                                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 bg-gray-50/50">Total Omset</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse($volumePenjualan as $item)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                            {{ $item['nama'] }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-2.5 py-1 rounded-full font-bold text-[10px] uppercase tracking-wider {{ $item['tipe'] === 'Produk' ? 'bg-orange-50 text-orange-600 border border-orange-100' : 'bg-teal-50 text-teal-600 border border-teal-100' }}">
                                                {{ $item['tipe'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-700">
                                            {{ number_format($item['qty'], 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-bold text-gray-950">
                                            Rp {{ number_format($item['omset'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                            <div class="flex flex-col items-center justify-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                                <span>Tidak ada penjualan pada bulan berjalan</span>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            {{-- 4. Grafik Kedua: Kepuasan Pelanggan --}}
            <div data-aos="fade-up" class="mb-8">
                <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-gray-100 shadow-sm relative overflow-hidden">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-1">Grafik Ulasan: Kepuasan Pelanggan</h2>
                        <p class="text-xs text-gray-400 mb-6">Skala performa rata-rata rating (1.0 - 5.0) per bulan.</p>
                    </div>
                    <div class="w-full min-h-[350px] relative">
                        <canvas id="ratingChart" class="w-full h-full min-h-[350px]"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Skrip untuk memuat Chart.js dan Inisialisasi Grafik --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Global helper function to update dataset visibility from Alpine
    window.toggleDataset = function(index, isVisible) {
        if (window.financialChartInstance) {
            window.financialChartInstance.setDatasetVisibility(index, isVisible);
            window.financialChartInstance.update();
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        const months = @json($bulanNames);
        
        // ----------------------------------------------------
        // 1. Inisialisasi FINANCIAL LINE CHART (3 Lines)
        // ----------------------------------------------------
        const financialCtx = document.getElementById('financialChart').getContext('2d');
        window.financialChartInstance = new Chart(financialCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: @json($pemasukanData),
                        borderColor: '#16a34a', // green-600
                        backgroundColor: 'rgba(22, 163, 74, 0.05)',
                        borderWidth: 3.5,
                        tension: 0.35,
                        pointBackgroundColor: '#16a34a',
                        pointHoverRadius: 7,
                        fill: true
                    },
                    {
                        label: 'Pengeluaran',
                        data: @json($pengeluaranData),
                        borderColor: '#dc2626', // red-600
                        backgroundColor: 'rgba(220, 38, 38, 0.05)',
                        borderWidth: 3.5,
                        tension: 0.35,
                        pointBackgroundColor: '#dc2626',
                        pointHoverRadius: 7,
                        fill: true
                    },
                    {
                        label: 'Kas Bulanan (Net)',
                        data: @json($kasData),
                        borderColor: '#4f46e5', // indigo-600
                        backgroundColor: 'rgba(79, 70, 229, 0.05)',
                        borderWidth: 4,
                        borderDash: [5, 5],
                        tension: 0.35,
                        pointBackgroundColor: '#4f46e5',
                        pointHoverRadius: 8,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // Hidden because we use custom toggle buttons in view
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6'
                        },
                        ticks: {
                            font: {
                                family: 'Inter'
                            },
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID', { compactDisplay: 'short', notation: 'compact' }).format(value);
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: 'Inter',
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        });

        // ----------------------------------------------------
        // 2. Inisialisasi RATING LINE CHART (1 Line, Skala 1.0 - 5.0)
        // ----------------------------------------------------
        const ratingCtx = document.getElementById('ratingChart').getContext('2d');
        const ratingChart = new Chart(ratingCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Rata-rata Rating',
                        data: @json($ratingData),
                        borderColor: '#eab308', // yellow-500
                        backgroundColor: 'rgba(234, 179, 8, 0.1)',
                        borderWidth: 4,
                        tension: 0.3,
                        pointBackgroundColor: '#eab308',
                        pointHoverRadius: 8,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rating: ' + context.parsed.y.toFixed(1) + ' ★';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        min: 1.0,
                        max: 5.0,
                        grid: {
                            color: '#f3f4f6'
                        },
                        ticks: {
                            stepSize: 1.0,
                            font: {
                                family: 'Inter',
                                weight: 'bold'
                            },
                            callback: function(value) {
                                return value.toFixed(1) + ' ★';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: 'Inter',
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
