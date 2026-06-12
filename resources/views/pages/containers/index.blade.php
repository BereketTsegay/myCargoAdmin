<?php

new #[\Livewire\Attributes\Layout('components.layouts.app')] class extends \Livewire\Component
{
    use \Livewire\WithPagination;

    public $title = 'Show Containers';
    public \App\Models\container $container;
    public $search = '';
    public $filterStatus = 'all';
    public $filterType = 'all';
    public $filterTransitor = '';
    public $filterShipping = '';
    public $filterShipper = '';
    public \App\Livewire\Forms\ContainerForm $form;
    public $showModal = false;
    public function with(): array
    {
        $query = \App\Models\container::query();

        // Apply search filter (including related models)
        if ($this->search) {
            $s = '%' . $this->search . '%';
            $query->where(function ($q) use ($s) {
                $q->where('container_number', 'like', $s)
                    ->orWhere('booking_number', 'like', $s)
                    ->orWhere('seal_number', 'like', $s)
                    ->orWhereHas('transitor', function ($qq) use ($s) {
                        $qq->where('name', 'like', $s);
                    })
                    ->orWhereHas('shipping', function ($qq) use ($s) {
                        $qq->where('name', 'like', $s);
                    })
                    ->orWhereHas('customer', function ($qq) use ($s) {
                        $qq->where('name', 'like', $s);
                    })
                    ->orWhereHas('shipper', function ($qq) use ($s) {
                        $qq->where('name', 'like', $s);
                    })
                    ->orWhereHas('vessel', function ($qq) use ($s) {
                        $qq->where('name', 'like', $s);
                    });
            });
        }

        // Apply status filter
        if ($this->filterStatus !== 'all') {
            $query->where('status', $this->filterStatus);
        }

        // Apply type filter
        if ($this->filterType !== 'all') {
            $query->where('container_type', $this->filterType);
        }

        // Apply transitor filter
        if ($this->filterTransitor) {
            $query->where('transitor_id', $this->filterTransitor);
        }

        // Apply shipping filter
        if ($this->filterShipping) {
            $query->where('shipping_id', $this->filterShipping);
        }

        // Apply shipper filter
        if ($this->filterShipper) {
            $query->where('shipper_id', $this->filterShipper);
        }

        return [
            'containers' => $query->with(['transitor', 'shipping', 'customer', 'shipper', 'vessel', 'originPort', 'destinationPort'])->paginate(10),
            'transitors' => \App\Models\Party::where('type', 'transitor')->get(),
            'shippings' => \App\Models\Party::where('type', 'shipping')->get(),
            'shippers' => \App\Models\Party::where('type', 'shipper')->get(),
        ];
    }

    public function openModal($id = null)
    {
        //$this->dispatchBrowserEvent('open-modal', ['id' => 'edit-container-modal']);
        $this->container = $id? \App\Models\container::whereId($id)->first() : new \App\Models\container();
        $this->form->setContainer($this->container);
        $this->showModal = true;
    }

    public function saveContainer()
    {
        
        $this->form->saveContainer();
        // Close the modal after saving
        $this->showModal = false;
    }
    
    public function updatedFilterTransitor()
    {
        $this->resetPage();
    }

    public function updatedFilterShipping()
    {
        $this->resetPage();
    }

    public function updatedFilterShipper()
    {
        $this->resetPage();
    }
  
};
?>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold">Containers</h1>
        <flux:button variant="primary" wire:click="openModal">+ Add Container</flux:button>
    </div>

    <!-- Filters & Search Section -->
    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <!-- Search -->
            <div>
             <flux:label for="search"></flux:label>
                <flux:input 
                    wire:model.live.debounce="search" 
                    placeholder="Search container, booking, seal..." 
                    icon="magnifying-glass"
               
                />
            </div>
            <!-- Filter by Status -->
            <div>
                <flux:select wire:model.live="filterStatus" label="Status">
                    <flux:select.option value="all">All Status</flux:select.option>
                    <flux:select.option value="in_transit">In Transit</flux:select.option>
                    <flux:select.option value="arrived">Arrived</flux:select.option>
                    <flux:select.option value="departed">Departed</flux:select.option>
                    <flux:select.option value="delayed">Delayed</flux:select.option>
                </flux:select>
            </div>
            <!-- Filter by Type -->
            <div>
                <flux:select wire:model.live="filterType" label="Container Type">
                    <flux:select.option value="all">All Types</flux:select.option>
                    <flux:select.option value="20ft">20ft</flux:select.option>
                    <flux:select.option value="40ft">40ft</flux:select.option>
                    <flux:select.option value="40ft_high_cube">40ft High Cube</flux:select.option>
                </flux:select>
            </div>
            <!-- Filter by Transitor -->
            <div>
                <flux:select wire:model.live="filterTransitor" label="Transitor">
                    <flux:select.option value="">All Transitors</flux:select.option>
                    @foreach($transitors as $t)
                        <flux:select.option value="{{ $t->id }}">{{ $t->name }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>
            <!-- Filter by Shipping -->
            <div>
                <flux:select wire:model.live="filterShipping" label="Shipping Company">
                    <flux:select.option value="">All Shippings</flux:select.option>
                    @foreach($shippings as $s)
                        <flux:select.option value="{{ $s->id }}">{{ $s->name }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>
            <!-- Filter by Shipper -->
            <div>
                <flux:select wire:model.live="filterShipper" label="Shipper">
                    <flux:select.option value="">All Shippers</flux:select.option>
                    @foreach($shippers as $sp)
                        <flux:select.option value="{{ $sp->id }}">{{ $sp->name }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900">
                        <th class="px-6 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">Container #</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">Booking #</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">Type</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">Route</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">Customer</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">Arrival</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-zinc-900 dark:text-white">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($containers as $container)
                        <tr class="border-b border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition">
                            <td class="px-6 py-4">
                                <span class="font-semibold text-zinc-900 dark:text-white">{{ $container->container_number }}</span>
                            </td>
                            <td class="px-6 py-4 text-zinc-600 dark:text-zinc-400">{{ $container->booking_number }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                    {{ strtoupper(str_replace('_', ' ', $container->container_type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($container->status === 'in_transit') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                    @elseif($container->status === 'arrived') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                    @elseif($container->status === 'departed') bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200
                                    @else bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $container->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                                {{ $container->originPort?->name ?? 'N/A' }} → {{ $container->destinationPort?->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                                {{ $container->customer?->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                                {{ $container->arrival_date ? $container->arrival_date->format('M d, Y') : '—' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('container.show', $container->id) }}" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium rounded-md text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition">View</a>
                                    <flux:button size="sm" variant="subtle" wire:click="openModal({{ $container->id }})">Edit</flux:button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-zinc-500 dark:text-zinc-400">
                                <p class="text-base">No containers found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700">
            {{ $containers->links() }}
        </div>
    </div>

    <!-- Container Form Modal -->
    <x-modal wire:model.self="showModal" title="{{ $container?->id ? 'Edit Container' : 'Add New Container' }}" wire:ignore.self>
        <form wire:submit="saveContainer" class="space-y-4">
            <!-- Row 1: Container Number, Booking Number, Seal Number -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <flux:input wire:model="form.container_number" label="Container Number" placeholder="Enter container number" required/>
                <flux:input wire:model="form.booking_number" label="Booking Number" placeholder="Enter booking number" required/>
                <flux:input wire:model="form.seal_number" label="Seal Number" placeholder="Enter seal number" required/>
            </div>

            <!-- Row 2: Type, Status, Voyage Number -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <flux:select wire:model="form.container_type" label="Container Type" required>
                    <flux:select.option value="">Select Type</flux:select.option>
                    @foreach(['20ft', '40ft', '40ft_high_cube'] as $type)
                        <flux:select.option value="{{ $type }}">{{ strtoupper(str_replace('_', ' ', $type)) }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:select wire:model="form.status" label="Status" required>
                    <flux:select.option value="">Select Status</flux:select.option>
                    @foreach(['in_transit', 'arrived', 'departed', 'delayed'] as $status)
                        <flux:select.option value="{{ $status }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:input wire:model="form.voyage_number" label="Voyage Number" placeholder="Enter voyage number"/>
            </div>

            <!-- Row 3: Ports -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:select wire:model="form.origin_port_id" label="Origin Port" placeholder="Select origin port" required>
                    <flux:select.option value="">Select Origin Port</flux:select.option>
                    @foreach(\App\Models\Port::all() as $port)
                        <flux:select.option value="{{ $port->id }}">{{ $port->name }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:select wire:model="form.destination_port_id" label="Destination Port" placeholder="Select destination port" required>
                    <flux:select.option value="">Select Destination Port</flux:select.option>
                    @foreach(\App\Models\Port::all() as $port)
                        <flux:select.option value="{{ $port->id }}">{{ $port->name }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            <!-- Row 4: Dates -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="form.arrival_date" label="Arrival Date" type="date"/>
                <flux:input wire:model="form.departure_date" label="Departure Date" type="date"/>
            </div>

            <!-- Row 5: Parties -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:select wire:model="form.transitor_id" label="Transitor" placeholder="Select transitor" required>
                    <flux:select.option value="">Select Transitor</flux:select.option>
                    @foreach(\App\Models\Party::where('type', 'transitor')->get() as $transitor)
                        <flux:select.option value="{{ $transitor->id }}">{{ $transitor->name }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:select wire:model="form.shipping_id" label="Shipping Company" placeholder="Select shipping company" required>
                    <flux:select.option value="">Select Shipping Company</flux:select.option>
                    @foreach(\App\Models\Party::where('type', 'shipping')->get() as $shipping)
                        <flux:select.option value="{{ $shipping->id }}">{{ $shipping->name }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            <!-- Row 6: Optional Parties -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:select wire:model="form.customer_id" label="Customer" placeholder="Select customer">
                    <flux:select.option value="">Select Customer</flux:select.option>
                    @foreach(\App\Models\Party::where('type', 'customer')->get() as $customer)
                        <flux:select.option value="{{ $customer->id }}">{{ $customer->name }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:select wire:model="form.shipper_id" label="Shipper" placeholder="Select shipper">
                    <flux:select.option value="">Select Shipper</flux:select.option>
                    @foreach(\App\Models\Party::where('type', 'shipper')->get() as $shipper)
                        <flux:select.option value="{{ $shipper->id }}">{{ $shipper->name }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            <!-- Row 7: Vessel -->
            <flux:select wire:model="form.vessel_id" label="Vessel" placeholder="Select vessel">
                <flux:select.option value="">Select Vessel</flux:select.option>
                @foreach(\App\Models\Party::where('type', 'vessel')->get() as $vessel)
                    <flux:select.option value="{{ $vessel->id }}">{{ $vessel->name }}</flux:select.option>
                @endforeach
            </flux:select>

            <!-- Row 8: Checkboxes -->
            <div class="flex gap-6">
                <flux:checkbox wire:model="form.is_soc" label="Is SOC"/>
                <flux:checkbox wire:model="form.is_group_container" label="Is Group Container"/>
            </div>

            <!-- Actions -->
            <div class="flex gap-2 justify-end pt-4 border-t border-zinc-200 dark:border-zinc-700">
                <flux:button variant="subtle" wire:click="$set('showModal', false)">Cancel</flux:button>
                <flux:button variant="primary" type="submit">{{ $container?->id ? 'Update Container' : 'Create Container' }}</flux:button>
            </div>
        </form>
    </x-modal>
</div>