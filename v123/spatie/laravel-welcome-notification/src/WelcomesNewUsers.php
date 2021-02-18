<?php

namespace Spatie\WelcomeNotification;

use Carbon\Carbon;
use Closure;
use Symfony\Component\HttpFoundation\Response;

class WelcomesNewUsers
{
    public function handle($request, Closure $next)
    {
        if (! $request->hasValidSignature()) {
            abort(Response::HTTP_FORBIDDEN, 'The welcome link does not have a valid signature or is expired.');
        }

        if (! $request->user) {
            abort(Response::HTTP_FORBIDDEN, 'Could not find a user to be welcomed.');
        }

        if (is_null($request->user->welcome_valid_until)) {
            return abort(Response::HTTP_FORBIDDEN, 'The welcome link has already been used.');
        }

        if (Carbon::create($request->user->welcome_valid_until)->isPast()) {
            return abort(Response::HTTP_FORBIDDEN, 'The welcome link has expired.');
        }

        return $next($request);
    }
}
