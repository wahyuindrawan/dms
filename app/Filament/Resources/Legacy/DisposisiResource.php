<?php

namespace App\Filament\Resources\Legacy;

use App\Filament\Resources\Legacy\DisposisiResource\Pages;
use App\Models\Legacy\Disposisi;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class DisposisiResource extends Resource
{
    protected static ?string $model = Disposisi::class;
    protected static bool $shouldRegisterNavigation = false;
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

                Textarea::make('deskripsi_dokumen')
                    ->label('Deskripsi Dokumen/Surat')
                    ->rows(2)
                    ->placeholder('Contoh: Surat Masuk - Perihal Permohonan Izin...')
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
                TextColumn::make('deskripsi_dokumen')
                    ->label('Dokumen/Surat')
                    ->searchable()
                    ->html()
                    ->formatStateUsing(function ($state, $record) {
                        $title = $state ?? '-';
                        $date = $record->created_at?->format('d M Y') ?? '-';

                        return "<div class='leading-tight'>\n"
                            . "<div class='font-semibold text-gray-900 py-1'>{$title}</div>\n"
                            . "<div class='text-xs text-gray-600'>Disposisi: {$date}</div>\n"
                            . "</div>";
                    }),

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
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Action::make('Lihat Detail')
                    ->label('')
                    ->tooltip('Lihat Detail')
                    ->icon('heroicon-o-eye')
                    ->modalContent(
                        fn($record) => view('filament.modals.detail-disposisi', ['record' => $record])
                    )
                    ->modalWidth('3xl'),

                DeleteAction::make()
                    ->requiresConfirmation()
                    ->label('')
                    ->tooltip('Hapus'),
            ])
            ->actionsPosition(ActionsPosition::BeforeColumns)
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
