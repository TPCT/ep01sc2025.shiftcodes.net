<?php

namespace App\Http\Middleware;

use App\Settings\Site;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ComingSoonMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (in_array($request->segment(1), [
            'livewire', 'admin', 'storage', 'select-language', 'merchant'
        ]))
            return $next($request);

        if (app(Site::class)->maintenance && !\request()->routeIs('site.coming-soon'))
            return redirect()->route('site.coming-soon');
        return $next($request);
    }
}
