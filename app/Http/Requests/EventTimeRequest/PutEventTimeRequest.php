<?php

namespace App\Http\Requests\EventTimeRequest;

use Illuminate\Foundation\Http\FormRequest;

class PutEventTimeRequest extends FormRequest
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
			'event_id' => 'string|nullable',
			'position' => 'string|nullable',
			'time_star' => 'string|required',
            'time_end' => 'string|required',
            'total_person' => 'string|required'
		];
	}
}