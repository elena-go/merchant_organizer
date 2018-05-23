<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Session;
use DB;

class MainController extends Controller {
    static protected $data = ['title' => 'iPayTech'];
    function __construct() {
        self::$data['is_login'] = Session::has('user_id') ? true : false;
        self::$data['is_sadmin'] = Session::has('is_sadmin') ? true : false;
        self::$data['is_main_admin'] = Session::has('is_main_admin') ? true : false;
        self::$data['is_admin'] = Session::has('is_admin') ? true : false;
        self::$data['is_merch'] = Session::has('is_merch') ? true : false;
        self::$data['is_bank'] = Session::has('is_bank') ? true : false;
        self::$data['phone_code'] = DB::table('phones')->get();
    }
}
