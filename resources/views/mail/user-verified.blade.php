<x-mail::message>

    # Account Verified

    Hello {{ $user->name }},

    We are pleased to inform you that your account has been verified successfully.

    <x-mail::button :url="config('app.url') . '/admin/login'">
        Login to Your Account
    </x-mail::button>

    Thank you for being a part of our community!

    Best regards,
    {{ config('app.name') }}
</x-mail::message>
