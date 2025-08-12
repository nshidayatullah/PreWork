<?php

namespace App\Filament\Resources\RosterResource\Pages;

use App\Models\Roster;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\RosterResource;

class CreateRoster extends CreateRecord
{
    protected static string $resource = RosterResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Kita tidak perlu unset apa pun jika semua field sesuai form schema
        return $data;
    }

    protected function handleRecordCreation(array $data): Roster
    {
        // Pisahkan data utama dan relasi
        $details = $data['details'] ?? [];
        unset($data['details']);

        // Simpan data utama ke tabel rosters
        $roster = Roster::create($data);

        // Simpan detail roster harian melalui relasi
        $roster->details()->createMany($details);

        return $roster;
    }
}
