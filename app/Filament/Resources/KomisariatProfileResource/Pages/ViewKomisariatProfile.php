<?php

namespace App\Filament\Resources\KomisariatProfileResource\Pages;

use App\Filament\Resources\KomisariatProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKomisariatProfile extends ViewRecord
{
    protected static string $resource = KomisariatProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
