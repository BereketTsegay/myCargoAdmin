<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class container extends Model
{
    protected $fillable = [
        'container_number',
        'booking_number',
        'seal_number',
        'transitor_id',
        'shipping_id',
        'customer_id',
        'shipper_id',
        'vessel_id',
        'voyage_number',
        'container_type',
        'is_soc',
        'arrival_date',
        'departure_date',
        'origin_port',
        'destination_port',
        'origin_port_id',
        'destination_port_id',
        'status',
        'is_group_container'
    ];

    protected $casts = [
        'arrival_date' => 'date',
        'departure_date' => 'date',
        'is_soc' => 'boolean',
        'is_group_container' => 'boolean',
    ];

    public function transitor()
    {
        return $this->belongsTo(Party::class, 'transitor_id');
    }

    public function shipping()
    {
        return $this->belongsTo(Party::class, 'shipping_id');
    }
    public function customer()
    {
        return $this->belongsTo(Party::class, 'customer_id');
    }
    public function shipper()
    {
        return $this->belongsTo(Party::class, 'shipper_id');
    }
    public function vessel()
    {
        return $this->belongsTo(Party::class, 'vessel_id');
    }

    public function originPort()
    {
        return $this->belongsTo(Port::class, 'origin_port_id');
    }

    public function destinationPort()
    {
        return $this->belongsTo(Port::class, 'destination_port_id');
    }
}
