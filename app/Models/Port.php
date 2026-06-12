<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    // The attributes that are mass assignable.
    protected $fillable = [
        'name',
        'country',  
        'country_code',
    ];
    
}
