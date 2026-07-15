<?php

namespace App\Filament\Resources\Legacy;

use App\Filament\Resources\Legacy\AgendaResource\Pages;
use App\Models\Legacy\Agenda;
use App\Models\Legacy\Worker;
use App\Models\User;
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
    protected static bool $shouldRegisterNavigation = false;
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
                    ->maxSize(2048),
                Select::make('workers')
                    ->label('Peserta (User)')
                    ->multiple()
                    ->relationship('workers', 'name', function ($query) {
                        return $query->whereNotNull('id');
                    })
                    ->options(function () {
                        $options = User::whereNotNull('id')->pluck('name', 'id')->toArray();
                        return ['ALL' => 'Semua Peserta'] + $options;
                    })
                    ->required()
                    ->searchable()
                    ->dehydrated(false)
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

                TextColumn::make('workers.name')
                    ->label('Peserta')
                    ->formatStateUsing(function ($state, $record) {
                        $totalUsers = User::whereNotNull('id')->count();
                        return count($record->workers) === $totalUsers
                            ? 'Semua Peserta'
                            : implode(', ', $record->workers->pluck('name')->take(3)->toArray()) . (count($record->workers) > 3 ? '...' : '');
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
