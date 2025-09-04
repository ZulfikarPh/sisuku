<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MapabaRegistrationResource\Pages;
use App\Filament\Resources\MapabaRegistrationResource\RelationManagers;
use App\Models\MapabaRegistration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Rayon; // Import Rayon
use App\Models\Prodi; // Import Prodi
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker; // Tetap import jika ada DatePicker
use Filament\Forms\Components\FileUpload; // DIKEMBALIKAN
use Filament\Forms\Components\Radio; // Untuk jenis kelamin
use Filament\Notifications\Notification; // Untuk notifikasi
use Illuminate\Support\Facades\Log; // Untuk logging
use Carbon\Carbon; // Untuk tahun otomatis

class MapabaRegistrationResource extends Resource
{
    protected static ?string $model = MapabaRegistration::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Pendaftaran'; // Opsional, sesuaikan dengan grup navigasi Anda

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pribadi')
                    ->description('Data dasar calon peserta MAPABA.')
                    ->schema([
                        TextInput::make('nama_lengkap')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Lengkap')
                            ->disabled(),
                        TextInput::make('nim')
                            ->required()
                            ->maxLength(255)
                            ->label('NIM')
                            ->disabled(),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->label('Email')
                            ->disabled(),
                        TextInput::make('no_hp')
                            ->tel()
                            ->required()
                            ->maxLength(255)
                            ->label('Nomor HP')
                            ->disabled(),
                        TextInput::make('ttl')
                            ->nullable()
                            ->maxLength(255)
                            ->label('Tempat, Tanggal Lahir')
                            ->disabled(),
                        Radio::make('jenis_kelamin')
                            ->options([
                                'Laki-Laki' => 'Laki-Laki',
                                'Perempuan' => 'Perempuan',
                            ])
                            ->label('Jenis Kelamin')
                            ->nullable()
                            ->disabled(),
                        Textarea::make('alamat')
                            ->nullable()
                            ->label('Alamat Lengkap')
                            ->columnSpanFull()
                            ->disabled(),
                        // TextInput::make('tahun_mapaba') TIDAK PERLU DI FORM, AKAN DIISI OTOMATIS
                        // Textarea::make('minat_dan_bakat') TIDAK PERLU DI FORM
                    ])->columns(2),

                Forms\Components\Section::make('Informasi Organisasi & Dokumen')
                    ->description('Pilih rayon, program studi, dan unggah berkas wajib.')
                    ->schema([
                        Select::make('rayon_id')
                            ->label('Rayon Asal')
                            ->options(Rayon::all()->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->disabled(),
                        Select::make('prodi_id')
                            ->label('Program Studi')
                            ->options(Prodi::all()->pluck('nama_prodi', 'id'))
                            ->required()
                            ->searchable()
                            ->disabled(),
                        FileUpload::make('bukti_follow_path') // DIKEMBALIKAN
                            ->label('Bukti Follow Akun (Foto/Screenshot)')
                            ->directory('mapaba-docs/bukti-follow')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/*', 'application/pdf']) // Tambah PDF
                            ->required()
                            ->disabled(),
                        FileUpload::make('foto_ktm_path') // DIKEMBALIKAN
                            ->label('Foto KTM (Kartu Tanda Mahasiswa)')
                            ->directory('mapaba-docs/foto-ktm')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/*'])
                            ->required()
                            ->disabled(),
                        FileUpload::make('bukti_pembayaran_path') // DIKEMBALIKAN
                            ->label('Bukti Pembayaran Pendaftaran')
                            ->directory('mapaba-docs/bukti-bayar')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/*', 'application/pdf']) // Tambah PDF
                            ->required()
                            ->disabled(),
                    ])->columns(2), // Ini bisa diatur 1 kolom jika ingin file di bawah

                Forms\Components\Section::make('Status Pendaftaran')
                    ->schema([
                        Select::make('status')
                            ->label('Status Pendaftaran')
                            ->options([
                                'pending' => 'Menunggu Verifikasi',
                                'verified' => 'Terverifikasi',
                                'rejected' => 'Ditolak',
                            ])
                            ->required()
                            ->default('pending')
                            ->disabled(fn (string $operation): bool => $operation === 'create'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nim')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('no_hp')
                    ->label('No. HP')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rayon.name')
                    ->label('Rayon Asal')
                    ->sortable(),
                Tables\Columns\TextColumn::make('prodi.nama_prodi')
                    ->label('Program Studi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tahun_mapaba') // TETAP TAMPIL DI TABEL
                    ->label('Thn. MAPABA')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'verified' => 'success',
                        'rejected' => 'danger',
                    })
                    ->searchable()
                    ->sortable(),
                // Tampilkan gambar bukti di tabel (opsional, bisa toggleable)
                Tables\Columns\ImageColumn::make('bukti_follow_path')
                    ->label('Bukti Follow')
                    ->square()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ImageColumn::make('foto_ktm_path')
                    ->label('Foto KTM')
                    ->square()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ImageColumn::make('bukti_pembayaran_path')
                    ->label('Bukti Bayar')
                    ->square()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Menunggu Verifikasi',
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                    ])
                    ->label('Filter Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Verifikasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (MapabaRegistration $record): bool => $record->status === 'pending')
                    ->action(function (MapabaRegistration $record) {
                        try {
                            $rayon = Rayon::find($record->rayon_id);
                            $prodi = Prodi::find($record->prodi_id);

                            if (!$rayon || !$prodi) {
                                Notification::make()
                                    ->title('Gagal Memverifikasi')
                                    ->body('Rayon atau Prodi tidak ditemukan untuk pendaftaran ini.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $kategoriAnggota = \App\Models\KategoriAnggota::where('nama_kategori', 'Calon Anggota')->first();
                            if (!$kategoriAnggota) {
                                Notification::make()
                                    ->title('Gagal Memverifikasi')
                                    ->body('Kategori Anggota "Calon Anggota" tidak ditemukan. Harap buat kategori ini terlebih dahulu.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            // Buat Anggota baru
                            \App\Models\Anggota::create([
                                'nama' => $record->nama_lengkap,
                                'nia' => $record->nim,
                                'email' => $record->email,
                                'no_hp' => $record->no_hp,
                                'rayon_id' => $record->rayon_id,
                                'prodi_id' => $record->prodi_id,
                                'kategori_anggota_id' => $kategoriAnggota->id,
                                'ttl' => $record->ttl,
                                'jenis_kelamin' => $record->jenis_kelamin ?? 'Laki-Laki', // Default jika dari form kosong
                                'alamat' => $record->alamat,
                                'foto' => $record->foto_ktm_path, // Menggunakan foto_ktm_path sebagai foto anggota
                                'kampus_asal' => $prodi->nama_prodi, // Mengisi dengan nama prodi
                                'long' => null,
                                'lat' => null,
                                'tahun_mapaba' => Carbon::now()->year, // OTOMATIS TAHUN INI
                                'minat_dan_bakat' => null, // HAPUS DATA MINAT BAKAT
                                'user_id' => null,
                            ]);

                            // Update status pendaftaran MAPABA
                            $record->status = 'verified';
                            $record->save();

                            Notification::make()
                                ->title('Pendaftaran Berhasil Diverifikasi')
                                ->body('Data calon anggota berhasil ditambahkan ke daftar anggota.')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Gagal Memverifikasi')
                                ->body('Terjadi kesalahan: ' . $e->getMessage() . '. Periksa log server.')
                                ->danger()
                                ->send();
                            Log::error('Error verifying MAPABA registration: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
                        }
                    }),
                Tables\Actions\Action::make('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (MapabaRegistration $record): bool => $record->status === 'pending')
                    ->action(function (MapabaRegistration $record) {
                        $record->status = 'rejected';
                        $record->save();
                        Notification::make()
                            ->title('Pendaftaran Ditolak')
                            ->body('Pendaftaran MAPABA berhasil ditolak.')
                            ->success()
                            ->send();
                    }),
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
            'index' => Pages\ListMapabaRegistrations::route('/'),
            'create' => Pages\CreateMapabaRegistration::route('/create'),
            'edit' => Pages\EditMapabaRegistration::route('/{record}/edit'),
        ];
    }
}
