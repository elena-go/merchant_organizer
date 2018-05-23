<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use File;

class EditbankValidation extends Request {

    public function authorize() {
        /* return false : nobody cant send post reuqest to here. you can disable it to some persons, like if they are in blacklist or something */
        return true;
    }

    public function rules() {
        return [
            'name' => 'required|regex:/^[a-zA-Z\s\.\&\(\)]{3,255}+$/',
            'file' => 'mimes:pdf|max:3000'
        ];
    }

    public function messages() {
        return[
            'name.required' => 'Please provide bank\'s name!',
            'name.regex' => 'Bank\'s name should be at least 3 characters, can contain only letters, space, ".", "&" and brackets!',
            'file.mimes' => 'Upploaded file must be PDF only!',
            'bank_file.max' => 'Upploaded file\'s size is too big!'
        ];
    }

}
