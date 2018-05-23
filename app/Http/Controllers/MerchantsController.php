<?php

namespace App\Http\Controllers;

use App\Http\Requests\MerchantValidation;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Merchant;
use App\Currency;
use App\User;
use App\Bank;
use App\Fee;
use Session;

class MerchantsController extends MainController {

    function __construct() {
        parent::__construct();
        $this->middleware('logedin');
    }

    public function index() {
        Merchant::getMerch(self::$data);
        self::$data['title'] = 'iPayTech | Companies';
        return view('_merch.all_merch', self::$data);
    }

    public function create() {
        if (Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w')) {
            Currency::getCurrency(self::$data);
            Bank::getBanksForWire(self::$data);
            Fee::getRegions(self::$data);
            self::$data['title'] = 'iPayTech | Register Company';
            return view('_merch.add_merch', self::$data);
        } else {
            Session::flash('em', 'No information was found!');
            return redirect('companies');
        }
    }

    public function store(MerchantValidation $request) {
        if (Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w')) {
            if (Merchant::saveMerchant($request)) {
                return redirect('companies');
            } else {
                Session::flash('em', 'Something went wrong, please try again!');
                return redirect('companies/create');
            }
        } else {
            Session::flash('em', 'No information was found!');
            return redirect('companies');
        }
    }

    public function show($id) {
        if (Session::has('active_sa') || Session::has('active_mw')) {
            self::$data['title'] = 'iPayTech | Archive Company';
            Merchant::merchFullInfo(self::$data, $id);
            self::$data['id'] = $id;
            return view('_merch/archive_merch', self::$data);
        } else {
            Session::flash('em', 'No information was found!');
            return redirect('companies');
        }
    }

    public function edit($id) {
        if (Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w')) {
            Merchant::merchFullInfo(self::$data, $id);
            Currency::getCurrency(self::$data);
            Bank::getBanksForWire(self::$data);
            Fee::getRegions(self::$data);
            Fee::displayFeesOfMerchant(self::$data, $id);
            self::$data['title'] = 'iPayTech | Edit  Company';
            return view('_merch/edit_merch', self::$data);
        } else {
            Session::flash('em', 'No information was found!');
            return redirect('companies');
        }
    }

    public function update(MerchantValidation $request, $id) {
        if (Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w')) {
            Merchant::updateMerchant($request, $id);
            return redirect('companies');
        } else {
            Session::flash('em', 'No information was found!');
            return redirect('companies');
        }
    }

    public function destroy($id) {
        if (Session::has('active_sa') || Session::has('active_mw')) {
            Merchant::archiveMerchant($id);
            Session::flash('sm', 'Company has been archived succesfuly!');
            return redirect('companies');
        } else {
            Session::flash('em', 'No information was found!');
            return redirect('companies');
        }
    }

}
