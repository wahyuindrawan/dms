<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon     = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup    = 'Pengaturan';
    protected static ?string $navigationLabel    = 'Log Aktivitas';
    protected static ?string $pluralModelLabel   = 'Log Aktivitas';
    protected static ?string $modelLabel         = 'Log Aktivitas';
    protected static ?int    $navigationSort     = 99;
    protected static ?string $slug              = 'activity-log';

    // Hanya dapat dilihat, tidak bisa dibuat/diubah dari UI
    public static function canCreate(): bool { return false; }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Detail Aktivitas')
                ->schema([
                    Forms\Components\TextInput::make('log_name')->label('Log')->disabled(),
                    Forms\Components\TextInput::make('event')->label('Event')->disabled(),
                    Forms\Components\TextInput::make('description')->label('Deskripsi')->disabled()->columnSpanFull(),
                    Forms\Components\KeyValue::make('properties')->label('Detail Properti')->disabled()->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('log_name')
                    ->label('Log')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'document' => 'primary',
                        'default'  => 'gray',
                        default    => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('event')
                    ->label('Event')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default   => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Keterangan')
                    ->searchable()
                    ->wrap()
                    ->limit(80),

                Tables\Columns\TextColumn::make('causer.name')
                    ->label('Dilakukan Oleh')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Sistem'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('log_name')
                    ->label('Log')
                    ->options([
                        'document' => 'Dokumen',
                        'default'  => 'Autentikasi',
                    ]),

                Tables\Filters\SelectFilter::make('event')
                    ->label('Event')
                    ->options([
                        'created' => 'Dibuat',
                        'updated' => 'Diubah',
                        'deleted' => 'Dihapus',
                    ]),

                Tables\Filters\Filter::make('causer_id')
                    ->form([
                        Forms\Components\Select::make('causer_id')
                            ->label('Pengguna')
                            ->options(fn () => \App\Models\User::pluck('name', 'id')->toArray())
                            ->searchable(),
                    ])
                    ->query(fn ($query, array $data) => $query->when(
                        $data['causer_id'],
                        fn ($q) => $q->where('causer_id', $data['causer_id'])
                    )),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('until')->label('Sampai Tanggal'),
                    ])
                    ->query(fn ($query, array $data) => $query
                        ->when($data['from'], fn ($q) => $q->whereDate('created_at', '>=', $data['from']))
                        ->when($data['until'], fn ($q) => $q->whereDate('created_at', '<=', $data['until']))
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s'); // auto-refresh tiap 30 detik
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
            'view'  => Pages\ViewActivityLog::route('/{record}'),
        ];
    }
}
