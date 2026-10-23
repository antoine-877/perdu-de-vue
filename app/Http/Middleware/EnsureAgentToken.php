<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAgentToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = (string) config('services.agent_action_token');
        $provided = (string) $request->input('agent_token', $request->header('X-Agent-Token', ''));

        if ($expected === '' || $provided === '' || ! hash_equals($expected, $provided)) {
            abort(403);
        }

        return $next($request);
    }
}
