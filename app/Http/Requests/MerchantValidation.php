<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class MerchantValidation extends Request {

    public function authorize() {
        /* return false : nobody cant send post reuqest to here. you can disable it to some persons, like if they are in blacklist or something */
        return true;
    }

    public function rules() {
        return [
            'name' => 'required|regex:/^[0-9a-zA-Z\s\.\-\&\+\,]{2,255}+$/',
            'email' => 'required|email|min:5',
            'mobile_code' => 'required|regex:/^([+\(\)0-9A-Za-z\-\,\.\s])+$/',
            'mobile_phone' => 'required|regex:/^([0-9\-]){5,}+$/',
            'landline_code' => 'regex:/^([+\(\)0-9A-Za-z\-\,\.\s])+$/',
            'landline_phone' => 'regex:/^([0-9\-]){5,}+$/',
            'skype' => 'required|regex:/^([A-Za-z0-9\.\-\_\!\@]{3,}+)$/',
            'address' => 'regex:/^([\w\d\s.-\/\,]{3,}+)$/',
            'bank_name' => 'required|regex:/^([a-zA-Z0-9\(\)\s]{3,}+)$/',
            'bank_address' => 'required|regex:/^([\w\d\s.-\/\,]{3,}+)$/',
            'account_holder' => 'required|regex:/^([a-zA-Z0-9\.\s\-\&\+\,]{2,}+)$/',
            'beneficiary_address' => 'required|regex:/^([\w\d\s.-\/\,]{3,}+)$/',
            'eur_iban' => 'required|regex:/^[A-Z0-9]+$/',
            'reference' => 'regex:/^[A-Za-z0-9]+$/',
            'swift_bic' => 'required|regex:/^[A-Z0-9]+$/',
            'currency' => 'required|array',
            'bank' => 'required|array'
        ];
    }

    public function messages() {
        return[
            'name.regex' => 'Company name field should be at least 3 characters',
            'account_holder.required' => 'The beneficiary name field is required.',
            'eur_iban.required' => 'The EUR IBAN field is required.',
            'swift_bic.required' => 'The SWIFT/BIC field is required.',
            'fname.regex' => 'The first name field should contain 2-30 letters, space and ".".',
            'lname.regex' => 'The last name field should contain 2-30 letters, space and ".".',
            'account_holder.regex' => 'Beneficiary name field should be at least 2 characters.',
            'address' => 'Address field should be at least 3 characters.',
            'skype.regex' => 'Skype field should be at least 3 characters.',
            'eur_iban.regex' => 'The EUR IBAN field is invalid.',
            'swift_bic.regex' => 'SWIFT/BIC Code field should be at least 8 characters ',
            'currency.required' => 'Please choose currencies from the list',
            'email.email' => 'Please enter a valid email address',
            'mobile_phone.regex' => 'Please use valid mobile phone number format: +(123)456-7890',
            'landine_phone.regex' => 'Please use valid landline phone number format: (123)456-7890',
            'bank_name' => 'Please provide bank\'s name. It may contain at least 3 characters,which can be: letters, numbers and space.',
            'bank_address' => 'Bank address field should be at least 3 characters.',
            'beneficiary_address.regex' =>'Beneficiary address field should be at least 3 characters.'
        ];
    }

}
