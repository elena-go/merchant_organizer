<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
use DB;
use App\Fee;

class Merchant extends Model {
    static public function getMerch(&$data) {
        /* Receive data for Tech Team */
        if (Session::has('active_sa')) {
            $data['merchants'] = DB::table('merchants')
                    ->leftJoin('phones', 'merchants.mobile_code', '=', 'phones.id')
                    ->select('merchants.*', 'phones.code as m_code', 'phones.country as m_country')
                    ->where('merchants.updated', '=', 1)
                    ->get();
            foreach ($data['merchants'] as $merch) {
                $data['merchants_users'] = DB::table('users')
                        ->where('merchant_id', '=', $merch->merch_id)
                        ->get();
                $data['l_phone'][$merch->merch_id] = DB::table('merchants')
                        ->leftJoin('phones', 'merchants.landline_code', '=', 'phones.id')
                        ->select('merchants.landline_phone', 'phones.code as l_code', 'phones.country as l_country')
                        ->where('merchants.merch_id', '=', $merch->merch_id)
                        ->where('merchants.updated', '=', 1)
                        ->get();
                $currency_arr = explode(',', $merch->available_currencies);
                $data['merch_currency'][$merch->id] = DB::table('currencys')
                        ->whereIn('id', $currency_arr)
                        ->get();
                $bank_arr = explode(',', $merch->available_banks);
                $data['merch_bank'][$merch->id] = DB::table('banks')
                        ->whereIn('bank_id', $bank_arr)
                        ->where('updated', '=', 1)
                        ->get();
                /* getting merchant's fees per region & country */
                $data['regions_fees'][$merch->id] = DB::table('region_fee')
                        ->join('regions', 'region_fee.region_id', '=', 'regions.id')
                        ->select('regions.name', 'region_fee.region_id', 'region_fee.fee')
                        ->where('merch_id', '=', $merch->id)
                        ->get();
                foreach ($data['regions_fees'][$merch->id] as $region_fee) {
                    $data['country_fees'][$merch->id][] = DB::table('countries_fee')
                            ->join('countries', 'countries.id', '=', 'countries_fee.country_id')
                            ->where('countries.region', '=', $region_fee->region_id)
                            ->where('countries_fee.merch_id', '=', $merch->id)
                            ->get();
                }
            }
        }/* Receive data for Wire Manager, Wire Team and Bank's User */ elseif (Session::has('active_mw') || Session::has('active_w') || Session::has('active_b')) {
            $data['merchants'] = DB::table('merchants')
                    ->leftJoin('phones', 'merchants.mobile_code', '=', 'phones.id')
                    ->select('merchants.*', 'phones.code as m_code', 'phones.country as m_country')
                    ->where('updated', '=', 1)
                    ->where('status', '=', 1)
                    ->get();
            foreach ($data['merchants'] as $merch) {
                $data['l_phone'][$merch->merch_id] = DB::table('merchants')
                        ->leftJoin('phones', 'merchants.landline_code', '=', 'phones.id')
                        ->select('merchants.landline_phone', 'phones.code as l_code', 'phones.country as l_country')
                        ->where('merchants.merch_id', '=', $merch->merch_id)
                        ->where('merchants.updated', '=', 1)
                        ->get();
                $data['merchants_users'] = DB::table('users')
                        ->where('merchant_id', '=', $merch->id)
                        ->get();
                $currency_arr = explode(',', $merch->available_currencies);
                $data['merch_currency'][$merch->id] = DB::table('currencys')
                        ->whereIn('id', $currency_arr)
                        ->get();
                $bank_arr = explode(',', $merch->available_banks);
                $data['merch_bank'][$merch->id] = DB::table('banks')
                        ->whereIn('bank_id', $bank_arr)
                        ->where('updated', '=', 1)
                        ->get();
                $data['regions_fees'][$merch->id] = DB::table('region_fee')
                        ->join('regions', 'region_fee.region_id', '=', 'regions.id')
                        ->select('regions.name', 'region_fee.region_id', 'region_fee.fee')
                        ->where('merch_id', '=', $merch->id)
                        ->get();
                foreach ($data['regions_fees'][$merch->id] as $region_fee) {
                    $data['country_fees'][$merch->id][] = DB::table('countries_fee')
                            ->join('countries', 'countries.id', '=', 'countries_fee.country_id')
                            ->where('countries.region', '=', $region_fee->region_id)
                            ->where('countries_fee.merch_id', '=', $merch->id)
                            ->get();
                }
            }
        }/* Receive data for Active Merchant's user */ elseif (Session::has('active_m')) {
            $data['merchants'] = DB::table('merchants')
                    ->leftJoin('phones', 'merchants.mobile_code', '=', 'phones.id')
                    ->select('merchants.*', 'phones.code as m_code', 'phones.country as m_country')
                    ->where('merchants.merch_id', '=', Session::get('active_m'))
                    ->where('updated', '=', 1)
                    ->get();
            $data['l_phone'][Session::get('active_m')] = DB::table('merchants')
                    ->leftJoin('phones', 'merchants.landline_code', '=', 'phones.id')
                    ->select('merchants.landline_phone', 'phones.code as l_code', 'phones.country as l_country')
                    ->where('merchants.merch_id', '=', Session::get('active_m'))
                    ->where('merchants.updated', '=', 1)
                    ->get();
            $data['merchants_users'] = DB::table('users')
                    ->where('merchant_id', '=', Session::get('active_m'))
                    ->get();
            $currency_arr = explode(',', $data['merchants'][0]->available_currencies);
            $data['merch_currency'] = DB::table('currencys')
                    ->whereIn('id', $currency_arr)
                    ->get();
            $bank_arr = explode(',', $data['merchants'][0]->available_banks);
            $data['merch_bank'] = DB::table('banks')
                    ->whereIn('bank_id', $bank_arr)
                    ->where('updated', '=', 1)
                    ->where('status', '=', 1)
                    ->get();
        }/* Receive data for Limited Merchant's user */ elseif (Session::has('limited_m')) {
            $data['merchants'] = DB::table('merchants')
                    ->leftJoin('phones', 'merchants.mobile_code', '=', 'phones.id')
                    ->select('merchants.*', 'phones.code as m_code', 'phones.country as m_country')
                    ->where('merchants.merch_id', '=', Session::get('limited_m'))
                    ->get();
            $data['l_phone'][Session::get('limited_m')] = DB::table('merchants')
                    ->leftJoin('phones', 'merchants.landline_code', '=', 'phones.id')
                    ->select('merchants.landline_phone', 'phones.code as l_code', 'phones.country as l_country')
                    ->where('merchants.merch_id', '=', Session::get('limited_m'))
                    ->get();
            $data['merchants_users'] = DB::table('users')
                    ->where('merchant_id', '=', Session::get('limited_m'))
                    ->get();
            $currency_arr = explode(',', $data['merchants'][0]->available_currencies);
            $data['merch_currency'] = DB::table('currencys')
                    ->whereIn('id', $currency_arr)
                    ->get();
            $bank_arr = explode(',', $data['merchants'][0]->available_banks);
            $data['merch_bank'] = DB::table('banks')
                    ->whereIn('bank_id', $bank_arr)
                    ->where('updated', '=', 1)
                    ->where('status', '=', 1)
                    ->get();
        }
    }
    static public function getActiveMerch(&$data) {
        $data['merchants'] = DB::table('merchants')
                ->where('updated', '=', 1)
                ->where('status', '=', 1)
                ->get();
    }
    static public function getAllMerchs(&$data) {
        $data['merchants'] = DB::table('merchants')
                ->where('updated', '=', 1)
                ->get();
    }
    static public function saveMerchant($request) {
        $email = filter_var(trim($request['email']), FILTER_VALIDATE_EMAIL);
        $new_merch = false;
        $merch = new self();
        $merch->name = trim($request['name']);
        $merch->address = trim($request['address']);
        $merch->mobile_code = trim($request['mobile_code']);
        $merch->mobile_phone = trim($request['mobile_phone']);
        $merch->landline_code = trim($request['landline_code']);
        $merch->landline_phone = trim($request['landline_phone']);
        $merch->skype = trim($request['skype']);
        $merch->email = $email;
        $merch->account_holder = trim($request['account_holder']);
        $merch->beneficiary_address = trim($request['beneficiary_address']);
        $merch->bank_name = trim($request['bank_name']);
        $merch->bank_address = trim($request['bank_address']);
        $merch->eur_iban = trim($request['eur_iban']);
        $merch->reference = trim($request['reference']);
        $merch->swift_bic = trim($request['swift_bic']);
        $merch->available_banks = implode($request['bank'], ",");
        $merch->available_currencies = implode($request['currency'], ",");
        $merch->action = 1;
        $merch->changed_by = Session::get('user_id');
        $merch->save();
        if (!empty($merch->id)) {
            /* Giving to new Merchant 6-digit ID and updating his data */
            $new_id = $merch->id;
            $length = 6;
            $characters = '0123456789';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            };
            DB::table('merchants')->where('id', $new_id)
                    ->update(['merchant_id' => $randomString, 'merch_id' => $new_id]);

            foreach (Fee::getRegionsIds() as $region) {
                $fees[] = $region->id . ';' . $request['region_' . $region->id];
            };
            foreach ($fees as $fee) {
                /* Saving first default fees for the new merchant */
                $fee = explode(';', $fee);
                DB::table('region_fee')->insert(['merch_id' => $new_id, 'fee' => $fee[1], 'region_id' => $fee[0]]);
                foreach (Fee::getCountriesByRegionId($fee[0]) as $country) {
                    DB::table('countries_fee')->insert(['country_id' => $country->id, 'merch_id' => $new_id, 'fee' => $fee[1]]);
                }
            }

            $new_merch = true;
            Session::flash('sm', 'New company has been created successfully!');
        }
        return $new_merch;
    }
    static public function updateMerchant($request, $id) {
        $email = filter_var(trim($request['email']), FILTER_VALIDATE_EMAIL);
        $new_merch = false;
        $merch_id = DB::table('merchants')->where('id', '=', $id)->get();
        $merch = new self();
        $merch->merch_id = $merch_id[0]->merch_id;
        if ($merch_id[0]->merch_id == 0 || strlen($merch_id[0]->merch_id) > 6) {
            $length = 6;
            $characters = '0123456789';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            };
            $merch->merchant_id = $randomString;
        } else {
            $merch->merchant_id = $merch_id[0]->merchant_id;
        }
        $merch->name = trim($request['name']);
        $merch->address = trim($request['address']);
        $merch->mobile_code = trim($request['mobile_code']);
        $merch->mobile_phone = trim($request['mobile_phone']);
        $merch->landline_code = trim($request['landline_code']);
        $merch->landline_phone = trim($request['landline_phone']);
        $merch->skype = trim($request['skype']);
        $merch->email = $email;
        $merch->account_holder = trim($request['account_holder']);
        $merch->beneficiary_address = trim($request['beneficiary_address']);
        $merch->bank_name = trim($request['bank_name']);
        $merch->bank_address = trim($request['bank_address']);
        $merch->eur_iban = trim($request['eur_iban']);
        $merch->reference = trim($request['reference']);
        $merch->swift_bic = trim($request['swift_bic']);
        $merch->available_banks = implode($request['bank'], ",");
        $merch->available_currencies = implode($request['currency'], ",");
        $merch->action = 2;
        $merch->changed_by = Session::get('user_id');
        $status = trim($request['status']);
        if ($status === "1") {
            $merch->status = true;
            DB::table('users')
                    ->where('merchant_id', $merch_id[0]->merch_id)
                    ->update(['status' => 1]);
        } else {
            $merch->status = false;
            DB::table('users')
                    ->where('merchant_id', $merch_id[0]->merch_id)
                    ->update(['status' => 0]);
        }
        $merch->save();
        /* Gathering previos Fees, checking if country fee = region fee or not and saving new relevant data */
        DB::table('merchants')->where('id', '=', $id)->update(['updated' => 0]);
        if (count(DB::table('region_fee')->where('merch_id', '=', $id)->get()) == 4) {
            foreach (Fee::getRegionIds($id) as $reg) {
                $fees[] = $reg->region_id . ';' . $reg->fee . ';' . $request['region_' . $reg->id];
            };
            foreach ($fees as $fee) {
                $fee = explode(';', $fee);
                DB::table('region_fee')
                        ->where('merch_id', '=', $id)
                        ->where('region_id', '=', $fee[0])
                        ->update(['fee' => $fee[2], 'merch_id' => $merch->id]);
                foreach (Fee::getCountriesIds($id) as $row) {
                    $c_fees[] = $row->country_id . ';' . $request['country_' . $row->country_id];
                };
                if (!empty($c_fees) || $c_fees != 0) {
                    foreach ($c_fees as $cfee) {
                        $cfee = explode(';', $cfee);
                        if ($cfee[1] != $fee[1] && $cfee[1] != 0) {
                            DB::table('countries_fee')
                                    ->join('countries', 'countries.id', '=', 'countries_fee.country_id')
                                    ->where('countries_fee.merch_id', '=', $id)
                                    ->where('countries_fee.country_id', '=', $cfee[0])
                                    ->where('countries.region', '=', $fee[0])
                                    ->update(['fee' => $cfee[1], 'merch_id' => $merch->id]);
                        } else {
                            DB::table('countries_fee')
                                    ->join('countries', 'countries.id', '=', 'countries_fee.country_id')
                                    ->where('countries_fee.merch_id', '=', $id)
                                    ->where('countries_fee.country_id', '=', $cfee[0])
                                    ->where('countries.region', '=', $fee[0])
                                    ->update(['fee' => $fee[2], 'merch_id' => $merch->id]);
                        }
                    }
                } else {
                    foreach ($fees as $fee) {
                        foreach (Fee::getCountriesByRegionId($fee[0]) as $country) {
                            DB::table('countries_fee')->insert(['country_id' => $country->id, 'merch_id' => $merch->id, 'fee' => $fee[1]]);
                        }
                    }
                }
            }
        } else {
            foreach (Fee::getRegionsIds() as $region) {
                $fees[] = $region->id . ';' . $request['region_' . $region->id];
            };
            foreach ($fees as $fee) {
                $fee = explode(';', $fee);
                DB::table('region_fee')->insert(['merch_id' => $merch->id, 'fee' => $fee[1], 'region_id' => $fee[0]]);
                foreach (Fee::getCountriesByRegionId($fee[0]) as $country) {
                    DB::table('countries_fee')->insert(['country_id' => $country->id, 'merch_id' => $merch->id, 'fee' => $fee[1]]);
                }
            }
        }

        Session::flash('sm', 'Company has been updated successfuly!');
    }
    static public function archiveMerchant($id) {
        $new_merch = false;
        $merch_id = DB::table('merchants')->where('id', '=', $id)->get();
        $merch = new self();
        $merch->status = 0;
        $merch->merch_id = $merch_id[0]->merch_id;
        $merch->name = $merch_id[0]->name;
        $merch->merchant_id = $merch_id[0]->merchant_id;
        $merch->address = $merch_id[0]->address;
        $merch->mobile_code = $merch_id[0]->mobile_code;
        $merch->mobile_phone = $merch_id[0]->mobile_phone;
        $merch->landline_code = $merch_id[0]->landline_code;
        $merch->landline_phone = $merch_id[0]->landline_phone;
        $merch->skype = $merch_id[0]->skype;
        $merch->email = $merch_id[0]->email;
        $merch->account_holder = $merch_id[0]->account_holder;
        $merch->beneficiary_address = $merch_id[0]->beneficiary_address;
        $merch->bank_name = $merch_id[0]->bank_name;
        $merch->bank_address = $merch_id[0]->bank_address;
        $merch->eur_iban = $merch_id[0]->eur_iban;
        $merch->reference = $merch_id[0]->reference;
        $merch->swift_bic = $merch_id[0]->swift_bic;
        $merch->available_banks = $merch_id[0]->available_banks;
        $merch->available_currencies = $merch_id[0]->available_currencies;
        $merch->action = 3;
        $merch->changed_by = Session::get('user_id');
        $merch->save();
        $new_merch = true;
        DB::table('merchants')->where('id', $id)->update(['updated' => 0]);
        DB::table('users')
                ->where('merchant_id', $merch_id[0]->merch_id)
                ->update(['status' => 0]);
        Session::flash('sm', 'Company has been archived successfuly!');
    }
    static public function merchFullInfo(&$data, $id) {
        $data['merchant'] = [];
        $full_merch = DB::table('merchants')
                ->leftJoin('phones', 'merchants.mobile_code', '=', 'phones.id')
                ->select('merchants.*', 'phones.id as m_id', 'phones.code as m_code', 'phones.country as m_country')
                ->where('merchants.id', '=', $id)
                ->get();
        if (!empty($full_merch[0])) {
            $data['merchant'] = $full_merch[0];
            $data['l_phone'] = DB::table('merchants')
                    ->leftJoin('phones', 'merchants.landline_code', '=', 'phones.id')
                    ->select('merchants.landline_phone', 'phones.id as l_id', 'phones.code as l_code', 'phones.country as l_country')
                    ->where('merchants.merch_id', '=', $data['merchant']->merch_id)
                    ->get();
            $data['merch_currencies'] = explode(",", $data['merchant']->available_currencies);
            $data['merch_banks'] = explode(",", $data['merchant']->available_banks);
        }
    }
}
