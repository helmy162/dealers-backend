<?php

namespace App\Http\Requests\CarSpace;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarSpaceRequest extends FormRequest
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
            'car_id' => 'required|integer',
            'inputs' => 'required|array',

        ];
    }
}
