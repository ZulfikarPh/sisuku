<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnggotaResource\Pages;
use App\Models\Anggota;
use App\Models\Prodi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

// Impor untuk fitur Impor & Ekspor manual
use App\Exports\AnggotaExport;
use App\Imports\AnggotaImport;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;

class AnggotaResource extends Resource
{
    protected static ?string $model = Anggota::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $modelLabel = 'Anggota';
    protected static ?string $slug = 'anggota';

    public static function form(Form $form): Form
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('super_admin');

        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pribadi')
                    ->schema([
                        Forms\Components\FileUpload::make('foto')->image()->avatar()->directory('foto-anggota')->columnSpanFull(),
                        Forms\Components\TextInput::make('nama')->required(),
                        Forms\Components\TextInput::make('nia')->required()->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('ttl')->required()->label('Tempat, Tanggal Lahir'),
                        Forms\Components\Select::make('jenis_kelamin')->options(['Laki-laki' => 'Laki-laki', 'Perempuan' => 'Perempuan'])->required(),
                        Forms\Components\TextInput::make('no_hp')->tel()->label('Nomor HP'),
                        Forms\Components\TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
                        Forms\Components\Textarea::make('alamat')->required()->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Informasi Keanggotaan')
                    ->schema([
                        Forms\Components\Select::make('rayon_id')
                            ->label('Fakultas (Rayon)')
                            ->relationship('rayon', 'name')
                            ->searchable()->preload()->required()
                            ->live()
                            ->default(fn () => !$isSuperAdmin ? Auth::user()->rayon_id : null)
                            ->disabled(! $isSuperAdmin)
                            ->hidden(! $isSuperAdmin),

                        Forms\Components\Select::make('prodi_id')
                            ->label('Program Studi')
                            ->required()
                            ->searchable()->preload()
                            ->options(fn (Get $get): Collection =>
                                Prodi::query()
                                    ->where('rayon_id', $get('rayon_id'))
                                    ->pluck('nama_prodi', 'id')
                            ),

                        Forms\Components\TextInput::make('tahun_mapaba')->numeric()->required()->minValue(1960),
                        Forms\Components\Select::make('kategori_anggota_id')
                            ->label('Kategori Anggota')
                            ->relationship('kategoriAnggota', 'nama_kategori')
                            ->required(),
                        Forms\Components\Textarea::make('minat_dan_bakat')->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto')->circular()->defaultImageUrl(url('/images/default-avatar.png')),
                Tables\Columns\TextColumn::make('nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('nia')->searchable(),
                Tables\Columns\TextColumn::make('rayon.name')->label('Fakultas/Rayon')->badge()->searchable(),
                Tables\Columns\TextColumn::make('prodi.nama_prodi')->label('Prodi')->searchable()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('kategoriAnggota.nama_kategori')->label('Kategori')->badge()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('rayon')->relationship('rayon', 'name')->searchable()->preload(),
                Tables\Filters\SelectFilter::make('prodi')->relationship('prodi', 'nama_prodi')->searchable()->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                // Tombol Impor Manual
                // Action::make('import')
                //     ->label('Impor Anggota')
                //     ->icon('heroicon-o-document-arrow-up')->color('success')
                //     ->form([ FileUpload::make('attachment')->label('File Excel')->required()->acceptedFileTypes(['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']) ])
                //     ->action(function (array $data) {
                //         try {
                //             Excel::import(new AnggotaImport, $data['attachment']);
                //             Notification::make()->title('Impor Berhasil')->success()->send();
                //         } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                //             $failures = $e->failures();
                //             $errorMessages = [];
                //             foreach ($failures as $failure) { $errorMessages[] = 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors()); }
                //             Notification::make()->title('Impor Gagal, Ada Kesalahan Data')->body(implode("\n", $errorMessages))->danger()->persistent()->send();
                //         }
                //     }),
                // // Tombol Ekspor Manual
                // Action::make('export')->label('Ekspor ke Excel')->icon('heroicon-o-document-arrow-down')->action(fn () => Excel::download(new AnggotaExport, 'semua-data-anggota.xlsx')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Aksi Ekspor data terpilih Manual
                    BulkAction::make('export_selected')->label('Ekspor yang dipilih')->icon('heroicon-o-document-arrow-down')
                        ->action(fn (Collection $records) => Excel::download(new AnggotaExport($records), 'data-terpilih-anggota.xlsx')),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();
        if (!$user->hasRole('super_admin')) {
            $data['rayon_id'] = $user->rayon_id;
        }
        return $data;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnggotas::route('/'),
            'create' => Pages\CreateAnggota::route('/create'),
            'view' => Pages\ViewAnggota::route('/{record}'),
            'edit' => Pages\EditAnggota::route('/{record}/edit'),
        ];
    }
}
