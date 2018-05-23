<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Update;

class UpdatesController extends MainController {
    function __construct() {
        parent::__construct();
        $this->middleware('logedin');
    }
    static public function getUpdates(){
        Update::getUpdates(self::$data);
        self::$data['title'] = 'iPayTech | Updates Tracker';
        return view('_static/updates', self::$data);
    }
}
