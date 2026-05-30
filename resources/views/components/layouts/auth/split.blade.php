<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
        <div class="relative min-h-screen overflow-hidden">
            <div class="pointer-events-none absolute inset-x-0 top-0 h-64 bg-gradient-to-b from-slate-950/90 via-slate-950/40 to-transparent"></div>
            <div class="relative mx-auto grid min-h-screen max-w-7xl gap-8 px-4 py-10 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:px-8">
                <div class="hidden overflow-hidden rounded-[32px] border border-slate-700/40 bg-slate-900/90 p-10 shadow-[0_40px_120px_-40px_rgba(0,0,0,0.45)] backdrop-blur-xl lg:flex lg:flex-col lg:justify-between">
                    <div class="space-y-6">
                        <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-900 text-white shadow-sm">
                            <x-app-logo-icon class="h-6 w-6 fill-current" />
                        </div>
                        <div class="space-y-3">
                            <h2 class="text-3xl font-semibold tracking-tight text-white">Modern access</h2>
                            <p class="text-sm leading-6 text-slate-400">A refined sign-in experience with calm visuals, clear structure, and an uncluttered page layout.</p>
                        </div>
                    </div>
                    <div class="space-y-4 text-sm leading-6 text-slate-400">
                        <p class="uppercase tracking-[0.24em] text-slate-500">Designed for focus</p>
                        <ul class="space-y-3">
                            <li class="flex gap-3"><span class="mt-1 h-2.5 w-2.5 rounded-full bg-slate-700"></span><span>Soft contrast and spacious cards.</span></li>
                            <li class="flex gap-3"><span class="mt-1 h-2.5 w-2.5 rounded-full bg-slate-700"></span><span>Helpful details without distractions.</span></li>
                            <li class="flex gap-3"><span class="mt-1 h-2.5 w-2.5 rounded-full bg-slate-700"></span><span>Built for fast mobile and desktop flow.</span></li>
                        </ul>
                    </div>
                </div>
                <div class="flex flex-col justify-center overflow-hidden rounded-[32px] border border-slate-700/40 bg-slate-950/95 p-8 shadow-[0_24px_80px_-24px_rgba(0,0,0,0.45)] backdrop-blur-xl lg:p-10">
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-3 self-start text-base font-semibold text-white transition hover:text-slate-200" wire:navigate>
                            <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-700 text-white shadow-sm dark:bg-slate-100 dark:text-slate-950">
                                <x-app-logo-icon class="h-6 w-6 fill-current" />
                            </span>
                            <span>{{ config('app.name', 'Laravel') }}</span>
                        </a>
                        <div class="lg:hidden">
                            <p class="text-sm text-slate-400">{{ config('app.name', 'Laravel') }} — sign in securely with a calm, modern layout.</p>
                        </div>
                    </div>
                    <div class="mt-8">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
