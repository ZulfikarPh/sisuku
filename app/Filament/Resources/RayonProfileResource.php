<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RayonProfileResource\Pages;
use App\Models\RayonProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class RayonProfileResource extends Resource
{
    protected static ?string $model = RayonProfile::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = 'Profil Rayon';
    protected static ?string $modelLabel = 'Profil Rayon';
    protected static ?string $slug = 'profil-rayon';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Kolom utama, selalu terlihat
                Forms\Components\Select::make('rayon_id')
                    ->relationship('rayon', 'name')
                    ->label('Rayon')
                    ->searchable()
                    ->preload()
                    ->disabledOn('edit')
                    ->unique(ignoreRecord: true)
                    ->required(),

                // Menggunakan Tabs untuk mengorganisir form
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        // TAB 1: Profil Utama & Branding
                        Forms\Components\Tabs\Tab::make('Profil Utama')
                            ->icon('heroicon-o-identification')
                            ->schema([
                                Forms\Components\Section::make('Branding & Jargon')
                                    ->description('Informasi dasar dan branding untuk rayon.')
                                    ->schema([
                                        Forms\Components\TextInput::make('jargon')
                                            ->maxLength(255),
                                        // Ganti 'logo' menjadi 'logo_path' sesuai nama kolom DB
                                        Forms\Components\FileUpload::make('logo_path')
                                            ->label('Logo Rayon')
                                            ->image()
                                            ->imageEditor()
                                            ->directory('rayon-assets')
                                            ->columnSpanFull(),
                                        // Tambahkan field untuk banner
                                        Forms\Components\FileUpload::make('banner_path')
                                            ->label('Gambar Banner Halaman')
                                            ->image()
                                            ->imageEditor()
                                            ->directory('rayon-assets')
                                            ->columnSpanFull(),
                                    ])->columns(1),
                            ]),

                        // TAB 2: Konten Halaman
                        Forms\Components\Tabs\Tab::make('Konten Halaman')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Forms\Components\RichEditor::make('description')->label('Deskripsi / Tentang Rayon')->columnSpanFull(),
                                Forms\Components\RichEditor::make('vision')->label('Visi')->columnSpanFull(),
                                Forms\Components\RichEditor::make('mission')->label('Misi')->columnSpanFull(),
                                Forms\Components\RichEditor::make('history')->label('Sejarah')->columnSpanFull(),
                            ]),

                        // TAB 3: Kontak & Media Sosial
                        Forms\Components\Tabs\Tab::make('Kontak & Media Sosial')
                            ->icon('heroicon-o-share')
                            ->schema([
                                Forms\Components\Section::make('Informasi Kontak')
                                    ->schema([
                                        Forms\Components\Textarea::make('address')->label('Alamat Sekretariat'),
                                        Forms\Components\TextInput::make('email')->email()->label('Email Resmi'),
                                        Forms\Components\TextInput::make('phone_number')->tel()->label('Nomor Telepon'),
                                    ])->columns(1),

                                Forms\Components\Section::make('Tautan Media Sosial')
                                    ->description('Masukkan username atau URL lengkap.')
                                    ->schema([
                                        // Komponen KeyValue sangat cocok untuk mengelola data JSON
                                        Forms\Components\KeyValue::make('social_media')
                                            ->label('Akun Media Sosial')
                                            ->keyLabel('Platform (e.g., instagram)')
                                            ->valueLabel('Username atau Link')
                                            ->reorderable(),
                                    ]),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tambahkan kolom gambar agar lebih informatif
                Tables\Columns\ImageColumn::make('logo_path')->label('Logo')->circular(),
                Tables\Columns\TextColumn::make('rayon.name')->label('Nama Rayon')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('jargon')->searchable()->limit(50),
                Tables\Columns\TextColumn::make('updated_at')->label('Terakhir Diubah')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Tambahkan filter berdasarkan rayon, hanya untuk Super Admin
                Tables\Filters\SelectFilter::make('rayon')
                    ->relationship('rayon', 'name')
                    ->searchable()
                    ->preload()
                    ->visible(fn () => Auth::user()->hasRole('super_admin')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->visible(fn () => Auth::user()->hasRole('super_admin')),
                ]),
            ]);
    }

    // Hak akses di bawah ini sudah bagus, tidak perlu diubah.
    public static function getEloquentQuery(): Builder
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->hasRole('super_admin')) {
            return parent::getEloquentQuery();
        }
        return parent::getEloquentQuery()->where('rayon_id', $user->rayon_id);
    }

    public static function canCreate(): bool
    {
        return Auth::user()->hasRole('super_admin');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRayonProfiles::route('/'),
            'edit' => Pages\EditRayonProfile::route('/{record}/edit'),
        ];
    }
}
