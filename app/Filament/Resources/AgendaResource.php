<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgendaResource\Pages;
use App\Filament\Resources\AgendaResource\RelationManagers;
use App\Models\Agenda;
use App\Models\Worker;
use Filament\Forms;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                TextColumn::make('judul')->sortable(),
                TextColumn::make('waktu')->label('Tanggal & Waktu')->dateTime(),
                TextColumn::make('tempat'),
                TextColumn::make('workers.nama')
                    ->label('Peserta')
                    ->formatStateUsing(function ($state, $record) {
                        $totalWorker = Worker::count();
                        return count($record->workers) === $totalWorker
                            ? 'Semua Karyawan'
                            : implode(', ', $record->workers->pluck('nama')->take(3)->toArray()) . (count($record->workers) > 3 ? '...' : '');
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
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
