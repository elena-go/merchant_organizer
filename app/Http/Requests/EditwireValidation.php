<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Session;

class EditwireValidation extends Request {

    public function authorize() {
        /* return false : nobody cant send post reuqest to here. you can disable it to some persons, like if they are in blacklist or something */
        return true;
    }
    public function rules() {
        if(Session::has('active_m')){
        return [
            'client_name' => 'required|min:2|max:250|regex:/^[a-zA-Z\s\.]+$/',
            'kyc' => 'required|string',
            
        ];
        }elseif(Session::has('active_b')){
        return [
            'received_on' => 'required_with:amount_received',
            'currency_received' => 'required_with:amount_received|regex:/^([A-Z]{3})$/',
            'amount_received' => 'required_with:currency_received|regex:/^([1-90]){1,10}(\.([0-9]){1,2})?$/'
        ];
        }else{
            return [
            'status' => 'regex:/^[1]?[2]?[3]?[4]?[5]?[6]?$/',
            'client_name' => 'required|min:2|max:255|regex:/^[a-zA-Z\s\.]+$/',
            'mobile_code' => 'required|regex:/^([+\(\)0-9A-Za-z\-\,\.\s])+$/',
            'mobile_phone' => 'required|regex:/^([0-9\-]){5,}+$/',
            'client_email' => 'required|email',
            'merchant' => 'regex:/^([a-zA-Z0-9\s\-]{1,50})$/',
            'bank' => 'required|regex:/^([a-zA-Z0-9&\(\)\s]+)$/',
            'currency' => 'required|regex:/^([A-Z]{3})$/',
            'amount_sent' => 'required|regex:/^([1-90]){1,10}(\.([0-9]){1,2})?$/',
            'currency_received' => 'required_with:amount_received|regex:/^([A-Z]{3})$/',
            'amount_received' => 'required_with:currency_received|regex:/^([1-90]){1,10}(\.([0-9]){1,2})?$/',
            'received_on' => 'required_with:amount_received',
            'percent' => 'regex:/^[0-9]{1,2}(\.([0-9]{1})+)?$/',
            'country' => 'required|numeric',
            'kyc' => 'required|string',
            'wc' => 'string',
        ];
        }
    }

    public function messages() {
        return[
            'client_name.required' => 'Please provide client\'s name!',
            'client_name.regex' => 'Client\'s name should be at least 2 letters.',
            'mobile_code.regex' => 'Please choose a phone code from a list.',
            'mobile_phone.regex' => 'Please enter a valid phone number',
            'client_email.required' => 'Please provide client\'s email!',
            'amount_sent.required' => 'Please provide sending amount!',
            'amount_sent.regex' => 'Amount field is invalid.',
            'country' => 'Please choose a country from the list',
            'kyc.string' => 'Please choose the KYC status from the list',
            'wc.boolean' => 'Please choose the World Check status from the list.',
            'kyc.required' => 'Please choose the KYC status from the list.',
            'currency.regex' => 'Please choose a currency from the list!',
            'currency_received.required_with' => 'Please enter received funds\' currency!',
            'amount_received.required_with' => 'Please enter the received amount!',
            'amount_received.regex' => 'Please enter a valid the amount received! It should contain only numbers (first not 0) and "." !',
            'received_on.required_with' => 'Please enter date for receive amount',
            'country.numeric' => 'Please choose a country from the list.',
            'client_email.email' => 'Pase enter a valid email address.'
        ];
    }

}
