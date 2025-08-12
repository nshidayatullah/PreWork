<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BloodPressure extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'sistole',
        'diastole',
        'medications',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'medications' => 'array',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    // Accessor untuk menampilkan tekanan darah
    public function getBloodPressureAttribute(): string
    {
        return $this->sistole . '/' . $this->diastole;
    }

    // Accessor untuk status dengan warna
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'Fit To Work' => 'success',
            'Fit With Medical Therapy' => 'warning',
            'Unfit' => 'danger',
            'Observasi' => 'info',
            default => 'gray'
        };
    }
}
