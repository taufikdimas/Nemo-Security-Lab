<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menahan role `client` agar tidak bisa mencapai halaman/feed milik internal.
 *
 * Latar: grup `['auth','prefs']` di routes/web.php hanya dijaga `auth`, tanpa
 * cek role.iintinya semua orang yang login — termasuk akun Client Portal —
 * bisa membuka /products, /assets, /projects, /incidents, /clients,
 * /employees, /vulndb, /files, /reports, /import, dan /tools/diagnostic.
 * (/admin/* sudah aman karena punya `role:admin`.)
 *
 * Feed `api.token` punya masalah serupa: ClientUserSeeder/UserSeeder memberi
 * api_token ke semua user, dan AssetController::apiIndex / IncidentController::apiIndex
 * tidak melakukan scoping, jadi token client bisa menarik seluruh tabel assets
 * dan incidents.
 *
 * Kenapa approach "tolak client" dan bukan "bolehkan admin+user"?
 * CheckRole hanya Exact match (`$request->user()->role !== $role`) sehingga tidak
 * mendukung banyak role. Menolak satu role tertentu membuat route lama tetap
 * utuh tanpa menulis ulang definisinya, dan role baru tetap otomatis tertutup.
 *
 * Path yang tetap dibuka untuk client hanya yang dibutuhkan agar alur login
 * tidak mati: `dashboard` (tempat DashboardController mengarahkan client ke
 * portal) dan `logout`.
 */
class RestrictClientAccess
{
    /**
     * @param  string  ...$allowedPaths  Path yang tetap boleh diakses role client.
     */
    public function handle(Request $request, Closure $next, string ...$allowedPaths): Response
    {
        $user = $request->user();

        if ($user && $user->isClient() && ! $request->is(...$allowedPaths)) {
            abort(403, 'Halaman ini hanya untuk user internal dan administrator.');
        }

        return $next($request);
    }
}