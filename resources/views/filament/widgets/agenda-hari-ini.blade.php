@php
use App\Models\Agenda;

$agendaToday = Agenda::whereDate('waktu', now())->get();
@endphp

<x-filament::card>
    <h2 class="text-xl font-bold mb-4">Agenda Hari Ini</h2>

    @if ($agendaToday->isEmpty())
    <p class="text-gray-500">Tidak ada agenda untuk hari ini.</p>
    @else
    <ul class="space-y-4">
        @foreach ($agendaToday as $agenda)
        <li class="border-b pb-2 last:border-b-0">
            <div class="font-semibold text-sm">{{ $agenda->judul }}</div>
            <div class="text-xs text-gray-500">
                {{ \Carbon\Carbon::parse($agenda->waktu)->format('H:i') }} | {{ $agenda->tempat }}
            </div>
        </li>
        @endforeach
    </ul>
    @endif

    <div class="mt-4">
        <a href="{{ App\Filament\Resources\AgendaResource::getUrl() }}" class="text-sm font-medium text-primary-600 hover:text-primary-500">
            Lihat Agenda Lainnya &rarr;
        </a>
    </div>
</x-filament::card>