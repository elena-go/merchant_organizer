<?php

namespace App\Http\Controllers;

use App\Http\Requests\SigninValidation;
use App\Http\Requests\ResetpasValidation;
use App\Http\Requests\ResetPassword;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use Session;
use Config;
use Mail;
use DB;

class UserController extends MainController {

    function __construct() {
        parent::__construct();
    }

    public function getSignin() {
        if(!Session::has('user_id')){
        self::$data['title'] = 'iPayTech | Signin';
        return view('_static.login', self::$data);
        }else{
            return redirect('wires');
        }
    }

    public function postSignin(SigninValidation $request) {
        if (User::checkUser($request['email'], $request['password'])) {
            return redirect('wires');
        } else {
            Session::flash('em', 'Email or Password are wrong.');
            return redirect('user/signin');
        }
    }

    public function getReset() {
        return view('_static.resetpass');
    }

    public function beginReset(ResetpasValidation $request) {
        $length = 32;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $timestamp = new \DateTime();
        $email = $request['email'];
        DB::table('resetpass')->insert(['email' => $email, 'token' => $randomString, 'created_at' => $timestamp]);
        $fromTitle = "From Wire";
        $emailFrom = 'no-reply@payobin.com';
        $emailTo = $email;
        $token = $randomString;

        $subject = "Password Recovery";
        $message = "To reset your password, please click the following <a href=\"http://localhost:8000/softtransfer_update/public/reset-password/$token\">link</a>";
        $header = "From: $fromTitle <$emailFrom>\r\n";
        $header .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        $email_sent = mail($emailTo, $subject, $message, $header);
        self::$data['email_to'] = $emailTo;
        if ($email_sent) {
            return view('_static.check_incomes', self::$data);
        } else {
            Session::flash('em', 'Something went wrong, please try again! Or contact our support - support@payobin.com');
            return redirect('reset-password');
        }
    }

    public function endReset($token) {
        $row = DB::table('resetpass')->where('token', '=', $token)->take(1)->get();
        if (!empty($row)) {
            $diff = (new \DateTime())->diff(new \DateTime($row[0]->created_at), true);
            if ($diff->i < 20) {
                self::$data['user_id'] = DB::table('users')
                        ->where('updated', '=', 1)
                        ->where('status','=',1)
                        ->where('email', '=', $row[0]->email)
                        ->select('user_id')
                        ->get();
                return view('_static.resetpass-form', self::$data);
            } else {
                Session::flash('em', 'Time\'s out, please try again!');
                return redirect('reset-password');
            }
        } else {
            Session::flash('em', 'Something went wrong, please try again!');
            return redirect('reset-password');
        }
    }

    public function updatePassword(ResetPassword $request) {
        $id = trim($request['user_id']);
        if (User::changePassword($request, $id)) {
            return redirect('/');
        } else {
            Session::flash('em', 'Something went wrong, please try again!');
            return redirect('reset-password');
        }
    }

    public function getLogout() {
        if (Session::has('active_sa')) {
            Session::flush('active_sa');
        } elseif (Session::has('limited_sa')) {
            Session::flush('limited_sa');
        } elseif (Session::has('active_mw')) {
            Session::flush('active_mw');
        } elseif (Session::has('limited_mw')) {
            Session::flush('limited_mw');
        } elseif (Session::has('active_w')) {
            Session::flush('active_w');
        } elseif (Session::has('limited_w')) {
            Session::flush('limited_w');
        } elseif (Session::has('limited_m')) {
            Session::flush('limited_m');
        } elseif (Session::has('limited_m')) {
            Session::flush('limited_m');
        } elseif (Session::has('limited_b')) {
            Session::flush('limited_b');
        } elseif (Session::has('limited_b')) {
            Session::flush('limited_b');
        }
        Session::flush('user_id');
        Session::flush('user_name');
        Session::flush('user_lastname');
        return redirect('user/signin');
    }

}
