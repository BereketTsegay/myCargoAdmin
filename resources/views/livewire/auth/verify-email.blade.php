<x-layouts.auth>
    <div class="flex flex-col gap-8">
        <x-auth-header :title="__('Verify your email')" :description="__('Please verify your email address by clicking the link we just emailed to you.')" />

        <div class="space-y-4 text-center text-sm text-slate-600 dark:text-slate-400">
            <p>{{ __('If you didn\'t receive the email, we can send you another one.') }}</p>
            @if (session('status') == 'verification-link-sent')
                <p class="font-medium text-slate-900 dark:text-slate-100">{{ __('A new verification link has been sent to the email address you provided during registration.') }}</p>
            @endif
        </div>

        <div class="grid gap-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <flux:button type="submit" variant="primary" class="w-full">
                    {{ __('Resend verification email') }}
                </flux:button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <flux:button variant="ghost" type="submit" class="w-full text-sm text-slate-600 dark:text-slate-400" data-test="logout-button">
                    {{ __('Log out') }}
                </flux:button>
            </form>
        </div>
    </div>
</x-layouts.auth>
