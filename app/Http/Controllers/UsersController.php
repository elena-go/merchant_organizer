<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditUserValidation;
use App\Http\Requests\SignupValidation;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Merchant;
use App\User;
use Session;
use Input;
use DB;

class UsersController extends MainController {

    function __construct() {
        parent::__construct();
        $this->middleware('logedin');
    }

    public function index() {
        if (!Session::has('active_b')) {
            User::getUsers(self::$data);
            Merchant::getAllMerchs(self::$data);
            self::$data['title'] = 'iPayTech | Users';
            return view('_user.all_users', self::$data);
        } else {
            return redirect('wires');
        }
    }

    public function create() {
        if (Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w')) {
            Merchant::getMerch(self::$data);
            self::$data['title'] = 'iPayTech | Register User';
            return view('_user.add_user', self::$data);
        } else {
            Session::flash('em', 'No information was found!');
            return redirect('users');
        }
    }

    public function store(SignupValidation $request) {
        if (Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w')) {
            if (User::saveUser($request)) {
                return redirect('users');
            } else {
                return redirect('users/create');
            }
        } else {
            Session::flash('em', 'No information was found!');
            return redirect('users');
        }
    }

    public function show($id) {
        if (Session::has('active_sa')) {
            self::$data['title'] = 'iPayTech | Archive User';
            User::getUser(self::$data, $id);
            self::$data['id'] = $id;
            return view('_user/archive_user', self::$data);
        } else {
            Session::flash('em', 'No information was found!');
            return redirect('users');
        }
    }

    public function edit($id) {
        if (Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w')) {
            User::getUser(self::$data, $id);
            Merchant::getMerch(self::$data);
            self::$data['title'] = 'iPayTech | Edit User';
            return view('_user/edit_user', self::$data);
        } else {
            Session::flash('em', 'No information was found!');
            return redirect('users');
        }
    }

    public function update(EditUserValidation $request, $id) {
        if (Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w')) {
            if (User::updateUser($request, $id)) {
                return redirect('users');
            } else {
                return redirect('users/'.$id.'/edit');
            }
        } else {
            Session::flash('em', 'No information was found!');
            return redirect('users');
        }
    }

    public function destroy($id) {
        if (Session::has('active_sa')) {
            User::archiveUser($id);
            Session::flash('sm', 'User has been archived succesfuly!');
            return redirect('users');
        } else {
            Session::flash('em', 'No information was found!');
            return redirect('users');
        }
    }

}
