<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Mo'men Sarsour</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

    @stack('styles')

    <style>
        /* ============================================================
           ROOT VARIABLES
        ============================================================ */
        :root {
            --sidebar-w: 252px;
            --topbar-h: 66px;
            --bg: #f0f2f8;
            --white: #ffffff;
            --sidebar-bg: #111827;
            --sidebar-border: rgba(255, 255, 255, 0.06);
            --sidebar-active: #2f7bff;
            --sidebar-active-glow: rgba(47, 123, 255, 0.35);
            --sidebar-text: #8b97b0;
            --sidebar-hover: rgba(255, 255, 255, 0.05);
            --text-primary: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f3;
            --shadow-sm: 0 1px 4px rgba(0, 0, 0, 0.06);
            --shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 16px 48px rgba(0, 0, 0, 0.14);
            --radius: 14px;
            --radius-sm: 10px;
            --font: 'Plus Jakarta Sans', sans-serif;
            --g1: linear-gradient(135deg, #667eea, #764ba2);
            --transition: all .22s cubic-bezier(.4, 0, .2, 1);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font);
            background: var(--bg);
            color: var(--text-primary);
            overflow-x: hidden;
        }

        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 99px;
        }

        /* ============================================================
           SIDEBAR
        ============================================================ */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform .3s cubic-bezier(.4, 0, .2, 1);
            overflow: hidden;
        }

        #sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #667eea, #2f7bff, #11998e);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 18px 16px;
            border-bottom: 1px solid var(--sidebar-border);
            text-decoration: none;
            flex-shrink: 0;
        }

        .brand-logo {
            width: 38px;
            height: 38px;
            border-radius: 11px;
            background: var(--sidebar-active);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            font-weight: 800;
            color: #fff;
            box-shadow: 0 4px 16px var(--sidebar-active-glow);
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
        }

        .brand-logo::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, .15) 50%, transparent 70%);
            transform: translateX(-100%);
            transition: transform .6s;
        }

        .sidebar-brand:hover .brand-logo::after {
            transform: translateX(100%);
        }

        .brand-name {
            font-size: 14px;
            font-weight: 800;
            color: #fff;
            display: block;
        }

        .brand-sub {
            font-size: 10.5px;
            color: var(--sidebar-text);
        }

        .nav-group-label {
            padding: 16px 18px 6px;
            font-size: 9.5px;
            font-weight: 800;
            color: var(--sidebar-text);
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .sidebar-scroll {
            flex: 1;
            overflow-y: auto;
            padding-bottom: 10px;
        }

        .sidebar-scroll::-webkit-scrollbar {
            width: 3px;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0 10px;
            margin: 0;
        }

        .sidebar-nav li a {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 14px;
            border-radius: var(--radius-sm);
            margin: 1px 0;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .sidebar-nav li a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 0;
            border-radius: 0 3px 3px 0;
            background: var(--sidebar-active);
            transition: height .2s;
        }

        .sidebar-nav li a:hover {
            background: var(--sidebar-hover);
            color: #e2e8f0;
        }

        .sidebar-nav li a:hover::before {
            height: 60%;
        }

        .sidebar-nav li a.active {
            background: rgba(47, 123, 255, .15);
            color: #fff;
            font-weight: 600;
        }

        .sidebar-nav li a.active::before {
            height: 70%;
        }

        .sidebar-nav li a i {
            font-size: 17px;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
            transition: transform .2s;
        }

        .sidebar-nav li a:hover i {
            transform: scale(1.1);
        }

        .sidebar-nav li a.active i {
            color: var(--sidebar-active);
        }

        .sidebar-nav li a.nav-danger {
            color: #f87171;
        }

        .sidebar-nav li a.nav-danger:hover {
            background: rgba(239, 68, 68, .1);
            color: #ef4444;
        }

        .nav-badge {
            margin-left: auto;
            background: var(--sidebar-active);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 20px;
            box-shadow: 0 2px 8px var(--sidebar-active-glow);
        }

        .nav-badge.danger {
            background: #ef4444;
            box-shadow: 0 2px 8px rgba(239, 68, 68, .35);
        }

        .sidebar-footer {
            padding: 12px 10px;
            border-top: 1px solid var(--sidebar-border);
            flex-shrink: 0;
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            background: rgba(255, 255, 255, .04);
            cursor: pointer;
            text-decoration: none;
            transition: var(--transition);
            border: 1px solid var(--sidebar-border);
        }

        .user-card:hover {
            background: rgba(255, 255, 255, .08);
            border-color: rgba(47, 123, 255, .3);
        }

        .user-avatar-sb {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--g1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 800;
            font-size: 14px;
            flex-shrink: 0;
            box-shadow: 0 2px 10px rgba(102, 126, 234, .4);
            position: relative;
        }

        .user-avatar-sb .status-dot {
            position: absolute;
            bottom: 1px;
            right: 1px;
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #22c55e;
            border: 2px solid var(--sidebar-bg);
        }

        .user-name {
            font-size: 13px;
            font-weight: 700;
            color: #e2e8f0;
            display: block;
        }

        .user-role {
            font-size: 11px;
            color: var(--sidebar-text);
            display: block;
        }

        .user-menu-arrow {
            margin-left: auto;
            color: var(--sidebar-text);
            font-size: 12px;
            transition: transform .2s;
        }

        .user-card:hover .user-menu-arrow {
            transform: rotate(90deg);
            color: var(--sidebar-active);
        }

        /* ============================================================
           TOPBAR
        ============================================================ */
        #topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: rgba(255, 255, 255, .92);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 12px;
            z-index: 900;
            box-shadow: var(--shadow-sm);
            transition: left .3s cubic-bezier(.4, 0, .2, 1);
        }

        .topbar-title {
            flex: 1;
            min-width: 0;
        }

        .topbar-title h1 {
            font-size: 19px;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1;
        }

        .topbar-breadcrumb {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .topbar-breadcrumb a {
            color: var(--sidebar-active);
            text-decoration: none;
            font-weight: 600;
        }

        .topbar-search {
            position: relative;
            display: flex;
            align-items: center;
        }

        .topbar-search input {
            background: var(--bg);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 8px 40px 8px 38px;
            font-size: 13px;
            font-family: var(--font);
            width: 220px;
            color: var(--text-primary);
            transition: var(--transition);
            outline: none;
            cursor: pointer;
        }

        .topbar-search input:focus {
            width: 280px;
            border-color: var(--sidebar-active);
            box-shadow: 0 0 0 3px rgba(47, 123, 255, .1);
            background: #fff;
        }

        .topbar-search .s-icon {
            position: absolute;
            left: 12px;
            color: var(--text-muted);
            font-size: 15px;
            pointer-events: none;
        }

        .topbar-search .s-kbd {
            position: absolute;
            right: 10px;
            font-size: 10px;
            color: var(--text-muted);
            background: var(--border);
            padding: 2px 6px;
            border-radius: 5px;
            font-weight: 700;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tb-btn {
            width: 38px;
            height: 38px;
            border-radius: var(--radius-sm);
            background: var(--bg);
            border: 1.5px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 16px;
            color: var(--text-muted);
            transition: var(--transition);
            position: relative;
            text-decoration: none;
        }

        .tb-btn:hover {
            background: var(--sidebar-active);
            color: #fff;
            border-color: var(--sidebar-active);
            box-shadow: 0 4px 14px var(--sidebar-active-glow);
            transform: translateY(-1px);
        }

        .badge-count {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #ef4444;
            color: #fff;
            font-size: 9px;
            font-weight: 800;
            min-width: 16px;
            height: 16px;
            border-radius: 99px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--bg);
            padding: 0 3px;
            animation: pulse-badge 2s infinite;
        }

        @keyframes pulse-badge {

            0%,
            100% {
                transform: scale(1)
            }

            50% {
                transform: scale(1.15)
            }
        }

        .badge-dot {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            border: 2px solid var(--bg);
        }

        .topbar-divider {
            width: 1px;
            height: 28px;
            background: var(--border);
            margin: 0 4px;
        }

        .topbar-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--g1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 800;
            font-size: 15px;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(102, 126, 234, .4);
            border: 2px solid #fff;
            transition: var(--transition);
            position: relative;
        }

        .topbar-avatar:hover {
            transform: scale(1.08);
        }

        .topbar-avatar .online-ring {
            position: absolute;
            inset: -3px;
            border-radius: 50%;
            border: 2px solid #22c55e;
            opacity: 0;
            animation: ring-pulse 3s infinite;
        }

        @keyframes ring-pulse {

            0%,
            70%,
            100% {
                opacity: 0;
                transform: scale(1)
            }

            35% {
                opacity: .6;
                transform: scale(1.15)
            }
        }

        /* ============================================================
           DROPDOWN PANELS
        ============================================================ */
        .drop-panel {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transform: translateY(-8px) scale(.97);
            transition: all .2s cubic-bezier(.4, 0, .2, 1);
            transform-origin: top right;
        }

        .drop-panel.open {
            opacity: 1;
            visibility: visible;
            pointer-events: all;
            transform: translateY(0) scale(1);
        }

        .drop-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 18px 12px;
            border-bottom: 1px solid var(--border);
        }

        .drop-header h6 {
            font-size: 14px;
            font-weight: 800;
            margin: 0;
        }

        .drop-mark-all {
            font-size: 12px;
            font-weight: 600;
            color: var(--sidebar-active);
            cursor: pointer;
            background: none;
            border: none;
            transition: opacity .2s;
        }

        .drop-mark-all:hover {
            opacity: .7;
        }

        /* ── Notifications Panel ── */
        .notif-panel {
            width: 360px;
        }

        .notif-tabs {
            display: flex;
            padding: 10px 18px 0;
            gap: 4px;
            border-bottom: 1px solid var(--border);
        }

        .notif-tab {
            padding: 7px 14px;
            font-size: 12px;
            font-weight: 700;
            border-radius: 8px 8px 0 0;
            cursor: pointer;
            color: var(--text-muted);
            border: none;
            background: none;
            border-bottom: 2px solid transparent;
            transition: var(--transition);
        }

        .notif-tab.active {
            color: var(--sidebar-active);
            border-bottom-color: var(--sidebar-active);
        }

        .notif-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .notif-item {
            display: flex;
            gap: 12px;
            padding: 14px 18px;
            border-bottom: 1px solid #f8fafc;
            cursor: pointer;
            transition: background .15s;
            position: relative;
        }

        .notif-item:hover {
            background: #f8fafc;
        }

        .notif-item.unread {
            background: rgba(47, 123, 255, .025);
        }

        .notif-item.unread::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--sidebar-active);
            border-radius: 0 3px 3px 0;
        }

        .notif-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .notif-body p {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-primary);
            margin: 0 0 3px;
            line-height: 1.4;
        }

        .notif-time {
            font-size: 11px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .notif-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--sidebar-active);
            flex-shrink: 0;
            margin-top: 4px;
        }

        .notif-footer {
            padding: 12px 18px;
            border-top: 1px solid var(--border);
            text-align: center;
        }

        .notif-footer a {
            font-size: 13px;
            font-weight: 700;
            color: var(--sidebar-active);
            text-decoration: none;
        }

        .notif-footer a:hover {
            opacity: .7;
        }

        /* ── Messages Panel ── */
        .msg-panel {
            width: 340px;
        }

        .msg-search-wrap {
            padding: 12px 18px;
            border-bottom: 1px solid var(--border);
        }

        .msg-search-wrap input {
            width: 100%;
            background: var(--bg);
            border: 1.5px solid var(--border);
            border-radius: 8px;
            padding: 7px 12px;
            font-size: 13px;
            font-family: var(--font);
            outline: none;
            transition: border-color .2s;
            color: var(--text-primary);
        }

        .msg-search-wrap input:focus {
            border-color: var(--sidebar-active);
        }

        .msg-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .msg-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px 18px;
            border-bottom: 1px solid #f8fafc;
            cursor: pointer;
            transition: background .15s;
        }

        .msg-item:hover {
            background: #f8fafc;
        }

        .msg-av {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 800;
            font-size: 16px;
            flex-shrink: 0;
            position: relative;
        }

        .msg-av .online {
            position: absolute;
            bottom: 1px;
            right: 1px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #22c55e;
            border: 2px solid #fff;
        }

        .msg-body {
            flex: 1;
            min-width: 0;
        }

        .msg-name {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--text-primary);
        }

        .msg-preview {
            font-size: 12px;
            color: var(--text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .msg-meta {
            text-align: right;
            flex-shrink: 0;
        }

        .msg-time {
            font-size: 11px;
            color: var(--text-muted);
            display: block;
        }

        .msg-badge-sm {
            background: var(--sidebar-active);
            color: #fff;
            font-size: 10px;
            font-weight: 800;
            border-radius: 99px;
            padding: 1px 7px;
            display: inline-block;
            margin-top: 4px;
        }

        /* ── User Menu Panel ── */
        .user-panel {
            width: 240px;
        }

        .user-panel-header {
            padding: 18px;
            border-bottom: 1px solid var(--border);
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .user-panel-av {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: var(--g1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 800;
            font-size: 18px;
            box-shadow: 0 4px 14px rgba(102, 126, 234, .4);
        }

        .user-panel-name {
            font-size: 14px;
            font-weight: 800;
            color: var(--text-primary);
        }

        .user-panel-role {
            font-size: 11.5px;
            color: var(--text-muted);
        }

        .user-panel-email {
            font-size: 11px;
            color: var(--sidebar-active);
            font-weight: 600;
            margin-top: 2px;
        }

        .user-menu-list {
            padding: 8px;
        }

        .umenu-item {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 500;
            color: var(--text-primary);
            text-decoration: none;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }

        .umenu-item:hover {
            background: var(--bg);
        }

        .umenu-item i {
            font-size: 16px;
            width: 18px;
            text-align: center;
        }

        .umenu-item.danger {
            color: #ef4444;
        }

        .umenu-item.danger:hover {
            background: #fef2f2;
        }

        .umenu-divider {
            height: 1px;
            background: var(--border);
            margin: 6px 8px;
        }

        .umenu-tag {
            margin-left: auto;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px;
        }

        /* ── Search Overlay ── */
        .search-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .65);
            backdrop-filter: blur(8px);
            z-index: 9998;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding-top: 80px;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: all .2s;
        }

        .search-overlay.open {
            opacity: 1;
            visibility: visible;
            pointer-events: all;
        }

        .search-modal {
            background: #fff;
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            width: 560px;
            max-width: 95vw;
            overflow: hidden;
            transform: translateY(-20px);
            transition: transform .2s;
        }

        .search-overlay.open .search-modal {
            transform: translateY(0);
        }

        .search-input-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 18px 20px;
            border-bottom: 1px solid var(--border);
        }

        .search-input-row i {
            font-size: 20px;
            color: var(--text-muted);
        }

        .search-input-row input {
            flex: 1;
            border: none;
            outline: none;
            font-size: 16px;
            font-family: var(--font);
            color: var(--text-primary);
            background: transparent;
        }

        .search-input-row input::placeholder {
            color: var(--text-muted);
        }

        .esc-btn {
            background: var(--bg);
            border: none;
            border-radius: 7px;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 700;
            color: var(--text-muted);
            cursor: pointer;
        }

        .search-results {
            padding: 12px;
            max-height: 360px;
            overflow-y: auto;
        }

        .s-label {
            font-size: 10.5px;
            font-weight: 800;
            color: var(--text-muted);
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 4px 8px 8px;
        }

        .s-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 10px;
            cursor: pointer;
            transition: background .15s;
        }

        .s-item:hover {
            background: var(--bg);
        }

        .s-icon {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        .s-item p {
            font-size: 13.5px;
            font-weight: 600;
            margin: 0;
            color: var(--text-primary);
        }

        .s-item small {
            font-size: 11.5px;
            color: var(--text-muted);
        }

        .search-foot {
            padding: 10px 20px;
            background: #f8fafc;
            border-top: 1px solid var(--border);
            display: flex;
            gap: 16px;
        }

        .s-hint {
            font-size: 11px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .s-hint kbd {
            background: var(--border);
            border-radius: 4px;
            padding: 1px 6px;
            font-size: 10px;
            font-weight: 700;
        }

        /* ── Toast ── */
        #toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }

        .toast-item {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #fff;
            border-radius: 14px;
            padding: 14px 18px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border);
            min-width: 290px;
            max-width: 340px;
            pointer-events: all;
            transform: translateX(120%);
            transition: transform .35s cubic-bezier(.4, 0, .2, 1);
            position: relative;
            overflow: hidden;
        }

        .toast-item.show {
            transform: translateX(0);
        }

        .toast-item::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: var(--sidebar-active);
            animation: t-prog 4s linear forwards;
        }

        @keyframes t-prog {
            from {
                width: 100%
            }

            to {
                width: 0
            }
        }

        .toast-ico {
            font-size: 22px;
            flex-shrink: 0;
        }

        .toast-txt p {
            font-size: 13px;
            font-weight: 700;
            margin: 0 0 2px;
            color: var(--text-primary);
        }

        .toast-txt small {
            font-size: 12px;
            color: var(--text-muted);
        }

        .toast-x {
            margin-left: auto;
            background: none;
            border: none;
            font-size: 17px;
            color: var(--text-muted);
            cursor: pointer;
            flex-shrink: 0;
        }

        /* ── Logout Modal ── */
        .logout-modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .2);
        }

        /* ============================================================
           MAIN
        ============================================================ */
        #main {
            margin-left: var(--sidebar-w);
            padding-top: var(--topbar-h);
            min-height: 100vh;
            transition: margin-left .3s cubic-bezier(.4, 0, .2, 1);
        }

        .content-area {
            padding: 28px;
        }

        /* ── Shared components ── */
        .card-box {
            background: var(--white);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            padding: 22px 24px;
        }

        .card-box .card-title-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
        }

        .card-box .card-title-row h6 {
            font-size: 15px;
            font-weight: 800;
            margin: 0;
        }

        .btn-primary-dash {
            background: var(--sidebar-active);
            color: #fff;
            border: none;
            border-radius: var(--radius-sm);
            padding: 9px 20px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: var(--transition);
            box-shadow: 0 4px 14px var(--sidebar-active-glow);
            text-decoration: none;
        }

        .btn-primary-dash:hover {
            background: #1a65e8;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px var(--sidebar-active-glow);
        }

        .alert-dash {
            border-radius: var(--radius-sm);
            padding: 13px 18px;
            font-size: 13.5px;
            font-weight: 500;
            border: none;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: fadeUp .4s ease both;
        }

        /* Mobile */
        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .5);
            z-index: 999;
            backdrop-filter: blur(2px);
        }

        .mob-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 22px;
            color: var(--text-primary);
            cursor: pointer;
        }

        @media (max-width:991px) {
            #sidebar {
                transform: translateX(-100%);
            }

            #sidebar.open {
                transform: translateX(0);
            }

            #sidebar-overlay.open {
                display: block;
            }

            #topbar {
                left: 0;
            }

            #main {
                margin-left: 0;
            }

            .mob-toggle {
                display: flex;
            }

            .topbar-search {
                display: none;
            }
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(14px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .fade-up {
            animation: fadeUp .45s ease both;
        }

        .d1 {
            animation-delay: .05s
        }

        .d2 {
            animation-delay: .1s
        }

        .d3 {
            animation-delay: .15s
        }

        .d4 {
            animation-delay: .2s
        }

        .d5 {
            animation-delay: .25s
        }

        .d6 {
            animation-delay: .3s
        }
    </style>
</head>

<body>

    {{-- Overlays --}}
    <div id="sidebar-overlay" onclick="closeSidebar()"></div>

    {{-- ── SEARCH OVERLAY ── --}}
    <div class="search-overlay" id="searchOverlay" onclick="closeSearchOverlay(event)">
        <div class="search-modal">
            <div class="search-input-row">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Search pages, projects, settings…"
                    oninput="liveSearch(this.value)" autocomplete="off">
                <button class="esc-btn" onclick="closeSearch()">ESC</button>
            </div>
            <div class="search-results" id="searchResults">
                <div class="s-label">Quick Links</div>
                <div class="s-item" onclick="goto('{{ route('dashboard.index') }}')">
                    <div class="s-icon" style="background:rgba(47,123,255,.1);color:#2f7bff;"><i
                            class="bi bi-grid-1x2-fill"></i></div>
                    <div>
                        <p>Dashboard</p><small>Overview & statistics</small>
                    </div>
                </div>
                <div class="s-item" onclick="goto('{{ route('dashboard.projects.index') }}')">
                    <div class="s-icon" style="background:rgba(17,153,142,.1);color:#11998e;"><i
                            class="bi bi-folder2-open"></i></div>
                    <div>
                        <p>Projects</p><small>Manage portfolio projects</small>
                    </div>
                </div>
                <div class="s-item" onclick="goto('{{ route('profile.edit') }}')">
                    <div class="s-icon" style="background:rgba(249,83,198,.1);color:#f953c6;"><i
                            class="bi bi-person-lines-fill"></i></div>
                    <div>
                        <p>Profile</p><small>Edit personal info</small>
                    </div>
                </div>
                <div class="s-item" onclick="goto('{{ route('dashboard.settings') }}')">
                    <div class="s-icon" style="background:rgba(247,151,30,.1);color:#f7971e;"><i
                            class="bi bi-gear-fill"></i></div>
                    <div>
                        <p>Settings</p><small>Account & preferences</small>
                    </div>
                </div>
            </div>
            <div class="search-foot">
                <span class="s-hint"><kbd>↵</kbd> Open</span>
                <span class="s-hint"><kbd>ESC</kbd> Close</span>
                <span class="s-hint"><kbd>Ctrl K</kbd> Toggle</span>
            </div>
        </div>
    </div>

    {{-- ── TOAST CONTAINER ── --}}
    <div id="toast-container"></div>

    {{-- ════════════════════════════════════════
     SIDEBAR
════════════════════════════════════════ --}}
    <nav id="sidebar">

        <a href="{{ route('dashboard.index') }}" class="sidebar-brand">
            <div class="brand-logo">M</div>
            <div>
                <span class="brand-name">CV Admin</span>
                <span class="brand-sub">Mo'men Sarsour</span>
            </div>
        </a>

        <div class="sidebar-scroll">

            <div class="nav-group-label">Main</div>
            <ul class="sidebar-nav">
                <li><a href="{{ route('dashboard.index') }}"
                        class="{{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i> Dashboard
                    </a></li>
                <li><a href="{{ route('dashboard.profile') }}"
                        class="{{ request()->routeIs('dashboard.profile*') ? 'active' : '' }}">
                        <i class="bi bi-person-lines-fill"></i> Profile
                    </a></li>
                <li><a href="{{ route('dashboard.projects.index') }}"
                        class="{{ request()->routeIs('dashboard.projects*') ? 'active' : '' }}">
                        <i class="bi bi-folder2-open"></i> Projects
                        <span class="nav-badge">{{ \App\Models\Project::count() }}</span>
                    </a></li>
                <li><a href="{{ route('dashboard.skills.index') }}"
                        class="{{ request()->routeIs('dashboard.skills*') ? 'active' : '' }}">
                        <i class="bi bi-lightning-charge-fill"></i> Skills
                    </a></li>
                <li><a href="{{ route('dashboard.resume') }}"
                        class="{{ request()->routeIs('dashboard.resume*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-person-fill"></i> Resume
                    </a></li>
                <li><a href="{{ route('dashboard.experiences.index') }}"
                        class="{{ request()->routeIs('dashboard.experiences*') ? 'active' : '' }}">
                        <i class="bi bi-briefcase-fill"></i> Experiences
                    </a></li>
                <li><a href="{{ route('dashboard.education.index') }}"
                        class="{{ request()->routeIs('dashboard.education*') ? 'active' : '' }}">
                        <i class="bi bi-mortarboard-fill"></i> Education
                    </a></li>
                <li><a href="{{ route('dashboard.social-links.index') }}"
                        class="{{ request()->routeIs('dashboard.social-links*') ? 'active' : '' }}">
                        <i class="bi bi-share-fill"></i> Social Links
                    </a></li>
                <li><a href="{{ route('dashboard.messages.index') }}"
                        class="{{ request()->routeIs('dashboard.messages*') ? 'active' : '' }}">
                        <i class="bi bi-envelope-fill"></i> Messages
                        @php
                            $unreadCount = \App\Models\Message::where('is_read', false)->count();
                        @endphp
                        @if ($unreadCount > 0)
                            <span class="nav-badge danger">{{ $unreadCount }}</span>
                        @endif
                    </a></li>

            </ul>

            <div class="nav-group-label">Manage</div>
            <ul class="sidebar-nav">
                <li><a href="{{ route('dashboard.messages.index') }}"
                        class="{{ request()->routeIs('dashboard.messages.index*') ? 'active' : '' }}">
                        <i class="bi bi-envelope-fill"></i> Messages
                        <span class="nav-badge danger">3</span>
                    </a></li>
                <li><a href="{{ route('dashboard.analytics') }}"
                        class="{{ request()->routeIs('dashboard.analytics*') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-fill"></i> Analytics
                    </a></li>
                <li><a href="{{ route('dashboard.clients') }}"
                        class="{{ request()->routeIs('dashboard.clients*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i> Clients
                    </a></li>
                <li><a href="{{ route('dashboard.settings') }}"
                        class="{{ request()->routeIs('dashboard.settings*') ? 'active' : '' }}">
                        <i class="bi bi-gear-fill"></i> Settings
                    </a></li>
            </ul>

            <div class="nav-group-label">Account</div>
            <ul class="sidebar-nav">
                <li><a href="{{ route('profile.edit') }}"
                        class="{{ request()->routeIs('profile*') ? 'active' : '' }}">
                        <i class="bi bi-person-circle"></i> Edit Profile
                    </a></li>
                <li><a href="{{ route('password.edit') }}"
                        class="{{ request()->routeIs('password*') ? 'active' : '' }}">
                        <i class="bi bi-shield-lock-fill"></i> Change Password
                    </a></li>
                <li>
                    <a href="#" class="nav-danger" onclick="event.preventDefault();confirmLogout();">
                        <i class="bi bi-box-arrow-right"></i> Sign Out
                    </a>
                    <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST"
                        style="display:none;">@csrf</form>
                </li>
            </ul>

        </div>

        <div class="sidebar-footer">
            <a href="{{ route('profile.edit') }}" class="user-card">
                <div class="user-avatar-sb">
                    M
                    <span class="status-dot"></span>
                </div>
                <div>
                    <span class="user-name">Mo'men Sarsour</span>
                    <span class="user-role">Full Stack Developer</span>
                </div>
                <i class="bi bi-chevron-right user-menu-arrow"></i>
            </a>
        </div>
    </nav>

    {{-- ════════════════════════════════════════
     TOPBAR
════════════════════════════════════════ --}}
    <header id="topbar">

        <button class="mob-toggle me-1" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>

        <div class="topbar-title">
            <h1>@yield('page-title', 'Overview')</h1>
            <nav class="topbar-breadcrumb">
                <a href="{{ route('dashboard.index') }}"><i class="bi bi-house-fill" style="font-size:11px;"></i>
                    Home</a>
                <span class="sep" style="opacity:.4;">/</span>
                <span>@yield('page-title', 'Dashboard')</span>
            </nav>
        </div>

        {{-- Search trigger --}}
        <div class="topbar-search">
            <i class="bi bi-search s-icon"></i>
            <input type="text" placeholder="Quick search… (Ctrl+K)" readonly onclick="openSearch()"
                style="cursor:pointer;">
            <span class="s-kbd">Ctrl K</span>
        </div>

        <div class="topbar-actions">

            {{-- ── Notifications Button + Panel ── --}}
            <div style="position:relative;">
                <button class="tb-btn" id="notifBtn" onclick="togglePanel('notifPanel')" aria-label="Notifications"
                    title="Notifications">
                    <i class="bi bi-bell-fill"></i>
                    <span class="badge-count" id="notifBadge">3</span>
                </button>
                <div class="drop-panel notif-panel" id="notifPanel">
                    <div class="drop-header">
                        <h6>🔔 Notifications</h6>
                        <button class="drop-mark-all" onclick="markAllRead()">Mark all read</button>
                    </div>
                    <div class="notif-tabs">
                        <button class="notif-tab active" onclick="filterNotif(this,'all')">All <span
                                style="background:rgba(47,123,255,.15);color:#2f7bff;border-radius:20px;padding:1px 7px;font-size:10px;margin-left:3px;"
                                id="notifAllCount">3</span></button>
                        <button class="notif-tab" onclick="filterNotif(this,'unread')">Unread</button>
                        <button class="notif-tab" onclick="filterNotif(this,'read')">Read</button>
                    </div>
                    <div class="notif-list">
                        <div class="notif-item unread" onclick="readNotif(this)">
                            <div class="notif-icon" style="background:rgba(47,123,255,.12);color:#2f7bff;"><i
                                    class="bi bi-person-plus-fill"></i></div>
                            <div class="notif-body">
                                <p>New client <strong>Ali Haffez</strong> sent a project request</p>
                                <span class="notif-time"><i class="bi bi-clock" style="font-size:10px;"></i> 5
                                    minutes ago</span>
                            </div>
                            <div class="notif-dot" id="nd0"></div>
                        </div>
                        <div class="notif-item unread" onclick="readNotif(this)">
                            <div class="notif-icon" style="background:rgba(17,153,142,.12);color:#11998e;"><i
                                    class="bi bi-github"></i></div>
                            <div class="notif-body">
                                <p><strong>Hotel Booking</strong> repo got a new ⭐ on GitHub</p>
                                <span class="notif-time"><i class="bi bi-clock" style="font-size:10px;"></i> 22
                                    minutes ago</span>
                            </div>
                            <div class="notif-dot" id="nd1"></div>
                        </div>
                        <div class="notif-item unread" onclick="readNotif(this)">
                            <div class="notif-icon" style="background:rgba(249,83,198,.12);color:#f953c6;"><i
                                    class="bi bi-envelope-fill"></i></div>
                            <div class="notif-body">
                                <p>New message from <strong>momensarsour5@gmail.com</strong></p>
                                <span class="notif-time"><i class="bi bi-clock" style="font-size:10px;"></i> 1 hour
                                    ago</span>
                            </div>
                            <div class="notif-dot" id="nd2"></div>
                        </div>
                    </div>
                    <div class="notif-footer">
                        <a href="{{ route('dashboard.messages.index') }}">View all notifications →</a>
                    </div>
                </div>
            </div>

            {{-- ── Messages Button + Panel ── --}}
            <div style="position:relative;">
                <button class="tb-btn" id="msgBtn" onclick="togglePanel('msgPanel')" aria-label="Messages"
                    title="Messages">
                    <i class="bi bi-chat-dots-fill"></i>
                    <span class="badge-dot" style="background:#22c55e;"></span>
                </button>
                <div class="drop-panel msg-panel" id="msgPanel">
                    <div class="drop-header">
                        <h6>💬 Messages</h6>
                        <button class="drop-mark-all" onclick="showToast('✅','Messages','All marked as read')">Mark
                            read</button>
                    </div>
                    <div class="msg-search-wrap">
                        <input type="text" placeholder="Search messages…">
                    </div>
                    <div class="msg-list">
                        <div class="msg-item" onclick="goto('{{ route('dashboard.messages.index') }}')">
                            <div class="msg-av" style="background:linear-gradient(135deg,#667eea,#764ba2);">A<span
                                    class="online"></span></div>
                            <div class="msg-body">
                                <div class="msg-name">Ali Haffez</div>
                                <div class="msg-preview">Can we discuss the new project scope?</div>
                            </div>
                            <div class="msg-meta">
                                <span class="msg-time">5m</span>
                                <span class="msg-badge-sm">1</span>
                            </div>
                        </div>
                        <div class="msg-item" onclick="goto('{{ route('dashboard.messages.index') }}')">
                            <div class="msg-av" style="background:linear-gradient(135deg,#11998e,#38ef7d);">S</div>
                            <div class="msg-body">
                                <div class="msg-name">Sarah K.</div>
                                <div class="msg-preview">The website looks amazing, thank you!</div>
                            </div>
                            <div class="msg-meta">
                                <span class="msg-time">2h</span>
                            </div>
                        </div>
                        <div class="msg-item" onclick="goto('{{ route('dashboard.messages.index') }}')">
                            <div class="msg-av" style="background:linear-gradient(135deg,#f7971e,#ffd200);">M</div>
                            <div class="msg-body">
                                <div class="msg-name">Maximus Foundation</div>
                                <div class="msg-preview">Please send the updated data report</div>
                            </div>
                            <div class="msg-meta">
                                <span class="msg-time">1d</span>
                                <span class="msg-badge-sm">2</span>
                            </div>
                        </div>
                    </div>
                    <div class="notif-footer">
                        <a href="{{ route('dashboard.messages.index') }}">Open inbox →</a>
                    </div>
                </div>
            </div>

            {{-- ── Dark/Light Theme Toggle ── --}}
            <button class="tb-btn" onclick="toggleTheme()" title="Toggle dark mode" aria-label="Toggle theme">
                <i class="bi bi-moon-fill" id="themeIcon"></i>
            </button>

            <div class="topbar-divider"></div>

            {{-- ── User Avatar + Menu ── --}}
            <div style="position:relative;">
                <div class="topbar-avatar" id="userAvatarBtn" onclick="togglePanel('userMenuPanel')" role="button"
                    aria-haspopup="true" title="Account menu">
                    M
                    <span class="online-ring"></span>
                </div>
                <div class="drop-panel user-panel" id="userMenuPanel">
                    <div class="user-panel-header">
                        <div class="user-panel-av">M</div>
                        <div>
                            <div class="user-panel-name">Mo'men Sarsour</div>
                            <div class="user-panel-role">Full Stack Developer</div>
                            <div class="user-panel-email">momensarsour5@gmail.com</div>
                        </div>
                    </div>
                    <div class="user-menu-list">
                        <a href="{{ route('profile.edit') }}" class="umenu-item">
                            <i class="bi bi-person-fill" style="color:#667eea;"></i>
                            My Profile
                            <span class="umenu-tag" style="background:#f0f2ff;color:#667eea;">Edit</span>
                        </a>
                        <a href="{{ route('dashboard.settings') }}" class="umenu-item">
                            <i class="bi bi-gear-fill" style="color:#11998e;"></i>
                            Settings
                        </a>
                        <a href="{{ route('dashboard.analytics') }}" class="umenu-item">
                            <i class="bi bi-bar-chart-fill" style="color:#f7971e;"></i>
                            Analytics
                        </a>
                        <div class="umenu-divider"></div>
                        <a href="/" target="_blank" class="umenu-item">
                            <i class="bi bi-globe" style="color:#2f7bff;"></i>
                            View Portfolio
                            <i class="bi bi-box-arrow-up-right ms-auto" style="font-size:11px;color:#94a3b8;"></i>
                        </a>
                        <a href="https://github.com/Momen9Sarsour" target="_blank" class="umenu-item">
                            <i class="bi bi-github" style="color:#333;"></i>
                            GitHub
                            <i class="bi bi-box-arrow-up-right ms-auto" style="font-size:11px;color:#94a3b8;"></i>
                        </a>
                        <div class="umenu-divider"></div>
                        <button class="umenu-item danger" onclick="confirmLogout()">
                            <i class="bi bi-box-arrow-right"></i>
                            Sign Out
                        </button>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                            @csrf</form>
                    </div>
                </div>
            </div>

        </div>
    </header>

    {{-- ════════════════════════════════════════
     MAIN
════════════════════════════════════════ --}}
    <div id="main">
        <div class="content-area">

            @if (session('success'))
                <div class="alert-dash fade-up" style="background:#d1fae5;color:#065f46;">
                    <i class="bi bi-check-circle-fill" style="font-size:18px;color:#22c55e;"></i>
                    <div><strong>Success!</strong> {{ session('success') }}</div>
                    <button onclick="this.parentElement.remove()"
                        style="margin-left:auto;background:none;border:none;font-size:20px;cursor:pointer;color:#065f46;opacity:.5;line-height:1;">×</button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert-dash fade-up" style="background:#fee2e2;color:#991b1b;">
                    <i class="bi bi-x-circle-fill" style="font-size:18px;color:#ef4444;"></i>
                    <div><strong>Error!</strong> {{ session('error') }}</div>
                    <button onclick="this.parentElement.remove()"
                        style="margin-left:auto;background:none;border:none;font-size:20px;cursor:pointer;color:#991b1b;opacity:.5;line-height:1;">×</button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert-dash fade-up" style="background:#fef3c7;color:#92400e;">
                    <i class="bi bi-exclamation-triangle-fill" style="font-size:18px;color:#f59e0b;"></i>
                    <div><strong>Please fix:</strong>
                        <ul style="margin:4px 0 0 16px;font-size:13px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    {{-- ── LOGOUT CONFIRM MODAL ── --}}
    <div class="modal fade" id="logoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width:340px;">
            <div class="modal-content logout-modal-content">
                <div class="modal-body" style="padding:32px;text-align:center;">
                    <div
                        style="width:64px;height:64px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;font-size:28px;color:#ef4444;">
                        <i class="bi bi-box-arrow-right"></i>
                    </div>
                    <h5 style="font-weight:800;margin-bottom:8px;color:var(--text-primary);">Sign out?</h5>
                    <p style="color:var(--text-muted);font-size:14px;margin-bottom:24px;">You'll be redirected to the
                        login page.</p>
                    <div style="display:flex;gap:10px;">
                        <button class="btn btn-light w-50" style="border-radius:10px;font-weight:600;"
                            data-bs-dismiss="modal">Cancel</button>
                        <button class="w-50 btn-primary-dash"
                            style="justify-content:center;background:#ef4444;box-shadow:0 4px 14px rgba(239,68,68,.3);"
                            onclick="document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right"></i> Sign Out
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        /* ============================================================
       SIDEBAR
    ============================================================ */
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebar-overlay').classList.toggle('open');
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebar-overlay').classList.remove('open');
        }

        /* ============================================================
           DROPDOWN PANELS - toggle open/close
        ============================================================ */
        const panelIds = ['notifPanel', 'msgPanel', 'userMenuPanel'];

        function togglePanel(id) {
            const el = document.getElementById(id);
            const isOpen = el.classList.contains('open');
            panelIds.forEach(p => document.getElementById(p).classList.remove('open'));
            if (!isOpen) el.classList.add('open');
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('#notifBtn') && !e.target.closest('#notifPanel')) document.getElementById(
                'notifPanel').classList.remove('open');
            if (!e.target.closest('#msgBtn') && !e.target.closest('#msgPanel')) document.getElementById('msgPanel')
                .classList.remove('open');
            if (!e.target.closest('#userAvatarBtn') && !e.target.closest('#userMenuPanel')) document.getElementById(
                'userMenuPanel').classList.remove('open');
        });

        /* ============================================================
           NOTIFICATIONS
        ============================================================ */
        let unread = 3;

        function readNotif(el) {
            if (el.classList.contains('unread')) {
                el.classList.remove('unread');
                el.querySelector('.notif-dot').style.opacity = '0';
                unread = Math.max(0, unread - 1);
                syncBadge();
            }
        }

        function markAllRead() {
            document.querySelectorAll('.notif-item.unread').forEach(el => {
                el.classList.remove('unread');
                const d = el.querySelector('.notif-dot');
                if (d) d.style.opacity = '0';
            });
            unread = 0;
            syncBadge();
            showToast('✅', 'Notifications', 'All notifications marked as read');
        }

        function syncBadge() {
            const b = document.getElementById('notifBadge');
            const c = document.getElementById('notifAllCount');
            if (unread > 0) {
                b.textContent = unread;
                b.style.display = 'flex';
            } else b.style.display = 'none';
            if (c) c.textContent = unread;
        }

        function filterNotif(btn, type) {
            document.querySelectorAll('.notif-tab').forEach(t => t.classList.remove('active'));
            btn.classList.add('active');
            document.querySelectorAll('.notif-item').forEach(item => {
                if (type === 'all') item.style.display = '';
                else if (type === 'unread') item.style.display = item.classList.contains('unread') ? '' : 'none';
                else item.style.display = !item.classList.contains('unread') ? '' : 'none';
            });
        }

        /* ============================================================
           SEARCH
        ============================================================ */
        const searchPages = [{
                title: 'Dashboard',
                sub: 'Overview & statistics',
                icon: 'bi-grid-1x2-fill',
                color: '#2f7bff',
                bg: 'rgba(47,123,255,.1)',
                url: '{{ route('dashboard.index') }}'
            },
            {
                title: 'Projects',
                sub: 'Manage portfolio projects',
                icon: 'bi-folder2-open',
                color: '#11998e',
                bg: 'rgba(17,153,142,.1)',
                url: '{{ route('dashboard.projects.index') }}'
            },
            {
                title: 'Profile',
                sub: 'Edit your info',
                icon: 'bi-person-lines-fill',
                color: '#f953c6',
                bg: 'rgba(249,83,198,.1)',
                url: '{{ route('dashboard.profile') }}'
            },
            {
                title: 'Skills',
                sub: 'Skill levels & bars',
                icon: 'bi-lightning-charge-fill',
                color: '#f7971e',
                bg: 'rgba(247,151,30,.1)',
                url: '{{ route('dashboard.skills.index') }}'
            },
            {
                title: 'Resume',
                sub: 'Your CV & experience',
                icon: 'bi-file-earmark-person-fill',
                color: '#667eea',
                bg: 'rgba(102,126,234,.1)',
                url: '{{ route('dashboard.resume') }}'
            },
            {
                title: 'Messages',
                sub: 'Inbox & contacts',
                icon: 'bi-envelope-fill',
                color: '#ef4444',
                bg: 'rgba(239,68,68,.1)',
                url: '{{ route('dashboard.messages.index') }}'
            },
            {
                title: 'Analytics',
                sub: 'Charts & reports',
                icon: 'bi-bar-chart-fill',
                color: '#764ba2',
                bg: 'rgba(118,75,162,.1)',
                url: '{{ route('dashboard.analytics') }}'
            },
            {
                title: 'Clients',
                sub: 'Client management',
                icon: 'bi-people-fill',
                color: '#22c55e',
                bg: 'rgba(34,197,94,.1)',
                url: '{{ route('dashboard.clients') }}'
            },
            {
                title: 'Settings',
                sub: 'Account & preferences',
                icon: 'bi-gear-fill',
                color: '#0ea5e9',
                bg: 'rgba(14,165,233,.1)',
                url: '{{ route('dashboard.settings') }}'
            },
            {
                title: 'Edit Profile',
                sub: 'Breeze profile page',
                icon: 'bi-person-circle',
                color: '#8b5cf6',
                bg: 'rgba(139,92,246,.1)',
                url: '{{ route('profile.edit') }}'
            },
        ];

        function openSearch() {
            document.getElementById('searchOverlay').classList.add('open');
            setTimeout(() => document.getElementById('searchInput').focus(), 100);
        }

        function closeSearch() {
            document.getElementById('searchOverlay').classList.remove('open');
            document.getElementById('searchInput').value = '';
            liveSearch('');
        }

        function closeSearchOverlay(e) {
            if (e.target === document.getElementById('searchOverlay')) closeSearch();
        }

        function liveSearch(val) {
            const r = document.getElementById('searchResults');
            if (!val.trim()) {
                r.innerHTML = `
            <div class="s-label">Quick Links</div>
            ${searchPages.slice(0,4).map(p => `
                <div class="s-item" onclick="goto('${p.url}')">
                    <div class="s-icon" style="background:${p.bg};color:${p.color};"><i class="bi ${p.icon}"></i></div>
                    <div><p>${p.title}</p><small>${p.sub}</small></div>
                </div>`).join('')}`;
                return;
            }
            const found = searchPages.filter(p =>
                p.title.toLowerCase().includes(val.toLowerCase()) ||
                p.sub.toLowerCase().includes(val.toLowerCase())
            );
            if (!found.length) {
                r.innerHTML =
                    `<div style="padding:30px;text-align:center;color:#94a3b8;"><i class="bi bi-search" style="font-size:32px;display:block;margin-bottom:8px;opacity:.25;"></i>No results for "<strong>${val}</strong>"</div>`;
                return;
            }
            r.innerHTML = `<div class="s-label">Results (${found.length})</div>` +
                found.map(p => `
        <div class="s-item" onclick="goto('${p.url}')">
            <div class="s-icon" style="background:${p.bg};color:${p.color};"><i class="bi ${p.icon}"></i></div>
            <div><p>${p.title}</p><small>${p.sub}</small></div>
        </div>`).join('');
        }

        document.addEventListener('keydown', e => {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                openSearch();
            }
            if (e.key === 'Escape') {
                closeSearch();
                panelIds.forEach(p => document.getElementById(p).classList.remove('open'));
            }
        });

        /* ============================================================
           DARK / LIGHT THEME
        ============================================================ */
        let dark = false;

        function toggleTheme() {
            dark = !dark;
            const icon = document.getElementById('themeIcon');
            const root = document.documentElement;
            if (dark) {
                root.style.setProperty('--bg', '#0f172a');
                root.style.setProperty('--white', '#1e293b');
                root.style.setProperty('--border', '#334155');
                root.style.setProperty('--text-primary', '#f1f5f9');
                root.style.setProperty('--text-muted', '#94a3b8');
                document.body.style.background = '#0f172a';
                icon.className = 'bi bi-sun-fill';
                showToast('🌙', 'Dark Mode', 'Switched to dark theme');
            } else {
                root.style.setProperty('--bg', '#f0f2f8');
                root.style.setProperty('--white', '#ffffff');
                root.style.setProperty('--border', '#e2e8f3');
                root.style.setProperty('--text-primary', '#0f172a');
                root.style.setProperty('--text-muted', '#64748b');
                document.body.style.background = '#f0f2f8';
                icon.className = 'bi bi-moon-fill';
                showToast('☀️', 'Light Mode', 'Switched to light theme');
            }
        }

        /* ============================================================
           TOAST SYSTEM
        ============================================================ */
        function showToast(icon, title, message, type = 'default') {
            const c = document.getElementById('toast-container');
            const t = document.createElement('div');
            t.className = 'toast-item';
            const colors = {
                success: '#22c55e',
                error: '#ef4444',
                warning: '#f59e0b',
                default: 'var(--sidebar-active)'
            };
            t.style.setProperty('--toast-color', colors[type] || colors.default);
            t.style.cssText += `--toast-color:${colors[type]||colors.default};`;
            t.innerHTML = `
        <span class="toast-ico">${icon}</span>
        <div class="toast-txt"><p>${title}</p><small>${message}</small></div>
        <button class="toast-x" onclick="killToast(this.parentElement)">×</button>`;
            // override the bottom bar color
            t.querySelector('button').insertAdjacentHTML('afterend',
                `<style>.toast-item:last-child::after{background:${colors[type]||colors.default}}</style>`);
            c.appendChild(t);
            requestAnimationFrame(() => requestAnimationFrame(() => t.classList.add('show')));
            setTimeout(() => killToast(t), 4500);
        }

        function killToast(el) {
            el.classList.remove('show');
            setTimeout(() => el?.remove(), 400);
        }

        /* ============================================================
           LOGOUT CONFIRM
        ============================================================ */
        function confirmLogout() {
            panelIds.forEach(p => document.getElementById(p).classList.remove('open'));
            new bootstrap.Modal(document.getElementById('logoutModal')).show();
        }

        /* ============================================================
           HELPER
        ============================================================ */
        function goto(url) {
            window.location.href = url;
        }
    </script>

    @stack('scripts')
</body>

</html>
