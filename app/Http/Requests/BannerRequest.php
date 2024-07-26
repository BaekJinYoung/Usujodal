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
        $removeImage = $this->input('remove_image');

        $rules = [
            'title' => 'required',
            'mobile_title' => 'required',
        ];

        if ($removeImage == '1') {
            $rules['image'] = 'required|image';
            $rules['mobile_image'] = 'required|image';
        } else {
            $rules['image'] = 'nullable';
            $rules['mobile_image'] = 'nullable';
        }

        return $rules;
    }
}
