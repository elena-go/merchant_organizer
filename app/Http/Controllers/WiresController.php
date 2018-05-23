<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditwireValidation;
use App\Http\Requests\AddwireValidation;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Merchant;
use App\Currency;
use App\Bank;
use App\Wire;
use App\Fee;
use Session;
use DB;

class WiresController extends MainController {
    function __construct() {
        parent::__construct();
        $this->middleware('logedin');
    }
    public function index() {
        Wire::getWires(self::$data);
        Merchant::getMerch(self::$data);
        Currency::getCurrency(self::$data);
        Bank::getBanks(self::$data);
        self::$data['title'] = 'iPayTech | Overview';
        return view('_wire.all_wire', self::$data);
    }
    public function create() {
        if (Session::has('active_sa') || Session::has('active_m')) {
            Merchant::getActiveMerch(self::$data);
            Currency::getCurrency(self::$data);
            Bank::getBanksForWire(self::$data);
            Fee::getCountries(self::$data);
            self::$data['title'] = 'iPayTech | Add New Wire';
            return view('_wire.add_wire', self::$data);
        } else {
            return redirect('wires');
            Session::flash('em', 'You don\'t have a permission for the action.');
        }
    }
    public function store(AddwireValidation $request) {
        if (Session::has('active_sa') || Session::has('active_m')) {
            if (Wire::saveWire($request)) {
                return redirect('wires');
            } else {
                Session::flash('em', 'Something went wrong, please try again!');
                return redirect('wires/create');
            }
        } else {
            return redirect('wires');
            Session::flash('em', 'It seems like you don\'t have permission to view this page!');
        }
    }
    public function show($id) {
        if (Session::has('active_sa') || Session::has('active_mw')) {
            self::$data['title'] = 'iPayTech | Archive Wire';
            Wire::getWire(self::$data, $id);
            self::$data['id'] = $id;
            return view('_wire.archive_wire', self::$data);
        } else {
            return redirect('wires');
            Session::flash('em', 'It seems like you don\'t have permission to view this page!');
        }
    }
    public function edit($id) {
        if (Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w') || Session::has('active_m') || Session::has('active_b')) {
            Wire::getWire(self::$data, $id);
            if (self::$data['wire']->updated == 0) {
                Session::flash('em', 'This wire has been updated recently.Please try again.');
                return redirect('wires');
            } else {
                Bank::getBanksForWire(self::$data);
                Currency::getCurrency(self::$data);
                Merchant::getActiveMerch(self::$data);
                Fee::getCountries(self::$data);
                self::$data['title'] = 'iPayTech | Edit Wire';
                return view('_wire.edit_wire', self::$data);
            }
        } else {
            return redirect('wires');
            Session::flash('em', 'It seems like you don\'t have permission to view this page!');
        }
    }
    public function update(EditwireValidation $request, $id) {
        if (Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w') || Session::has('active_m') || Session::has('active_b')) {
            Wire::updateWire($request, $id);
            return redirect('wires');
        } else {
            return redirect('wires');
            Session::flash('em', 'It seems like you don\'t have permission to view this page!');
        }
    }
    public function destroy($id) {
        if (Session::has('active_sa') || Session::has('active_mw')) {
            Wire::archiveWire($id);
            Session::flash('sm', 'The wire has been archived successfully!');
            return redirect('wires');
        } else {
            return redirect('wires');
            Session::flash('em', 'It seems like you don\'t have permission to view this page!');
        }
    }
}
