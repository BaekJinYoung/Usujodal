<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'required',
            'mobile_title' => 'required',
            'image' => 'required',
            'mobile_image' => 'required',
        ];

        if ($this->isMethod('post')) {
            $rules['image'] = 'required';
        } elseif ($this->isMethod('patch')) {
            $rules['image'] = 'nullable';
        }

        return $rules;
    }
}
