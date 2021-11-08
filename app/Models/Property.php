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

    // public function setPropertyImagesAttribute($value)
	// {
	//     $property_images = [];

	//     foreach ($value as $array_item) {
	//         if (!is_null($array_item['key'])) {
	//             $property_images[] = $array_item;
	//         }
	//     }

	//     $this->attributes['property_images'] = json_encode($property_images);
	// }

    public function setPropertyImagesAttribute($value)
    {
        $this->attributes['property_images'] = json_encode($value);
    }


    
    public function tenant(){
        return $this->hasOne(Tenant::class);
    }

    public function landlord(){
        return $this->hasOne(Landlord::class);
    }
}
