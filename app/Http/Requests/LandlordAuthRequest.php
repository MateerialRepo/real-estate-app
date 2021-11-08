<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LandlordAuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => ['string', 'required'],
            'last_name' => ['string', 'required'],
            'email' => ['string', 'required', 'unique:landlords'],
            'phone_number' => ['string', 'required'],
            'password' => ['string', 'required'],
            'gender' => ['string'],
            'dob' => ['string'],
            'occupation' => ['string'],
            'address' => ['string'],
            'landmark' => ['string'],
            'state' => ['string'],
            'country' => ['string'],
            'profile_pic' => ['string'],
            'kyc_type' => ['string'],
            'kyc_id' => ['string'],
            'email_verified_at' => ['string'],
            'is_approved' => ['string']
        ];
    }
}
