<?php
declare(strict_types=1);

/**
 * index.php — точка монтирования Vue 3 SPA.
 *
 * Отвечает за:
 *  - передачу серверных переменных в окно браузера (__APP_CONFIG__)
 *  - подключение стилей и ES-модульного скрипта
 *  - рендеринг <div id="app"> для монтирования Vue
 */

// ── Серверный конфиг, который будет доступен во Vue через window.__APP_CONFIG__
$appConfig = [
        'apiBase' => '/api',
        'locale'  => 'ru-RU',
        'version' => '1.0.0',
];
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($appConfig['locale']) ?>">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MediaVault — Photos, Videos &amp; Notes</title>
    <meta name="description" content="Your personal media hub — photos, videos, and notes in one place." />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Mono:wght@400;500&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet" />

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #0d0e11;
            --surface:   #15171c;
            --surface-2: #1c1f27;
            --border:    rgba(255,255,255,.07);
            --accent:    #c8f135;
            --accent-2:  #5e9fff;
            --accent-3:  #ff6b6b;
            --text:      #e8eaf0;
            --muted:     #6b7280;
            --radius:    14px;
            --font-disp: 'DM Serif Display', serif;
            --font-body: 'DM Sans', sans-serif;
            --font-mono: 'DM Mono', monospace;
        }

        html { scroll-behavior: smooth; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: var(--font-body);
            font-size: 15px;
            line-height: 1.6;
            min-height: 100vh;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 0;
        }

        /* ── Header ── */
        .header {
            position: sticky; top: 0; z-index: 100;
            background: rgba(13,14,17,.82);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            padding: 0 32px;
            display: flex; align-items: center; gap: 24px;
            height: 64px;
        }
        .logo { font-family: var(--font-disp); font-size: 22px; letter-spacing: -.5px; color: var(--accent); flex-shrink: 0; }
        .header-sep { flex: 1; }
        .filter-tabs { display: flex; gap: 4px; background: var(--surface); padding: 4px; border-radius: 10px; border: 1px solid var(--border); }
        .filter-tab { padding: 6px 18px; border-radius: 7px; cursor: pointer; font-size: 13px; font-weight: 500; color: var(--muted); transition: all .2s; border: none; background: transparent; letter-spacing: .3px; font-family: var(--font-body); }
        .filter-tab:hover { color: var(--text); }
        .filter-tab.active { background: var(--surface-2); color: var(--accent); box-shadow: 0 1px 4px rgba(0,0,0,.4); }

        /* ── Hero ── */
        .hero { padding: 72px 32px 48px; max-width: 1200px; margin: 0 auto; }
        .hero-eyebrow { font-family: var(--font-mono); font-size: 11px; text-transform: uppercase; letter-spacing: 3px; color: var(--muted); margin-bottom: 16px; }
        .hero-title { font-family: var(--font-disp); font-size: clamp(40px, 6vw, 72px); line-height: 1.1; letter-spacing: -1.5px; margin-bottom: 12px; }
        .hero-title em { font-style: italic; color: var(--accent); }
        .hero-sub { color: var(--muted); font-size: 16px; max-width: 520px; }

        /* ── Stats ── */
        .stats-bar { max-width: 1200px; margin: 0 auto 48px; padding: 0 32px; display: flex; gap: 32px; }
        .stat { display: flex; align-items: center; gap: 10px; }
        .stat-icon { width: 36px; height: 36px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 16px; }
        .stat-icon.photo { background: rgba(200,241,53,.12); }
        .stat-icon.video { background: rgba(94,159,255,.12); }
        .stat-icon.note  { background: rgba(255,107,107,.12); }
        .stat-val { font-family: var(--font-mono); font-size: 22px; font-weight: 500; line-height: 1; }
        .stat-lbl { font-size: 12px; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; }

        /* ── Grid ── */
        .main { max-width: 1200px; margin: 0 auto; padding: 0 32px 80px; }
        .masonry { columns: 3; column-gap: 20px; }
        @media (max-width: 900px) { .masonry { columns: 2; } }
        @media (max-width: 540px) { .masonry { columns: 1; } }

        /* ── Cards ── */
        .card { break-inside: avoid; display: block; background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden; margin-bottom: 20px; transition: transform .25s, box-shadow .25s, border-color .25s; cursor: pointer; position: relative; }
        .card:hover { transform: translateY(-4px); box-shadow: 0 24px 60px rgba(0,0,0,.5); border-color: rgba(255,255,255,.15); }
        .card-media { width: 100%; aspect-ratio: 16/10; object-fit: cover; display: block; background: var(--surface-2); }
        .card-media-placeholder { width: 100%; aspect-ratio: 16/10; display: flex; align-items: center; justify-content: center; font-size: 48px; background: var(--surface-2); }
        .card-badge { position: absolute; top: 12px; left: 12px; padding: 3px 10px; border-radius: 6px; font-size: 10px; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase; font-family: var(--font-mono); }
        .badge-photo { background: rgba(200,241,53,.9);  color: #0d0e11; }
        .badge-video { background: rgba(94,159,255,.9);  color: #0d0e11; }
        .badge-note  { background: rgba(255,107,107,.85); color: #0d0e11; }
        .card-body { padding: 18px 20px 20px; }
        .note-card .card-body { padding: 22px; }
        .card-title { font-family: var(--font-disp); font-size: 19px; line-height: 1.2; margin-bottom: 8px; letter-spacing: -.3px; }
        .card-text { font-size: 13.5px; color: var(--muted); line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        .card-meta { margin-top: 14px; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .meta-author { font-size: 12px; color: var(--muted); display: flex; align-items: center; gap: 5px; }
        .meta-author::before { content: ''; display: inline-block; width: 20px; height: 20px; border-radius: 50%; background: linear-gradient(135deg, var(--accent), var(--accent-2)); flex-shrink: 0; }
        .meta-sep { flex: 1; }
        .meta-date { font-size: 11px; color: var(--muted); font-family: var(--font-mono); }
        .card-tags { margin-top: 10px; display: flex; gap: 6px; flex-wrap: wrap; }
        .tag { padding: 2px 8px; background: var(--surface-2); border: 1px solid var(--border); border-radius: 5px; font-size: 11px; color: var(--muted); font-family: var(--font-mono); }
        .duration-pill { position: absolute; bottom: 10px; right: 10px; background: rgba(0,0,0,.7); backdrop-filter: blur(4px); padding: 2px 8px; border-radius: 6px; font-size: 12px; font-family: var(--font-mono); font-weight: 500; }
        .note-color-bar { height: 4px; width: 100%; }
        .pin-icon { position: absolute; top: 14px; right: 14px; font-size: 16px; }

        /* ── States ── */
        .state-loading, .state-empty, .state-error { text-align: center; padding: 80px 20px; color: var(--muted); }
        .spinner { width: 40px; height: 40px; border: 2px solid var(--border); border-top-color: var(--accent); border-radius: 50%; animation: spin .8s linear infinite; margin: 0 auto 20px; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .load-more { display: block; margin: 40px auto 0; padding: 14px 40px; background: transparent; border: 1px solid var(--border); border-radius: 10px; color: var(--text); font-size: 14px; font-family: var(--font-body); cursor: pointer; transition: all .2s; letter-spacing: .5px; }
        .load-more:hover { background: var(--surface); border-color: rgba(255,255,255,.2); transform: translateY(-1px); }

        /* ── Transitions ── */
        .list-enter-active { transition: opacity .4s, transform .4s; }
        .list-enter-from   { opacity: 0; transform: translateY(16px); }

        /* ── Preloader (пока Vue не смонтирован) ── */
        #preloader { display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        #app:not(:empty) + #preloader,
        #app[data-v-app] ~ #preloader { display: none; }
    </style>
</head>
<body>

<!-- ── Vue mount point ── -->
<div id="app"></div>

<!-- ── Fallback preloader до монтирования Vue ── -->
<div id="preloader">
    <div class="spinner"></div>
</div>

<!-- ── Серверный конфиг → window.__APP_CONFIG__ ── -->
<script>
    window.__APP_CONFIG__ = <?= json_encode($appConfig, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
</script>

<!-- ── Vue 3 ES-модуль ── -->
<script type="module" src="/js/app.js"></script>

</body>
</html>