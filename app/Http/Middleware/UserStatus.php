<?php namespace App\Http\Middleware;

use Closure;
use Session;

class UserStatus {

    public function handle($request, Closure $next) {
        if (!Session::has('user_id')) {
            return redirect('user/signin');
        }else {
            return $next($request);
        }
    }

}
