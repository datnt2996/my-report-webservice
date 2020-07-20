<?php

namespace App\Http\Requests\EventRequests;

use Illuminate\Foundation\Http\FormRequest;

class PostEventRequest extends FormRequest
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
            
            'address' => 'required|string',
            'number_phone' => 'required|string',
            'title' => 'required|string',
            'description' => 'required|string',
            'handle' => 'nullable|string',
            'represent_position' => 'nullable|string',
            'organization_name' => 'nullable|string',
            'trade_assurance' => 'nullable|string',
            'time_start' => 'nullable|string',
            'time_end' => 'nullable|string',
            'total_person' => 'nullable|integer',
            'price' => 'nullable|integer',
            'category_lv1' => 'required|string',
            'category_lv2' => 'nullable|string',
            'is_trade_assurance' => 'nullable|boolean',
            'trade_assurance_status' => 'nullable|string',
            'trade_assurance_reason' => 'nullable|array',

			'event_times.*.position' => 'integer|nullable',
			'event_times.*.time_start' => 'string|nullable',
            'event_times.*.time_end' => 'string|nullable',
            'event_times.*.total_person' => 'integer|nullable',

			'event_images.*.image_id' => 'string|nullable',
			'event_images.*.position' => 'integer|nullable',
			'event_images.*.src' => 'string|nullable',
			'event_images.*.width' => 'integer|nullable',
			'event_images.*.height' => 'integer|nullable',
			'event_images.*.is_checked' => 'boolean|nullable',
        ];
    }
}