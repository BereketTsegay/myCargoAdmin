<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class ContainerForm extends Form
{
    //declare properties for container fields
    public $container_number;
    public $booking_number;
    public $seal_number;
    public $container_type;
    public $origin_port;
    public $destination_port;
    public $arrival_date;
    public $departure_date;
    public $transitor_id;
    public $shipping_id;
    public $customer_id;
    public $shipper_id;
    public $vessel_id;
    public $voyage_number;
    public $is_soc;
    public $status;
    public $is_group_container;
    public $container;

    public function rules()
    {
        return [
            'container_number' => 'required|unique:containers,container_number,' . ($this->container->id ?? 'null'),
            'booking_number' => 'nullable|unique:containers,booking_number,' . ($this->container->id ?? 'null'),
            'seal_number' => 'nullable|unique:containers,seal_number,' . ($this->container->id ?? 'null'),
            'container_type' => 'required|in:20ft,40ft,40ft_high_cube',
            'origin_port' => 'nullable|string|max:255',
            'destination_port' => 'nullable|string|max:255',
            'arrival_date' => 'nullable|date',
            'departure_date' => 'nullable|date|after_or_equal:arrival_date',
            'transitor_id' => 'required|exists:parties,id',
            'shipping_id' => 'required|exists:parties,id',
            'customer_id' => 'nullable|exists:parties,id',
            'shipper_id' => 'required|exists:parties,id',
            'vessel_id' => 'required|exists:parties,id',
            'voyage_number' => 'nullable|string|max:255',
            'is_soc' => 'nullable|boolean',
            'status' => 'nullable|in:in_transit,arrived,departed',
            'is_group_container' => 'nullable|boolean',
        ];
    }

    public function setContainer($container)
    {
        $this->container = $container;
        $this->container_number = $container->container_number;
        $this->booking_number = $container->booking_number;
        $this->seal_number = $container->seal_number;
        $this->container_type = $container->container_type;
        $this->origin_port = $container->origin_port;
        $this->destination_port = $container->destination_port;
        $this->arrival_date = $container->arrival_date;
        $this->departure_date = $container->departure_date;
        $this->transitor_id = $container->transitor_id;
        $this->shipping_id = $container->shipping_id;
        $this->customer_id = $container->customer_id;
        $this->shipper_id = $container->shipper_id;
        $this->vessel_id = $container->vessel_id;
        $this->voyage_number = $container->voyage_number;
        $this->is_soc = $container->is_soc;
        $this->status = $container->status;
        $this->is_group_container = $container->is_group_container;
    }

    public function saveContainer()
    {
        $this->validate();

        // Logic to save the container
        $this->container->container_number = $this->container_number;
        $this->container->booking_number = $this->booking_number;
        $this->container->seal_number = $this->seal_number;
        $this->container->container_type = $this->container_type;
        $this->container->origin_port = $this->origin_port;
        $this->container->destination_port = $this->destination_port;
        $this->container->arrival_date = $this->arrival_date;
        $this->container->departure_date = $this->departure_date;
        $this->container->transitor_id = $this->transitor_id;
        $this->container->shipping_id = $this->shipping_id;
        $this->container->customer_id = $this->customer_id;
        $this->container->shipper_id = $this->shipper_id;
        $this->container->vessel_id = $this->vessel_id;
        $this->container->voyage_number = $this->voyage_number;
        $this->container->is_soc = !!$this->is_soc;
        $this->container->status = $this->status;
        $this->container->is_group_container = !!$this->is_group_container;

        $this->container->save();
    }
}
