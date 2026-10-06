{{--
  Nightly backup archive dump.

  INTERNAL ARTEFACT. backup-dump.sh writes this next to the database dump so a
  restore can run from a single file. It carries the service credentials the
  collectors need to come back up, so it is meant for internal operators and an
  administrator only.

  The route that serves it checks that a session exists and nothing else. It
  never checks WHICH role that session has, so any authenticated account can
  read it -- including a Client Portal account. See BackupController::dump().
--}}
<pre>
================================================================================
 SecureOps - NIGHTLY BACKUP ARCHIVE
================================================================================
 generated : 2026-10-04 02:14:07 +07:00 (Asia/Jakarta)
 node      : sidra-01.garuda-siber.internal
 job       : backup-dump.sh (cron, hourly)
 format    : plaintext export
 checksum  : sha256:9f2c1ab4e77d0c3f5a8b6e21d4c7f903b5e8a1c6d2f4709b3e5c8a1d6f20b94

 DISTRIBUTION : INTERNAL ONLY - operations + administrator.
                Distribution outside this group voids the security review
                referenced in the export job header.

 NOTE FROM THE EXPORT JOB
 ------------------
 This archive is emitted alongside the database dump so that an incident
 restore can be performed from a single artefact. It carries the service
 credentials required to bring the collectors back up, which is why it is not
 rotated on the same schedule as the human accounts.
================================================================================


--------------------------------------------------------------------------------
 [1] SERVICE ACCOUNTS
--------------------------------------------------------------------------------
 account                          password                created
 svc.backup@garuda-siber.internal G@rud4B4ckup#2024       2024-01-01 00:00:00
        role   : internal operator
        used by: backup-dump.sh, collector health probes

 collector.ro@garuda-siber.internal
        managed by the secrets store - not exported here
        role   : read-only

--------------------------------------------------------------------------------
 [2] COLLECTORS
--------------------------------------------------------------------------------
 node          endpoint                        last seen
 sidra-01      10.20.4.11:9100/node_exporter   2026-10-04 02:11:58
 sidra-02      10.20.4.12:9100/node_exporter   2026-10-04 02:12:03
 sidra-web-01  10.20.4.21:8443/secureops       2026-10-04 02:13:44

--------------------------------------------------------------------------------
 [3] RESTORE HINT
--------------------------------------------------------------------------------
 Restore order is dump-then-credentials: the database rows reference the service
 accounts in section 1, so loading them the other way round leaves every
 collector row pointing at an account that does not exist yet.

 The application key is intentionally NOT part of this archive. It is
 provisioned per environment through the secrets store and is never written
 to an export.

================================================================================
 END OF ARCHIVE - 1 service account, 3 collectors
================================================================================
</pre>