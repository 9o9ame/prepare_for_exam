<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vendor;

class SubscriptionValidate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $subscription=Auth::user()->subscription_expire;

            $time=strtotime($subscription);
            $current_time=time();
            if($current_time>$time){
                $response['status']=false;
                $response['msg']="Subscription Expired!";

              return response()->json($response);
            }
        }

        return $next($request);
    }
    //    return "aakash.rikh";
    //    exit;
       // return $next($request);
}
