<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PeriodReportResource\Pages;
use App\Models\Roster;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class PeriodReportResource extends Resource
{
    protected static ?string $model = Roster::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Laporan Periode';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $pluralModelLabel = 'Laporan Periode';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canView($record): bool
    {
        return false;
    }

    /**
     * Custom query untuk generate laporan
     */
    public static function getEloquentQuery(): Builder
    {
        return static::getModel()::query()
            ->selectRaw("
                REPLACE(rosters.date, '-', '') as id,
                rosters.date as tanggal,
                COUNT(DISTINCT rosters.employee_id) as total_karyawan_shift,
                COUNT(DISTINCT bp.employee_id) as sudah_periksa,
                (COUNT(DISTINCT rosters.employee_id) - COUNT(DISTINCT bp.employee_id)) as belum_periksa,
                ROUND(
                    CASE
                        WHEN COUNT(DISTINCT rosters.employee_id) > 0
                        THEN (COUNT(DISTINCT bp.employee_id) * 100.0 / COUNT(DISTINCT rosters.employee_id))
                        ELSE 0
                    END, 1
                ) as persentase_periksa
            ")
            ->leftJoin('blood_pressures as bp', function($join) {
                $join->on('rosters.employee_id', '=', 'bp.employee_id')
                     ->on('rosters.date', '=', 'bp.date');
            })
            ->whereIn('rosters.shift', ['shift 1', 'shift 2'])
            ->whereBetween('rosters.date', ['2025-08-01', '2025-08-31'])
            ->groupBy('rosters.date')
            ->orderBy('rosters.date', 'asc');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('total_karyawan_shift')
                    ->label('Total Karyawan Shift 1&2')
                    ->numeric()
                    ->alignCenter()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('sudah_periksa')
                    ->label('Sudah Periksa')
                    ->numeric()
                    ->alignCenter()
                    ->color('success')
                    ->icon('heroicon-m-check-circle'),

                Tables\Columns\TextColumn::make('belum_periksa')
                    ->label('Belum Periksa')
                    ->numeric()
                    ->alignCenter()
                    ->color('danger')
                    ->icon('heroicon-m-x-circle'),

                Tables\Columns\TextColumn::make('persentase_periksa')
                    ->label('Persentase')
                    ->alignCenter()
                    ->formatStateUsing(fn($state) => number_format($state, 1) . '%')
                    ->badge()
                    ->color(fn($state) => match(true) {
                        $state >= 90 => 'success',
                        $state >= 70 => 'warning',
                        default => 'danger'
                    }),
            ])
            ->filters([
                Tables\Filters\Filter::make('periode')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal')
                            ->label('Dari Tanggal')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->default('2025-08-01'),

                        Forms\Components\DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->default('2025-08-31'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn(Builder $query, $date): Builder => $query->where('rosters.date', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn(Builder $query, $date): Builder => $query->where('rosters.date', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['dari_tanggal'] ?? null) {
                            $indicators[] = 'Dari: ' . Carbon::parse($data['dari_tanggal'])->format('d/m/Y');
                        }
                        if ($data['sampai_tanggal'] ?? null) {
                            $indicators[] = 'Sampai: ' . Carbon::parse($data['sampai_tanggal'])->format('d/m/Y');
                        }
                        return $indicators;
                    }),
            ])
            ->actions([])
            ->bulkActions([])
            ->defaultSort('tanggal', 'asc')
            ->striped()
            ->paginated([25, 50, 100]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPeriodReports::route('/'),
        ];
    }
}
