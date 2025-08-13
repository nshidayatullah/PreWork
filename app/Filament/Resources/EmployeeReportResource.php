<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeReportResource\Pages;
use App\Models\Employee;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EmployeeReportResource extends Resource
{
    protected static ?string $model = Employee::class;
    protected static ?string $navigationIcon = 'heroicon-s-document-chart-bar';
    protected static ?string $navigationLabel = 'Laporan Karyawan';
    protected static ?string $modelLabel = 'Laporan Karyawan';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $pluralModelLabel = 'Laporan Karyawan';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('NRP')
                    ->label('NRP')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('Name')
                    ->label('Nama Karyawan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('Position')
                    ->label('Jabatan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('Departement')
                    ->label('Departemen')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('Status')
                    ->label('Status')
                    ->colors([
                        'success' => true,
                        'danger' => false,
                    ])
                    ->formatStateUsing(fn ($state) => $state ? 'Aktif' : 'Tidak Aktif'),
            ])
            ->actions([
                Tables\Actions\Action::make('view_report')
                    ->label('Lihat Laporan')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->modalHeading(fn ($record) => 'Laporan Karyawan - ' . $record->Name)
                    ->modalWidth('5xl')
                    ->modalContent(function ($record) {
                        // Ambil data roster bulan ini dengan sorting ascending
                        $rosters = \App\Models\Roster::where('employee_id', $record->id)
                            ->whereMonth('date', now()->month)
                            ->whereYear('date', now()->year)
                            ->orderBy('date', 'asc')
                            ->get();

                        // Ambil data tekanan darah bulan ini
                        $bloodPressures = \App\Models\BloodPressure::where('employee_id', $record->id)
                            ->whereMonth('date', now()->month)
                            ->whereYear('date', now()->year)
                            ->get()
                            ->keyBy(function ($item) {
                                return $item->date->format('Y-m-d');
                            });



                        return view('filament.components.blood-pressure-history', [
                            'rosters' => $rosters,
                            'bloodPressures' => $bloodPressures,
                            'employee' => $record
                        ]);
                    })
                    ->modalActions([
                        Tables\Actions\Action::make('download_pdf')
                            ->label('Download PDF')
                            ->icon('heroicon-o-arrow-down-tray')
                            ->color('success')
                            ->action(function ($record) {
                                return redirect()->route('employee.report.pdf', ['employee' => $record->id]);
                            }),
                    ]),
            ])
            ->defaultSort('Name');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployeeReports::route('/'),
        ];
    }
}
