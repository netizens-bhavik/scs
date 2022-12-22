<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventBackHistory
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $app_url = env('APP_URL');
        $no_header_add_url = [
            $app_url . 'call_status_uw_report',
            $app_url . 'call_status_report',
            $app_url . 'mom_report',
            $app_url . 'client_report',
        ];

        if (!in_array($request->url(), $no_header_add_url)) {
            $response = $next($request);
            $response->headers->set('Cache-Control', 'nocache, no-store, max-age=0, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
            return $response;
        } else {
            return $next($request);
        }

//        $response = $next($request);
//        return $response->header('Cache-Control','nocache, no-store, max-age=0, must-revalidate')
//            ->header('Pragma','no-cache')
//            ->header('Expires','Sun, 02 Jan 1990 00:00:00 GMT');
    }
}
