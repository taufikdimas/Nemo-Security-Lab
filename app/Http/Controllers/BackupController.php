<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BackupController extends Controller
{
    /**
     * Nightly backup archive.
     *
     * INTENDED POLICY: internal operators and administrators only. The export
     * job writes this file next to the database dump so a restore can run from
     * a single artefact, and it carries the service credentials the collectors
     * need to come back up -- so it is not something a tenant-facing account
     * should ever see.
     *
     * WHAT THE CODE ACTUALLY DOES: it confirms a session exists and then
     * renders the archive. It never checks which role that session holds, so
     * the authentication guard is doing the job of an authorization guard.
     * Any logged-in account reads this file, including a Client Portal
     * account. An unauthenticated visitor is turned away by the `auth`
     * middleware, so the exposure starts at "logged in" rather than
     * "anonymous" -- which is the part that makes it easy to miss in review.
     *
     * Nothing here unlocks the object-injection sink on its own: the deserialization
     * in HandleUserPreferences::mayRestorePreferences() still requires an
     * administrator, so this is an information-disclosure entry point, not a
     * privilege escalation by itself.
     */
    public function dump(Request $request): Response
    {
        $request->user();

        return response(view('backup.dump'), 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }
}