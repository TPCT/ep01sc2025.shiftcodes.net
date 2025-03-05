<?php

namespace App\Http\Middleware;

use App\Helpers\Responses;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */

    public function handle($request, Closure $next, ...$guards){
        if (\Auth::guard($guards[0])->guest())
            return Responses::error([], 401, __("errors.unauthorized"));

        return parent::handle($request, $next, ...$guards);
    }
}
