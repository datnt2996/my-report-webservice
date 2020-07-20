<?php

namespace App\Http\Requests\UserRequests;

use Illuminate\Foundation\Http\FormRequest;

class PutUserRequest extends FormRequest
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
			'number_phone' => 'nullable|phone:VN|unique:mongodb.users,number_phone',
			'full_name' => 'nullable|string|max:250',
			'screen_name' => 'nullable	|string|max:250|unique:mongodb.users,screen_name',
			'image' => 'nullable|string|max:250',
			'receive_announcements' => 'nullable|boolean',
			'locale' => 'nullable|string',
			'password' => 'nullable|string|min:8'
		];
	}
}