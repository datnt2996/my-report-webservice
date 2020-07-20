<?php

namespace App\Http\Requests\ImageRequests;

use Illuminate\Foundation\Http\FormRequest;

class PostEventImageRequest extends FormRequest
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
			'image_id' => 'string|nullable',
			'position' => 'integer|nullable',
			'src' => 'string|required',
			'width' => 'integer|nullable',
			'height' => 'integer|nullable',
			'is_checked' => 'boolean|nullable'
		];
	}
}