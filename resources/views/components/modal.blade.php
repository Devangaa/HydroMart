@props([
    'show',
    'zIndex' => 'z-[9999]',
    'scrollable' => false,
    'closeOnBackdrop' => true,
    'closeOnAway' => false,
    'teleport' => true,
])

@php
    $overlayAlign = $scrollable
        ? 'items-start overflow-y-auto py-8'
        : 'items-center';
    $panelClasses = trim('relative z-10 ' . ($attributes->get('class') ?? ''));
@endphp

@if ($teleport)
<template x-teleport="body">
@endif
    <div
        x-show="{{ $show }}"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 {{ $zIndex }} flex {{ $overlayAlign }} justify-center p-4 bg-black/40 backdrop-blur-sm"
        @if ($closeOnBackdrop) @click.self="{{ $show }} = false" @endif
        style="display: none;"
    >
        <div
            @click.stop
            @if ($closeOnAway) @click.away="{{ $show }} = false" @endif
            x-show="{{ $show }}"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="{{ $panelClasses }}"
        >
            {{ $slot }}
        </div>
    </div>
@if ($teleport)
</template>
@endif
