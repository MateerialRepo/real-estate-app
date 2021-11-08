<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyVerification extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'property_document' => 'array'
    ];

    protected $fillable = [ 
        'property_id', 
        'verification_type', 
        'property_document', 
        'description'
    ];

    public function setPropertyDocumentAttribute($value)
	{
	    $property_document = [];

	    foreach ($value as $array_item) {
	        if (!is_null($array_item['key'])) {
	            $property_document[] = $array_item;
	        }
	    }

	    $this->attributes['property_document'] = json_encode($property_document);
	}

    public function property()
    {
        return $this->belongsTo('App\Models\Property');
    }
}
