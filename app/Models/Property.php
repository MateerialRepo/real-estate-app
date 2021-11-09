<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    // protected $with = ['referee'];
    // Allow any field to be inserted
    protected $guarded = [];

    protected $casts = [
        'property_images' => 'array',
        'property_amenities' => 'array',
    ];

    protected $fillable = [
        'landlord_id',
        'property_amount',
        'property_unique_id',
        'property_type',
        'is_serviced',
        'property_title',
        'year_built',
        'lga',
        'country',
        'state',
        'city',
        'street_address',
        'landmark',
        'zipcode',
        'property_images',
        'bedrooms',
        'bathrooms',
        'parking',
        'property_desc',
        'lease_type',
        'preferred_religion',
        'preferred_tribe',
        'preferred_marital_status',
        'preferred_employment_status',
        'max_coresidents',
        'preferred_gender',
        'property_amenities',
        'side_attraction_details',
        'is_available',
        'is_verified',

    ];




    public function tenant(){
        return $this->hasOne(Tenant::class);
    }

    public function landlord(){
        return $this->hasOne(Landlord::class);
    }
}
