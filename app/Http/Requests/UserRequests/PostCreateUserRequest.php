<?php

namespace App\Http\Requests\UserRequests;

use Illuminate\Foundation\Http\FormRequest;

class PostCreateUserRequest extends FormRequest
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
			'full_name' => 'required|string|max:250',
			'email' => 'required|email|unique:mongodb.users,email',
			'screen_name' => 'required|string|max:250|unique:mongodb.users,screen_name',
			'image' => 'nullable|string|max:250',
			'number_phone' => 'required|phone:VN|unique:mongodb.users,number_phone',
			'receive_announcements' => 'nullable|boolean',
			'permission' => 'nullable|string',
			'locale' => 'nullable|string',
			'user_type' => 'nullable|string',
			'password' => 'required|string|min:6',
			'roles' => 'nullable|integer',
			'is_organization' => 'required|boolean',
			'is_vip' => 'nullable|boolean',
		];
	}
}