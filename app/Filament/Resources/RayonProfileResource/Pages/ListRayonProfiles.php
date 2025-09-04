<?php

namespace App\Filament\Resources\RayonProfileResource\Pages;

use App\Filament\Resources\RayonProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRayonProfiles extends ListRecords
{
    protected static string $resource = RayonProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
