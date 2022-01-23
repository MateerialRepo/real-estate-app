<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyLike extends Model
{
    use HasFactory;

    protected $with = ['tenant', 'property'];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'property_id', 
        'tenant_id',
        'isLiked'
    ];

    public function property() {
        return $this->belongsTo(Property::class);
    }

    public function tenant(){
        return $this->belongsTo(Tenant::class);
    }
}
