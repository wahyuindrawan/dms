@php
use App\Models\Disposisi;
use App\Filament\Resources\DisposisiResource;

$user = auth()->user();
$jumlahDisposisiMasuk = Disposisi::where('ke_worker_id', $user?->worker_id)
->whereNull('dibaca')
->count();
@endphp

<x-filament::card class="h-full">
    <div class="flex flex-col h-full justify-between">
        <div>
            <span class="text-sm text-gray-500 font-medium">Disposisi Saya</span>
            <h2 class="text-3xl font-bold text-warning-600 mt-2">{{ $jumlahDisposisiMasuk }}</h2>
        </div>
        <div class="mt-4">
            <a href="{{ DisposisiResource::getUrl() }}" class="inline-flex items-center text-sm font-medium text-warning-600 hover:text-warning-500 transition-colors">
                <span>Klik untuk lihat detail</span>
                <x-heroicon-o-inbox class="w-4 h-4 ml-1" />
            </a>
        </div>
    </div>
</x-filament::card>