<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $with = ['ticketComment'];

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
        'landlord_id'
    ];



    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function landlord()
    {
        return $this->belongsTo(Landlord::class);
    }

    public function ticketComment()
    {
        return $this->hasMany(TicketComment::class);
    }


}
