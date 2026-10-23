<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromSession
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale', config('app.locale'));

        $locale = in_array($locale, ['fr', 'en'], true) ? $locale : 'fr';
        app()->setLocale($locale);
        Carbon::setLocale($locale);

        return $next($request);
    }
}
