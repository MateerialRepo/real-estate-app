<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $fillable = [
        'tenant_id',
        'document_unique_id',
        'document_category',
        'document_url',
        'document_format',
        'description',
        'landlord_id'
    ];



    public function tenant()
    {
        return $this->hasOne(Tenant::class);
    }

    public function landlord()
    {
        return $this->hasOne(Landlord::class);
    }
}
