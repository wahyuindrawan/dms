<x-filament-panels::page>
    <div class="space-y-4">
        <x-filament::card>
            <h2 class="text-xl font-bold">Agenda Hari Ini</h2>

            @if ($agendaToday->isEmpty())
            <p class="text-gray-500 mt-2">Tidak ada agenda hari ini.</p>
            @else
            <ul class="mt-4 space-y-2">
                @foreach ($agendaToday as $agenda)
                <li class="border p-3 rounded shadow-sm">
                    <strong>{{ $agenda->judul }}</strong><br>
                    Waktu: {{ $agenda->tanggal->format('d M Y') }} {{ $agenda->jam ?? '' }}<br>
                    Tempat: {{ $agenda->tempat }}
                </li>
                @endforeach
            </ul>
            @endif
        </x-filament::card>
    </div>
</x-filament-panels::page>