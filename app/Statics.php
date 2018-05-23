<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
use DB;

class Statics extends Model {
    /* All the data for Billing page */
    static public function getForBilling(&$data) {
        /* Receiving data due to filtered needs */
        if (!empty($_GET)) {
            $dates = explode(' ', $_GET['date']);
            $bfrom_date = $dates[0];
            $bto_date = $dates[2];
            $bmerchant = $_GET['merchant'];
            $bbank = $_GET['bank'];
            $bcurrency_r = $_GET['currency_r'];
            $bcurrency_s = $_GET['currency_s'];
            if (!empty($_GET['wc'])) {
                $bwc = $_GET['wc'];
            }
            $filtered_wire = DB::table('wires')
                    ->leftjoin('merchants', 'wires.merchant_id', '=', 'merchants.merch_id')
                    ->leftjoin('banks', 'wires.sent_to_bank', '=', 'banks.bank_id')
                    ->leftjoin('countries', 'wires.sending_country', '=', 'countries.id')
                    ->leftJoin('phones', 'wires.client_phone_code', '=', 'phones.id')
                    ->select('wires.*', 'banks.name as bank_name', 'countries.country as country_name', 'merchants.name as merch_name', 'phones.code as m_code', 'phones.country as m_country')
                    ->where('merchants.updated', '=', 1)
                    ->where('wires.updated', '=', 1)
                    ->where('wires.active', '=', 1)
                    ->whereNotNull('wires.received_on')
                    ->where('wires.received_on', '>=', $bfrom_date)
                    ->where('wires.received_on', '<=', $bto_date)
                    ->whereIn('wires.status', [5]);
            if ($bmerchant != "-") {
                $filtered_wire = $filtered_wire->where('wires.merchant_id', '=', $bmerchant);
            }
            if ($bbank != "-") {
                $filtered_wire = $filtered_wire->where('wires.sent_to_bank', '=', $bbank);
            }
            if ($bcurrency_r != "-") {
                $filtered_wire = $filtered_wire->where('wires.currency_received', '=', $bcurrency_r);
            }
            if ($bcurrency_s != "-") {
                $filtered_wire = $filtered_wire->where('wires.currency', '=', $bcurrency_s);
            }
            if (isset($bwc) && $bwc != "-") {
                if (is_array($bwc)) {
                    $filtered_wire = $filtered_wire->whereIn('wires.wc', $bwc);
                }
            }
            $data['wires'] = $filtered_wire->get();
            /* Get Balance due to filtered data */
            $currencies = DB::table('wires')
                    ->select('currency_received')
                    ->where('amount_received', '>', 0)
                    ->where('received_on', '>=', $bfrom_date)
                    ->where('received_on', '<=', $bto_date);
            if ($bmerchant != "-") {
                $currencies = $currencies->where('wires.merchant_id', '=', $bmerchant);
            }
            if ($bbank != "-") {
                $currencies = $currencies->where('wires.sent_to_bank', '=', $bbank);
            }
            if ($bcurrency_r != "-") {
                $currencies = $currencies->where('wires.currency_received', '=', $bcurrency_r);
            }
            if ($bcurrency_s != "-") {
                $currencies = $currencies->where('wires.currency', '=', $bcurrency_s);
            }
            if (isset($bwc) && $bwc != "-") {
                if (is_array($bwc)) {
                    $currencies = $currencies->whereIn('wires.wc', $bwc);
                }
            }
            $currencies = $currencies->groupBy('currency_received');
            $currencies = $currencies->get();

            foreach ($currencies as $curr) {
                $balances = DB::table('wires')
                        ->select(DB::raw('sum(wires.amount_received) AS total_amount_received'), DB::raw('sum(wires.amount_of_percent) AS total_amount_of_percent'), DB::raw('sum(wires.amountafterpercent) AS total_amountafterpercent'), 'currency_received')
                        ->where('active', '=', 1)
                        ->where('updated', '=', 1)
                        ->where('amount_received', '>', 0)
                        ->where('received_on', '>=', $bfrom_date)
                        ->where('received_on', '<=', $bto_date)
                        ->where('wires.status', '=', 5)
                        ->where('currency_received', '=', $curr->currency_received);
                if ($bmerchant != "-") {
                    $balances = $balances->where('wires.merchant_id', $bmerchant);
                }
                if ($bbank != "-") {
                    $balances = $balances->where('wires.sent_to_bank', $bbank);
                }
                if ($bcurrency_r != "-") {
                    $balances = $balances->where('wires.currency_received', $bcurrency_r);
                }
                if ($bcurrency_s != "-") {
                    $balances = $balances->where('wires.currency', $bcurrency_s);
                }
                $data['balances'][] = $balances->get();
            }

            $data['banks'] = DB::table('banks')
                    ->where('status','=',1)
                    ->where('updated','=',1)
                    ->get();
            $data['merchants'] = DB::table('merchants')
                    ->where('updated', '=', 1)
                    ->get();
            $data['currencys'] = DB::table('currencys')->get();
        } else {
            /* Default received data for Tech Team, Wire Manager and Wire Team */
            $data['wires'] = [];
            if (Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w')) {
                $two_weeks_ago = strtotime(date('d-m-Y')) - 2 * 7 * 24;
                $two_weeks_ago = date('m-d-Y', $two_weeks_ago);
                $data['wires'] = DB::table('wires')
                        ->leftjoin('merchants', 'wires.merchant_id', '=', 'merchants.merch_id')
                        ->leftjoin('banks', 'wires.sent_to_bank', '=', 'banks.bank_id')
                        ->leftJoin('phones', 'wires.client_phone_code', '=', 'phones.id')
                        ->leftjoin('countries', 'wires.sending_country', '=', 'countries.id')
                        ->whereIn('wires.status', [5])
                        ->where('wires.received_on', '>=', $two_weeks_ago)
                        ->where('merchants.status', '=', 1)
                        ->where('merchants.updated', '=', 1)
                        ->where('wires.updated', '=', 1)
                        ->where('wires.active', '=', 1)
                        ->select('wires.*', 'banks.name as bank_name', 'countries.country as country_name', 'merchants.name as merch_name', 'phones.code as m_code', 'phones.country as m_country')
                        ->get();
                if (!empty($data['wires']->client_phone)) {
                    $data['phone'] = explode(")", $data['wires']->client_phone);
                } else {
                    $data['phone'][0] = "";
                    $data['phone'][1] = "";
                }
                $data['banks'] = DB::table('banks')->where('status', '=', 1)->get();
                $data['merchants'] = DB::table('merchants')
                        ->where('updated', '=', 1)
                        ->get();
                $data['currencys'] = DB::table('currencys')->get();
                $currencies = DB::table('wires')
                        ->leftjoin('merchants', 'wires.merchant_id', '=', 'merchants.merch_id')
                        ->select('wires.currency_received')
                        ->where('wires.amount_received', '>', 0)
                        ->where('merchants.status', '=', 1)
                        ->where('wires.updated', '=', 1)
                        ->where('wires.active', '=', 1)
                        ->groupBy('wires.currency_received')
                        ->get();
                foreach ($currencies as $curr) {
                    $data['balances'][] = DB::table('wires')
                            ->leftjoin('merchants', 'wires.merchant_id', '=', 'merchants.merch_id')
                            ->select(DB::raw('sum(wires.amount_received) AS total_amount_received'), DB::raw('sum(wires.amount_of_percent) AS total_amount_of_percent'), DB::raw('sum(wires.amountafterpercent) AS total_amountafterpercent'), 'currency_received')
                            ->where('wires.active', '=', 1)
                            ->where('merchants.updated', '=', 1)
                            ->where('merchants.status', '=', 1)
                            ->where('wires.amount_received', '>', 0)
                            ->whereIn('wires.status', [5])
                            ->where('wires.updated', '=', 1)
                            ->where('wires.currency_received', '=', $curr->currency_received)
                            ->get();
                }
            }
        }
    }
    static public function getData(&$data) {
        if (!empty($_GET)) {
            $dates = explode(' ', $_GET['date']);
            $bfrom_date = $dates[0];
            $bto_date = $dates[2];
            $bmerchant = $_GET['merchant'];
            $data['count_wires_per_curr'] = DB::table('wires')
                    ->select('currency_received as currency', DB::raw('count(id) AS wire_count'))
                    ->where('amount_received', '>', 0)
                    ->where('updated', '=', 1)
                    ->where('active', '=', 1)
                    ->where('received_on', '>=', $bfrom_date)
                    ->where('received_on', '<=', $bto_date);
            if ($bmerchant != "-") {
                $data['count_wires_per_curr'] = $data['count_wires_per_curr']->where('wires.merchant_id', '=', $bmerchant);
            }
            $data['count_wires_per_curr'] = $data['count_wires_per_curr']->groupBy('currency_received');
            $data['count_wires_per_curr'] = $data['count_wires_per_curr']->get();

            $data['sum_received_wires_per_curr'] = DB::table('wires')
                    ->select('currency_received as currency', DB::raw('sum(wires.amount_received) AS total_received'), DB::raw('sum(wires.amount_of_percent) AS total_percent'), DB::raw('sum(wires.amountafterpercent) AS total_after_percent'))
                    ->where('amount_received', '>', 0)
                    ->where('updated', '=', 1)
                    ->where('active', '=', 1)
                    ->where('received_on', '>=', $bfrom_date)
                    ->where('received_on', '<=', $bto_date);
            if ($bmerchant != "-") {
                $data['sum_received_wires_per_curr'] = $data['sum_received_wires_per_curr']->where('wires.merchant_id', '=', $bmerchant);
            }
            $data['sum_received_wires_per_curr'] = $data['sum_received_wires_per_curr']->groupBy('currency_received');
            $data['sum_received_wires_per_curr'] = $data['sum_received_wires_per_curr']->get();

            $merchant_group = DB::table('wires')
                    ->select('merchant_id')
                    ->where('amount_received', '>', 0)
                    ->where('updated', '=', 1)
                    ->where('active', '=', 1)
                    ->where('received_on', '>=', $bfrom_date)
                    ->where('received_on', '<=', $bto_date);
            if ($bmerchant != "-") {
                $merchant_group = $merchant_group->where('wires.merchant_id', '=', $bmerchant);
            }
            $merchant_group = $merchant_group->groupBy('merchant_id');
            $merchant_group = $merchant_group->get();

            foreach ($merchant_group as $merchant) {
                $sum_per_merch = DB::table('wires')
                        ->join('merchants', 'wires.merchant_id', '=', 'merchants.merch_id')
                        ->select('merchants.name as m_name', 'currency_received as currency', DB::raw('sum(wires.amount_received) AS total_received'), DB::raw('sum(wires.amount_of_percent) AS total_percent'), DB::raw('sum(wires.amountafterpercent) AS total_after_percent'))
                        ->where('wires.amount_received', '>', 0)
                        ->where('wires.updated', '=', 1)
                        ->where('wires.active', '=', 1)
                        ->where('wires.merchant_id', '=', $merchant->merchant_id)
                        ->where('merchants.updated','=',1)
                        ->where('wires.received_on', '>=', $bfrom_date)
                        ->where('wires.received_on', '<=', $bto_date);
                if ($bmerchant != "-") {
                    $sum_per_merch = $sum_per_merch->where('wires.merchant_id', '=', $bmerchant);
                }
                $sum_per_merch = $sum_per_merch->groupBy('wires.currency_received');
                $sum_per_merch = $sum_per_merch->get();
                $data['sum_received_wires_per_merch'][] = $sum_per_merch;
            }
            $bank_group = DB::table('wires')
                    ->select('sent_to_bank')
                    ->where('amount_received', '>', 0)
                    ->where('updated', '=', 1)
                    ->where('active', '=', 1)
                    ->where('received_on', '>=', $bfrom_date)
                    ->where('received_on', '<=', $bto_date);
            if ($bmerchant != "-") {
                $bank_group = $bank_group->where('wires.merchant_id', '=', $bmerchant);
            }
            $bank_group = $bank_group->groupBy('sent_to_bank');
            $bank_group = $bank_group->get();
            foreach ($bank_group as $bank) {
                $sum_per_bank = DB::table('wires')
                        ->join('banks', 'wires.sent_to_bank', '=', 'banks.bank_id')
                        ->select('banks.name as b_name', 'currency_received as currency', DB::raw('sum(wires.amount_received) AS total_received'), DB::raw('sum(wires.amount_of_percent) AS total_percent'), DB::raw('sum(wires.amountafterpercent) AS total_after_percent'))
                        ->where('wires.amount_received', '>', 0)
                        ->where('wires.updated', '=', 1)
                        ->where('wires.active', '=', 1)
                        ->where('wires.sent_to_bank', '=', $bank->sent_to_bank)
                        ->where('banks.updated','=',1)
                        ->where('wires.received_on', '>=', $bfrom_date)
                        ->where('wires.received_on', '<=', $bto_date);
                if ($bmerchant != "-") {
                    $sum_per_bank = $sum_per_bank->where('wires.merchant_id', '=', $bmerchant);
                }
                $sum_per_bank = $sum_per_bank->groupBy('wires.currency_received');
                $sum_per_bank = $sum_per_bank->get();
                $data['sum_received_wires_per_bank'][] = $sum_per_bank;
            }
        } else {
            $data['count_wires_per_curr'] = DB::table('wires')
                    ->select('currency_received as currency', DB::raw('count(id) AS wire_count'))
                    ->where('amount_received', '>', 0)
                    ->where('updated', '=', 1)
                    ->where('active', '=', 1)
                    ->groupBy('currency_received')
                    ->get();
            $data['sum_received_wires_per_curr'] = DB::table('wires')
                    ->select('currency_received as currency', DB::raw('sum(wires.amount_received) AS total_received'), DB::raw('sum(wires.amount_of_percent) AS total_percent'), DB::raw('sum(wires.amountafterpercent) AS total_after_percent'))
                    ->where('amount_received', '>', 0)
                    ->where('updated', '=', 1)
                    ->where('active', '=', 1)
                    ->groupBy('currency_received')
                    ->get();
            $merchant_group = DB::table('wires')
                    ->select('merchant_id')
                    ->where('amount_received', '>', 0)
                    ->where('updated', '=', 1)
                    ->where('active', '=', 1)
                    ->groupBy('merchant_id')
                    ->get();
            foreach ($merchant_group as $merchant) {
                $data['sum_received_wires_per_merch'][] = DB::table('wires')
                        ->join('merchants', 'wires.merchant_id', '=', 'merchants.merch_id')
                        ->select('merchants.name as m_name', 'currency_received as currency', DB::raw('sum(wires.amount_received) AS total_received'), DB::raw('sum(wires.amount_of_percent) AS total_percent'), DB::raw('sum(wires.amountafterpercent) AS total_after_percent'))
                        ->where('wires.merchant_id', '=', $merchant->merchant_id)
                        ->where('merchants.updated','=',1)
                        ->where('wires.amount_received', '>', 0)
                        ->where('wires.active', '=', 1)
                        ->where('wires.updated', '=', 1)
                        ->groupBy('wires.currency_received')
                        ->get();
            }
            $bank_group = DB::table('wires')
                    ->select('sent_to_bank')
                    ->where('amount_received', '>', 0)
                    ->where('updated', '=', 1)
                    ->where('active', '=', 1)
                    ->groupBy('sent_to_bank')
                    ->get();
            foreach ($bank_group as $bank) {
                $data['sum_received_wires_per_bank'][] = DB::table('wires')
                        ->join('banks', 'wires.sent_to_bank', '=', 'banks.bank_id')
                        ->select('banks.name as b_name', 'currency_received as currency', DB::raw('sum(wires.amount_received) AS total_received'), DB::raw('sum(wires.amount_of_percent) AS total_percent'), DB::raw('sum(wires.amountafterpercent) AS total_after_percent'))
                        ->where('wires.amount_received', '>', 0)
                        ->where('wires.updated', '=', 1)
                        ->where('wires.active', '=', 1)
                        ->where('wires.sent_to_bank', '=', $bank->sent_to_bank)
                        ->where('banks.updated','=',1)
                        ->groupBy('wires.currency_received')
                        ->get();
            }
        }
    }
}
