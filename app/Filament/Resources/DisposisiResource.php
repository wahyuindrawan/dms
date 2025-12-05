<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DisposisiResource\Pages;
use App\Filament\Resources\DisposisiResource\RelationManagers;
use App\Models\Disposisi;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DisposisiResource extends Resource
{
    protected static ?string $model = Disposisi::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Tracking & Agenda';
    protected static ?string $navigationLabel = 'Disposisi';
    protected static ?int $navigationSort = 5;
    protected static ?string $slug = 'disposisi';

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();
        $data['dari_worker_id'] = $user->worker->id ?? null;
        return $data;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Hidden::make('dari_worker_id')
                //     ->default(fn () => Auth::user()?->workers?->id),

                Select::make('surat_masuk_id')
                    ->relationship('suratMasuk', 'judul')
                    ->label('Surat Masuk')
                    ->searchable()
                    ->required(),

                Select::make('ke_worker_id')
                    ->relationship('keWorker', 'nama')
                    ->label('Diteruskan ke Pegawai')
                    ->searchable()
                    ->required(),

                Textarea::make('catatan')
                    ->rows(3)
                    ->nullable(),

                Select::make('status')
                    ->options([
                        'belum' => 'Belum Diproses',
                        'proses' => 'Sedang Diproses',
                        'selesai' => 'Selesai',
                    ])
                    ->default('belum')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('suratMasuk.judul')
                    ->label('Judul Surat'),

                TextColumn::make('dariWorker.nama')
                    ->label('Dari'),

                TextColumn::make('keWorker.nama')
                    ->label('Kepada'),

                TextColumn::make('status')->badge()->color(
                    fn($state) => match ($state) {
                        'belum' => 'gray',
                        'proses' => 'warning',
                        'selesai' => 'success',
                    }
                ),
                TextColumn::make('created_at')->label('Tanggal')->dateTime(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListDisposisis::route('/'),
            'create' => Pages\CreateDisposisi::route('/create'),
            'edit' => Pages\EditDisposisi::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Disposisi::where('ke_worker_id', auth()->user()?->worker_id)
            ->where('dibaca', false)
            ->count();
    }

    public function mount($record)
    {
        if ($record->ke_worker_id === auth()->user()?->worker_id && !$record->dibaca) {
            $record->update(['dibaca' => true]);
        }

        parent::mount($record);
    }

    public static function getEloquentQuery(): Builder
    {
        // $workerId = auth()->user()?->worker_id;

        $query = parent::getEloquentQuery();

        if (auth()->user()->role !== 'admin') {
            $query->where('ke_worker_id', auth()->user()?->worker_id);
        }

        return $query;
    }
}
