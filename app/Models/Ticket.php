<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

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
        return $this->hasOne(Tenant::class);
    }

    public function landlord()
    {
        return $this->hasOne(Landlord::class);
    }

    public function ticketComment()
    {
        return $this->hasMany(TicketComment::class);
    }
}
