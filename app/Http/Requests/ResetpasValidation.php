<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class ResetpasValidation extends Request {

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
            'email' => 'required|email|exists:users,email',
        ];
    }
    public function messages(){
        return[
            'email.required' => 'Please provide your email.',
            'email.email' => 'Be sure you provide correct email.',
            'email.exists' => 'Please enter your registered email address.'
            ];
    }

}
