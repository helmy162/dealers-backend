<?php

namespace App\Http\Requests\Webhook;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class StorePipedrivePersonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * Middleware has already authorized the request. No need to add further authorization rules here.
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
            'current' => 'required|array',
            'current.name' => 'required|string',
            'current.email' => 'required|array',
            'current.email.*.value' => 'required|string',
            'current.phone' => 'required|array',
            'current.phone.*.value' => 'required|string',
        ];
    }

    /**
     * In the unlikely event that validation fails, we'll just return a 422 and log the error.
     */
    protected function failedValidation(Validator $validator)
    {
        Log::critical('Pipedrive webhook validation failed', [
            'errors' => $validator->errors(),
        ]);

        parent::failedValidation($validator);
    }
}
