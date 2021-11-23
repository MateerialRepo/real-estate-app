<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Landlord extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable, SoftDeletes;


    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'gender',
        'dob',
        'occupation',
        'address',
        'landmark',
        'state',
        'country',
        'profile_pic',
        'kyc_type',
        'kyc_id',
        'is_approved',
        'password'
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function property(){
        $this->hasMany(Property::class);
    }

    public function propertyLike(){
        $this->hasManyThrough(PropertyLike::class, Property::class);
    }
}
