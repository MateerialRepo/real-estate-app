<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTicketRequest extends FormRequest
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
            'ticket_title' => 'required|max:255',
            'ticket_category' => 'required|string',
            'description' => 'required|string',
            'ticket_img.*' => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
            'landlord_id' => 'required',
        ];
    }
}
