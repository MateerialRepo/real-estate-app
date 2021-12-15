<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupportTicket extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'img' => 'array',
    ];

    protected $fillable = [
        'user_type',
        'user_id',
        'category',
        'email',
        'summary',
        'description',
        'img'
    ];
}
