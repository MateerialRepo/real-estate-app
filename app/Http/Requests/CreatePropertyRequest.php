<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePropertyRequest extends FormRequest
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
            'property_amount' => ['string', 'required'],
            'property_type' => ['string', 'required'],
            'is_serviced' => ['string', 'required'],
            'property_title' => ['string', 'required'],
            'year_built' => ['string', 'required'],
            'country' => ['string', 'required'],
            'state' => ['string', 'required'],
            'city' => ['string', 'required'],
            'street_address' => ['string', 'required'],
            'landmark' => ['string', 'required'],
            'zipcode' => ['string', 'required'],
            'property_images' => ['required'],
            'bedrooms' => ['string', 'required'],
            'bathrooms' => ['string', 'required'],
            'parking' => ['string', 'required'],
            'property_desc' => ['string', 'required'],
            'lease_type' => ['string', 'required'],
        ];
    }
}
