<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
use Input;
use Hash;
use Form;
use DB;

class User extends Model {
    /* Validating user who is logging in, saving his id and full name to Session, if the user is merchant's user - saving merchant's id in session */

    static public function checkUser($email, $pwd) {
        $valid = false;
        $email = trim($email);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $sql = "SELECT * FROM users u JOIN roles r ON u.user_id = r.user_id WHERE u.email = ? AND u.login = 1 AND u.updated = 1 LIMIT 1";
            $result = DB::select($sql, [$email]);
            if (count($result) == 1) {
                if (Hash::check($pwd, $result[0]->password)) {
                    Session::put('user_id', $result[0]->user_id);
                    Session::put('user_name', $result[0]->name);
                    Session::put('user_lastname', $result[0]->lastname);
                    if ($result[0]->role_id == 8) {
                        Session::put('active_sa', true);
                        $valid = true;
                    } elseif ($result[0]->role_id == 6) {
                        Session::put('active_mw', true);
                        $valid = true;
                    } elseif ($result[0]->role_id == 5) {
                        Session::put('active_w', true);
                        $valid = true;
                    } elseif ($result[0]->role_id == 3) {
                        if (!empty($result[0]->merchant_id)) {
                            $m_id = DB::table('merchants')
                                    ->where('merch_id', '=', $result[0]->merchant_id)
                                    ->where('updated', '=', 1)
                                    ->get();
                            if ($m_id[0]->status == 1 && $result[0]->login == 1) {
                                Session::put('active_m', $m_id[0]->merch_id);
                                $valid = true;
                            } else {
                                Session::put('limited_m', $m_id[0]->merch_id);
                                $valid = true;
                            }
                        }
                    } elseif ($result[0]->role_id == 1) {
                        Session::put('active_b', true);
                        $valid = true;
                    }
                }
            }
        } else {
            $valid = false;
            Session::flash('em', ' Please enter a  valid email address.');
        }
        return $valid;
    }

    static public function getUsers(&$data) {
        $data['users'] = [];
        if (Session::has('active_sa')) {
            $all_users = DB::table('users')
                    ->join('roles', 'users.id', '=', 'roles.user_id')
                    ->leftJoin('phones', 'users.phone_code', '=', 'phones.id')
                    ->select('users.*', 'roles.role_id', 'phones.code as m_code', 'phones.country as m_country')
                    ->where('users.updated', '=', 1)
                    ->groupBy('users.user_id')
                    ->get();
        } elseif (Session::has('active_mw') || Session::has('active_w')) {
            $all_users = DB::table('users')
                    ->join('roles', 'users.id', '=', 'roles.user_id')
                    ->leftJoin('phones', 'users.phone_code', '=', 'phones.id')
                    ->select('users.*', 'roles.role_id', 'phones.code as m_code', 'phones.country as m_country')
                    ->where('users.status', '=', 1)
                    ->where('users.updated', '=', 1)
                    ->groupBy('users.user_id')
                    ->get();
        } elseif (Session::has('active_m')) {
            $all_users = DB::table('users')->join('roles', 'users.id', '=', 'roles.user_id')
                    ->join('merchants', 'users.merchant_id', '=', 'merchants.merch_id')
                    ->leftJoin('phones', 'users.phone_code', '=', 'phones.id')
                    ->select('users.*', 'roles.role_id', 'merchants.name as merchant', 'phones.code as m_code', 'phones.country as m_country')
                    ->where('merchants.merch_id', '=', Session::get('active_m'))
                    ->where('merchants.updated', '=', 1)
                    ->where('users.user_id', Session::get('user_id'))
                    ->where('users.updated','=',1)
                    ->get();
        } elseif (Session::has('limited_m')) {
            $all_users = DB::table('users')->join('roles', 'users.id', '=', 'roles.user_id')
                    ->join('merchants', 'users.merchant_id', '=', 'merchants.merch_id')
                    ->leftJoin('phones', 'users.phone_code', '=', 'phones.id')
                    ->select('users.*', 'roles.role_id', 'merchants.name as merchant', 'phones.code as m_code', 'phones.country as m_country')
                    ->where('merchants.merch_id', '=', Session::get('limited_m'))
                    ->where('users.user_id', Session::get('user_id'))
                    ->where('users.updated','=',1)
                    ->get();
        }
        $data['users'] = $all_users;
    }

    static public function getUser(&$data, $id) {
        if (Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w')) {
            $user = DB::table('users')
                    ->join('roles', 'users.id', '=', 'roles.user_id')
                    ->leftJoin('phones', 'users.phone_code', '=', 'phones.id')
                    ->select('users.*', 'roles.role_id as role', 'phones.code as m_code', 'phones.country as m_country')
                    ->where('users.id', '=', $id)
                    ->where('users.updated', '=', 1)
                    ->get();
            $data['user'] = $user[0];
        } else {
            $user = DB::table('users')
                    ->join('roles', 'users.id', '=', 'roles.user_id')
                    ->leftJoin('phones', 'users.phone_code', '=', 'phones.id')
                    ->select('users.*', 'roles.role_id as role', 'phones.code as m_code', 'phones.country as m_country')
                    ->where('users.id', '=', Session::get('user_id'))
                    ->where('users.updated', '=', 1)
                    ->get();
            $data['user'] = $user;
        }
    }

    static public function getMerchUrl(&$data, &$id) {
        $data['url'] = DB::table('users')
                ->join('merchants', 'merchants.id', '=', 'users.merchant_id')
                ->select('users.url')
                ->where('merchants.id', '=', $id)
                ->get();
    }

    static public function saveUser($request) {
        $new_user = false;
        $email = trim($request['email']);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $user_name = DB::table('users')->select('name', 'lastname')->where('user_id', '=', Session::get('user_id'))->get();
            $emails = DB::table('users')->select('email')->where('updated', '>', 0)->get();
            foreach ($emails as $em) {
                if ($em->email == $email) {
                    $output[] = 1;
                } else {
                    $output[] = 0;
                }
            }
            if (in_array(1, $output)) {
                Session::flash('em', 'The email address is already in use!');
                $new_user = false;
            } else {
                $user = new self();
                $user->name = trim($request['fname']);
                $user->lastname = trim($request['lname']);
                $user->email = $email;
                $user->password = bcrypt($request['password']);
                if ($request['merchant'] != "-") {
                    $user->merchant_id = trim($request['merchant']);
                } else {
                    $user->merchant_id = "";
                }
                $user->phone_code = trim($request['mobile_code']);
                $user->phone = trim($request['mobile_phone']);
                $user->skype = trim($request['skype']);
                $user->url = trim($request['url']);
                $user->action = 1;
                if ($request['merchant'] == "-" && $request['role'] == 3) {
                    $user->login = 0;
                }
                $user->changed_by = Session::get('user_name') . ' ' . Session::get('user_lastname');
                $user->save();
                if (!empty($user->id)) {
                    $new_id = $user->id;
                    /* Validating and saving the role of new user */
                    if ($request['role'] == 1) {
                        DB::table('roles')->insert(['user_id' => $new_id, 'role_id' => 8]);
                    } elseif ($request['role'] == 2) {
                        DB::table('roles')->insert(['user_id' => $new_id, 'role_id' => 5]);
                    } elseif ($request['role'] == 3) {
                        DB::table('roles')->insert(['user_id' => $new_id, 'role_id' => 3]);
                    } elseif ($request['role'] == 4) {
                        DB::table('roles')->insert(['user_id' => $new_id, 'role_id' => 1]);
                    } elseif ($request['role'] == 5) {
                        DB::table('roles')->insert(['user_id' => $new_id, 'role_id' => 6]);
                    }
                    DB::table('users')
                            ->where('id', $new_id)
                            ->update(['user_id' => $new_id]);
                    $new_user = true;
                    Session::flash('sm', 'New user has been created successfully!');
                }
            }
        } else {
            $new_user = false;
            Session::flash('em', ' Please enter a  valid email address.');
        }
        return $new_user;
    }

    static public function updateUser($request, $id) {
        $new_user = false;
        $email = trim($request['email']);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $user_name = DB::table('users')->select('name', 'lastname')->where('id', '=', Session::get('user_id'))->get();
            $user_id = DB::table('users')->where('id', '=', $id)->get();
            $user = new self();
            $user->user_id = $user_id[0]->user_id;
            $user->name = trim($request['fname']);
            $user->lastname = trim($request['lname']);
            $user->email = $email;
            $user->password = $user_id[0]->password;
            $user->merchant_id = trim($request['merchants']);
            $user->phone_code = trim($request['mobile_code']);
            $user->phone = trim($request['mobile_phone']);
            $user->skype = trim($request['skype']);
            $user->url = trim($request['url']);
            $user->action = 2;
            $user->changed_by = Session::get('user_name') . ' ' . Session::get('user_lastname');
            $status = trim($request['status']);
            if ($request['role'] == 3 && trim($request['merchants']) != "") {
                $user->login = true;
            } elseif ($request['role'] != 3 && trim($request['merchants']) == "" && $status === "1") {
                $user->login = true;
            } else {
                $user->login = false;
            }
            $user->save();
            $new_id = $user->id;
            if ($request['role'] == 1) {
                DB::table('roles')->insert(['user_id' => $new_id, 'role_id' => 8]);
            } elseif ($request['role'] == 2) {
                DB::table('roles')->insert(['user_id' => $new_id, 'role_id' => 5]);
            } elseif ($request['role'] == 3) {
                DB::table('roles')->insert(['user_id' => $new_id, 'role_id' => 3]);
            } elseif ($request['role'] == 4) {
                DB::table('roles')->insert(['user_id' => $new_id, 'role_id' => 1]);
            } elseif ($request['role'] == 5) {
                DB::table('roles')->insert(['user_id' => $new_id, 'role_id' => 6]);
            }
            DB::table('users')->where('id', $id)->update(['updated' => 0]);
            $new_user = true;
            Session::flash('sm', 'User has been updated successfuly!');
        } else {
            $new_user = false;
            Session::flash('em', ' Please enter a  valid email address.');
        }
        return $new_user;
    }

    static public function archiveUser($id) {
        $user_name = DB::table('users')->select('name', 'lastname')->where('user_id', '=', Session::get('user_id'))->get();
        $new_user = false;
        $user_id = DB::table('users')
                ->join('roles', 'users.id', '=', 'roles.user_id')
                ->where('users.id', '=', $id)
                ->get();
        $user = new self();
        $user->user_id = $user_id[0]->user_id;
        $user->name = $user_id[0]->name;
        $user->lastname = $user_id[0]->lastname;
        $user->email = $user_id[0]->email;
        $user->password = $user_id[0]->password;
        $user->merchant_id = $user_id[0]->merchant_id;
        $user->phone = $user_id[0]->phone;
        $user->skype = $user_id[0]->skype;
        $user->url = $user_id[0]->url;
        $user->status = 0;
        $user->login = 0;
        $user->action = 3;
        $user->changed_by = Session::get('user_name') . ' ' . Session::get('user_lastname');
        $user->save();
        DB::table('users')->where('id', $id)->update(['updated' => 0]);
        DB::table('roles')->insert(['user_id' => $user->id, 'role_id' => $user_id[0]->role_id]);
        $new_user = true;
        Session::flash('sm', 'User has been archived successfuly!');
    }

    static public function getRole(&$data, &$id) {
        $data['role'] = DB::table('roles')->where('user_id', '=', $id)->get();
    }

    static public function changePassword($request, $id) {
        $new_user = false;
        $prev_user = DB::table('users')
                ->join('roles', 'users.id', '=', 'roles.user_id')
                ->where('users.id', '=', trim($request['user_id']))
                ->get();
        $user = new self();
        $user->user_id = trim($request['user_id']);
        $user->name = $prev_user[0]->name;
        $user->lastname = $prev_user[0]->lastname;
        $user->email = $prev_user[0]->email;
        $user->password = bcrypt($request['password']);
        $user->merchant_id = $prev_user[0]->merchant_id;
        $user->phone = $prev_user[0]->phone;
        $user->skype = $prev_user[0]->skype;
        $user->url = $prev_user[0]->url;
        $user->action = 2;
        $user->changed_by = $prev_user[0]->name . " " . $prev_user[0]->lastname;
        $user->status = $prev_user[0]->status;
        $user->login = $prev_user[0]->login;
        $user->save();
        $new_id = $user->id;
        if ($new_id) {
            $new_role = DB::table('roles')->insert(['user_id' => $new_id, 'role_id' => $prev_user[0]->role_id]);
            $new_status = DB::table('users')->where('user_id', $id)->whereNotIn('id', [$new_id])->update(['updated' => 0]);
            Session::flash('sm', 'Password has been changed successfuly!');
            return $new_user = true;
        }
    }

}
