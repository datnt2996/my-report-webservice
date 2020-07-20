<?php

namespace App\Http\Requests\CommentRequests;

use Illuminate\Foundation\Http\FormRequest;

class PostCommentRequest extends FormRequest
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
            'event_id' => 'nullable|string',
            'reference_comment_id' => 'nullable|string',
            'title' => 'nullable|string',
            'image_id' => 'nullable|string',
            'note' => 'nullable|string',
            'message' => 'required|string'
        ];
	}
}