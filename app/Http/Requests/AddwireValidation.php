<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Session;

class AddwireValidation extends Request {

    public function authorize() {
        /* return false : nobody cant send post reuqest to here. you can disable it to some persons, like if they are in blacklist or something */
        return true;
    }

    public function rules() {
        if(Session::has('is_sadmin') || Session::has('is_admin')){
            $rule = 'required|regex:/^([0-9]+)+{1,255}$/';
        }else{
            $rule = 'regex:/^([0-9]+){1,255}$/';
        }
        return [
            'client_name' => 'required|regex:/^[a-zA-Z\s\.]{2,255}+$/',
            'mobile_code' => 'required|regex:/^([+\(\)0-9A-Za-z\-\,\.\s])+$/',
            'mobile_phone' => 'required|regex:/^([0-9\-]){5,}+$/',
            'client_email' => 'required|email',
            'merchant' => $rule,
            'bank' => 'required|regex:/^([a-zA-Z0-9&\(\)\s&]+)$/',
            'currency' => 'required|regex:/^([A-Z]){3}$/',
            'amount_sent' => 'required|regex:/^[1-9]([0-9]){1,10}(\.([0-9]){1,2})?$/',
            'percent' => 'regex:/^[0-9]{1,2}(\.([0-9]{1})+)?$/',
            'amountafterpercent' => 'regex:/^([\-])$/',
            'country' => 'required|numeric',
            'kyc' => 'string',
            'wc' => 'string',
        ];
    }

    public function messages() {
        return[
            'client_name.required' => 'Please provide client\'s name!',
            'client_name.regex' => 'Client\'s name should be at least 2 letters.',
            'mobile_code.regex' => 'Please choose a phone code from a list.',
            'mobile_phone.regex' => 'Please enter a valid phone number',
            'client_email.required' => 'Please provide client\'s email!',
            'client_email.email' => 'Please provide a correct email address!',
            'merchant.required' => 'Please choose a company from list!',
            'merchant.regex' => 'Please choose a company from list!',
            'bank.regex' => 'Please choose a bank from the list!',
            'currency.regex' => 'Please choose a currency from the list!',
            'amount_sent.required' => 'Please provide sending amount!',
            'amount_sent.regex' => 'Amount field is invalid.',
            'country.required' => 'Please choose a country from the list!',
            'country.numeric' => 'Please choose a country from the list!',
            'kyc.string' => 'Please choose the KYC status from the list',
            'wc.boolean' => 'Please choose the world check status from the list.',
            'bank.regex' => 'Please choose a bank from the list!',
            'currency.numeric' => 'Please choose a currency from the list!'
        ];
    }

}
