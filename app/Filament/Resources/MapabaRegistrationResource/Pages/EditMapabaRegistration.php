<?php

namespace App\Filament\Resources\MapabaRegistrationResource\Pages;

use App\Filament\Resources\MapabaRegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMapabaRegistration extends EditRecord
{
    protected static string $resource = MapabaRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
