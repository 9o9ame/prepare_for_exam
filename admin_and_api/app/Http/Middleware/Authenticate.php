<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Auth;
use Session;
class Authenticate extends  Middleware
{
    // public function handle($request, Closure $next, ...$guards)
    // {
    //     if (Session::get('ADMIN_LOGIN')) {
    //         return $next($request);
    //     }

    //     if (! $request->expectsJson()) {
    //         // return route('login');
    //         $response['status']='error';
    //         $response['msg']="Unauthorized Access!";
    //         echo json_encode($response);
    //     exit;
    //     }

    //     return redirect('/');
    // }

    protected function redirectTo($request)
    {

        if (! $request->expectsJson()) {
            // return route('login');
            $response['status']='error';
            $response['msg']="Unauthorized Access!";
            echo json_encode($response);
        exit;
        }
    }

}
