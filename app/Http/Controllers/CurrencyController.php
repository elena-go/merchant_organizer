<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

class CurrencyController extends MainController {
    function __construct() {
        parent::__construct();
        $this->middleware('logedin');
    }
    
}
