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
            'image' => 'required',
            'mobile_title' => 'required',
            'mobile_image' => 'required',
        ];

        if ($this->input('remove_image') == 0) {
            $rules['image'] = 'nullable';
        } elseif ($this->input('remove_image') == 1) {
            $rules['image'] = 'required';
        }

        if ($this->input('mobile_remove_image') == 0) {
            $rules['mobile_image'] = 'nullable';
        } elseif ($this->input('mobile_remove_image') == 1) {
            $rules['mobile_image'] = 'required';
        }

        return $rules;
    }
}
