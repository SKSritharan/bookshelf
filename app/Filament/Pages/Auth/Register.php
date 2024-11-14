<?php

namespace App\Filament\Pages\Auth;

use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Database\Eloquent\Model;

class Register extends BaseRegister
{
    protected function handleRegistration(array $data): Model
    {
        $user = $this->getUserModel()::create($data);
//        $member = $user->member()->create([
//            'membership_date' => now(),
//        ]);
//
//        $user->assignRole('member');

        $superAdmins = $this->getUserModel()::whereHas('roles', function ($query) {
            $query->where('name', 'super_admin');
        })->get();

        foreach ($superAdmins as $superAdmin) {
            $superAdmin->notify(
                Notification::make()
                    ->title('New User Registration')
                    ->body($user->name.' has registered')
                    ->actions([
                        Action::make('View User')
                            ->button()
                            ->url(route('filament.resources.users.edit', $user))
                            ->markAsRead(),
                    ])
                    ->toDatabase()
            );
        }

        return $user;
    }
}
