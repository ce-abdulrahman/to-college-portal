<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'system_id'         => 'required|exists:systems,id',
            'province_id'       => 'required|exists:provinces,id',
            'university_id'     => 'required|exists:universities,id',
            'college_id'        => 'required|exists:colleges,id',
            'name'              => 'required|string|max:255',
            'local_score'       => 'nullable|numeric|min:50',
            'external_score'    => 'nullable|numeric|min:50',
            'type'              => 'required|in:زانستی,وێژەیی,زانستی و وێژەیی',
            'sex'               => 'nullable|string',
            'lat'               => ['nullable','numeric','between:-90,90'],
            'lng'               => ['nullable','numeric','between:-180,180'],
            'description'       => 'nullable|string',
            'status'            => 'required|boolean',
        ];
    }
}
