<?php

new class extends \Livewire\Component
{
    public $container;

    public function mount($container)
    {
        $this->container = $container;
    }
};
?>

<div>
    <h1 class="text-2xl font-bold mb-4">{{ $container->container_number }}</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <h2 class="text-lg font-semibold">Container Details</h2>
            <p><strong>Booking Number:</strong> {{ $container->booking_number }}</p>
            <p><strong>Seal Number:</strong> {{ $container->seal_number }}</p>
            <p><strong>Container Type:</strong> {{ $container->container_type }}</p>
            <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $container->status)) }}</p>
            <p><strong>Origin Port:</strong> {{ $container->origin_port }}</p>
            <p><strong>Destination Port:</strong> {{ $container->destination_port }}</p>
            <p><strong>Arrival Date:</strong> {{ $container->arrival_date ? $container->arrival_date->format('Y-m-d') : 'N/A' }}</p>
            <p><strong>Departure Date:</strong> {{ $container->departure_date ? $container->departure_date->format('Y-m-d') : 'N/A' }}</p>
        </div>

        <div>
            <h2 class="text-lg font-semibold">Parties Involved</h2>
            <p><strong>Transitor:</strong> {{ $container->transitor->name }}</p>
            <p><strong>Shipping Company:</strong> {{ $container->shipping->name }}</p>
            @if($container->customer)
                <p><strong>Customer:</strong> {{ $container->customer->name }}</p>
            @endif
            @if($container->shipper)
                <p><strong>Shipper:</strong> {{ $container->shipper->name }}</p>
            @endif
            @if($container->vessel)
                <p><strong>Vessel:</strong> {{ $container->vessel->name }}</p>
                @if($container->voyage_number)
                    <p><strong>Voyage Number:</strong> {{ $container->voyage_number }}</p>
                @endif
            @endif
        </div>
        <div>
            
        </div>
</div>