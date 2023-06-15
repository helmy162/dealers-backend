<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetPipedriveActivitiesRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'start' => 'required|int',
            'limit' => 'required|int',
            'done' => 'required|boolean',
            'search' => 'sometimes|string',
            'filter' => 'sometimes|string',
        ];
    }
}
