<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    protected $casts = [
        'img' => 'array',
    ];

    protected $fillable = [
        'user_type',
        'category',
        'email',
        'summary',
        'description',
        'img'
    ];
}
