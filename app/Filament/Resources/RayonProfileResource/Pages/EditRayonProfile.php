<?php

namespace App\Filament\Resources\RayonProfileResource\Pages;

use App\Filament\Resources\RayonProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRayonProfile extends EditRecord
{
    protected static string $resource = RayonProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
