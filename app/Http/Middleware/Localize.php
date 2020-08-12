<?php

namespace App\Http\Middleware;

use App\Language;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

class Localize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $default = Language::where('default', 1)->first()->abrev;
        $locale = Route::current()->parameter('language', $default);
        App::setLocale($locale);
        return $next($request);
    }
}
