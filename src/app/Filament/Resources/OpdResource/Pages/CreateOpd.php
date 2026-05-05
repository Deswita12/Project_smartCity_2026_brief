<?php

namespace App\Filament\Resources\OpdResource\Pages;

use App\Filament\Resources\OpdResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateOpd extends CreateRecord
{
    protected static string $resource = OpdResource::class;

    protected function afterCreate(): void
    {
        $data = $this->data;

        $this->record->user()->create([
            'name' => $data['user']['name'],
            'email' => $data['user']['email'],
            'password' => Hash::make($data['user']['password']),
        ]);
    }
}