<?php

new #[\Livewire\Attributes\Layout('components.layouts.app')] class extends \Livewire\Component
{
    use \Livewire\WithPagination;

    public $title = 'Parties';
    public $search = '';
    public $filter = '';
    public $sort = '';
    public $showPartyForm = false;
    public $party = null;
    public $listeners = ['showAddPartyForm', 'hideAddPartyForm'];
    public $shippingAgents = [];
    public \App\Livewire\Forms\PartyForm $partyForm;

    public function deleteParty($id)
    {
        $party = \App\Models\Party::find($id);
        if ($party) {
            $party->delete();
            $this->resetPage();
        }
    }
   

    public function with(): array
    {
        //$this->parties = Party::all();

        $query = \App\Models\Party::query();
            if($this->search){
                $query->where('name','like','%'.$this->search.'%');
            }
            if($this->filter){
                $query->where('type', $this->filter);
            }
        
        return [
            'parties' =>  $query->orderBy('created_at', 'desc')->paginate(10),
            'shippingAgents' => \App\Models\Party::where('type', 'shipping')->get(),
        ];
        //$this->partyForm = new PartyForm();

    }

    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function updatedFilter() 
    {
        $this->resetPage();
    }

        public function showAddPartyForm($id = null)
        {
            // Logic to show the add party form
            $this->party = $id ? \App\Models\Party::find($id) : new \App\Models\Party();
            $this->partyForm->setParty($this->party);
            $this->showPartyForm = true;

            //$this->dispatch('show-party-form', party : $this->party);
        }

        public function saveParty()
        {
            // Logic to save the party
            $this->partyForm->saveParty();
            $this->resetPage(); 
            $this->showPartyForm = false;
        }
    

};
?>

<div class="space-y-6">
    {{-- People find pleasure in different ways. I find it in keeping my mind clear. - Marcus Aurelius --}}
    
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold">Parties</h1>
        <flux:button variant="primary" wire:click="showAddPartyForm">+ Add Party</flux:button>
    </div>

    <!-- Filters & Search Section -->
    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Search -->
            <div>
                <flux:input 
                    type="text" 
                    placeholder="Search by name, address, or contact..." 
                    wire:model.live.debounce.300ms="search"
                    icon="magnifying-glass"
                />
            </div>
            <!-- Filter by Type -->
            <div>
                <flux:select 
                    placeholder="Filter by Type" 
                    wire:model.live.debounce.300ms="filter" 
                    icon="funnel"
                >
                    <flux:select.option value="">All Types</flux:select.option>
                    <flux:select.option value="customer">Customer</flux:select.option>
                    <flux:select.option value="shipping">Shipping Agent</flux:select.option>
                    <flux:select.option value="transitor">Transitor</flux:select.option>
                    <flux:select.option value="shipper">Shipper</flux:select.option>
                    <flux:select.option value="vessel">Vessel</flux:select.option>
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
                        <th class="px-6 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">Type</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">Address</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">Contact Number</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-zinc-900 dark:text-white">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parties as $party)
                        <tr class="border-b border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img 
                                        class="h-10 w-10 rounded-full flex-shrink-0" 
                                        src="https://ui-avatars.com/api/?name={{ urlencode($party->name) }}&background=4f46e5&color=fff" 
                                        alt="{{ $party->name }}" 
                                    />
                                    <span class="font-semibold text-zinc-900 dark:text-white">{{ $party->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($party->type === 'customer') bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200
                                    @elseif($party->type === 'shipping') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                    @elseif($party->type === 'transitor') bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200
                                    @elseif($party->type === 'shipper') bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200
                                    @elseif($party->type === 'vessel') bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $party->type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                                {{ $party->address ?: '—' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                                {{ $party->contact_number ?: '—' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('party.show', $party->id) }}" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium rounded-md text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition">View</a>
                                    <flux:button size="sm" variant="subtle" wire:click="showAddPartyForm({{ $party->id }})">Edit</flux:button>
                                    <flux:button 
                                        size="sm" 
                                        variant="danger" 
                                        wire:confirm="Are you sure you want to delete {{ $party->name }}?" 
                                        wire:click="deleteParty({{ $party->id }})"
                                    >Delete</flux:button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-zinc-500 dark:text-zinc-400">
                                <p class="text-base">No parties found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700">
            {{ $parties->links() }}
        </div>
    </div>

    <!-- Add/Edit Party Modal -->
    <flux:modal id="party-form-modal" wire:model.self="showPartyForm">
        <form wire:submit.prevent="saveParty" class="space-y-4">
            <flux:heading>{{ $this->party && $this->party->id ? 'Edit Party' : 'Add New Party' }}</flux:heading>
            
            <div class="space-y-4">
                <!-- Row 1: Name and Type -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <flux:input 
                            type="text" 
                            placeholder="Name" 
                            wire:model="partyForm.name"
                            label="Name"
                        />
                        <flux:error name="partyForm.name"></flux:error>
                    </div>
                    <div>
                        <flux:select placeholder="Select Type" wire:model="partyForm.type" label="Type">
                            <flux:select.option value="">Select Type</flux:select.option>
                            <flux:select.option value="customer">Customer</flux:select.option>
                            <flux:select.option value="shipping">Shipping Agent</flux:select.option>
                            <flux:select.option value="transitor">Transitor</flux:select.option>
                            <flux:select.option value="shipper">Shipper</flux:select.option>
                            <flux:select.option value="vessel">Vessel</flux:select.option>
                        </flux:select>
                        <flux:error name="partyForm.type"></flux:error>
                    </div>
                </div>

                <!-- Shipping Agent (conditional for vessel type) -->
                @if($this->partyForm->type == 'vessel')
                    <div>
                        <flux:select placeholder="Shipping Agent" wire:model="partyForm.vessel_shipping_id" label="Shipping Agent">
                            <flux:select.option value="">Select Shipping Agent</flux:select.option>
                            @forelse($this->shippingAgents as $agent)
                                <flux:select.option value="{{ $agent->id }}">{{ $agent->name }}</flux:select.option>
                            @empty
                                <flux:select.option value="">No Shipping Agents Available</flux:select.option>
                            @endforelse 
                        </flux:select>
                    </div>
                @endif

                <!-- Row 2: Address -->
                <div>
                    <flux:input 
                        type="text" 
                        placeholder="Address" 
                        wire:model="partyForm.address"
                        label="Address"
                    />
                    <flux:error name="partyForm.address"></flux:error>
                </div>

                <!-- Row 3: Contact Number -->
                <div>
                    <flux:input 
                        type="text" 
                        placeholder="Contact Number" 
                        wire:model="partyForm.contact_number"
                        label="Contact Number"
                    />
                    <flux:error name="partyForm.contact_number"></flux:error>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-2 justify-end pt-4 border-t border-zinc-200 dark:border-zinc-700">
                <flux:button variant="subtle" wire:click="showPartyForm = false">Cancel</flux:button>
                <flux:button variant="primary" type="submit">{{ $this->party && $this->party->id ? 'Update Party' : 'Create Party' }}</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
</div>