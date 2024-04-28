<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'payload'               =>['required','array'],
            'payload.event'         =>['required','array'],
            'payload.event.type'    =>['required','string', Rule::in(['create', 'update'])],
            'payload.employee_id'   =>['required_if:payload.event.type,update','string'],
            'payload.provider'      =>['required','string', Rule::in(['provider_1', 'provider_2'])],
            'payload.data'          =>['required','array']
        ];
    }
}
