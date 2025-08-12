<?php

namespace App\Filament\Resources\RosterResource\Pages;

use App\Filament\Resources\RosterResource;
use App\Models\Roster;
use Filament\Pages\Page;
use Filament\Forms;
use Illuminate\Support\Carbon;

class EditRoster extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public $employee;
    public $month;
    public $shifts = [];

    protected static string $resource = RosterResource::class;
    protected static string $view = 'filament.resources.roster-resource.pages.edit-roster';

    public function mount($record)
    {
        $this->employee = \App\Models\Employee::findOrFail($record);
        $this->month = now()->format('Y-m');

        // ambil data roster yang sudah ada
        $rosters = $this->employee->rosters()
            ->whereMonth('date', Carbon::parse($this->month)->month)
            ->whereYear('date', Carbon::parse($this->month)->year)
            ->get();

        foreach (range(1, Carbon::parse($this->month)->daysInMonth) as $day) {
            $tanggal = Carbon::parse("{$this->month}-{$day}")->format('Y-m-d');
            $this->shifts[$day] = optional(
                $rosters->firstWhere('date', $tanggal)
            )->shift ?? '';
        }
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(7)
                    ->schema(
                        collect(range(1, Carbon::parse($this->month)->daysInMonth))->map(function ($day) {
                            return Forms\Components\TextInput::make("shifts.{$day}")
                                ->label(str_pad($day, 2, '0', STR_PAD_LEFT))
                                ->maxLength(2);
                        })->toArray()
                    )
            ]);
    }

    public function submit()
    {
        foreach ($this->shifts as $day => $shift) {
            $tanggal = Carbon::parse("{$this->month}-{$day}")->format('Y-m-d');
            if ($shift) {
                Roster::updateOrCreate(
                    [
                        'employee_id' => $this->employee->id,
                        'date' => $tanggal,
                    ],
                    ['shift' => $shift]
                );
            }
        }

        $this->redirect(RosterResource::getUrl('index'));
    }
}
