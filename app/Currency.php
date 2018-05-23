<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
use DB;

class Currency extends Model {
    static public function getCurrency(&$data) {
        $data['currencys'] = DB::table('currencys')->get();
    }
    static public function getTotalForCurrencies(&$data) {
        $data['total_curr'] = DB::table('currencys')
                ->rightjoin('wires', 'currencys.name', '=', 'wires.currency_received')
                ->groupBy('wires.currency_received')
                ->get();
        foreach ($data['total_curr'] as $row) {
            $total = DB::table('wires')
                    ->select('amount_received')
                    ->where('currency_received', '=', $row->currency_received)
                    ->sum('amount_received');
            $data['total_amount'][] = $total;
        }
    }
    static public function deleteMerchCurr($id) {
        DB::table('merch_currencys')->where('merchant_id', '=', $id)->delete();
    }
}
