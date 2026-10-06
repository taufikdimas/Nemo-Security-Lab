<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in · SecureOps</title>
    <link rel="icon" type="image/svg+xml" href="/images/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #05070D;
            --sf: #0B1020;
            --el: #111936;
            --bd: rgba(255,255,255,0.08);
            --bd2: rgba(255,255,255,0.14);
            --tx: #F8FAFC;
            --t2: #A8B3C7;
            --mu: #6B7790;
            --danger: #EF4444;
            --cy: #22D3EE;
            --bl: #3B82F6;
            --in: #6366F1;
            --g: linear-gradient(90deg,#22D3EE,#3B82F6 55%,#6366F1);
            --mono: 'JetBrains Mono', ui-monospace, Menlo, monospace;
        }
        *,*::before,*::after{box-sizing:border-box}
        html{margin:0;padding:0;min-height:100dvh}
        body{margin:0;padding:0;background:var(--bg);color:var(--tx);font-family:Inter,system-ui,sans-serif;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;min-height:100dvh;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden}
        /* cyber background layer — identical to the landing page */
        .bg-cyber{position:fixed;inset:0;z-index:-1;pointer-events:none;overflow:hidden;background:#12161f}
        .bg-cyber svg{width:100%;height:100%;display:block;animation:bgdrift 48s ease-in-out infinite alternate}
        @keyframes bgdrift{from{transform:scale(1.06) translate3d(0,0,0)}to{transform:scale(1.14) translate3d(-1.5%,-1%,0)}}
        @media(prefers-reduced-motion:reduce){.bg-cyber svg{animation:none}}
        /* decorative shields flanking the card */
        .shield-deco{position:fixed;top:50%;z-index:0;pointer-events:none;opacity:.55;filter:drop-shadow(0 0 48px rgba(34,211,238,.4));animation:shieldFloat 7s ease-in-out infinite}
        .shield-deco svg{width:clamp(160px,20vw,320px);height:auto;display:block}
        .shield-deco.left{left:clamp(12px,5vw,110px);transform:translateY(-50%)}
        .shield-deco.right{right:clamp(12px,5vw,110px);transform:translateY(-50%) scaleX(-1)}
        @keyframes shieldFloat{0%,100%{margin-top:-10px}50%{margin-top:10px}}
        @media(max-width:900px){.shield-deco{display:none}}
        @media(prefers-reduced-motion:reduce){.shield-deco{animation:none}}
        main{position:relative;z-index:1;width:100%;max-width:420px;padding:24px}
        .card{background:linear-gradient(180deg,rgba(17,25,54,.92),rgba(11,16,32,.92));border:1px solid var(--bd);border-radius:16px;box-shadow:0 1px 0 rgba(255,255,255,.02) inset,0 40px 80px -40px rgba(34,211,238,.25);backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);padding:32px}
        .brand{display:flex;align-items:center;gap:10px;margin-bottom:16px}
        .brand img{width:32px;height:32px}
        .brand-text{display:flex;flex-direction:column;line-height:1.1}
        .brand-name{font-weight:700}
        .brand-by{font-size:11px;color:var(--mu);font-family:var(--mono);text-transform:uppercase;letter-spacing:.12em;margin-top:2px}
        h1{font-size:1.5rem;margin:0 0 6px;letter-spacing:-.02em}
        .sub{color:var(--t2);font-size:.9rem;margin:0 0 20px}
        .alert{background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.4);color:#FCA5A5;border-radius:10px;padding:10px 12px;font-size:.875rem;margin-bottom:16px}
        .alert strong{display:block;margin-bottom:2px}
        .ok{background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.35);color:#86EFAC;border-radius:10px;padding:10px 12px;font-size:.875rem;margin-bottom:16px}
        .field{margin-bottom:14px}
        .label{display:block;font-size:.8rem;color:var(--t2);margin-bottom:6px}
        .input-wrap{position:relative}
        .input{width:100%;height:48px;background:var(--sf);border:1px solid var(--bd2);border-radius:10px;color:var(--tx);padding:0 12px 0 40px;font:inherit;font-size:.95rem;transition:border-color .12s,box-shadow .12s}
        .input:focus{outline:none;border-color:var(--cy);box-shadow:0 0 0 3px rgba(34,211,238,.18)}
        .input.error{border-color:var(--danger);box-shadow:0 0 0 3px rgba(239,68,68,.18)}
        .icon{position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--mu);pointer-events:none}
        .toggle{position:absolute;right:8px;top:50%;transform:translateY(-50%);background:transparent;border:none;color:var(--t2);cursor:pointer;font-size:.8rem;padding:6px 8px;border-radius:8px}
        .toggle:hover{color:var(--tx);background:rgba(255,255,255,.04)}
        .error-msg{color:var(--danger);font-size:.8rem;margin-top:6px}
        .row{display:flex;align-items:center;justify-content:space-between;margin:14px 0 18px}
        .checkbox{display:flex;align-items:center;gap:8px;color:var(--t2);font-size:.875rem}
        .link{color:var(--t2);text-decoration:none;font-size:.875rem}
        .link:hover{color:var(--tx)}
        .btn{width:100%;height:48px;border:none;border-radius:12px;background:var(--g);color:#fff;font-weight:600;cursor:pointer;box-shadow:0 8px 24px -8px rgba(34,211,238,.55);transition:filter .12s,transform .12s}
        .btn:hover{filter:brightness(1.08)}
        .btn:active{filter:brightness(.96)}
        .btn:disabled{opacity:.6;cursor:not-allowed}
        .back{display:flex;justify-content:center;margin-top:16px}
        .back a{color:var(--mu);text-decoration:none;font-size:.875rem}
        .back a:hover{color:var(--tx)}
        @media (max-width:480px){.card{padding:24px}}
    </style>
</head>
<body>
    @include('partials.landing-bg')
    @include('partials.landing-svgs')
    <div class="shield-deco left" aria-hidden="true"><svg viewBox="0 0 24 24"><use href="#shield"/></svg></div>
    <div class="shield-deco right" aria-hidden="true"><svg viewBox="0 0 24 24"><use href="#shield"/></svg></div>
    <main>
        <div class="card">
            <div class="brand">
                <img src="/images/logo.svg" alt="">
                <div class="brand-text">
                    <span class="brand-name">SecureOps</span>
                    <span class="brand-by">by Nemo Security</span>
                </div>
            </div>
            <h1>Sign in to your account</h1>
            <p class="sub">Use your work email to continue.</p>
            @if (session('success'))
                <div class="ok" role="status">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert" role="alert">
                    <strong>Login failed.</strong>
                    @foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                </div>
            @endif
            @if (session('error'))
                <div class="alert" role="alert">
                    <strong>Login failed.</strong>{{ session('error') }}
                </div>
            @endif
            <!--
    Post-login destination is driven by the "redirect" query parameter.

        GET  /login?redirect=/dashboard      -> hidden field carries /dashboard
        POST /login  (valid credentials)     -> 302 Location: /dashboard
        POST /login  (invalid credentials)   -> 302 Location: /login  + "Login failed."

    Defaults: /dashboard for users, /admin/dashboard for admins.
-->
            <form method="POST" action="{{ route('login') }}" data-redirect-default="{{ $redirectTo ?: '/dashboard' }}">
                @csrf
                @if(! empty($redirectTo))
                    <input type="hidden" name="redirect" value="{{ $redirectTo }}">
                @endif
                <div class="field">
                    <label class="label" for="email">Email</label>
                    <div class="input-wrap">
                        <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        <input class="input @error('email') error @enderror" type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@company.internal" autocomplete="username" required>
                    </div>
                    @error('email')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label class="label" for="password">Password</label>
                    <div class="input-wrap">
                        <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input class="input @error('password') error @enderror" type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
                        <button class="toggle" type="button" aria-label="Show password" onclick="togglePwd()">Show</button>
                    </div>
                    @error('password')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <div class="row">
                    <label class="checkbox"><input type="checkbox" name="remember" id="remember" {{ old('remember')?'checked':'' }}><span>Remember me</span></label>
                    @if(Route::has('password.request'))<a class="link" href="{{ route('password.request') }}">Forgot password?</a>@endif
                </div>
                <button class="btn" type="submit">Sign in</button>
            </form>
            <div class="back"><a href="{{ url('/') }}">← Back to home</a></div>
        </div>
    </main>
    <script>
        function togglePwd(){
            const p=document.getElementById('password');
            const t=document.querySelector('.toggle');
            if(p.type==='password'){p.type='text';t.textContent='Hide';t.setAttribute('aria-label','Hide password');}else{p.type='password';t.textContent='Show';t.setAttribute('aria-label','Show password');}
        }
    </script>
</body>
</html>
