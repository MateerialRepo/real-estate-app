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
        'document_type', 
        'property_document', 
        'description'
    ];


    // public function property()
    // {
    //     return $this->belongsTo('App\Models\Property');
    // }
}
