<?php

namespace App\Http\Middleware;

use App\Helpers\Responses;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasClientProfileMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $client = auth()->guard('clients')->user();

        if ($client && !($client->name && $client->phone))
            return Responses::error([], 422, __("errors.PLEASE_UPDATE_PROFILE_TO_CONTINUE"));
        return $next($request);
    }
}
