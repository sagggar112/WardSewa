<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle locale switching via query param (?lang=ne|en) or session.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('lang') && in_array($request->get('lang'), ['en', 'ne'])) {
            $locale = $request->get('lang');
            Session::put('locale', $locale);
        } else {
            $locale = Session::get('locale', config('app.locale', 'ne'));
        }

        App::setLocale($locale);

        return $next($request);
    }
}
