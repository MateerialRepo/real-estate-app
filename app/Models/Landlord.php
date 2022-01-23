<?php

namespace App\Models;

use App\Models\Property;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Landlord extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable, SoftDeletes;

    protected $with = ['property', 'transaction', 'document', 'ticket', 'propertyLike', 'propertyReservation'];


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

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->landlord_unique_id = "LND-".rand(100000000, 999999999)."-BRC";
        });
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
        return $this->hasMany(Property::class);
    }


    public function propertyLike(){
        return $this->hasManyThrough(PropertyLike::class, Property::class);
    }

    public function propertyReservation(){
        return $this->hasManyThrough(PropertyReservation::class, Property::class);
    }

    public function transaction(){
        return $this->hasManyThrough(Transaction::class, Property::class);
    }

    public function document(){
        return $this->hasManyThrough(Document::class, Property::class);
    }
    
    public function ticket(){
        return $this->hasManyThrough(Ticket::class, Property::class);
    }
}
