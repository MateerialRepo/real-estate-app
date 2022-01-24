<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $with = [];

    protected $casts = [
        'commenter_id' => 'array',
    ];

    protected $fillable = [
        'ticket_id',
        'comment',
        'commenter_id',
    ];



    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    //cereate separate columns for those likely to comment on ticket on the ticket comment model and fetch them accordingly with column specification on the ticket comment model

}
