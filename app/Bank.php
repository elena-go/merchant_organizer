<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
use Request;
use Input;
use DB;
use File;

class Bank extends Model {

    static public function getBanksForWire(&$data) {
        if (!Session::has('active_m')) {
            $data['banks'] = DB::table('banks')
                    ->where('updated', '=', 1)
                    ->where('status', '=', 1)
                    ->get();
        } elseif (Session::has('active_m')) {
            $bank = DB::table('merchants')
                    ->select('available_banks')
                    ->where('merch_id', '=', Session::get('active_m'))
                    ->where('updated', '=', 1)
                    ->get();
            if (!empty($bank)) {
                $bank = json_decode(json_encode($bank[0]), true);
                $bank = explode(",", $bank['available_banks']);
                foreach ($bank as $row) {
                    $bank_list[] = DB::table('banks')
                            ->where('bank_id', '=', $row)
                            ->where('updated', '=', 1)
                            ->where('status', '=', 1)
                            ->get();
                    if (!empty($bank_list)) {
                        $banks = $bank_list;
                    }
                }
                $data['banks'] = $banks;
            }
        }
    }

    static public function getBanks(&$data) {
        if (Session::has('active_sa')) {
            $data['banks'] = DB::table('banks')
                    ->where('updated', '=', 1)
                    ->get();
        } elseif (Session::has('active_mw') || Session::has('active_w')) {
            $data['banks'] = DB::table('banks')
                    ->where('updated', '=', 1)
                    ->where('status', '=', 1)
                    ->get();
        } elseif (Session::has('active_b')) {
            $data['banks'] = DB::table('banks')
                    ->where('updated', '=', 1)
                    ->where('status', '=', 1)
                    ->get();
        } else {
            $bank = DB::table('merchants')
                    ->select('available_banks');
            if (Session::has('active_m')) {
                $bank = $bank->where('id', '=', Session::get('active_m'));
            } elseif (Session::has('limited_m')) {
                $bank = $bank->where('id', '=', Session::get('limited_m'));
            }
            $bank = $bank->get();
            $bank = json_decode(json_encode($bank), true);
            $data['bank_arr'] = explode(",", $bank[0]['available_banks']);
            foreach ($data['bank_arr'] as $row) {
                $bank_list = DB::table('banks')
                        ->where('banks.id', '=', $row)
                        ->get();
                if (!empty($bank_list)) {
                    $banks[] = $bank_list;
                }
            }
            if (!empty($banks)) {
                foreach ($banks as $list) {
                    if (!empty($list)) {
                        $val[] = $list[0];
                    }
                }
                $data['banks'] = $val;
            }
        }
    }

    static public function allBanks(&$data) {
        if (Session::has('active_sa')) {
            $data['banks'] = DB::table('banks')
                    ->where('updated', '=', 1)
                    ->get();
        } elseif (Session::has('active_mw') || Session::has('active_w')) {
            $data['banks'] = DB::table('banks')
                    ->where('updated', '=', 1)
                    ->where('status', '=', 1)
                    ->get();
        } elseif (Session::has('active_m')) {
            $bank = DB::table('merchants')
                    ->select('available_banks')
                    ->where('merch_id', '=', Session::get('active_m'))
                    ->where('updated', '=', 1)
                    ->get();
            if (!empty($bank)) {
                $bank = json_decode(json_encode($bank[0]), true);
                $bank = explode(",", $bank['available_banks']);
                foreach ($bank as $row) {
                    $bank_list[] = DB::table('banks')
                            ->where('bank_id', '=', $row)
                            ->where('updated', '=', 1)
                            ->where('status', '=', 1)
                            ->get();
                    if (!empty($bank_list)) {
                        $banks = $bank_list;
                    }
                }
                $data['banks'] = $banks;
            }
        }
    }

    static public function saveBank($request) {
        $new_bank = false;
        if (Input::hasFile('bank_file')) {
            $file = Input::file('bank_file');
            $file_name = str_random(15) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path() . '/bank', $file_name);
            $bank = new self();
            $bank->name = trim($request['name']);
            $bank->file = $file_name;
            $bank->action = 1;
            $bank->changed_by = Session::get('user_id');
            $bank->save();
            DB::table('banks')
                    ->where('id', $bank->id)
                    ->update(['bank_id' => $bank->id]);
            return $new_bank = true;
        }
    }

    static public function editBank(&$data, $id) {
        $data['bank'] = DB::table('banks')->where('id', '=', $id)->get();
    }

    static public function updateBank($request, $id) {
        $bank_id = DB::table('banks')->where('id', '=', $id)->get();
        $new_bank = false;
        if (Input::hasFile('bank_file')) {
            $file = Input::file('bank_file');
            $file_name = str_random(15) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path() . '/bank', $file_name);
        } else {
            $file_name = $bank_id[0]->file;
        }
        $bank = new self();
        $bank->bank_id = $bank_id[0]->bank_id;
        $bank->name = trim($request['name']);
        $bank->file = $file_name;
        $bank->action = 2;
        $bank->changed_by = Session::get('user_id');
        $bank->save();
        DB::table('banks')
                ->where('id', $id)
                ->update(['updated' => 0]);
        return $new_bank = true;
    }

    static public function archiveBank($id) {
        $bank_id = DB::table('banks')->where('id', '=', $id)->get();
        $new_bank = false;
        $bank = new self();
        $bank->bank_id = $bank_id[0]->bank_id;
        $bank->name = $bank_id[0]->name;
        $bank->file = $bank_id[0]->file;
        $bank->status = 0;
        $bank->action = 3;
        $bank->changed_by = Session::get('user_id');
        $bank->save();
        DB::table('banks')
                ->where('id', $id)
                ->update(['updated' => 0]);
        return $new_bank = true;
    }

}
