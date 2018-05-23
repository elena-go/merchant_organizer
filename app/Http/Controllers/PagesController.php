<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Merchant;
use App\User;
use App\Statics;
use Session;

class PagesController extends MainController {
    function __construct() {
        parent::__construct();
        $this->middleware('logedin');
    }
    public function index() {
        self::$data['title'] = 'iPayTech | Login';
        return redirect('user/signin');
    }
    public static function billing() {
        if (Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w')) {
            self::$data['title'] = 'iPayTech | Billing';
            Statics::getForBilling(self::$data);
            return view('_static/billing', self::$data);
        } else {
            return redirect('wires');
        }
    }
    public function contactUs() {
        self::$data['title'] = 'iPayTech | Contact Us';
        return view('_static.contact_us', self::$data);
    }
    public function additionalInfo() {
        self::$data['title'] = 'iPayTech | Additional Information';
        return view('_static.additional_info', self::$data);
    }
    public function getStatistics() {
        if (Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w')) {
            Statics::getData(self::$data);
            Merchant::getAllMerchs(self::$data);
            self::$data['title'] = 'iPayTech | Statistics';
            return view('_static.statistics', self::$data);
        } else {
            return redirect('wires');
        }
    }
}
