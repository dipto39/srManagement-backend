<?php

namespace App\Http\Requests\Auth;

use App\Traits\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OtpRequest extends FormRequest
{
    use ApiResponse;
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
            'identifier' => 'required',
            'type'       => 'required|in:email,phone',
            'action'     => 'required|in:register,reset',
        ];
    }
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException($this->error(
            $validator->errors()->first(),
            422
        ));
    }

    // For valid email or phone
    // 'identifier' => [
    //     'required',
    //     function ($attribute, $value, $fail) use ($request) {
    //         if ($request->type === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
    //             $fail('The identifier must be a valid email address.');
    //         }

    //         if ($request->type === 'phone' && !preg_match('/^(?:\+88)?01[3-9]\d{8}$/', $value)) {
    //             $fail('The identifier must be a valid Bangladeshi phone number.');
    //         }
    //     },
    // ],
}
