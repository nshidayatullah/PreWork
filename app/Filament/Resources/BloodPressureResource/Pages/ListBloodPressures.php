<?php

namespace App\Filament\Resources\BloodPressureResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\BloodPressureResource;
use App\Filament\Resources\BloodPressureResource\Widgets\BloodPresureOverview;

class ListBloodPressures extends ListRecords
{
    protected static string $resource = BloodPressureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            BloodPresureOverview::class,
        ];
    }
}
