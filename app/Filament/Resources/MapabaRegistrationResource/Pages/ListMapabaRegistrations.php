<?php

namespace App\Filament\Resources\MapabaRegistrationResource\Pages;

use App\Filament\Resources\MapabaRegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMapabaRegistrations extends ListRecords
{
    protected static string $resource = MapabaRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
