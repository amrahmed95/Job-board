<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'salary' => 'nullable|integer|min:0',
            'salary_currency' => 'nullable|string|size:3',
            'salary_period' => 'nullable|in:hour,day,week,month,year,project',
            'employment_type' => 'required|in:full-time,part-time,contract,freelance,internship,temporary',
            'work_location_type' => 'required|in:remote,on-site,hybrid',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'experience' => 'required|in:entry,mid,mid-senior,senior,manager,director,executive',
        ];
    }

    /**
     * Custom validation messages for the request.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'category_id.required' => 'Please select a category',
            'category_id.exists' => 'The selected category is invalid',
        ];
    }
}
