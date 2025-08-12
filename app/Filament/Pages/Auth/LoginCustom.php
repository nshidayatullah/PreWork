<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Page;
use Filament\Pages\Auth\Login;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Illuminate\Validation\ValidationException;

class LoginCustom extends Login
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getLoginFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

        protected function getLoginFormComponent(): Component
    {
        return TextInput::make('nrp')
            ->label(__('NRP'))
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

        protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'nrp' => $data['nrp'],
            'password' => $data['password'],
        ];
    }

        protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.nrp' => __('filament-panels::pages/auth/login.messages.failed'),
        ]);
    }
}
