<?php

new #[\Livewire\Attributes\Layout('components.layouts.app')] class extends \Livewire\Component
{
    public $containerId;

    #[\Livewire\Attributes\Computed]
    public function container()
    {
        return \App\Models\container::with(['transitor', 'shipping', 'customer', 'shipper', 'vessel'])->findOrFail($this->containerId);
    }

    public function mount($id)
    {
        $this->containerId = $id;
    }
};
?>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold">{{ $this->container->container_number }}</h1>
            <p class="text-zinc-500 dark:text-zinc-400 mt-1">Container Details</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('containers') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md bg-zinc-100 dark:bg-zinc-700 text-zinc-900 dark:text-white hover:bg-zinc-200 dark:hover:bg-zinc-600 transition">
                ← Back to Containers
            </a>
            <a href="{{ route('containers') }}" wire:navigate class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md bg-blue-600 text-white hover:bg-blue-700 transition">
                Edit Container
            </a>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="flex items-center gap-3">
        <span class="text-sm text-zinc-600 dark:text-zinc-400">Status:</span>
        @php
            $statusClasses = [
                'in_transit' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
                'arrived' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
                'departed' => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200',
                'delayed' => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
            ];
            $badgeClass = $statusClasses[$this->container->status] ?? 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200';
        @endphp
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $badgeClass }}">
            {{ ucfirst(str_replace('_', ' ', $this->container->status)) }}
        </span>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Container Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Basic Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-1">Container Number</p>
                        <p class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $this->container->container_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-1">Booking Number</p>
                        <p class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $this->container->booking_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-1">Seal Number</p>
                        <p class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $this->container->seal_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-1">Container Type</p>
                        <p class="text-lg font-semibold text-zinc-900 dark:text-white">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                {{ strtoupper(str_replace('_', ' ', $this->container->container_type)) }}
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Additional Details -->
                <div class="mt-6 pt-6 border-t border-zinc-200 dark:border-zinc-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-1">SOC</p>
                            <p class="text-zinc-900 dark:text-white">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->container->is_soc ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}">
                                    {{ $this->container->is_soc ? 'Yes' : 'No' }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-1">Group Container</p>
                            <p class="text-zinc-900 dark:text-white">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->container->is_group_container ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}">
                                    {{ $this->container->is_group_container ? 'Yes' : 'No' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Route Information -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Route Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-1">Origin Port</p>
                        <p class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $this->container->origin_port }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-1">Destination Port</p>
                        <p class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $this->container->destination_port }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-1">Voyage Number</p>
                        <p class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $this->container->voyage_number ?? '—' }}</p>
                    </div>
                </div>
            </div>

            <!-- Dates -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Dates</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-1">Arrival Date</p>
                        <p class="text-lg font-semibold text-zinc-900 dark:text-white">
                            {{ $this->container->arrival_date ? $this->container->arrival_date->format('M d, Y') : '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-1">Departure Date</p>
                        <p class="text-lg font-semibold text-zinc-900 dark:text-white">
                            {{ $this->container->departure_date ? $this->container->departure_date->format('M d, Y') : '—' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Parties Involved -->
        <div class="space-y-6">
            <!-- Transitor -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
                <h3 class="text-sm font-semibold text-zinc-900 dark:text-white uppercase tracking-wide mb-4">Transitor</h3>
                <div class="space-y-2">
                    <p class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $this->container->transitor?->name ?? '—' }}</p>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $this->container->transitor?->address ?? '—' }}</p>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $this->container->transitor?->contact_number ?? '—' }}</p>
                </div>
            </div>

            <!-- Shipping Company -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
                <h3 class="text-sm font-semibold text-zinc-900 dark:text-white uppercase tracking-wide mb-4">Shipping Company</h3>
                <div class="space-y-2">
                    <p class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $this->container->shipping?->name ?? '—' }}</p>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $this->container->shipping?->address ?? '—' }}</p>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $this->container->shipping?->contact_number ?? '—' }}</p>
                </div>
            </div>

            <!-- Customer (if available) -->
            @if($this->container->customer)
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
                    <h3 class="text-sm font-semibold text-zinc-900 dark:text-white uppercase tracking-wide mb-4">Customer</h3>
                    <div class="space-y-2">
                        <p class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $this->container->customer->name }}</p>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $this->container->customer->address ?? '—' }}</p>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $this->container->customer->contact_number ?? '—' }}</p>
                    </div>
                </div>
            @endif

            <!-- Shipper (if available) -->
            @if($this->container->shipper)
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
                    <h3 class="text-sm font-semibold text-zinc-900 dark:text-white uppercase tracking-wide mb-4">Shipper</h3>
                    <div class="space-y-2">
                        <p class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $this->container->shipper->name }}</p>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $this->container->shipper->address ?? '—' }}</p>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $this->container->shipper->contact_number ?? '—' }}</p>
                    </div>
                </div>
            @endif

            <!-- Vessel (if available) -->
            @if($this->container->vessel)
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
                    <h3 class="text-sm font-semibold text-zinc-900 dark:text-white uppercase tracking-wide mb-4">Vessel</h3>
                    <div class="space-y-2">
                        <p class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $this->container->vessel->name }}</p>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $this->container->vessel->address ?? '—' }}</p>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $this->container->vessel->contact_number ?? '—' }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
