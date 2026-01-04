<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgendaResource\Pages;
use App\Models\Agenda;
use App\Models\Worker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AgendaResource extends Resource
{
    protected static ?string $model = Agenda::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Tracking & Agenda';
    protected static ?string $navigationLabel = 'Agenda';
    protected static ?int $navigationSort = 4;
    protected static ?string $slug = 'agenda';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('judul')->required(),
                Textarea::make('deskripsi')->label('Deskripsi Kegiatan'),
                DateTimePicker::make('waktu')->label('Waktu Pelaksanaan')->required(),
                TextInput::make('tempat')->label('Tempat'),
                FileUpload::make('dokumen_path')
                    ->label('Dokumen Pendukung')
                    ->disk('public')
                    ->directory('agenda')
                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                    ->preserveFilenames()
                    ->maxSize(2048),
                Select::make('workers')
                    ->label('Karyawan')
                    ->multiple()
                    ->relationship('workers', 'nama')
                    ->options(function () {
                        $options = Worker::pluck('nama', 'id')->toArray();
                        return ['ALL' => 'Semua Karyawan'] + $options;
                    })
                    ->required()
                    ->searchable()
                    ->dehydrated(false) // Jangan langsung disimpan ke DB
                    ->preload()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')
                    ->label('Agenda')
                    ->html()
                    ->formatStateUsing(function ($state, $record) {
                        $title = $record->judul;
                        $date = $record->waktu ? \Carbon\Carbon::parse($record->waktu)->format('d M Y H:i') : '-';

                        return "<div class='leading-tight'>\n"
                            . "<div class='font-semibold text-gray-900 py-1'>{$title}</div>\n"
                            . "<div class='text-xs text-gray-600'>Waktu: {$date}</div>\n"
                            . "</div>";
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tempat')
                    ->label('Tempat')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('workers.nama')
                    ->label('Peserta')
                    ->formatStateUsing(function ($state, $record) {
                        $totalWorker = Worker::count();
                        return count($record->workers) === $totalWorker
                            ? 'Semua Karyawan'
                            : implode(', ', $record->workers->pluck('nama')->take(3)->toArray()) . (count($record->workers) > 3 ? '...' : '');
                    })
                    ->toggleable(),
            ])
            ->defaultSort('waktu', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('Lihat Detail')
                    ->label('')
                    ->tooltip('Lihat Detail')
                    ->icon('heroicon-o-eye')
                    ->modalContent(
                        fn($record) => view('filament.modals.detail-agenda', ['record' => $record])
                    )
                    ->modalWidth('3xl'),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->label('')
                    ->tooltip('Hapus'),
            ])
            ->actionsPosition(Tables\Enums\ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAgendas::route('/'),
            'create' => Pages\CreateAgenda::route('/create'),
            'edit' => Pages\EditAgenda::route('/{record}/edit'),
        ];
    }
}
