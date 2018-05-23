<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditbankValidation;
use App\Http\Requests\BankValidation;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Currency;
use App\Bank;
use Session;
use Storage;
use DB;

class BanksController extends MainController {

    function __construct() {
        parent::__construct();
        $this->middleware('logedin');
    }

    public function index() {
        if (Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w') || Session::has('active_m')) {
            Bank::allBanks(self::$data);
            self::$data['title'] = "iPayTech | Banks";
            return view('_bank.all_banks', self::$data);
        } else {
            Session::flash('em', 'No information were provided.');
            return redirect('wires');
        }
    }

    public function create() {
        if (Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w')) {
            self::$data['title'] = 'iPayTech | Add Bank';
            return view('_bank.add_bank', self::$data);
        } else {
            Session::flash('em', 'No information were provided.');
            return redirect('banks');
        }
    }

    public function store(BankValidation $request) {
        if (Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w')) {
            if (Bank::saveBank($request)) {
                Session::flash('sm', 'New bank has been added successfuly!');
                return redirect('banks');
            } else {
                Session::flash('em', 'Something went wrong, please try again!');
                return redirect('banks/create');
            }
        } else {
            Session::flash('em', 'No information were provided.');
            return redirect('banks');
        }
    }

    public function show($id) {
        if (Session::has('active_sa')) {
            self::$data['title'] = 'Commerzbank | Archive Bank';
            Bank::editBank(self::$data, $id);
            self::$data['id'] = $id;
            return view('_bank/archive_bank', self::$data);
        } else {
            return redirect('banks');
        }
    }

    public function edit($id) {
        if (Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w')) {
            Bank::editBank(self::$data, $id);
            self::$data['title'] = "iPayTech | Edit Bank";
            return view('_bank/edit_bank', self::$data);
        } else {
            Session::flash('em', 'No information was found!');
            return redirect('banks');
        }
    }

    public function update(EditbankValidation $request, $id) {
        if (Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w')) {
            Bank::updateBank($request, $id);
            return redirect('banks');
        } else {
            Session::flash('em', 'No information was found!');
            return redirect('banks');
        }
    }

    public function destroy($id) {
        if (Session::has('active_sa')) {
            Bank::archiveBank($id);
            return redirect('banks');
        } else {
            return redirect('banks');
        }
    }

}
