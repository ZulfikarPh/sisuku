<?php

namespace App\Filament\Resources\KomisariatProfileResource\Pages;

use App\Filament\Resources\KomisariatProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKomisariatProfile extends EditRecord
{
    protected static string $resource = KomisariatProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
