<?php

namespace App\Filament\Resources\RosterResource\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\RosterResource;
use App\Filament\Resources\RosterResource\Widgets\RosterOverview;

class ListRosters extends ListRecords
{
    protected static string $resource = RosterResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            RosterOverview::class,
        ];
    }

}
