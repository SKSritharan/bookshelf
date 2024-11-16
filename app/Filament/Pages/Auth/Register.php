<?php

namespace App\Filament\Pages\Auth;

use App\Helpers\NewUserRegistrationNotify;
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
        $newUserRegisterNotify = new NewUserRegistrationNotify();
        $newUserRegisterNotify->sendNotification($user);

        return $user;
    }
}
