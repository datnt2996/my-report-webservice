<?php

namespace App\Http\Requests\EnrollRequests;

use Illuminate\Foundation\Http\FormRequest;

class PostEnrollRequest extends FormRequest
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
            'reference_comment_id',
            'note',
            'created_at',
            'updated_at'
        ];
	}
}