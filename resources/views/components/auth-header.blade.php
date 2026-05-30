@props([
    'title',
    'description',
])

<div class="flex w-full max-w-xl flex-col items-start gap-3 text-left">
    <flux:heading size="xl" class="text-slate-900 dark:text-white">{{ $title }}</flux:heading>
    <flux:subheading class="text-slate-500 dark:text-slate-400">{{ $description }}</flux:subheading>
</div>
