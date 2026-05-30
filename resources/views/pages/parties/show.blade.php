<?php

new #[\Livewire\Attributes\Layout('components.layouts.app')] class extends \Livewire\Component
{
    public $partyId;

    #[\Livewire\Attributes\Computed]
    public function party()
    {
        return \App\Models\Party::with(['vesselShipping'])->findOrFail($this->partyId);
    }

    public function mount($id)
    {
        $this->partyId = $id;
    }
};
?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold">{{ $this->party->name }}</h1>
            <p class="text-zinc-500 dark:text-zinc-400 mt-1">{{ ucfirst(str_replace('_', ' ', $this->party->type)) }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('parties') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md bg-zinc-100 dark:bg-zinc-700 text-zinc-900 dark:text-white hover:bg-zinc-200 dark:hover:bg-zinc-600 transition">
                ← Back to Parties
            </a>
            <flux:button variant="primary" wire:click="$emit('showAddPartyForm', {{ $this->party->id }})">Edit</flux:button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Details</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Address</p>
                    <p class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $this->party->address ?: '—' }}</p>
                </div>
                <div>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Contact Number</p>
                    <p class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $this->party->contact_number ?: '—' }}</p>
                </div>
                @if($this->party->type === 'vessel' && $this->party->vesselShipping)
                    <div>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">Shipping Agent</p>
                        <p class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $this->party->vesselShipping->name }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
            <h3 class="text-sm font-semibold text-zinc-900 dark:text-white uppercase tracking-wide mb-4">Quick Actions</h3>
            <div class="flex flex-col gap-2">
                <flux:button variant="subtle" wire:click="$emit('showAddPartyForm', {{ $this->party->id }})">Edit Party</flux:button>
                <flux:button variant="danger" wire:confirm="Are you sure you want to delete {{ $this->party->name }}?" wire:click="$emit('deleteParty', {{ $this->party->id }})">Delete Party</flux:button>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white uppercase tracking-wide mb-4">Related Containers</h3>
        <div class="text-sm text-zinc-600 dark:text-zinc-400">
            @php
                $containers = \App\Models\container::where('transitor_id', $this->party->id)
                    ->orWhere('shipping_id', $this->party->id)
                    ->orWhere('customer_id', $this->party->id)
                    ->orWhere('shipper_id', $this->party->id)
                    ->orWhere('vessel_id', $this->party->id)
                    ->limit(8)
                    ->get();
            @endphp

            @if($containers->isEmpty())
                <p class="text-zinc-500">No related containers found.</p>
            @else
                <ul class="space-y-2">
                    @foreach($containers as $c)
                        <li class="flex items-center justify-between">
                            <div>
                                <div class="font-medium text-zinc-900 dark:text-white">{{ $c->container_number }}</div>
                                <div class="text-xs text-zinc-500">{{ $c->booking_number }} • {{ ucfirst(str_replace('_',' ', $c->status)) }}</div>
                            </div>
                            <a href="{{ route('container.show', $c->id) }}" class="text-sm text-blue-600 dark:text-blue-400">View</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>