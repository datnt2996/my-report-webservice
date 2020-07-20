<?php

namespace App\Http\Requests\NotificationRequests;

use Illuminate\Foundation\Http\FormRequest;

class CreateNotificationRequest extends FormRequest
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
            'user_id' => 'string|size:24',
            'body' => 'nullable',
            'content' => 'string',
            'type' => 'string',
            'status' => 'string'
        ];
    }
}