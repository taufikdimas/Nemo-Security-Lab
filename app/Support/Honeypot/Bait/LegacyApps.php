<?php

namespace App\Support\Honeypot\Bait;

/**
 * Tier 4 lures: legacy application surfaces. These are the paths a scanner
 * reaches for by reflex, and each one presents a login form.
 *
 * The forms are pure bait. Nothing is submitted anywhere: the honeypot middleware
 * records the POST body into honeypot_hits.payload and then returns the form
 * again with a generic failure, so an attacker learns nothing and the lab never
 * depends on these credentials working. The field names deliberately match the
 * real applications (pwd, user) rather than being caught by the redaction list,
 * which is what makes a captured attempt useful for teaching.
 */
final class LegacyApps
{
    public static function all(bool $resubmit = false): array
    {
        return [
            'wp-login.php' => self::wpLogin($resubmit),
            'administrator/index.php' => self::joomlaLogin(),
            'phpmyadmin/index.php' => self::phpMyAdmin(),
            'xmlrpc.php' => self::xmlrpc(),
            'cgi-bin/test-cgi' => self::testCgi(),
        ];
    }

    private static function shell(string $title, string $body, string $accent = '#0b7285'): string
    {
        $t = htmlspecialchars($title);

        return '<!DOCTYPE html><html><head><meta charset="utf-8">'
            . '<meta name="viewport" content="width=device-width,initial-scale=1">'
            . '<title>' . $t . '</title><style>'
            . '*{box-sizing:border-box}body{margin:0;min-height:100vh;display:flex;align-items:center;'
            . 'justify-content:center;background:#f1f3f5;font-family:system-ui,-apple-system,sans-serif;color:#212529}'
            . '.box{background:#fff;width:min(380px,92vw);border:1px solid #dee2e6;border-radius:10px;padding:28px;'
            . 'box-shadow:0 12px 32px -18px rgba(0,0,0,.35)}h1{margin:0 0 4px;font-size:1.15rem}'
            . 'p.sub{margin:0 0 18px;color:#6c757d;font-size:.85rem}'
            . 'label{display:block;font-size:.78rem;margin-bottom:10px;color:#495057}'
            . 'input{width:100%;height:38px;padding:0 10px;margin-bottom:14px;border:1px solid #ced4da;'
            . 'border-radius:6px;font:inherit;font-size:.9rem;background:#fff}'
            . 'input:focus{outline:none;border-color:' . $accent . ';box-shadow:0 0 0 3px rgba(13,110,253,.12)}'
            . 'button{width:100%;height:40px;border:0;border-radius:6px;background:' . $accent . ';color:#fff;'
            . 'font:inherit;font-weight:600;cursor:pointer}'
            . '.err{background:#fff3bf;border:1px solid #ffe69c;color:#7a5b00;padding:9px 11px;border-radius:6px;'
            . 'font-size:.82rem;margin-bottom:16px}'
            . 'footer{margin-top:16px;text-align:center;font-size:.75rem;color:#adb5bd}'
            . '</style></head><body><div class="box">' . $body . '</div></body></html>';
    }

    private static function wpLogin(bool $resubmit): array
    {
        $failed = $resubmit || isset($_GET['failed']);

        $error = $failed
            ? '<div class="err">ERROR: Invalid username or password.</div>'
            : '';

        $body = '<h1>SecureOps &rsaquo; Log In</h1>'
            . '<p class="sub">Legacy WordPress front end &mdash; migration pending.</p>'
            . $error
            . '<form method="post" action="">'
            . '<label>Username or Email Address</label>'
            . '<input type="text" name="log" autocomplete="username" autofocus>'
            . '<label>Password</label>'
            . '<input type="password" name="pwd" autocomplete="current-password">'
            . '<button type="submit">Log In</button>'
            . '</form>'
            . '<footer>&copy; SecureOps &middot; <a href="#">Lost your password?</a></footer>';

        return ['status' => 200, 'type' => 'text/html', 'body' => self::shell('Log In &lsaquo; SecureOps &rsaquo; WordPress', $body)];
    }

    private static function joomlaLogin(): array
    {
        $body = '<h1>SecureOps Control Panel</h1>'
            . '<p class="sub">Administrator login</p>'
            . '<form method="post" action="">'
            . '<label>Username</label>'
            . '<input type="text" name="username" autocomplete="username">'
            . '<label>Password</label>'
            . '<input type="password" name="passwd" autocomplete="current-password">'
            . '<button type="submit">Log in</button>'
            . '</form>'
            . '<footer>Powered by SecureOps Platform 2.4.0</footer>';

        return ['status' => 200, 'type' => 'text/html', 'body' => self::shell('Joomla Administrator', $body, '#0d6efd')];
    }

    private static function phpMyAdmin(): array
    {
        $body = '<h1>phpMyAdmin</h1>'
            . '<p class="sub">10.20.0.14 &rsaquo; secureops_prod</p>'
            . '<form method="post" action="">'
            . '<label>Username</label>'
            . '<input type="text" name="pma_username" autocomplete="username">'
            . '<label>Password</label>'
            . '<input type="password" name="pma_password" autocomplete="current-password">'
            . '<button type="submit">Go</button>'
            . '</form>'
            . '<footer>MySQL 8.0 &middot; connection is not permitted from this host</footer>';

        return ['status' => 200, 'type' => 'text/html', 'body' => self::shell('phpMyAdmin', $body, '#6c757d')];
    }

    private static function xmlrpc(): array
    {
        return [
            'status' => 200,
            'type' => 'text/xml',
            'body' => <<<'TXT'
<?xml version="1.0" encoding="UTF-8"?>
<methodResponse>
  <methodName>system.listMethods</methodName>
  <params>
    <param>
      <value><array><data>
        <value><string>demo.sayHello</string></value>
        <value><string>wp.getUsersBlogs</string></value>
        <value><string>wp.getUsers</string></value>
        <value><string>wp.getOptions</string></value>
      </data></array></value>
    </param>
  </params>
</methodResponse>
TXT,
        ];
    }

    private static function testCgi(): array
    {
        return [
            'status' => 200,
            'type' => 'text/plain',
            'body' => "CGI/1.0 test script\n\nServer: scm-app-01\nServer Software: Apache/2.4.58 (Debian)\nRemote address: 203.0.113.24\nScript: /cgi-bin/test-cgi\n",
        ];
    }
}