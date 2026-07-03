@php
$user = auth()->user();
$namaWorker = $user->name;
$jabatan = $user->userRole?->display_name ?? 'Pegawai';
@endphp

<x-filament::card class="h-full">
    <div class="flex flex-col h-full justify-between">
        <div>
            <span class="text-sm text-gray-500 font-medium">Selamat Datang</span>
            <h2 class="text-2xl font-bold text-gray-800 mt-2">{{ $namaWorker }}</h2>
        </div>
        <!-- <div class="mt-4">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800">
                {{ $jabatan }}
            </span>
        </div> -->
    </div>
</x-filament::card>