<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SignupValidation extends Request {

    public function authorize() {
        /* return false : nobody cant send post reuqest to here. you can disable it to some persons, like if they are in blacklist or something */
        return true;
    }
    public function rules() {
        if ('role' == '3') {
            $rule = 'required|regex:/^([0-9a-zA-Z\s\.\-\&\+\,]{1,50})$/';
        } else {
            $rule = 'regex:/^([0-9a-zA-Z\s\.\-\&\+\,]{1,50})$/';
        }
        return [
            'fname' => 'required|regex:/^([a-zA-Z\s]){2,255}+$/',
            'lname' => 'required|regex:/^([a-zA-Z\s]){2,255}+$/',
            'role' => 'required|regex:/^[1]?[2]?[3]?[4]?[5]?$/',
            'email' => 'required',
            'password' => 'required|min:8|max:255',
            'merchant' => $rule,
            'mobile_code' => 'regex:/^([+\(\)0-9A-Za-z\-\,\.\s])+$/',
            'mobile_phone' => 'regex:/^([0-9\-]){5,}+$/',
            'skype' => 'regex:/^[A-Za-z0-9\.\-\!\_]+$/',
            'url' => 'regex:/^([a-z0-9\.\-\_])+$/',
        ];
    }

    public function messages() {
        return[
            'fname.required' => 'Please provide user\'s first name!',
            'fname.regex' => 'First name should be at least 2 letters.',
            'lname.required' => 'Please provide user\'s last name!',
            'lname.regex' => 'Last name should be at least 2 letters.',
            'role.required' => 'The role format is invalid!',
            'email.required' => 'Please prvide user\'s email address!',
            'email.email' => 'Please enter a  valid email address.',
            'email.unique' => 'The email address is already in use!',
            'password.required' => 'Please provide user\'s password!',
            'password.min' => 'Password must be at least 8 characters!',
            'merchant.regex' => 'Please choose company from list!',
            'mobile_phone.regex' => 'Please enter a valid phone number.',
            'skype.regex' => 'Skype username may contain only: letters, numbers, ".","_","!" and "-"!',
        ];
    }

}
