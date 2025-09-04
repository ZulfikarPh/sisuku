<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KomisariatProfileResource\Pages;
use App\Models\KomisariatProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class KomisariatProfileResource extends Resource
{
    protected static ?string $model = KomisariatProfile::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationLabel = 'Profil Komisariat';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Utama')
                ->schema([
                    Forms\Components\TextInput::make('nama_ketua'),
                    Forms\Components\TextInput::make('periode'),
                    Forms\Components\FileUpload::make('logo')->image()->directory('komisariat')->label('Logo Komisariat'),
                    Forms\Components\FileUpload::make('banner_utama')->image()->directory('komisariat')->label('Gambar Banner Utama'),
                ])->columns(2),

            Forms\Components\Section::make('Konten Profil')
                ->schema([
                    Forms\Components\TextInput::make('jargon')->label('Jargon / Tagline'),
                    Forms\Components\RichEditor::make('sambutan')->label('Sambutan Ketua')->columnSpanFull(),
                    Forms\Components\RichEditor::make('sejarah_singkat')->label('Sejarah Singkat')->columnSpanFull(),
                    Forms\Components\RichEditor::make('visi')->columnSpanFull(),
                    Forms\Components\RichEditor::make('misi')->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Kontak & Sosial Media')
                ->schema([
                    Forms\Components\TextInput::make('alamat_sekretariat'),
                    Forms\Components\TextInput::make('email_resmi')->email(),
                    Forms\Components\TextInput::make('link_instagram')->url()->prefix('https://instagram.com/'),
                    Forms\Components\TextInput::make('link_tiktok')->url()->prefix('https://tiktok.com/@'),
                    Forms\Components\TextInput::make('link_youtube')->url()->prefix('https://youtube.com/'),
                    Forms\Components\TextInput::make('link_facebook')->url()->prefix('https://facebook.com/'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_ketua')->label('Nama Ketua'),
                Tables\Columns\TextColumn::make('periode'),
                Tables\Columns\TextColumn::make('updated_at')->label('Terakhir Diperbarui')->dateTime(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            // Kita hapus bulk actions karena hanya ada satu data
            ->bulkActions([]);
    }

    // Hanya izinkan Super Admin yang bisa melihat menu ini
    public static function canViewAny(): bool
    {
        return Auth::user()->hasRole('super_admin');
    }

    // Fungsi ini akan menyembunyikan tombol "Create"
    public static function canCreate(): bool
    {
        return false;
    }

    // getPages() tidak perlu diubah dari default jika kita hanya menyembunyikan tombol Create
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKomisariatProfiles::route('/'),
            'edit' => Pages\EditKomisariatProfile::route('/{record}/edit'),
            'view' => Pages\ViewKomisariatProfile::route('/{record}'),
        ];
    }
}
