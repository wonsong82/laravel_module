<?php

namespace Module\Company\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'nullable|unique_multiple:company_users,code&company_id,' . request()->route('user'),
            'status_code' => 'required',
            'email' => 'required|email|unique:users,email',
            'name' => 'required|min:2',
            'password' => 'required|confirmed',
            'roles_show' => 'nullable', // array
            'permissions_show' => 'nullable' // array
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'code' => __('company::company_user.field.code'),
            'status_code' => __('company::company_user.field.status_code'),
            'name' => __('company::company_user.field.name'),
            'email' => __('company::company_user.field.email'),
            'password' => __('company::company_user.field.password'),
            'roles_show' => __('application::user.field.roles'),
            'permissions_show' => __('application::user.field.permissions'),
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
