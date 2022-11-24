<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'firstname' => 'required',
            'lastname' => 'required',
            'email'=>'required|unique:users',
            'password'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'firstname.required' => "FirstName is Required.",
            'lastname.required' => 'LastName is Required.',
            'email.required' => 'Email is Required.',
            'password.required'=> 'Passowrd is Required',
        ];
    }
}
