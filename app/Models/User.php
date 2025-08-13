<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'name',
        'nrp',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Method wajib untuk FilamentUser
    public function canAccessPanel(Panel $panel): bool
    {
        // Karena panel ID Anda adalah 'medic'
        if ($panel->getId() === 'medic') {
            return true; // Izinkan semua user yang berhasil login

            // Atau jika ingin berdasarkan role (karena Anda pakai Spatie):
            // return $this->hasRole(['admin', 'medic', 'doctor']); // sesuaikan role
        }

        return false;
    }
}
