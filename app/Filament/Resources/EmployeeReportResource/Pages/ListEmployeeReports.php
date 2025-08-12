<?php

namespace App\Filament\Resources\EmployeeReportResource\Pages;

use App\Filament\Resources\EmployeeReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListEmployeeReports extends ListRecords
{
    protected static string $resource = EmployeeReportResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\Action::make('export_all')
    //             ->label('Export Semua')
    //             ->icon('heroicon-o-arrow-down-tray')
    //             ->color('success')
    //             ->action(function () {
    //                 // Logic untuk export semua data
    //                 // Bisa menggunakan Laravel Excel atau lainnya
    //             }),
    //     ];
    // }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Karyawan')
                ->badge(\App\Models\Employee::count()),

            'active' => Tab::make('Ovservasi Aktif')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('Status', true))
                ->badge(\App\Models\Employee::where('Status', true)->count()),

            'inactive' => Tab::make('Observasi Nonaktif')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('Status', false))
                ->badge(\App\Models\Employee::where('Status', false)->count()),
        ];
    }

    public function getTitle(): string
    {
        return 'Laporan Karyawan - ' . now()->format('F Y');
    }
}
