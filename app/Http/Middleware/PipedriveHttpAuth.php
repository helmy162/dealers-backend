<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class PipedriveHttpAuth
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $username = config('pipedrive.webhook.username');
        $password = config('pipedrive.webhook.password');

        if ($request->getUser() !== $username ||
            $request->getPassword() !== $password ||
            $request->header('user-agent') !== 'Pipedrive Webhooks') {

            Log::critical('Pipedrive webhook authentication failed', [
                'username' => $request->getUser(),
                'password' => $request->getPassword(),
                'user-agent' => $request->header('user-agent'),
            ]);

            return response('Unauthorized.', 401, ['WWW-Authenticate' => 'Basic']);
        }

        Log::info('Pipedrive webhook authentication successful', [
            'username' => $request->getUser(),
            'password' => $request->getPassword(),
            'user-agent' => $request->header('user-agent'),
        ]);

        return $next($request);
    }
}
