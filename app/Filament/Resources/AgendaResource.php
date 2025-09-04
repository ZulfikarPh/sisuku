<?php
namespace App\Filament\Resources;

use App\Filament\Resources\AgendaResource\Pages;
use App\Models\Agenda;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AgendaResource extends Resource
{
    protected static ?string $model = Agenda::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Publikasi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->label('Nama Kegiatan')->required()->columnSpanFull(),
            Forms\Components\RichEditor::make('description')->label('Deskripsi')->columnSpanFull(),
            Forms\Components\TextInput::make('location')->label('Lokasi')->required(),
            Forms\Components\Select::make('rayon_id')->label('Diselenggarakan oleh')->relationship('rayon', 'name'),
            Forms\Components\DateTimePicker::make('start_time')->label('Waktu Mulai')->required(),
            Forms\Components\DateTimePicker::make('end_time')->label('Waktu Selesai'),
            Forms\Components\FileUpload::make('poster')->image()->directory('agenda-posters')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->label('Nama Kegiatan')->searchable(),
            Tables\Columns\TextColumn::make('location')->label('Lokasi'),
            Tables\Columns\TextColumn::make('start_time')->label('Waktu Mulai')->dateTime()->sortable(),
            Tables\Columns\TextColumn::make('rayon.name')->label('Penyelenggara')->badge(),
        ])->defaultSort('start_time', 'desc');
    }
    // ... (getPages() dan getRelations() tetap sama) ...


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
