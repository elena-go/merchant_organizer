<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class SigninValidation extends Request {

    public function authorize() {
        /*
         * return false : nobody cant send post reuqests. 
         * you can disable it to some persons, like 
         * if they are in blacklist or something
         */
        return true;
        
    }

    public function rules() {
        return [
            'email' => 'required',
            'password' => 'required|min:8',
        ];
    }
    public function messages(){
        return[
            'email.required' => 'Please provide your email.',
            'password.required' => 'Please provide your password.',
            'password.min' => 'Be sure you provide correct password.',
            ];
    }

}
