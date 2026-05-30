@props([
    'status',
])

@if ($status)
    <div {{ $attributes->merge(['class' => 'rounded-2xl border border-emerald-200/70 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 shadow-sm dark:border-emerald-800/70 dark:bg-emerald-950/20 dark:text-emerald-300']) }}>
        {{ $status }}
    </div>
@endif
