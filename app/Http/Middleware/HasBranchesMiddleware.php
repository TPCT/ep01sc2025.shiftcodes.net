<?php

namespace App\Http\Middleware;

use App\Helpers\Responses;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasBranchesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $merchant = auth()->guard('merchants')->user();
        if (!$merchant->has_branches)
            return Responses::error([], 422, __("errors.PLEASE_ADD_BRANCHES"));
        return $next($request);
    }
}
