<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Input;

class ResetPassword extends Request {

    public function authorize() {
        /*return false : nobody cant send post reuqest to here. you can disable it to some persons, like if they are in blacklist or something*/
        return true;
        
    }

    public function rules() {
        return [
            'password' => 'required|min:8',
            'conf-password' => 'required|same:password'
        ];
    }
    public function messages(){
        return[
            'password.required' => 'Please provide password!',
            'password.min' => 'Your new password must contain at least 8 characters!',
            'conf-password.required' => 'Please confirm your password!',
            'conf-password.same' => 'Passwords aren\'t the same!'
            ];
    }

}
