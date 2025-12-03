<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules():array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:50',

                Rule::unique('categories')->where(function ($query){
                    return $query->where('user-id', $this->user()->id)
                                ->where('type', $this->type);
                })
            ],
            'type' => ['required', 'in:income,expense'],
            'color_code' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'icon' => ['nullable', 'string', 'max:50'],
            ];
    }

    public function messages():array
    {
        return [
            'color_code.regex' => 'The color must be a valid HEX code (e.g. #FFFFFF)',
            'name.unique' => 'You already have a category with this name.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
}
