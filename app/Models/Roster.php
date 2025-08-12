<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Roster extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'month',
        'date',
        'shift',
    ];

    protected $casts = [
        'month' => 'date',
        'date' => 'date',
    ];

    /**
     * Relasi ke model Employee
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('employee');
    }

        protected static function boot()
    {
        parent::boot();

        static::creating(function ($roster) {
            if (empty($roster->month) && !empty($roster->date)) {
                $roster->month = Carbon::parse($roster->date)->format('Y-m');
            }
        });

    }

    public function bloodPressures()
    {
        return $this->hasMany(BloodPressure::class, 'employee_id', 'employee_id')
            ->whereColumn('tanggal', 'rosters.tanggal');
    }

}




