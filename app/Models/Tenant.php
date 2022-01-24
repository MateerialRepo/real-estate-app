<?php

namespace App\Models;

use App\Models\Property;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Tenant extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable, SoftDeletes;


    protected $with = ['referee', 'nextOfKin'];

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
    
    //setting tenant_unique_id to random string
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->tenant_unique_id = "TNT-".rand(100000000, 999999999)."-BRC";
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



    public function referee()
    {
        return $this->hasOne(Referee::class);
    }

    public function nextOfKin()
    {
        return $this->hasOne(NextOfKin::class);
    }

    public function property()
    {
        return $this->hasOne(Property::class);
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }

    public function propertyLike()
    {
        return $this->hasMany(PropertyLike::class,);
    }

    public function propertyReservation()
    {
        return $this->hasMany(PropertyReservation::class);
    }

    public function document()
    {
        return $this->hasMany(Document::class);
    }

    public function ticket(){
        return $this->hasMany(Ticket::class);
    }
}
