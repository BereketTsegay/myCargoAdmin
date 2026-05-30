<?php

namespace App\Livewire\Forms;
use App\Models\Party;
use Livewire\Form;

class PartyForm extends Form
{
    //
    public Party $party;
    public $name;
    public $type;
    public $vessel_shipping_id;
    public $address;
    public $contact_number;


    public function setParty(Party $party)
    {
        $this->party = $party;
        $this->name = $party->name;
        $this->type = $party->type;
        $this->vessel_shipping_id = $party->vessel_shipping_id;
        $this->address = $party->address;
        $this->contact_number = $party->contact_number;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:shipping,transitor,customer,shipper,vessel',
            'vessel_shipping_id' => 'nullable|exists:parties,id',
            'address' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
        ];
    }

    public function saveParty()
    {
        // Logic to save the party
        $this->validate();
        
        $this->party->name = $this->name;
        $this->party->type = $this->type;
        $this->party->vessel_shipping_id = $this->vessel_shipping_id;
        $this->party->address = $this->address;
        $this->party->contact_number = $this->contact_number;

        $this->party->save();
    }
}
