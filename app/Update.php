<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
use DB;

class Update extends Model {
    /*Data for Updates page*/
    static public function getUpdates(&$data) {
        $data['wire_updates'] = DB::table('wires')
                ->leftJoin('users', 'wires.changed_by', '=', 'users.id')
                ->leftjoin('merchants', 'wires.merchant_id', '=', 'merchants.id')
                ->leftjoin('banks', 'wires.sent_to_bank', '=', 'banks.id')
                ->leftjoin('currencys', 'wires.currency', '=', 'currencys.id')
                ->select('wires.*', 'users.name as user_name', 'users.lastname as user_lastname', 'merchants.name as merch_name', 'banks.name as bank_name', 'currencys.name as currency_name')
                ->get();
        $data['merchant_updates'] = DB::table('merchants')
                ->leftJoin('users', 'merchants.changed_by', '=', 'users.id')
                ->select('merchants.*', 'users.name as user_name', 'users.lastname as user_lastname')
                ->get();
        foreach ($data['merchant_updates'] as $merch) {
            $currency_arr = explode(',', $merch->available_currencies);
            $data['merch_currency'][$merch->id] = DB::table('currencys')
                    ->whereIn('id', $currency_arr)
                    ->get();
            $bank_arr = explode(',', $merch->available_banks);
            $data['merch_bank'][$merch->id] = DB::table('banks')
                    ->whereIn('banks.id', $bank_arr)
                    ->get();
        }
        /* Get name of user who made update, not user's name by it's ID */
        $data['user_updates'] = DB::table('users')->get();
        
        $data['bank_updates'] = DB::table('banks')
                ->leftJoin('users', 'banks.changed_by', '=', 'users.id')
                ->select('banks.*', 'users.name as user_name', 'users.lastname as user_lastname')
                ->get();
    }

}
