<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use App\Models\Rayon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;
    protected static ?string $navigationGroup = 'Publikasi';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('super_admin');

        return $form
            ->schema([
                Forms\Components\Section::make('Konten Utama')->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Judul')->required()->live(onBlur: true)
                        ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state))),
                    Forms\Components\TextInput::make('slug')->required()->unique(Article::class, 'slug', ignoreRecord: true),
                    Forms\Components\RichEditor::make('content')->label('Isi Artikel')->required()->columnSpanFull(),
                ])->columns(2),

                Forms\Components\Section::make('Meta Data')->schema([
                    Forms\Components\FileUpload::make('thumbnail')->image()->directory('thumbnails'),

                    // KOLOM PENULIS BARU (TEKS BIASA)
                    Forms\Components\TextInput::make('penulis')
                        ->required(),
                         // Otomatis terisi nama admin yang login

                    // KOLOM BARU UNTUK RAYON
                    Forms\Components\Select::make('rayon_id')
                        ->label('Untuk Rayon')
                        ->relationship('rayon', 'name')
                        ->searchable()->preload()
                        ->default(fn() => !$isSuperAdmin ? $user->rayon_id : null)
                        ->disabled(! $isSuperAdmin),

                    Forms\Components\Select::make('article_category_id')->label('Kategori')->relationship('category', 'name')->required(),
                    Forms\Components\Select::make('tags')->label('Tag')->relationship('tags', 'name')->multiple()->preload(),
                    Forms\Components\Select::make('status')->options(['draft' => 'Draft', 'published' => 'Published'])->required()->default('draft'),
                    Forms\Components\DateTimePicker::make('published_at')->label('Tanggal Publikasi'),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail'),
                Tables\Columns\TextColumn::make('title')->label('Judul')->searchable(),
                Tables\Columns\TextColumn::make('penulis')->searchable(),
                Tables\Columns\TextColumn::make('rayon.name')->label('Rayon')->badge()->searchable(),
                Tables\Columns\TextColumn::make('status')->badge(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
