<?php

namespace App\Support\Honeypot;

use App\Support\Honeypot\Bait\Infra;
use App\Support\Honeypot\Bait\LegacyApps;
use App\Support\Honeypot\Bait\Recon;
use App\Support\Honeypot\Bait\Secrets;

/**
 * Registry of bait responses served in place of the silent 404 that
 * HoneypotTrap used to return for every decoy path.
 *
 * Lookup is an EXACT match on the normalised path, never a substring match.
 * That is deliberate: HoneypotTrap runs before routing, so a substring rule
 * wide enough to be convenient is also wide enough to swallow a real route
 * (config/ would eat the live /config page, api/ would eat the whole API).
 *
 * The credential-shaped bait is fabricated on purpose. The live APP_KEY, the
 * debug master key and the admin API token are all different from the values in
 * these files, so a student who spends an hour in this dead end still has to walk
 * the real chain.
 */
final class Lures
{
    /** @var array<string, array{status:int,type:string,body:string}>|null */
    private static ?array $cache = null;

    /**
     * @param  bool  $resubmit  true when the client just POSTed to this lure, so the
     *                          credential form should come back with a failure.
     * @return array{status:int,type:string,body:string}|null
     */
    public static function for(string $path, bool $resubmit = false): ?array
    {
        $lure = self::catalog($resubmit)[self::normalise($path)] ?? null;

        return $lure;
    }

    /** @return list<string> */
    public static function paths(): array
    {
        return array_keys(self::catalog(false));
    }

    public static function normalise(string $path): string
    {
        $path = strtolower(trim($path));
        $path = preg_replace('#/+#', '/', $path) ?? $path;

        return trim($path, '/');
    }

    /**
     * @return array<string, array{status:int,type:string,body:string}>
     */
    private static function catalog(bool $resubmit): array
    {
        if (self::$cache !== null && ! $resubmit) {
            return self::$cache;
        }

        $catalog = array_merge(
            Secrets::all(),
            Infra::all(),
            Recon::all(),
            LegacyApps::all($resubmit),
        );

        if (! $resubmit) {
            self::$cache = $catalog;
        }

        return $catalog;
    }
}