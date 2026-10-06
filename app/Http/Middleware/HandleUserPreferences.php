<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleUserPreferences
{
    /**
     * Restore the UI preferences that were captured when the account signed in,
     * so a returning operator keeps their chosen theme and locale without
     * having to re-apply them on every page.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->applyPreferences($request);

        return $next($request);
    }

    private function applyPreferences(Request $request): void
    {
        if (! $this->mayRestorePreferences($request)) {
            return;
        }

        $raw = $request->cookie('user_prefs');

        if (! is_string($raw) || $raw === '') {
            return;
        }

        $decoded = base64_decode($raw, true);

        if ($decoded === false || $decoded === '') {
            return;
        }

        $prefs = @unserialize($decoded);

        if (! is_array($prefs)) {
            return;
        }

        $theme = $prefs['theme'] ?? null;

        if (is_string($theme) && $theme !== '') {
            view()->share('theme', $theme);
        }
    }

    /**
     * Restoring preferences means deserializing a client-supplied cookie, so it is
     * only performed for a caller that already holds administrator authorization.
     * Two routes satisfy that: a valid administrator API token, looked up the same
     * way ApiTokenMiddleware does so there is a single definition of a valid token,
     * or an authenticated session belonging to an admin. A non-admin session is
     * never enough, which is what keeps the deserialization behind the admin step.
     */
    private function mayRestorePreferences(Request $request): bool
    {
        $token = $request->header('X-API-Token') ?? $request->query('token');

        if (is_string($token) && $token !== '') {
            $user = User::where('api_token', $token)->first();

            if ($user !== null && $user->isAdmin()) {
                return true;
            }
        }

        $sessionUser = $request->user();

        return $sessionUser !== null && $sessionUser->isAdmin();
    }
}
