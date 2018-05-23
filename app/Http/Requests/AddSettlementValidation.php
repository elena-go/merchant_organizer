<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class AddSettlementValidation extends Request {

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
            'merchant' => 'required|numeric',
            'file' => 'required|image',
        ];
    }
    public function messages(){
        return[
            'merchant.required' => 'Please provide company name.',
            'merchant.regex' => 'Be sure you provide correct company name.',
            'file.required' => 'Please choose file.'
            ];
    }

}
