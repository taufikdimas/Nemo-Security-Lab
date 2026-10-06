#!/usr/bin/env python3
"""Stand-in for the internal inventory bridge that AdminFileController talks to.

The lab cannot resolve garuda-siber.internal (no root, no /etc/hosts access), so
this serves the same role on a separate port. It MUST be a different port from
the app itself: `php artisan serve` is single-threaded, so a request handler
that fetches its own server blocks forever.

Usage:
    python3 tools/internal_service.py [port]      # default 9001

Then exercise V10 against it:
    POST /admin/files/import-url   file_url=http://localhost:9001/backup-manifest.txt
"""

import http.server
import socketserver
import sys
from pathlib import Path

PORT = int(sys.argv[1]) if len(sys.argv) > 1 else 9001
ROOT = Path(__file__).resolve().parent.parent / "storage" / "internal-bridge"

MANIFEST = """SecureOps Internal Inventory Bridge
====================================
node            : scm-bridge-01.garuda-siber.internal
zone            : dmz-inventory (RFC 1918 reachable, not internet-exposed)
last_sync       : 2026-10-04 02:14 UTC
assets_tracked  : 4218
sensitive       : contains asset serial + internal IP map

INTERNAL-ONLY-DO-NOT-EXPOSE
"""


class Handler(http.server.SimpleHTTPRequestHandler):
    def __init__(self, *args, **kwargs):
        super().__init__(*args, directory=str(ROOT), **kwargs)

    def log_message(self, fmt, *args):  # keep PoC output readable
        sys.stderr.write("  [bridge] %s\n" % (fmt % args))


def main() -> None:
    ROOT.mkdir(parents=True, exist_ok=True)
    (ROOT / "backup-manifest.txt").write_text(MANIFEST)

    socketserver.TCPServer.allow_reuse_address = True
    with socketserver.TCPServer(("127.0.0.1", PORT), Handler) as httpd:
        print(f"  [bridge] internal inventory bridge on http://localhost:{PORT}")
        print(f"  [bridge] document root: {ROOT}")
        httpd.serve_forever()


if __name__ == "__main__":
    main()