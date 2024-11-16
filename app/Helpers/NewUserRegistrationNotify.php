<?php

namespace App\Helpers;

use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class NewUserRegistrationNotify
{
    public static function sendNotification($user)
    {
        $superAdmins = $user::whereHas('roles', function ($query) {
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
                            ->url(route('filament.admin.user-management.resources.users.edit', $user))
                            ->markAsRead(),
                    ])
                    ->toDatabase()
            );
        }
    }
}
