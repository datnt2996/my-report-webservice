<?php

namespace App\Http\Requests\RateEventRequests;

use Illuminate\Foundation\Http\FormRequest;

class PostRateEventRequest extends FormRequest
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
			'star' => 'required|numeric|min:0|max:5',
            'title' => 'nullable|string',
            'content'=> 'nullable|string',
            'type'=> 'nullable|string',
		];
	}
}