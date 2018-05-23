<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;
use Session;
use Update;
use DB;

class Wire extends Model {
    static public function getWires(&$data) {
        /* Receive wires due to filtered needs */
        if (!empty($_GET)) {
            Session::put('filters', $_SERVER['QUERY_STRING']);
            $date = explode(' ', $_GET['date']);
            $from_date = $date[0];
            $to_date = $date[2];
            $status = $_GET['status'];
            $merchant = $_GET['merchant'];
            $bank = $_GET['bank'];
            if (isset($_GET['currency_r'])) {
                $currency_r = $_GET['currency_r'];
            } else {
                $currency_r = '-';
            }
            if (isset($_GET['currency_s'])) {
                $currency_s = $_GET['currency_s'];
            } else {
                $currency_s = '-';
            }
            $kyc = $_GET['kyc'];
            if (!empty($_GET['wc'])) {
                $wc = $_GET['wc'];
            }
            $filtered_wire = DB::table('wires')
                    ->leftjoin('merchants', 'wires.merchant_id', '=', 'merchants.merch_id')
                    ->leftJoin('phones', 'wires.client_phone_code', '=', 'phones.id')
                    ->leftjoin('banks', 'wires.sent_to_bank', '=', 'banks.bank_id')
                    ->leftjoin('currencys', 'wires.currency', '=', 'currencys.id')
                    ->leftjoin('countries', 'wires.sending_country', '=', 'countries.id')
                    ->select('wires.*', 'merchants.name as merch_name', 'banks.name as bank_name', 'currencys.name as currency_name', 'countries.country as country_name', 'phones.code as m_code', 'phones.country as m_country')
                    ->where('merchants.updated', '=', 1)
                    ->where('wires.updated', '=', 1)
                    ->where('wires.active', '=', 1)
                    ->where('banks.updated', '=', 1)
                    ->where('wires.sent_on', '>=', $from_date)
                    ->where('wires.sent_on', '<=', $to_date);
            if ($status != "-") {
                $filtered_wire = $filtered_wire->where('wires.status', $status);
            }
            if (!Session::has('active_m') || !Session::has('limited_m'))
                if ($merchant != "-") {
                    $filtered_wire = $filtered_wire->where('wires.merchant_id', $merchant);
                } elseif (Session::has('active_m')) {
                    $filtered_wire = $filtered_wire->where('wires.merchant_id', '=', Session::get('active_m'));
                } elseif (Session::has('limited_m')) {
                    $filtered_wire = $filtered_wire->where('wires.merchant_id', '=', Session::get('limited_m'));
                }
            if ($bank != "-") {
                $filtered_wire = $filtered_wire->where('wires.sent_to_bank', $bank);
            }
            if ($currency_r != "-") {
                $filtered_wire = $filtered_wire->where('wires.currency_received', $currency_r);
            }
            if ($currency_s != "-") {
                $filtered_wire = $filtered_wire->where('wires.currency', $currency_s);
            }
            if ($kyc != "-") {
                if (is_array($kyc)) {
                    $filtered_wire = $filtered_wire->whereIn('wires.kyc', $kyc);
                }
            }
            if (isset($wc) && $wc != "-") {
                if (is_array($wc)) {
                    $filtered_wire = $filtered_wire->whereIn('wires.wc', $wc);
                }
            }
            $filtered_wire = $filtered_wire->orderBy('wires.sent_on', 'desc');
            $data['wires'] = $filtered_wire->get();
            $filter = DB::table('wires')
                    ->select('currency_received')
                    ->where('amount_received', '>', 0)
                    ->where('wires.sent_on', '>=', $from_date)
                    ->where('wires.sent_on', '<=', $to_date);
            if ($currency_r != "-") {
                $filter = $filter->where('currency_received', $currency_r);
            }
            if ($currency_s != "-") {
                $filter = $filter->where('wires.currency', $currency_s);
            }
            $filter->groupBy('currency_received');
            $currencies = $filter->get();
            foreach ($currencies as $curr) {
                $data['balances'][] = DB::table('wires')
                        ->select(DB::raw('sum(wires.amount_received) AS total_amount_received'), DB::raw('sum(wires.amount_of_percent) AS total_amount_of_percent'), DB::raw('sum(wires.amountafterpercent) AS total_amountafterpercent'), 'currency_received')
                        ->where('active', 1)
                        ->where('amount_received', '>', 0)
                        ->whereIn('status', [5])
                        ->where('updated', '=', 1)
                        ->where('wires.currency_received', '=', $curr->currency_received)
                        ->where('wires.sent_on', '>=', $from_date)
                        ->where('wires.sent_on', '<=', $to_date)
                        ->get();
            }
            $data['received_w_count'] = DB::table('wires')
                    ->select(DB::raw('COUNT(id) as wire_count'))
                    ->where('amount_received', '>', 0)
                    ->where('active', '=', 1)
                    ->where('updated', '=', 1)
                    ->get();
        } else {
            /* Default full data */
            $data['received_w_count'] = DB::table('wires')
                    ->select(DB::raw('COUNT(id) as wire_count'))
                    ->where('amount_received', '>', 0)
                    ->where('active', '=', 1)
                    ->where('updated', '=', 1)
                    ->get();
            $data['wires'] = [];
            $month = date('m');
            /* Data for Tech Team */
            if (Session::has('active_sa')) {
                $data['wires'] = DB::table('wires')
                        ->leftjoin('merchants', 'wires.merchant_id', '=', 'merchants.merch_id')
                        ->leftJoin('phones', 'wires.client_phone_code', '=', 'phones.id')
                        ->leftjoin('banks', 'wires.sent_to_bank', '=', 'banks.bank_id')
                        ->leftjoin('currencys', 'wires.currency', '=', 'currencys.id')
                        ->leftjoin('countries', 'wires.sending_country', '=', 'countries.id')
                        ->select('wires.*', 'merchants.name as merch_name', 'phones.code as m_code', 'phones.country as m_country', 'banks.name as bank_name', 'currencys.name as currency_name', 'countries.country as country_name')
                        ->where('merchants.updated', '=', 1)
                        ->where('banks.updated', '=', 1)
                        ->where('wires.updated', '=', 1)
                        ->get();
                if (!empty($data['wires']->client_phone)) {
                    $data['phone'] = explode(")", $data['wires']->client_phone);
                } else {
                    $data['phone'][0] = "";
                    $data['phone'][1] = "";
                }
                $currencies = DB::table('wires')
                        ->select('currency_received', 'received_on')
                        ->where('amount_received', '>', 0)
                        ->groupBy('currency_received')
                        ->get();
                if (!empty($currencies)) {
                    foreach ($currencies as $curr) {
                        $data['balances'][] = DB::table('wires')
                                ->select('received_on', DB::raw('sum(wires.amount_received) AS total_amount_received'), DB::raw('sum(wires.amount_of_percent) AS total_amount_of_percent'), DB::raw('sum(wires.amountafterpercent) AS total_amountafterpercent'), 'currency_received')
                                ->where('active', '=', 1)
                                ->where('amount_received', '>', 0)
                                ->whereIn('status', [5])
                                ->where('updated', '=', 1)
                                ->where(DB::raw('MONTH(received_on)'), '=', $month)
                                ->where('wires.currency_received', '=', $curr->currency_received)
                                ->get();
                    }
                }
            }/* Data for Wire Team, wire Manager and Bank's User */ elseif (Session::has('active_mw') || Session::has('active_w') || Session::has('active_b')) {
                $data['wires'] = DB::table('wires')
                        ->leftjoin('merchants', 'wires.merchant_id', '=', 'merchants.merch_id')
                        ->leftJoin('phones', 'wires.client_phone_code', '=', 'phones.id')
                        ->leftjoin('banks', 'wires.sent_to_bank', '=', 'banks.bank_id')
                        ->leftjoin('currencys', 'wires.currency', '=', 'currencys.id')
                        ->leftjoin('countries', 'wires.sending_country', '=', 'countries.id')
                        ->select('wires.*', 'merchants.name as merch_name', 'phones.code as m_code', 'phones.country as m_country', 'banks.name as bank_name', 'currencys.name as currency_name', 'countries.country as country_name')
                        ->where('merchants.updated', '=', 1)
                        ->where('wires.updated', '=', 1)
                        ->where('banks.updated', '=', 1)
                        ->where('wires.active', '=', 1)
                        ->get();
                $data['received_w_count'] = DB::table('wires')
                        ->select(DB::raw('COUNT(id) as wire_count'))
                        ->where('amount_received', '>', 0)
                        ->where('active', 1)
                        ->where('wires.updated', '=', 1)
                        ->get();
                if (!empty($data['wires']->client_phone)) {
                    $data['phone'] = explode(")", $data['wires']->client_phone);
                } else {
                    $data['phone'][0] = "";
                    $data['phone'][1] = "";
                }
                $currencies = DB::table('wires')
                        ->select('currency_received')
                        ->where('amount_received', '>', 0)
                        ->groupBy('currency_received')
                        ->get();
                foreach ($currencies as $curr) {
                    $data['balances'][] = DB::table('wires')
                            ->select(DB::raw('sum(wires.amount_received) AS total_amount_received'), DB::raw('sum(wires.amount_of_percent) AS total_amount_of_percent'), DB::raw('sum(wires.amountafterpercent) AS total_amountafterpercent'), 'currency_received')
                            ->where('active', 1)
                            ->where('amount_received', '>', 0)
                            ->whereIn('status', [5])
                            ->where('updated', '=', 1)
                            ->where('wires.currency_received', '=', $curr->currency_received)
                            ->get();
                }
            }/* Data for active and limited merchant's users */ else {
                /* For active Users */
                if (Session::has('active_m')) {
                    $data['received_w_count'] = DB::table('wires')
                            ->select(DB::raw('COUNT(id) as wire_count'))
                            ->where('amount_received', '>', 0)
                            ->where('wires.merchant_id', '=', Session::get('active_m'))
                            ->where('active', '=', 1)
                            ->where('updated', '=', 1)
                            ->get();
                    $data['wires'] = DB::table('wires')
                            ->leftjoin('merchants', 'wires.merchant_id', '=', 'merchants.merch_id')
                            ->leftJoin('phones', 'wires.client_phone_code', '=', 'phones.id')
                            ->leftjoin('banks', 'wires.sent_to_bank', '=', 'banks.bank_id')
                            ->leftjoin('currencys', 'wires.currency', '=', 'currencys.id')
                            ->leftjoin('countries', 'wires.sending_country', '=', 'countries.id')
                            ->select('wires.*', 'merchants.name as merch_name', 'phones.code as m_code', 'phones.country as m_country', 'banks.name as bank_name', 'currencys.name as currency_name', 'countries.country as country_name')
                            ->where('wires.merchant_id', '=', Session::get('active_m'))
                            ->where('merchants.updated', '=', 1)
                            ->where('wires.updated', '=', 1)
                            ->where('banks.updated', '=', 1)
                            ->where('wires.active', '=', 1)
                            ->get();
                    $approved_wires = DB::table('wires')
                            ->leftjoin('merchants', 'wires.merchant_id', '=', 'merchants.id')
                            ->join('users', 'users.merchant_id', '=', 'merchants.id')
                            ->where('wires.updated', '=', 1)
                            ->whereIn('wires.status', ['5', 'Complete'])
                            ->get();
                    $currencies = DB::table('wires')
                            ->select('currency_received')
                            ->where('merchant_id', '=', Session::get('active_m'))
                            ->where('amount_received', '>', 0)
                            ->groupBy('currency_received')
                            ->get();
                    if (!empty($currencies)) {
                        foreach ($currencies as $curr) {
                            $data['balances'][] = DB::table('wires')
                                    ->select(DB::raw('sum(wires.amount_received) AS total_amount_received'), DB::raw('sum(wires.amount_of_percent) AS total_amount_of_percent'), DB::raw('sum(wires.amountafterpercent) AS total_amountafterpercent'), 'currency_received')
                                    ->where('active', 1)
                                    ->where('merchant_id', '=', Session::get('active_m'))
                                    ->where('amount_received', '>', 0)
                                    ->whereIn('status', [5])
                                    ->where('updated', '=', 1)
                                    ->where('wires.currency_received', '=', $curr->currency_received)
                                    ->get();
                        }
                    } else {
                        $data['balances'] = [];
                    }
                }/* For Users whose merchant's has been archived */ elseif (Session::has('limited_m')) {
                    $data['received_w_count'] = DB::table('wires')
                            ->select(DB::raw('COUNT(id) as wire_count'))
                            ->where('amount_received', '>', 0)
                            ->where('wires.merchant_id', '=', Session::get('limited_m'))
                            ->where('active', 1)
                            ->where('wires.updated', '=', 1)
                            ->get();
                    $data['wires'] = DB::table('wires')
                            ->leftjoin('merchants', 'wires.merchant_id', '=', 'merchants.merch_id')
                            ->leftJoin('phones', 'wires.client_phone_code', '=', 'phones.id')
                            ->leftjoin('banks', 'wires.sent_to_bank', '=', 'banks.id')
                            ->leftjoin('currencys', 'wires.currency', '=', 'currencys.id')
                            ->leftjoin('countries', 'wires.sending_country', '=', 'countries.id')
                            ->select('wires.*', 'merchants.name as merch_name', 'phones.code as m_code', 'phones.country as m_country', 'banks.name as bank_name', 'currencys.name as currency_name', 'countries.country as country_name')
                            ->where('wires.merchant_id', '=', Session::get('limited_m'))
                            ->where('merchants.updated', '=', 1)
                            ->where('wires.updated', '=', 1)
                            ->where('wires.active', '=', 1)
                            ->get();
                    $approved_wires = DB::table('wires')
                            ->leftjoin('merchants', 'wires.merchant_id', '=', 'merchants.id')
                            ->join('users', 'users.merchant_id', '=', 'merchants.id')
                            ->where('wires.updated', '=', 1)
                            ->whereIn('wires.status', ['5', 'Complete'])
                            ->get();
                    $data['approved_wire_count'] = count($approved_wires);
                    $data['balances'][] = DB::table('wires')
                            ->select('created_at', 'updated_at', 'currency_received', 'amount_received', 'amount_of_percent', 'amountafterpercent')
                            ->where('active', 1)
                            ->where('merchant_id', '=', Session::get('limited_m'))
                            ->where('amount_received', '>', 0)
                            ->whereIn('status', [5])
                            ->where('updated', '=', 1)
                            ->groupBy('currency_received')
                            ->get();
                }
            }
        }
    }
    static public function saveWire($request) {
        $email = filter_var(trim($request['client_email']), FILTER_VALIDATE_EMAIL);
        $new_wire = false;
        $sent_on = date('Y-m-d H-i-s', strtotime(trim($request['created_at']) . " 00:00:00"));
        $wire = new self();
        $wire->updated = 1;
        $wire->client_name = trim($request['client_name']);
        $wire->client_phone_code = trim($request['mobile_code']);
        $wire->client_phone = trim($request['mobile_phone']);
        $wire->client_email = $email;
        if (Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w') || Session::has('active_b')) {
            $wire->merchant_id = trim($request['merchant']);
        } elseif (Session::has('active_m')) {
            $wire->merchant_id = Session::get('active_m');
        }
        $wire->sent_to_bank = trim($request['bank']);
        $wire->sending_country = trim($request['country']);
        $wire->currency = trim($request['currency']);
        $wire->amount_sent = trim($request['amount_sent']);
        $wire->sent_on = $sent_on;
        $wire->received_on = null;
        $wire->amountafterpercent = '-';
        $wire->kyc = trim($request['kyc']);
        $wire->wc = trim($request['wc']);
        $wire->changed_by = Session::get('user_id');
        $wire->notes = trim(str_replace('<br>', ';', $request['notes']));
        $wire->action = 1;
        $wire->save();
        if (!empty($wire->id)) {
            /* Validating wire's status, updating status and fee due to merchant and country that were chosen */
            $wire_id = $wire->id;
            if (!empty($wire->amount_received) && $wire->kyc == "approved") {
                $status = 5;
            } elseif (empty($wire->amount_received) && $wire->kyc == "approved") {
                $status = 4;
            } elseif (!empty($wire->amount_received) && $wire->kyc != "approved") {
                $status = 3;
            } elseif (empty($wire->amount_received) && $wire->kyc != "approved") {
                $status = 1;
            }
            $fee = DB::table('countries_fee')
                    ->join('wires', 'countries_fee.country_id', '=', 'wires.sending_country')
                    ->select('countries_fee.fee')
                    ->where('countries_fee.merch_id', '=', $request['merchant'])
                    ->where('wires.id', '=', $wire_id)
                    ->get();
            if (!empty($fee)) {
                DB::table('wires')
                        ->where('id', $wire_id)
                        ->update(['status' => $status, 'wire_id' => $wire_id, 'percent' => $fee[0]->fee]);
            } else {
                $fee = DB::table('countries')
                        ->join('regions', 'countries.region', '=', 'regions.id')
                        ->join('wires', 'wires.sending_country', '=', 'countries.id')
                        ->select('regions.fee')
                        ->where('wires.id', '=', $wire_id)
                        ->get();
                DB::table('wires')
                        ->where('id', $wire_id)
                        ->update(['status' => $status, 'wire_id' => $wire_id, 'percent' => $fee[0]->fee]);
            }
            $new_wire = true;
            Session::flash('sm', 'New wire was created successfully!');
        }
        return $new_wire;
    }
    static public function updateWire($request, $id) {
        $new_wire = false;
        if (Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w')) {
            $email = filter_var(trim($request['client_email']), FILTER_VALIDATE_EMAIL);
            $wire_id = DB::table('wires')->where('id', '=', $id)->select('wire_id', 'status')->get();
            $sent_on = date('Y-m-d H-i-s', strtotime(trim($request['created_on']) . " 00:00:00"));
            $wire = new self();
            $wire->sent_on = $sent_on;
            $wire->wire_id = $wire_id[0]->wire_id;
            $wire->updated = 1;
            $wire->client_name = trim($request['client_name']);
            $wire->client_phone_code = trim($request['mobile_code']);
            $wire->client_phone = trim($request['mobile_phone']);
            $wire->client_email = $email;
            $wire->merchant_id = trim($request['merchant']);
            $wire->sent_to_bank = trim($request['bank']);
            $wire->sending_country = trim($request['country']);
            $wire->currency = trim($request['currency']);
            $wire->amount_sent = trim($request['amount_sent']);
            $wire->kyc = trim($request['kyc']);
            $wire->wc = trim($request['wc']);
            $wire->changed_by = Session::get('user_id');
            $wire->notes = trim(str_replace('<br>', '', $request['notes']));
            $wire->action = 2;
            $active = trim($request['active']);
            if ($active === "1") {
                $wire->active = true;
            } else {
                $wire->active = false;
            }
            $wire->save();
            if (!empty($wire->id)) {
                DB::table('wires')->where('id', $id)->update(['updated' => 0]);
                $curr_wire = DB::table('wires')
                        ->where('id', $wire->id)
                        ->get();

                $status = trim($request['status']);
                if ($curr_wire[0]->status != 1 && $curr_wire[0]->status != 2 && $status == 1) {
                    $status = 1;
                }
                if ($curr_wire[0]->status != 2 && trim($request['status']) != 2 && $status != 6) {
                    if ($request['kyc'] == "approved" && $request['amount_received'] != "") {
                        $status = 5;
                    } elseif ($request['kyc'] == "approved" && $request['amount_received'] == "") {
                        $status = 4;
                    } elseif ($request['kyc'] != "approved" && $request['amount_received'] == "") {
                        $status = 1;
                    } elseif ($request['kyc'] != "approved" && $request['amount_received'] != "") {
                        $status = 3;
                    }
                } elseif ($status == 6 && $request['kyc'] == "approved" && $request['amount_received'] != "" && $request['amount_received'] > 0) {
                    $status = 6;
                } else {
                    if ($status == 2) {
                        $status = 2;
                    } else {
                        $status = $wire_id[0]->status;
                    }
                }

                if (!empty($request['percent']) || $request['percent'] != 0) {
                    $fee = trim($request['percent']);
                } else {
                    $get_fee = DB::table('countries_fee')
                            ->join('wires', 'countries_fee.country_id', '=', 'wires.sending_country')
                            ->select('countries_fee.fee')
                            ->where('countries_fee.merch_id', '=', $wire->merchant_id)
                            ->where('wires.id', '=', $wire->id)
                            ->get();
                    if (!empty($get_fee)) {
                        $fee = $get_fee[0]->fee;
                    } else {
                        $fee = '';
                    }
                }
                if (!empty($request['amount_received']) && !empty($request['currency_received'])) {
                    $received = trim($request['amount_received']);
                    $curr_received = trim($request['currency_received']);
                    $amount_of_fee = ($received * $fee) / 100;
                    $amountafterpercent = $received - $amount_of_fee;
                    $received_on = $request['received_on'];
                } else {
                    $received_on = $curr_wire[0]->received_on;
                    $received = $curr_wire[0]->amount_received;
                    $curr_received = $curr_wire[0]->currency_received;
                    $amount_of_fee = $curr_wire[0]->amount_of_percent;
                    $amountafterpercent = $curr_wire[0]->amountafterpercent;
                }
                DB::table('wires')
                        ->where('id', $wire->id)
                        ->update([
                            'status' => $status,
                            'percent' => $fee,
                            'amount_received' => $received,
                            'currency_received' => $curr_received,
                            'amount_of_percent' => $amount_of_fee,
                            'amountafterpercent' => $amountafterpercent,
                            'received_on' => $received_on
                                ]
                );
                $new_wire = true;
                Session::flash('sm', 'Wire has been updated successfully!');
            }
        } elseif (Session::has('active_b')) {
            $wire_id = DB::table('wires')
                    ->where('id', '=', $id)
                    ->get();
            $wire = new self();
            $wire->status = $wire_id[0]->status;
            $wire->sent_on = $wire_id[0]->sent_on;
            $wire->wire_id = $wire_id[0]->wire_id;
            $wire->updated = 1;
            $wire->client_name = $wire_id[0]->client_name;
            $wire->client_phone_code = trim($request['mobile_code']);
            $wire->client_phone = trim($request['mobile_phone']);
            $wire->client_email = $wire_id[0]->client_email;
            $wire->merchant_id = $wire_id[0]->merchant_id;
            $wire->sent_to_bank = $wire_id[0]->sent_to_bank;
            $wire->sending_country = $wire_id[0]->sending_country;
            $wire->currency = $wire_id[0]->currency;
            $wire->amount_sent = $wire_id[0]->amount_sent;
            $wire->amount_received = trim($request['amount_received']);
            $wire->currency_received = trim($request['currency_received']);
            $wire->percent = $wire_id[0]->percent;
            $wire->amount_of_percent = $wire_id[0]->amount_of_percent;
            $wire->amountafterpercent = $wire_id[0]->amountafterpercent;
            $wire->kyc = $wire_id[0]->kyc;
            $wire->wc = $wire_id[0]->wc;
            $wire->active = 1;
            $wire->action = 2;
            $wire->changed_by = Session::get('user_id');
            $wire->notes = trim(str_replace('<br>', '', $request['notes']));
            $wire->save();
            if (!empty($wire->id)) {
                DB::table('wires')->where('id', $id)->update(['updated' => 0]);
                $curr_wire = DB::table('wires')
                        ->where('id', $wire->id)
                        ->get();
                $status = trim($request['status']);
                if ($curr_wire[0]->status != 1 && $curr_wire[0]->status != 2 && $status == 1) {
                    $status = 1;
                }
                if ($curr_wire[0]->status != 2 && trim($request['status']) != 2 && $status != 6) {
                    if ($request['kyc'] == "approved" && $request['amount_received'] != "") {
                        $status = 5;
                    } elseif ($request['kyc'] == "approved" && $request['amount_received'] == "") {
                        $status = 4;
                    } elseif ($request['kyc'] != "approved" && $request['amount_received'] == "") {
                        $status = 1;
                    } elseif ($request['kyc'] != "approved" && $request['amount_received'] != "") {
                        $status = 3;
                    }
                } elseif ($status == 6 && $request['kyc'] == "approved" && $request['amount_received'] != "" && $request['amount_received'] > 0) {
                    $status = 6;
                } else {
                    if ($status == 2) {
                        $status = 2;
                    } else {
                        $status = $wire_id[0]->status;
                    }
                }
                $fee = $wire_id[0]->percent;
                if (!empty($request['amount_received']) && !empty($request['currency_received'])) {
                    $received = trim($request['amount_received']);
                    $curr_received = trim($request['currency_received']);
                    $amount_of_fee = ($received * $fee) / 100;
                    $amountafterpercent = $received - $amount_of_fee;
                    $received_on = $request['received_on'];
                } else {
                    $received_on = $curr_wire[0]->received_on;
                    $received = $curr_wire[0]->amount_received;
                    $curr_received = $curr_wire[0]->currency_received;
                    $amount_of_fee = $curr_wire[0]->amount_of_percent;
                    $amountafterpercent = $curr_wire[0]->amountafterpercent;
                }
                DB::table('wires')
                        ->where('id', $wire->id)
                        ->update(['status' => $status,
                            'percent' => $fee,
                            'amount_received' => $received,
                            'currency_received' => $curr_received,
                            'amount_of_percent' => $amount_of_fee,
                            'amountafterpercent' => $amountafterpercent,
                            'received_on' => $received_on
                                ]
                );

                $new_wire = true;
                Session::flash('sm', 'Wire has been updated successfully!');
            }
        } elseif (Session::has('active_m')) {
            $wire_id = DB::table('wires')
                    ->where('merchant_id', '=', Session::get('active_m'))
                    ->where('id', '=', $id)
                    ->get();
            $wire = new self();
            $wire->status = $wire_id[0]->status;
            $wire->sent_on = $wire_id[0]->sent_on;
            $wire->wire_id = $wire_id[0]->wire_id;
            $wire->updated = 1;
            $wire->client_name = trim($request['client_name']);
            $wire->client_phone_code = trim($request['mobile_code']);
            $wire->client_phone = trim($request['mobile_phone']);
            $wire->client_email = $wire_id[0]->client_email;
            $wire->merchant_id = $wire_id[0]->merchant_id;
            $wire->sent_to_bank = $wire_id[0]->sent_to_bank;
            $wire->sending_country = $wire_id[0]->sending_country;
            $wire->currency = $wire_id[0]->currency;
            $wire->amount_sent = $wire_id[0]->amount_sent;
            $wire->amount_received = $wire_id[0]->amount_received;
            if (!empty($request['received_on'])) {
                $wire->received_on = trim($request['received_on']);
            }
            $wire->currency_received = $wire_id[0]->currency_received;
            $wire->percent = $wire_id[0]->percent;
            $wire->amount_of_percent = $wire_id[0]->amount_of_percent;
            $wire->amountafterpercent = $wire_id[0]->amountafterpercent;
            $wire->kyc = trim($request['kyc']);
            $wire->wc = $wire_id[0]->wc;
            $wire->active = 1;
            $wire->action = 2;
            $wire->changed_by = Session::get('user_id');
            $wire->notes = $wire_id[0]->notes;
            $wire->save();
            if (!empty($wire->id)) {
                DB::table('wires')->where('id', $id)->update(['updated' => 0]);
                $new_wire = true;
                Session::flash('sm', 'Wire has been updated successfully!');
            }
        }
        return $new_wire;
    }
    static public function archiveWire($id) {
        $new_wire = false;
        if (Session::has('active_sa') || Session::has('active_mw')) {
            $wire_id = DB::table('wires')->where('id', '=', $id)->get();
            $wire = new self();
            $wire->status = $wire_id[0]->status;
            $wire->sent_on = $wire_id[0]->sent_on;
            $wire->wire_id = $wire_id[0]->wire_id;
            $wire->updated = 1;
            $wire->client_name = $wire_id[0]->client_name;
            $wire->client_phone = $wire_id[0]->client_phone;
            $wire->client_email = $wire_id[0]->client_email;
            $wire->merchant_id = $wire_id[0]->merchant_id;
            $wire->sent_to_bank = $wire_id[0]->sent_to_bank;
            $wire->sending_country = $wire_id[0]->sending_country;
            $wire->currency = $wire_id[0]->currency;
            $wire->amount_sent = $wire_id[0]->amount_sent;
            $wire->amount_received = $wire_id[0]->amount_received;
            $wire->currency_received = $wire_id[0]->currency_received;
            $wire->percent = $wire_id[0]->percent;
            $wire->amount_of_percent = $wire_id[0]->amount_of_percent;
            $wire->amountafterpercent = $wire_id[0]->amountafterpercent;
            $wire->kyc = $wire_id[0]->kyc;
            $wire->wc = $wire_id[0]->wc;
            $wire->active = 0;
            $wire->action = 3;
            $wire->changed_by = Session::get('user_id');
            $wire->notes = $wire_id[0]->notes;
            $wire->save();
            if (!empty($wire->id)) {
                DB::table('wires')->where('id', $id)->update(['updated' => 0]);
                $new_wire = true;
                Session::flash('sm', 'New wire was archived successfully!');
            }
        }
        return $new_wire;
    }
    static public function getWire(&$data, $id_url) {
        $data['wire'] = [];
        if (Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w') || Session::has('active_b')) {
            $full_wire = DB::table('wires')
                    ->join('banks', 'wires.sent_to_bank', '=', 'banks.id')
                    ->leftJoin('phones', 'wires.client_phone_code', '=', 'phones.id')
                    ->join('countries', 'wires.sending_country', '=', 'countries.id')
                    ->leftjoin('merchants', 'wires.merchant_id', '=', 'merchants.id')
                    ->where('wires.id', '=', $id_url)
                    ->select('wires.*', 'merchants.name as merchant_name', 'banks.name as bank_name', 'phones.id as m_id', 'phones.code as m_code', 'phones.country as m_country', 'countries.country as country_name')
                    ->get();
            if (!empty($full_wire[0])) {
                $data['wire'] = $full_wire[0];
            }
        } elseif (Session::has('active_m')) {
            $full_wire = DB::table('wires')
                    ->leftjoin('merchants', 'wires.merchant_id', '=', 'merchants.id')
                    ->join('countries', 'wires.sending_country', '=', 'countries.id')
                    ->leftJoin('phones', 'wires.client_phone_code', '=', 'phones.id')
                    ->join('banks', 'wires.sent_to_bank', '=', 'banks.id')
                    ->select('wires.*', 'merchants.name as merchant_name', 'banks.name as bank_name', 'phones.id as m_id', 'phones.code as m_code', 'phones.country as m_country', 'countries.country as country_name')
                    ->where('wires.id', '=', $id_url)
                    ->where('wires.merchant_id', '=', Session::get('active_m'))
                    ->get();
            if (!empty($full_wire[0])) {
                $data['wire'] = $full_wire[0];
            }
        }
    }
}
