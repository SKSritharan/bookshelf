<?php

namespace App\Filament\Pages\Auth;

use Filament\Facades\Filament;
use Filament\Pages\Concerns\HasRoutes;
use Filament\Pages\SimplePage;

class UserVerification extends SimplePage
{
    use HasRoutes;

    protected static string $view = 'filament.pages.auth.user-verification';
    protected static bool $shouldRegisterNavigation = false;

    public function mount(): void
    {
        $user = Filament::auth()->user();
        if ($user->approved_at) {
            redirect()->intended(Filament::getUrl());
        }
    }

    public static function getUrl(array $parameters = [], bool $isAbsolute = true, ?string $panel = null)
    {
        if (blank($panel) || Filament::getPanel($panel)->hasTenancy()) {
            $parameters['tenant'] ??= ($tenant ?? Filament::getTenant());
        }

        return route(static::getRouteName($panel), $parameters, $isAbsolute);
    }

    public static function getRouteName(?string $panel = null): string
    {
        $panel = $panel ? Filament::getPanel($panel) : Filament::getCurrentPanel();

        $routeName = static::getRelativeRouteName();

        return $panel->generateRouteName($routeName);
    }

    public static function registerNavigationItems(): void
    {
        return;
    }
}
