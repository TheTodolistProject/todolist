<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'fname' => "required|string|max:20",
            'lname' => "required|string|max:20",
            'email' => 'required|string|email|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ];
    }
}
