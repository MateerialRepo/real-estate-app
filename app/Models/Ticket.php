<?php

namespace App\Models;

use App\Models\Tenant;
use App\Models\Property;
use App\Models\TicketComment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $with = ['tenant', 'property', 'ticketComment'];

    protected $casts = [
        'ticket_img' => 'array',
    ];

    protected $fillable = [
        'tenant_id',
        'ticket_unique_id',
        'ticket_status',
        'ticket_title',
        'ticket_category',
        'description',
        'ticket_img',
        'property_id'
    ];



    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function property()
    {
        // return $this->belongsTo(Property::class)->with('landlord', function($query) {
        //     $query->without('property', 'propertyLike', 'propertyReservation', 'transaction', 'document');
        // });

        return $this->belongsTo(Property::class);

    }

    public function ticketComment()
    {
        return $this->hasMany(TicketComment::class);
    }


}
