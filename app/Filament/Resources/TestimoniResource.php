<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimoniResource\Pages;
use App\Models\Testimoni;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TestimoniResource extends Resource
{
    protected static ?string $model = Testimoni::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Publikasi'; // Dikelompokkan dengan menu Artikel

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Tokoh/Pemberi Testimoni')
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->label('Jabatan / Status')
                    ->required(),
                Forms\Components\Textarea::make('quote')
                    ->label('Kutipan / Isi Testimoni')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('photo')
                    ->label('Foto Tokoh')
                    ->image()->directory('testimonials'),
                Forms\Components\Toggle::make('is_visible')
                    ->label('Tampilkan di Website?')
                    ->required(),
                Forms\Components\TextInput::make('order')
                    ->label('Urutan Tampil')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')->label('Foto'),
                Tables\Columns\TextColumn::make('name')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('title')->label('Jabatan'),
                Tables\Columns\IconColumn::make('is_visible')->label('Tampil')->boolean(),

            ])
            ->defaultSort('order', 'asc')
            ->reorderable('order') // <-- Memungkinkan Anda drag-and-drop untuk mengubah urutan
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestimonis::route('/'),
            'create' => Pages\CreateTestimoni::route('/create'),
            'edit' => Pages\EditTestimoni::route('/{record}/edit'),
        ];
    }
}
