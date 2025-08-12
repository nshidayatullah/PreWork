<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeriodReport extends Model
{
    // Gunakan tabel dummy atau view
    protected $table = 'period_reports_view';

    protected $fillable = [
        'tanggal',
        'total_karyawan_shift',
        'sudah_periksa',
        'belum_periksa',
        'persentase_periksa'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total_karyawan_shift' => 'integer',
        'sudah_periksa' => 'integer',
        'belum_periksa' => 'integer',
        'persentase_periksa' => 'decimal:1'
    ];

    public $timestamps = false;

    /**
     * Boot the model - create temporary data when accessed
     */
    protected static function boot()
    {
        parent::boot();

        // Hook untuk generate data saat model di-boot
        static::addGlobalScope('generate_data', function ($builder) {
            // Logic akan dipindah ke scope atau accessor
        });
    }

    /**
     * Generate data laporan periode dan simpan ke tabel sementara
     */
    public static function refreshReportData($startDate = null, $endDate = null)
    {
        // Default periode bulan ini jika tidak ada filter
        $startDate = $startDate ?: Carbon::now()->startOfMonth();
        $endDate = $endDate ?: Carbon::now()->endOfMonth();

        // Pastikan format tanggal
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        // Hapus data lama dan generate yang baru
        DB::statement('DROP TABLE IF EXISTS period_reports_view');

        // Create temporary table
        DB::statement('
            CREATE TEMPORARY TABLE period_reports_view (
                id INT AUTO_INCREMENT PRIMARY KEY,
                tanggal DATE,
                total_karyawan_shift INT,
                sudah_periksa INT,
                belum_periksa INT,
                persentase_periksa DECIMAL(4,1)
            )
        ');

        $reports = [];

        // Loop setiap tanggal dalam periode
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $currentDate = $date->format('Y-m-d');

            // Hitung total karyawan shift 1 & 2 pada tanggal ini
            $totalKaryawanShift = DB::table('rosters')
                ->where('date', $currentDate)
                ->whereIn('shift', ['1', '2']) // Sesuaikan dengan format shift di database
                ->distinct('employee_id')
                ->count();

            // Hitung yang sudah periksa (ada di blood_pressures dan roster shift 1&2)
            $sudahPeriksa = DB::table('blood_pressures as bp')
                ->join('rosters as r', function($join) use ($currentDate) {
                    $join->on('bp.employee_id', '=', 'r.employee_id')
                         ->where('r.date', $currentDate)
                         ->whereIn('r.shift', ['1', '2']);
                })
                ->where('bp.date', $currentDate)
                ->distinct('bp.employee_id')
                ->count();

            $belumPeriksa = $totalKaryawanShift - $sudahPeriksa;
            $persentase = $totalKaryawanShift > 0
                ? round(($sudahPeriksa / $totalKaryawanShift) * 100, 1)
                : 0;

            // Hanya tambahkan jika ada karyawan shift 1&2 pada hari itu
            if ($totalKaryawanShift > 0) {
                $reports[] = [
                    'tanggal' => $currentDate,
                    'total_karyawan_shift' => $totalKaryawanShift,
                    'sudah_periksa' => $sudahPeriksa,
                    'belum_periksa' => $belumPeriksa,
                    'persentase_periksa' => $persentase,
                ];
            }
        }

        // Insert ke temporary table
        if (!empty($reports)) {
            DB::table('period_reports_view')->insert($reports);
        }

        return collect($reports);
    }

    /**
     * Scope untuk filter tanggal
     */
    public function scopeFilterPeriode($query, $startDate = null, $endDate = null)
    {
        // Refresh data dengan filter
        self::refreshReportData($startDate, $endDate);

        return $query;
    }
}
