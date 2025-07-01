<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSymptomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['superadmin', 'admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|string|max:255|unique:symptoms,code,' . $this->route('symptom')->id,
            'description' => 'required|string|max:255',
            'mb' => 'required|numeric|min:0|max:1',
            'md' => 'required|numeric|min:0|max:1',
            'disease_id' => 'required|exists:diseases,id',
        ];
    }
}
