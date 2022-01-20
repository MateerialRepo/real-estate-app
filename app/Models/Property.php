<?php

namespace App\Models;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $with = ['propertyVerification', 'document', 'propertyLike', 'propertyReservation', 'transaction', 'ticket' ];

    // Allow any field to be inserted
    protected $guarded = [];

    protected $casts = [
        'property_images' => 'array',
        'property_amenities' => 'array',
    ];

    protected $fillable = [
        'landlord_id',
        'tenant_id',
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


    // public function tenant()
    // {
    //     return $this->belongsTo(Tenant::class);
    // }

    // public function landlord()
    // {
    //     return $this->belongsTo(Landlord::class);
    // }

    public function propertyVerification() 
    {
        return $this->hasOne(PropertyVerification::class); //works!!
    }

    public function document(){
        return $this->hasMany(Document::class); //Works!!!
    }

    public function ticket(){
        return $this->hasMany(Ticket::class);
    }

    public function propertyLike(){
        return $this->hasMany(PropertyLike::class); //Works!!
    }

    public function propertyReservation(){
        return $this->hasMany(PropertyReservation::class); //Works!!
    }

    public function transaction(){
        return $this->hasMany(Transaction::class);
    }
}
