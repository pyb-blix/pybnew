<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class XSSProtection
{
    /**
     * The following method loops through all request input and strips out all tags from
     * the request. This to ensure that users are unable to set ANY HTML within the form
     * submissions, but also cleans up input.
     *
     * @param Request $request
     * @param callable $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $html_routes = array("reply_message","enter_address","location_not_found","verify_location","inbox_calendar");
        $response = $next($request);
        
        if(in_array($request->route()->getName(),$html_routes)) {
            $response->headers->set('Content-Type', 'text/html');
        }

        if (!in_array(strtolower($request->method()), ['put', 'post'])) {
            return $response;
        }
        
        $input = $request->all();

        array_walk_recursive($input, function(&$input) {
            $input = strip_tags($input);
        });

        $request->merge($input);

        return $response;
    }
}

?>