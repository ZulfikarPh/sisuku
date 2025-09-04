<?php

namespace App\Filament\Resources\KomisariatProfileResource\Pages;

use App\Filament\Resources\KomisariatProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKomisariatProfiles extends ListRecords
{
    protected static string $resource = KomisariatProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
