<?php

namespace App\Models;

use App\Models\Property;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    // protected $with = ['property'];


    protected $fillable = [
        'tenant_id',
        'property_id',
        'amount',
        'description',
        'duration'
    ];

    
    public function property(){
        $this->belongsTo(Property::class);
    }
}
