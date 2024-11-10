<?php

namespace App\Http\Middleware;

use App\Filament\Pages\Auth\UserVerification;
use Closure;
use Filament\Pages\Auth\EmailVerification\EmailVerificationPrompt;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserApproval
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || $request->routeIs('*logout')) {
            return $next($request);
        }

        if ($user->approved_at || !$user->hasVerifiedEmail() || $request->routeIs('*.user-verification')) {
            return $next($request);
        }

        return redirect()->to(UserVerification::getUrl());
    }
}
