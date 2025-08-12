<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model

{
    use HasFactory;


    protected $fillable = [
        'Name',
        'NRP',
        'Position',
        'Departement',
        'Company',
        'Status',
    ];

    public function getStatusAttribute($value)
{
    return $value === 'open'; // Mengembalikan true jika status 'open'
}

public function setStatusAttribute($value)
{
    $this->attributes['Status'] = $value ? 'open' : 'close'; // Mengatur status ke 'open' atau 'close'
}

public function rosters()
{
    return $this->hasMany(Roster::class);
}

public function bloodPressures()
{
    return $this->hasMany(BloodPressure::class);
}
public function todayBloodPressure()
{
    return $this->hasOne(BloodPressure::class)->whereDate('date', today());
}

}
