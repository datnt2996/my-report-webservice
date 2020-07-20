<?php

namespace App\Http\Requests\UserRequests;

use Illuminate\Foundation\Http\FormRequest;

class PostUserAddressRequest extends FormRequest
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
            'user_address' => 'required',
            'first_name' => 'required|string|max:250',
            'last_name' => 'required|string|max:250',
            'company' => 'nullable|string|max:250',
            'address1' => 'required|string|max:250',
            'address2' => 'nullable|string|max:250',
            'city' => 'required|string',
            'province' => 'required|string',
            'country' => 'nullable|string',
            'zip' => 'nullable|string|max:255',
            'phone' => 'required|phone:VN',
            'province_code' => 'nullable|string|max:2',
            'country_code' => 'nullable|string|max:2',
            'address_default' => 'nullable|boolean',
        ];
    }
}