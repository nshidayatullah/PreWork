<?php

namespace App\Filament\Resources\EmployeeShiftResource\Pages;

use App\Filament\Resources\EmployeeShiftResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEmployeeShift extends ViewRecord
{
    protected static string $resource = EmployeeShiftResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Kembali')
                ->url($this->getResource()::getUrl('index'))
                ->icon('heroicon-o-arrow-left')
                ->color('gray'),

        ];
    }

    public function getTitle(): string
    {
        return 'Detail Karyawan : ' . $this->record->Name;
    }

    public function getSubheading(): string
    {
        $currentShift = $this->record->rosters
            ->where('date', today()->format('Y-m-d'))
            ->first();

        $shiftText = $currentShift ? $this->getShiftLabel($currentShift->shift) : 'Tidak ada shift';

        $bloodPressureToday = \App\Models\BloodPressure::where('employee_id', $this->record->id)
            ->whereDate('date', today())
            ->first();

        $bpStatus = $bloodPressureToday ?
            " | TD: {$bloodPressureToday->sistole}/{$bloodPressureToday->diastole}" :
            " | Belum ada data TD hari ini";

        return "NRP: {$this->record->NRP} | {$this->record->Position} | Shift: {$shiftText}{$bpStatus}";
    }

    private function getShiftLabel(string $shift): string
    {
        return match($shift) {
            'shift 1' => 'S1',
            'shift 2' => 'S2',
            'off' => 'Off',
            'cuti' => 'C',
            'dirumahkan' => 'H',
            'training' => 'TR',
            'tugas luar' => 'TL',
            default => $shift
        };
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load relasi yang diperlukan
        $this->record->load([
            'rosters' => function ($query) {
                $query->latest('date')->limit(7);
            },
            'bloodPressures' => function ($query) {
                $query->latest('date')->limit(5);
            }
        ]);

        return $data;
    }
}
