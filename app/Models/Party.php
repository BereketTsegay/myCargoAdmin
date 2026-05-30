<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    //
        protected $fillable = [
            'name',
            'type',
            'vessel_shipping_id',
            'address',
            'contact_number',
        ];

        public function vesselShipping()
        {
            return $this->belongsTo(Party::class, 'vessel_shipping_id');
        }
        public function transitor()
        {
            return $this->hasMany(Container::class, 'transitor_id');
        }
        public function shipping()
        {
            return $this->hasMany(Container::class, 'shipping_id');
        }
        public function customer()
        {
            return $this->hasMany(Container::class, 'customer_id'); 
        }
    
        
}
