<?php
namespace Module\Application\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow if the user is logged in
        // return auth()->check();

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
            'status_code' => 'required',
            'email' => 'required|unique:users,email,' . request()->route('user'),
            'name' => 'required|min:2',
            'password' => 'confirmed',
            'timezone' => 'required',
            'locale_id' => 'required',
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
            'status_code' => __('application::user.field.status_code'),
            'email' => __('application::user.field.email'),
            'name' => __('application::user.field.name'),
            'password' => __('application::user.field.password'),
            'timezone' => __('application::user.field.timezone'),
            'locale_id' => __('application::user.field.locale'),
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
