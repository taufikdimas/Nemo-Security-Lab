<style>
/* ==========================================================================
   SecureOps — Dark SOC Design System
   Loaded after Bootstrap 5.3, so same-specificity overrides win by order.
   Strategy: additive layer. Re-themes the legacy class vocabulary used
   across ~40 views (navbar, sidebar, card, stat-card, badge-soft-*,
   bg-*-soft, breadcrumb-panel, empty-state, scan-freshness,
   table-responsive-wide) so pages are not rewritten to pick up the theme.
   ========================================================================== */

:root {
    /* Backgrounds */
    --bg-primary:    #0f1117;
    --bg-secondary:  #161b27;
    --bg-tertiary:   #1e2535;
    --bg-sidebar:    #0d1117;
    --bg-hover:      #243044;

    /* Borders */
    --border-color:  #1e293b;
    --border-subtle: rgba(255, 255, 255, 0.06);

    /* Text */
    --text-primary:  #e2e8f0;
    --text-muted:    #94a3b8;
    --text-dim:      #64748b;

    /* Accents */
    --accent-cyan:   #06b6d4;
    --accent-blue:   #3b82f6;
    --accent-purple: #8b5cf6;

    /* Severity */
    --critical: #ef4444;
    --high:     #f97316;
    --medium:   #eab308;
    --low:      #22c55e;
    --info:     #06b6d4;

    /* Status */
    --success: #10b981;
    --warning: #f59e0b;
    --danger:  #ef4444;

    /* Legacy aliases retained so existing views keep resolving. */
    --primary: var(--accent-cyan);
    --navy: var(--bg-sidebar);
    --navy-light: var(--bg-secondary);
    --accent: var(--accent-blue);
    --sidebar-bg: var(--bg-sidebar);
    --sidebar-hover: var(--bg-hover);
    --text-light: var(--text-primary);

    --topbar-h: 60px;
    --sidebar-w: 240px;
}

* { scrollbar-width: thin; scrollbar-color: #2a3444 transparent; }
*::-webkit-scrollbar { width: 9px; height: 9px; }
*::-webkit-scrollbar-track { background: transparent; }
*::-webkit-scrollbar-thumb { background: #2a3444; border-radius: 6px; }
*::-webkit-scrollbar-thumb:hover { background: #3a465a; }

/* === BASE === */
html, body { background-color: var(--bg-primary); }

body {
    font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
    background-color: var(--bg-primary);
    color: var(--text-primary);
    font-size: 14px;
    -webkit-font-smoothing: antialiased;
}

h1 { font-size: 1.5rem; font-weight: 700; color: var(--text-primary); }
h2 { font-size: 1.25rem; font-weight: 600; color: var(--text-primary); }
h3 { font-size: 1.05rem; font-weight: 600; color: var(--text-primary); }
h4, h5, h6 { color: var(--text-primary); }

a { color: var(--accent-cyan); text-decoration: none; }
a:hover { color: #22d3ee; }

hr { border-color: var(--border-color); opacity: 1; }

.text-muted, .text-secondary, .text-body-secondary { color: var(--text-muted) !important; }
.text-dim { color: var(--text-dim) !important; }
.text-light { color: var(--text-primary) !important; }
.bg-white { background-color: var(--bg-secondary) !important; }
.bg-body { background-color: var(--bg-primary) !important; }
.border-top, .border-bottom, .border-start, .border-end { border-color: var(--border-color) !important; }
.border { border-color: var(--border-color) !important; }

/* === BOOTSTRAP SURFACE RE-THEMES === */
.card {
    background-color: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    box-shadow: none;
    color: var(--text-primary);
    transition: border-color 0.15s ease;
}
.card:hover { border-color: #2b3a4f; }

.card-header {
    background-color: transparent;
    border-bottom: 1px solid var(--border-color);
    color: var(--text-primary);
    font-weight: 600;
    border-radius: 8px 8px 0 0;
}
.card-footer { background-color: var(--bg-tertiary); border-top: 1px solid var(--border-color); }
.card-body { color: var(--text-primary); }

.table {
    --bs-table-bg: transparent;
    --bs-table-color: var(--text-primary);
    --bs-table-border-color: var(--border-subtle);
    --bs-table-striped-bg: var(--bg-tertiary);
    --bs-table-striped-color: var(--text-primary);
    --bs-table-hover-bg: var(--bg-hover);
    --bs-table-hover-color: var(--text-primary);
    color: var(--text-primary);
    border-color: var(--border-subtle);
}
.table > :not(caption) > * > * { background-color: transparent; color: var(--text-primary); border-bottom-color: var(--border-subtle); }
.table > thead > tr > th {
    background-color: var(--bg-tertiary);
    color: var(--text-muted);
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid var(--border-color);
}
.table > tbody > tr:hover > * { background-color: var(--bg-hover); color: var(--text-primary); }
.table thead th { border-bottom: 2px solid var(--border-color); }
.table td { vertical-align: middle; }

/* Forms */
.form-control, .form-select, .form-control:focus, .form-select:focus {
    background-color: var(--bg-tertiary);
    border: 1px solid var(--border-color);
    color: var(--text-primary);
    font-size: 13px;
}
.form-control:focus, .form-select:focus {
    border-color: var(--accent-cyan);
    box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1);
    color: var(--text-primary);
}
.form-control::placeholder { color: var(--text-dim); }
.form-control:disabled, .form-select:disabled { background-color: #171c27; color: var(--text-dim); }
.form-label, label, .form-check-label { color: var(--text-muted); font-size: 12px; }
.form-text { color: var(--text-dim); }
.form-check-input { background-color: var(--bg-tertiary); border-color: var(--border-color); }
.form-check-input:checked { background-color: var(--accent-cyan); border-color: var(--accent-cyan); }

/* Dropdowns, modals, offcanvas */
.dropdown-menu {
    background-color: var(--bg-secondary);
    border: 1px solid var(--border-color);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5);
}
.dropdown-item { color: var(--text-primary); font-size: 13px; }
.dropdown-item:hover, .dropdown-item:focus { background-color: var(--bg-hover); color: var(--text-primary); }
.dropdown-item.text-danger { color: var(--critical) !important; }
.dropdown-item.text-danger:hover { background-color: rgba(239, 68, 68, 0.12); }
.dropdown-divider { border-color: var(--border-color); }
.dropdown-header { color: var(--text-dim); font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; }

.modal-content { background-color: var(--bg-secondary); border: 1px solid var(--border-color); color: var(--text-primary); }
.modal-header, .modal-footer { border-color: var(--border-color); }
.modal-header .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }
.offcanvas { background-color: var(--bg-secondary); color: var(--text-primary); border-color: var(--border-color) !important; }

/* Alerts */
.alert { border-radius: 8px; border: 1px solid var(--border-color); color: var(--text-primary); }
.alert-danger { background-color: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.3); color: #fca5a5; }
.alert-success { background-color: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.3); color: #6ee7b7; }
.alert-warning { background-color: rgba(245, 158, 11, 0.1); border-color: rgba(245, 158, 11, 0.3); color: #fcd34d; }
.alert-info { background-color: rgba(6, 182, 212, 0.1); border-color: rgba(6, 182, 212, 0.3); color: #67e8f9; }

/* List groups */
.list-group { background-color: transparent; border-color: var(--border-color); color: var(--text-primary); }
.list-group-item {
    background-color: transparent;
    border-color: var(--border-subtle);
    color: var(--text-primary);
}
.list-group-item:hover { background-color: var(--bg-hover); }
.list-group-item.active { background-color: rgba(6, 182, 212, 0.14); border-color: rgba(6, 182, 212, 0.35); color: var(--accent-cyan); }
.list-group-flush { border-color: var(--border-subtle); }

/* Accordion */
.accordion { --bs-accordion-bg: var(--bg-secondary); --bs-accordion-color: var(--text-primary); }
.accordion-item { background-color: var(--bg-secondary); border: 1px solid var(--border-color); color: var(--text-primary); }
.accordion-button {
    background-color: var(--bg-tertiary);
    color: var(--text-primary);
    font-weight: 500;
    box-shadow: none;
}
.accordion-button:not(.collapsed) { background-color: var(--bg-tertiary); color: var(--accent-cyan); box-shadow: none; }
.accordion-button:focus { box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1); border-color: var(--border-color); }
.accordion-button::after {
    filter: invert(1) grayscale(100%) brightness(200%);
    opacity: 0.6;
}
.accordion-button:not(.collapsed)::after { filter: invert(1) grayscale(100%) brightness(200%); }
.accordion-body { background-color: var(--bg-secondary); color: var(--text-primary); }

/* Progress */
.progress { background-color: var(--bg-tertiary); border-radius: 999px; }
.progress-bar { background-color: var(--accent-cyan); color: #04191f; font-size: 11px; font-weight: 600; }

/* Dismissible close buttons */
.btn-close { filter: invert(1) grayscale(100%) brightness(200%); opacity: 0.6; }
.btn-close:hover { opacity: 1; }

/* Pagination */
.pagination { font-size: 0.875rem; gap: 4px; }
.page-link {
    background-color: var(--bg-tertiary);
    border: 1px solid var(--border-color);
    color: var(--text-muted);
    border-radius: 6px !important;
}
.page-link:hover { background-color: var(--bg-hover); color: var(--text-primary); border-color: var(--border-color); }
.page-item.active .page-link { background-color: var(--accent-cyan); border-color: var(--accent-cyan); color: #06202a; font-weight: 600; }
.page-item.disabled .page-link { background-color: #171c27; color: var(--text-dim); }

/* Native Bootstrap badges -> dark chips */
.badge { font-weight: 600; letter-spacing: 0.02em; }
.bg-danger  { background-color: rgba(239, 68, 68, 0.18) !important; color: var(--critical) !important; }
.bg-info    { background-color: rgba(6, 182, 212, 0.18) !important; color: var(--info) !important; }
.bg-success { background-color: rgba(34, 197, 94, 0.18) !important; color: var(--low) !important; }
.bg-warning { background-color: rgba(234, 179, 8, 0.18) !important; color: var(--medium) !important; }
.bg-secondary { background-color: var(--bg-tertiary) !important; color: var(--text-muted) !important; }

/* === BUTTONS === */
.btn { font-size: 13px; font-weight: 500; border-radius: 6px; }

.btn-primary {
    background-color: var(--accent-cyan);
    border-color: var(--accent-cyan);
    color: #04191f;
    font-weight: 600;
}
.btn-primary:hover, .btn-primary:focus, .btn-primary:active {
    background-color: #22d3ee;
    border-color: #22d3ee;
    color: #04191f;
}
.btn-outline-primary { border-color: var(--accent-cyan); color: var(--accent-cyan); }
.btn-outline-primary:hover { background-color: var(--accent-cyan); border-color: var(--accent-cyan); color: #04191f; }
.btn-outline-secondary { border-color: var(--border-color); color: var(--text-muted); }
.btn-outline-secondary:hover { background-color: var(--bg-hover); border-color: var(--accent-cyan); color: var(--accent-cyan); }
.btn-outline-danger { border-color: rgba(239, 68, 68, 0.4); color: var(--critical); }
.btn-outline-danger:hover { background-color: var(--critical); border-color: var(--critical); color: #fff; }
.btn-danger { background-color: var(--danger); border-color: var(--danger); color: #fff; }
.btn-success { background-color: var(--success); border-color: var(--success); color: #04191f; font-weight: 600; }
.btn-link { color: var(--accent-cyan); }

.btn-sm-outline {
    background: transparent;
    color: var(--accent-cyan);
    border: 1px solid rgba(6, 182, 212, 0.3);
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 11px;
    cursor: pointer;
    text-decoration: none;
    white-space: nowrap;
}
.btn-sm-outline:hover { background: rgba(6, 182, 212, 0.1); color: #22d3ee; }

/* === LAYOUT: TOPBAR === */
.topbar {
    height: var(--topbar-h);
    background: var(--bg-secondary);
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 24px;
    position: fixed;
    top: 0; left: var(--sidebar-w); right: 0;
    z-index: 100;
}
.topbar-left { display: flex; align-items: center; gap: 14px; min-width: 0; }
.topbar-right { display: flex; align-items: center; gap: 18px; }

.sidebar-toggle {
    display: none;
    background: transparent;
    border: 1px solid var(--border-color);
    color: var(--text-muted);
    border-radius: 6px;
    width: 34px; height: 34px;
    font-size: 15px;
    cursor: pointer;
    align-items: center;
    justify-content: center;
}

.breadcrumb-nav { display: flex; align-items: center; gap: 8px; font-size: 13px; min-width: 0; }
.breadcrumb-nav .sep { color: var(--text-dim); }
.breadcrumb-nav .current { color: var(--text-primary); font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

.status-badge {
    display: flex; align-items: center; gap: 6px;
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.3);
    border-radius: 999px;
    padding: 4px 12px;
    font-size: 12px; color: var(--success);
    white-space: nowrap;
}
.dot-green { width: 6px; height: 6px; border-radius: 50%; background: var(--success); display: inline-block; flex-shrink: 0; }
.dot-red   { width: 6px; height: 6px; border-radius: 50%; background: var(--critical); display: inline-block; flex-shrink: 0; }

.notif-btn {
    position: relative;
    font-size: 16px;
    color: var(--text-muted);
    cursor: pointer;
    line-height: 1;
}
.notif-btn:hover { color: var(--text-primary); }

/* Panel notifikasi */
.notif-menu {
    min-width: 340px;
    max-width: 380px;
    padding: 0;
}
.notif-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 14px;
    font-weight: 600;
    font-size: 13px;
    border-bottom: 1px solid var(--border-color);
}
.notif-menu .dropdown-divider { margin: 0; }
.notif-item {
    display: flex;
    gap: 10px;
    align-items: flex-start;
    padding: 9px 14px;
    white-space: normal;
}
.notif-prio {
    flex-shrink: 0;
    width: 7px;
    height: 7px;
    margin-top: 6px;
    border-radius: 50%;
    background: var(--text-muted);
}
.notif-prio.critical { background: var(--critical); }
.notif-prio.high     { background: var(--high); }
.notif-prio.medium   { background: var(--medium); }
.notif-prio.low      { background: var(--low); }
.notif-body { display: flex; flex-direction: column; min-width: 0; }
.notif-title {
    font-size: 13px;
    line-height: 1.35;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.notif-meta { font-size: 11px; color: var(--text-dim); margin-top: 2px; }
.notif-foot { text-align: center; font-size: 12px; }

.badge-count {
    position: absolute; top: -6px; right: -9px;
    background: var(--critical);
    color: #fff;
    border-radius: 999px;
    font-size: 9px;
    font-weight: 700;
    padding: 1px 5px;
    border: 1px solid var(--bg-secondary);
}

.user-menu { display: flex; align-items: center; gap: 9px; cursor: pointer; }
.avatar {
    width: 32px; height: 32px; border-radius: 50%;
    background: linear-gradient(135deg, var(--accent-cyan), var(--accent-blue));
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 13px; color: #04191f;
    flex-shrink: 0; text-transform: uppercase;
}
.user-info { line-height: 1.25; }
.user-name { font-size: 12px; font-weight: 600; color: var(--text-primary); white-space: nowrap; }
.user-role { font-size: 10px; color: var(--text-muted); text-transform: capitalize; }

/* === LAYOUT: SIDEBAR === */
.sidebar {
    width: var(--sidebar-w);
    background: var(--bg-sidebar);
    border-right: 1px solid var(--border-color);
    position: fixed;
    top: 0; left: 0; bottom: 0;
    display: flex; flex-direction: column;
    overflow-y: auto;
    z-index: 200;
}
.sidebar-brand {
    height: var(--topbar-h);
    display: flex; align-items: center; gap: 10px;
    padding: 0 16px;
    border-bottom: 1px solid var(--border-color);
    flex-shrink: 0;
}
.brand-icon { font-size: 20px; color: var(--accent-cyan); line-height: 1; }
.brand-text { min-width: 0; }
.brand-name { font-size: 14px; font-weight: 700; color: var(--text-primary); line-height: 1.2; }
.brand-sub { font-size: 10px; color: var(--text-dim); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

.sidebar-nav { flex: 1; padding: 12px 8px; }
.nav-section-label {
    font-size: 10px; font-weight: 600; letter-spacing: 0.1em;
    color: var(--text-dim); padding: 14px 10px 6px;
    text-transform: uppercase;
}
.nav-item { display: block; }
.sidebar-nav .nav-link,
.sidebar .nav-link {
    display: flex; align-items: center; gap: 10px;
    padding: 8px 10px; border-radius: 6px;
    color: var(--text-muted); text-decoration: none;
    font-size: 13px; font-weight: 500;
    margin-bottom: 2px;
    border-left: 2px solid transparent;
    transition: background 0.15s ease, color 0.15s ease;
}
.sidebar-nav .nav-link i, .sidebar .nav-link i {
    font-size: 14px; color: var(--text-dim); flex-shrink: 0; width: 16px;
    transition: color 0.15s ease;
}
.sidebar-nav .nav-link:hover, .sidebar .nav-link:hover { background-color: var(--bg-hover); color: var(--text-primary); }
.sidebar-nav .nav-link:hover i, .sidebar .nav-link:hover i { color: var(--text-muted); }
.sidebar-nav .nav-link.active, .sidebar .nav-link.active {
    background-color: rgba(6, 182, 212, 0.12);
    color: var(--accent-cyan);
    border-left-color: var(--accent-cyan);
    font-weight: 600;
}
.sidebar-nav .nav-link.active i, .sidebar .nav-link.active i { color: var(--accent-cyan); }

.nav-badge {
    margin-left: auto; padding: 1px 7px;
    border-radius: 999px; font-size: 10px; font-weight: 700;
    background: rgba(239, 68, 68, 0.2); color: var(--critical);
    flex-shrink: 0;
}

.sidebar-footer {
    padding: 10px 12px;
    border-top: 1px solid var(--border-color);
    display: flex; align-items: center; justify-content: space-between;
    gap: 8px;
    flex-shrink: 0;
}
.footer-link { color: var(--text-muted); font-size: 12px; text-decoration: none; }
.footer-link:hover { color: var(--accent-cyan); }
.footer-btn-logout {
    background: none; border: none; color: var(--text-muted);
    cursor: pointer; font-size: 12px; padding: 0;
}
.footer-btn-logout:hover { color: var(--critical); }

/* Legacy Bootstrap navbar, kept for auth/welcome pages outside .topbar */
.navbar { background: var(--bg-secondary) !important; border-bottom: 1px solid var(--border-color); }
.navbar-brand { font-weight: 700; color: var(--text-primary) !important; letter-spacing: 0.3px; }
.navbar-brand i { color: var(--accent-cyan); }
.navbar .nav-link { color: var(--text-muted) !important; font-size: 13px; }
.navbar .nav-link:hover { color: var(--text-primary) !important; }
.navbar .dropdown-menu { background-color: var(--bg-secondary); border: 1px solid var(--border-color); }
.brand-sub { font-size: 10px; color: var(--text-dim); }

/* === MAIN === */
.main-wrapper { margin-left: var(--sidebar-w); padding-top: var(--topbar-h); }
.main-content { padding: 24px; min-height: calc(100vh - var(--topbar-h)); }

.app-footer {
    text-align: center;
    color: var(--text-dim);
    padding: 16px 0 24px;
    font-size: 11px;
    border-top: 1px solid var(--border-color);
    margin-top: 24px;
}

.page-header {
    display: flex; justify-content: space-between; align-items: flex-start;
    gap: 16px; margin-bottom: 20px; flex-wrap: wrap;
}
.page-header-actions { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
.page-title { font-size: 1.3rem; font-weight: 700; color: var(--text-primary); margin: 0; }
.page-subtitle { font-size: 0.8rem; color: var(--text-muted); margin-top: 2px; }

.breadcrumb-panel {
    background-color: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 0.6rem 0.9rem;
    margin-bottom: 16px;
}
.breadcrumb-panel .breadcrumb { margin-bottom: 0; font-size: 0.8rem; }
.breadcrumb-item + .breadcrumb-item::before { color: var(--text-dim); content: "/"; }
.breadcrumb-item.active { color: var(--text-muted); }
a.breadcrumb-item { color: var(--text-muted); }
a.breadcrumb-item:hover { color: var(--accent-cyan); }

/* === GRID === */
.stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 20px; }
.content-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
.content-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 20px; }

/* === STAT CARDS === */
.stat-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 18px;
}
.stat-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; }
.stat-label {
    font-size: 11px; font-weight: 500; color: var(--text-muted);
    text-transform: uppercase; letter-spacing: 0.05em;
}
.stat-icon { font-size: 17px; line-height: 1; opacity: 0.9; }
.stat-value { font-size: 2rem; font-weight: 800; color: var(--text-primary); line-height: 1; margin-bottom: 6px; }
.stat-sub { font-size: 11px; color: var(--text-muted); margin-bottom: 10px; }
.stat-breakdown { display: flex; gap: 12px; flex-wrap: wrap; }

.stat-card .card-body { padding: 0; }
.stat-card i { font-size: 1.6rem; opacity: 0.85; }
.stat-card.bg-primary-soft, .bg-primary-soft { background: linear-gradient(135deg, rgba(6,182,212,0.18), rgba(59,130,246,0.14)) !important; }
.stat-card.bg-success-soft, .bg-success-soft { background: linear-gradient(135deg, rgba(34,197,94,0.18), rgba(16,185,129,0.12)) !important; }
.stat-card.bg-warning-soft, .bg-warning-soft { background: linear-gradient(135deg, rgba(234,179,8,0.18), rgba(245,158,11,0.12)) !important; }
.stat-card.bg-info-soft, .bg-info-soft { background: linear-gradient(135deg, rgba(6,182,212,0.16), rgba(139,92,246,0.12)) !important; }

/* Bars */
.stat-bar { height: 3px; background: var(--bg-tertiary); border-radius: 2px; overflow: hidden; margin-top: 8px; }
.bar-fill { height: 100%; border-radius: 2px; transition: width 0.5s ease; background: var(--accent-cyan); }
.bar-fill.accent { background: var(--accent-cyan); }
.trend-up { color: var(--critical); font-weight: 600; }
.trend-down { color: var(--success); font-weight: 600; }

.breakdown-row { display: flex; align-items: center; gap: 12px; padding: 6px 0; }
.breakdown-label { font-size: 12px; color: var(--text-muted); width: 120px; flex-shrink: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.breakdown-bar { flex: 1; height: 4px; background: var(--bg-tertiary); border-radius: 2px; overflow: hidden; }
.breakdown-count { font-size: 12px; font-weight: 600; color: var(--text-primary); width: 32px; text-align: right; }

/* === SEVERITY / STATUS BADGES === */
.badge-sev {
    display: inline-block;
    padding: 2px 8px; border-radius: 999px;
    font-size: 11px; font-weight: 600; text-transform: uppercase;
    letter-spacing: 0.03em; white-space: nowrap;
}
.badge-sev.critical, .sev.critical { background: rgba(239,68,68,0.15);  color: var(--critical); }
.badge-sev.high,     .sev.high     { background: rgba(249,115,22,0.15); color: var(--high); }
.badge-sev.medium,   .sev.medium   { background: rgba(234,179,8,0.15);  color: var(--medium); }
.badge-sev.low,      .sev.low      { background: rgba(34,197,94,0.15);  color: var(--low); }
.badge-sev.info,     .sev.info     { background: rgba(6,182,212,0.15);  color: var(--info); }
.badge-sev.muted,    .sev.muted    { background: rgba(100,116,139,0.15); color: var(--text-dim); }

.badge-status {
    display: inline-block; padding: 2px 8px; border-radius: 4px;
    font-size: 11px; font-weight: 600; text-transform: capitalize; white-space: nowrap;
}
.badge-status.open        { background: rgba(239,68,68,0.1);   color: var(--critical); border: 1px solid rgba(239,68,68,0.3); }
.badge-status.in-progress { background: rgba(234,179,8,0.1);   color: var(--medium);   border: 1px solid rgba(234,179,8,0.3); }
.badge-status.in_progress { background: rgba(234,179,8,0.1);   color: var(--medium);   border: 1px solid rgba(234,179,8,0.3); }
.badge-status.resolved    { background: rgba(34,197,94,0.1);    color: var(--low);      border: 1px solid rgba(34,197,94,0.3); }
.badge-status.closed      { background: rgba(100,116,139,0.1); color: var(--text-muted); border: 1px solid var(--border-color); }
.badge-status.quarantined { background: rgba(234,179,8,0.1);   color: var(--medium);   border: 1px solid rgba(234,179,8,0.3); }
.badge-status.isolated    { background: rgba(249,115,22,0.1);  color: var(--high);     border: 1px solid rgba(249,115,22,0.3); }
.badge-status.offline     { background: rgba(100,116,139,0.1); color: var(--text-dim); border: 1px solid var(--border-color); }
.badge-status.online      { background: rgba(34,197,94,0.1);    color: var(--low);      border: 1px solid rgba(34,197,94,0.3); }

/* Legacy soft badges, re-themed for dark surfaces */
.badge-soft-success { background-color: rgba(34,197,94,0.15);  color: var(--low);     padding: 5px 10px; border-radius: 20px; font-weight: 600; }
.badge-soft-warning { background-color: rgba(234,179,8,0.15);  color: var(--medium);  padding: 5px 10px; border-radius: 20px; font-weight: 600; }
.badge-soft-danger  { background-color: rgba(239,68,68,0.15);  color: var(--critical); padding: 5px 10px; border-radius: 20px; font-weight: 600; }
.badge-soft-info    { background-color: rgba(6,182,212,0.15);  color: var(--info);    padding: 5px 10px; border-radius: 20px; font-weight: 600; }

/* === ACTIVITY LOG === */
.activity-list { display: flex; flex-direction: column; }
.activity-item {
    display: flex; gap: 12px; padding: 10px 0;
    border-bottom: 1px solid var(--border-subtle);
}
.activity-item:last-child { border-bottom: none; }
.activity-dot { width: 8px; height: 8px; border-radius: 50%; margin-top: 6px; flex-shrink: 0; background: var(--text-dim); }
.activity-dot.incident, .activity-dot.create { background: var(--critical); }
.activity-dot.asset, .activity-dot.update     { background: var(--accent-cyan); }
.activity-dot.login, .activity-dot.login      { background: var(--success); }
.activity-dot.report, .activity-dot.export    { background: var(--medium); }
.activity-dot.delete                        { background: var(--critical); }
.activity-body { min-width: 0; }
.activity-text { font-size: 13px; color: var(--text-primary); }
.activity-meta { font-size: 11px; color: var(--text-muted); margin-top: 2px; }

/* === SEARCH / FILTER BAR === */
.filter-bar {
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 16px; flex-wrap: wrap;
}
.search-bar {
    display: flex; align-items: center; gap: 10px;
    background: var(--bg-tertiary);
    border: 1px solid var(--border-color);
    border-radius: 8px; padding: 8px 14px;
    width: 320px; max-width: 100%;
    transition: all 0.2s ease;
}
.search-bar:focus-within {
    border-color: var(--accent-cyan);
    box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.15);
    background: rgba(30, 37, 53, 0.95);
}
.search-bar i { color: var(--text-dim); font-size: 14px; flex-shrink: 0; transition: color 0.15s ease; }
.search-bar:focus-within i { color: var(--accent-cyan); }
.search-bar input {
    background: none; border: none; color: var(--text-primary);
    font-size: 13px; flex: 1; outline: none; min-width: 0;
}
.search-bar input::placeholder { color: var(--text-dim); }

.input-group .input-group-text {
    background-color: var(--bg-tertiary);
    border-color: var(--border-color);
    color: var(--text-muted);
    border-top-left-radius: 8px;
    border-bottom-left-radius: 8px;
}
.input-group .form-control {
    border-radius: 0 8px 8px 0;
}
.input-group:focus-within .input-group-text {
    border-color: var(--accent-cyan);
    color: var(--accent-cyan);
}
.input-group:focus-within .form-control {
    border-color: var(--accent-cyan);
    box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.12);
}

/* === TOASTS === */
.toast-container { position: fixed; bottom: 24px; right: 24px; z-index: 9999; display: flex; flex-direction: column; }
.toast {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 8px; padding: 14px 18px; margin-top: 8px;
    min-width: 280px; max-width: 380px; font-size: 13px;
    color: var(--text-primary);
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    animation: slideIn 0.2s ease;
}
.toast.success { border-left: 3px solid var(--success); }
.toast.error   { border-left: 3px solid var(--critical); }
.toast.warning { border-left: 3px solid var(--warning); }
.toast.info    { border-left: 3px solid var(--info); }
@keyframes slideIn { from { transform: translateX(20px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

/* === EMPTY STATE === */
.empty-state { text-align: center; padding: 3rem 1rem; color: var(--text-dim); }
.empty-state i, .empty-icon { font-size: 3rem; opacity: 0.3; display: block; margin-bottom: 0.75rem; }
.empty-title { font-size: 15px; font-weight: 600; color: var(--text-muted); margin-bottom: 4px; }
.empty-desc { font-size: 13px; color: var(--text-dim); margin-bottom: 16px; }

/* === MISC === */
.table-responsive-wide { overflow-x: auto; }
.scan-freshness { font-size: 0.72rem; font-weight: 600; }
.mono, .text-mono {
    font-family: 'JetBrains Mono', 'Fira Code', 'SF Mono', 'Courier New', monospace;
    font-size: 12px;
}
.token-box {
    display: flex; align-items: center; gap: 10px;
    background: var(--bg-tertiary);
    border: 1px solid var(--border-color);
    border-radius: 6px; padding: 10px 12px;
}
.token-box code {
    flex: 1; color: var(--accent-cyan); font-size: 12px;
    word-break: break-all; margin: 0;
}

/* === RESPONSIVE === */
@media (max-width: 1200px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .content-grid-3 { grid-template-columns: 1fr; }
}
@media (max-width: 992px) {
    .sidebar { transform: translateX(-100%); transition: transform 0.2s ease; }
    .sidebar.open { transform: translateX(0); }
    .topbar { left: 0; padding: 0 16px; }
    .sidebar-toggle { display: flex; }
    .main-wrapper { margin-left: 0; }
    .content-grid-2 { grid-template-columns: 1fr; }
    .status-badge span:last-child { display: none; }
}
@media (max-width: 576px) {
    .stats-grid { grid-template-columns: 1fr; }
    .user-info { display: none; }
    .toast-container { left: 16px; right: 16px; bottom: 16px; }
    .toast { min-width: 0; max-width: none; }
}

/* === GLOBAL OVERRIDES: legacy light-mode classes === */
.bg-white {
    background-color: var(--bg-secondary) !important;
    color: var(--text-primary) !important;
    border-color: var(--border-color) !important;
}
.text-dark { color: var(--text-primary) !important; }
.text-black { color: var(--text-primary) !important; }
.bg-light {
    background-color: var(--bg-tertiary) !important;
    color: var(--text-primary) !important;
    border-color: var(--border-color) !important;
}
.border-light { border-color: var(--border-color) !important; }

/* === SIZE SCALE-UP: internal UI enlarged ~10% === */
:root { --topbar-h: 66px; --sidebar-w: 264px; }
body { font-size: 15px; }
h1 { font-size: 1.65rem; }
h2 { font-size: 1.4rem; }
h3 { font-size: 1.15rem; }
.card { border-radius: 10px; }
.card-header { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; padding: 1rem 1.25rem; border-radius: 10px 10px 0 0; }
.card-body { padding: 1.25rem; }
.table > thead > tr > th { font-size: 12px; }
.form-control, .form-select { font-size: 14px; }
.form-label, label, .form-check-label { font-size: 13px; }
.dropdown-item { font-size: 14px; }
.btn { font-size: 14px; }
.btn-sm-outline { font-size: 12px; padding: 5px 12px; }
.breadcrumb-nav { font-size: 14px; }
.status-badge { font-size: 13px; padding: 5px 14px; }
.notif-btn { font-size: 18px; }
.avatar { width: 36px; height: 36px; font-size: 14px; }
.user-name { font-size: 13px; }
.user-role { font-size: 11px; }
.brand-icon { font-size: 22px; }
.brand-name { font-size: 15px; }
.brand-sub { font-size: 11px; }
.nav-section-label { font-size: 11px; }
.sidebar-nav .nav-link, .sidebar .nav-link { padding: 10px 12px; font-size: 14px; }
.sidebar-nav .nav-link i, .sidebar .nav-link i { font-size: 16px; width: 18px; }
.nav-badge { font-size: 11px; }
.footer-link, .footer-btn-logout { font-size: 13px; }
.main-content { padding: 28px; }
.app-footer { font-size: 12px; }
.page-title { font-size: 1.5rem; }
.page-subtitle { font-size: 0.9rem; }
.breadcrumb-panel { padding: 0.7rem 1rem; }
.breadcrumb-panel .breadcrumb { font-size: 0.875rem; }
.stats-grid { gap: 18px; }
.content-grid-2 { gap: 22px; }
.content-grid-3 { gap: 18px; }
.stat-card { padding: 22px; border-radius: 10px; }
.stat-label { font-size: 12px; }
.stat-icon { font-size: 19px; }
.stat-value { font-size: 2.3rem; }
.stat-sub { font-size: 12px; }
.stat-card i { font-size: 1.8rem; }
.breakdown-label { font-size: 13px; width: 130px; }
.breakdown-count { font-size: 13px; }
.badge-sev { font-size: 12px; padding: 3px 10px; }
.badge-status { font-size: 12px; padding: 3px 10px; }
.activity-text { font-size: 14px; }
.activity-meta { font-size: 12px; }
.search-bar { padding: 10px 16px; width: 300px; }
.search-bar input { font-size: 14px; }
.toast { font-size: 14px; }
.empty-state { padding: 3.5rem 1rem; }
.empty-title { font-size: 16px; }
.empty-desc { font-size: 14px; }
.mono, .text-mono { font-size: 13px; }
.token-box code { font-size: 13px; }

/* Helpers */
.text-cyan { color: var(--accent-cyan) !important; }
.hover-cyan:hover { color: var(--accent-cyan) !important; }
.avatar-xs { width: 24px; height: 24px; font-size: 10px; font-weight: 700; border-radius: 50%; }
.avatar-sm { width: 32px; height: 32px; font-size: 12px; font-weight: 700; border-radius: 50%; }
.avatar-lg { width: 56px; height: 56px; font-size: 20px; font-weight: 700; }
.bg-tertiary { background-color: var(--bg-tertiary) !important; }
</style>
