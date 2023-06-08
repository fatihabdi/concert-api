<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class TicketStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|string",
            "type" => ["required", Rule::in(["cat1", "cat2", "cat3", "cat4", "cat5", "cat6"])],
            "price" => ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
            "startDate" => "required|date",
            "endDate" => "required|date|after:startDate",
            // "available_count" => "required|integer",
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name field must be a string.',
            'type.required' => 'The type field is required.',
            'type.in' => 'The selected type is invalid.',
            'serial_number.required' => 'The serial number field is required.',
            'serial_number.string' => 'The serial number field must be a string.',
            'serial_number.unique' => 'The serial number has already been taken.',
            'price.required' => 'The price field is required.',
            'price.numeric' => 'The price field must be a number.',
            'price.regex' => 'The price field must be in decimal format (e.g., 10.99).',
            'startDate.required' => 'The start date field is required.',
            'startDate.date' => 'The start date field must be a valid date.',
            'endDate.required' => 'The end date field is required.',
            'endDate.date' => 'The end date field must be a valid date.',
            'endDate.after' => 'The end date must be after the start date.',
            // 'available_count.required' => 'The available count field is required.',
            // 'available_count.integer' => 'The available count field must be an integer.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = [
            "status" => 422,
            "message" => "Input data failed",
            "errors" => $validator->errors(),
        ];
        throw new HttpResponseException(response()->json($response, 422));
    }
}
