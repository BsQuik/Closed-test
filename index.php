<?php
require_once __DIR__ . '/auth.php';

if (!isAuthenticated()):
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Accesso protetto — BS Lookup</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;800&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
  :root {
    --bg: #eff6ff; --surface: #ffffff; --surface-2: #f2f7fe;
    --gold: #ffc629; --gold-dark: #e0a400;
    --magenta: #ff4d6d; --text: #1e2a4a; --text-muted: #66759b; --border: #cfe0f7;
  }
  * { box-sizing: border-box; }
  body {
    margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center;
    background: var(--bg); color: var(--text); font-family: 'Nunito', system-ui, sans-serif; padding: 20px;
  }
  .login-card {
    width: min(340px, 100%);
    background: var(--surface);
    border: 3px solid var(--text); border-radius: 22px;
    padding: 34px 28px; text-align: center;
    box-shadow: 6px 6px 0 var(--text);
  }
  .login-icon {
    width: 58px; height: 58px; border-radius: 50%; margin: 0 auto 14px;
    display: flex; align-items: center; justify-content: center; font-size: 26px;
    background: radial-gradient(circle at 30% 30%, #ffe9a8, var(--gold-dark));
    border: 2.5px solid var(--text);
  }
  .login-card h2 {
    font-family: 'Baloo 2', sans-serif; font-size: 19px; margin: 0 0 4px; color: var(--text);
  }
  .login-card p { color: var(--text-muted); font-size: 12.5px; margin: 0 0 22px; }
  .error-msg {
    background: rgba(255,77,109,0.12); border: 2px solid var(--magenta); color: #c81e44;
    padding: 8px 12px; border-radius: 10px; font-size: 12.5px; margin-bottom: 14px; text-align: left;
    font-weight: 600;
  }
  .input-field {
    width: 100%; background: var(--surface-2); border: 2.5px solid var(--text);
    color: var(--text); padding: 12px 14px; border-radius: 14px;
    font-size: 15px; text-align: center; font-weight: 700; margin-bottom: 14px;
  }
  .input-field:focus { outline: none; border-color: #2f8fff; box-shadow: 0 0 0 3px rgba(47,143,255,0.25); }
  .btn-submit {
    width: 100%; border: 2px solid var(--text); border-radius: 999px; padding: 12px;
    font-family: 'Nunito', sans-serif; font-weight: 800; font-size: 14px;
    cursor: pointer; background: var(--gold); color: var(--text);
    box-shadow: 3px 3px 0 var(--text); transition: transform .08s ease, box-shadow .08s ease;
  }
  .btn-submit:active { transform: translate(3px, 3px); box-shadow: 0 0 0 var(--text); }
</style>
</head>
<body>

<div class="login-card">
  <div class="login-icon">🔒</div>
  <h2>🥊 BS Lookup</h2>
  <p>Accesso protetto — inserisci la password per continuare</p>

  <?php if (!empty($login_error)): ?>
    <div class="error-msg"><?= htmlspecialchars($login_error) ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <input type="hidden" name="action" value="login">
    <input type="password" name="password" class="input-field" placeholder="Password" required autofocus>
    <button type="submit" class="btn-submit">Sblocca</button>
  </form>
</div>

</body>
</html>
<?php
exit;
endif;
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Brawl Stars — Player Lookup</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;800&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
  :root {
    --bg: #eff6ff;
    --surface: #ffffff;
    --surface-2: #f2f7fe;
    --surface-3: #e3edfc;
    --gold: #ffc629;
    --gold-dark: #e0a400;
    --amber: #a9700a;
    --blue: #2f8fff;
    --magenta: #ff4d6d;
    --magenta-dark: #c81e44;
    --green: #17a34a;
    --text: #1e2a4a;
    --text-muted: #66759b;
    --border: #cfe0f7;
  }

  * { box-sizing: border-box; }

  body {
    font-family: 'Nunito', system-ui, sans-serif;
    background: var(--bg);
    color: var(--text);
    margin: 0;
    padding: 0 0 40px;
  }

  /* ---------- Barra superiore: logo + menu pagine ---------- */
  .topbar {
    position: sticky; top: 0; z-index: 20;
    background: var(--gold);
    border-bottom: 3px solid var(--text);
    padding: 10px 16px;
    display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
  }
  .topbar h1 {
    font-family: 'Baloo 2', sans-serif;
    font-weight: 800;
    font-size: 18px;
    color: var(--text);
    margin: 0;
    white-space: nowrap;
  }
  .page-switcher {
    margin-left: auto;
    background: var(--surface); color: var(--text);
    border: 2px solid var(--text); border-radius: 999px;
    padding: 8px 14px; font-size: 13px; font-weight: 700;
    font-family: 'Nunito', sans-serif; cursor: pointer;
  }

  .btn {
    border: 2px solid var(--text); border-radius: 999px; padding: 9px 16px;
    font-family: 'Nunito', sans-serif; font-weight: 800; font-size: 13px;
    cursor: pointer; white-space: nowrap;
    box-shadow: 3px 3px 0 var(--text);
    transition: transform .08s ease, box-shadow .08s ease;
  }
  .btn:active { transform: translate(3px, 3px); box-shadow: 0 0 0 var(--text); }
  .btn-primary { background: var(--gold); color: var(--text); }
  .btn-secondary { background: var(--surface); color: var(--text); }

  .content { max-width: 960px; margin: 0 auto; padding: 16px; }
  .error {
    background: rgba(255,77,109,0.12); border: 2px solid var(--magenta);
    color: var(--magenta-dark); padding: 10px 14px; border-radius: 10px; font-size: 13px; font-weight: 600;
  }
  .loading { color: var(--text-muted); font-size: 13px; padding: 8px 0; }

  /* ---------- Hero di ricerca (pagina Home) ---------- */
  .hero-search {
    background: linear-gradient(160deg, #ffffff 0%, #dcebff 100%);
    border: 3px solid var(--text); border-radius: 22px;
    padding: 32px 24px; text-align: center; margin-bottom: 18px;
    box-shadow: 5px 5px 0 var(--text);
  }
  .hero-search h2 {
    font-family: 'Baloo 2', sans-serif; font-size: 24px; margin: 0 0 6px; color: var(--text);
  }
  .hero-search p { color: var(--text-muted); font-size: 13px; margin: 0 0 18px; }
  .hero-search-row {
    display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;
  }
  .hero-search-row input {
    background: var(--surface); border: 2.5px solid var(--text);
    color: var(--text); padding: 12px 16px; border-radius: 14px;
    font-size: 15px; width: 220px; text-align: center; font-weight: 700;
  }
  .hero-search-row input:focus { outline: none; border-color: var(--blue); box-shadow: 0 0 0 3px rgba(47,143,255,0.25); }
  .hero-search-row .btn { padding: 12px 22px; font-size: 14px; }

  /* ---------- Persone salvate (roster) ---------- */
  .saved-players-bar {
    display: flex; flex-direction: column; gap: 8px;
    padding: 4px 0 12px;
  }
  .saved-player-chip {
    display: flex; align-items: center; gap: 12px;
    background: var(--surface); border: 2px solid var(--text);
    border-radius: 16px; padding: 10px 12px;
    cursor: pointer; position: relative;
    box-shadow: 3px 3px 0 var(--text);
    transition: box-shadow .1s ease, transform .1s ease, background .12s ease;
  }
  .saved-player-chip:hover { transform: translate(-1px, -1px); box-shadow: 4px 4px 0 var(--text); background: var(--surface-2); }
  .saved-player-chip:active { transform: translate(2px, 2px); box-shadow: 1px 1px 0 var(--text); }
  .saved-player-chip .avatar-sm {
    width: 52px; height: 52px; border-radius: 13px;
    background: var(--surface-2); border: 2.5px solid var(--gold);
    flex-shrink: 0; object-fit: cover;
  }
  .saved-player-chip .info { min-width: 0; flex: 1; }
  .saved-player-chip .kicker {
    font-size: 9px; font-weight: 800; letter-spacing: .4px; text-transform: uppercase;
    color: var(--blue); margin-bottom: 2px;
  }
  .saved-player-chip .name-row { display: flex; align-items: baseline; gap: 6px; min-width: 0; }
  .saved-player-chip .name {
    font-family: 'Baloo 2', sans-serif; font-weight: 700; font-size: 14.5px; color: var(--text);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px;
  }
  .saved-player-chip .tagline { font-size: 10.5px; color: var(--text-muted); font-family: monospace; flex-shrink: 0; }
  .saved-player-chip .club-line {
    font-size: 11px; color: var(--text-muted); margin-top: 2px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
  }
  .saved-player-chip .trophy-badge {
    display: flex; align-items: center; gap: 4px; flex-shrink: 0;
    background: var(--gold); border: 1.5px solid var(--text); border-radius: 999px;
    padding: 4px 10px; font-size: 12px; font-weight: 800; color: var(--text);
  }
  .saved-player-chip.is-loading .avatar-sm,
  .saved-player-chip.is-loading .name,
  .saved-player-chip.is-loading .club-line {
    background: linear-gradient(90deg, var(--surface-2) 25%, var(--surface-3) 50%, var(--surface-2) 75%);
    background-size: 200% 100%; animation: shimmer 1.2s infinite;
  }
  .saved-player-chip.is-loading .name,
  .saved-player-chip.is-loading .club-line { display: inline-block; height: 10px; border-radius: 4px; color: transparent; }
  .saved-player-chip.is-loading .name { width: 100px; margin-bottom: 5px; }
  .saved-player-chip.is-loading .club-line { width: 70px; }
  .saved-player-chip.is-error { border-color: var(--magenta); opacity: 0.8; }
  @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

  /* ---------- Riepilogo giocatore ---------- */
  .player-strip {
    display: flex; align-items: center; gap: 14px;
    background: var(--surface); border: 2.5px solid var(--text);
    border-radius: 18px; padding: 12px 16px; margin-bottom: 16px;
    box-shadow: 4px 4px 0 var(--text);
    flex-wrap: wrap;
  }
  .player-strip img.avatar {
    width: 52px; height: 52px; border-radius: 50%;
    background: var(--surface-2); border: 3px solid var(--gold); flex-shrink: 0;
  }
  .player-name-block { min-width: 120px; }
  .player-name-block h2 {
    font-family: 'Baloo 2', sans-serif; font-size: 17px; margin: 0; color: var(--text);
  }
  .player-name-block .tag { font-size: 11px; color: var(--text-muted); font-family: monospace; }
  .stat-pills { display: flex; gap: 8px; flex-wrap: wrap; margin-left: auto; }
  .pill {
    display: flex; align-items: center; gap: 5px;
    background: var(--surface-2); border: 1.5px solid var(--border); border-radius: 999px;
    padding: 5px 11px; font-size: 12px; font-weight: 700; color: var(--text);
  }
  .pill .n { color: var(--amber); font-weight: 700; }
  .pill.highlight { background: var(--gold); border-color: var(--text); }
  .pill.highlight .n { color: var(--text); }
  .player-level-badge {
    display: inline-flex; align-items: center; justify-content: center;
    width: 20px; height: 20px; border-radius: 50%;
    background: radial-gradient(circle at 30% 30%, #eaf3ff, var(--blue));
    color: #fff; font-weight: 800; font-size: 10px;
    border: 2px solid var(--surface); margin-left: 3px;
  }

  .section-label {
    font-family: 'Baloo 2', sans-serif; font-size: 13.5px; font-weight: 700;
    color: var(--text); letter-spacing: normal;
    margin: 18px 0 8px; display: flex; align-items: center; gap: 8px;
  }
  .section-label.toggle {
    cursor: pointer; user-select: none; background: var(--surface);
    border: 2px solid var(--text); border-radius: 14px; padding: 10px 12px;
    margin: 10px 0; font-size: 14px; color: var(--text);
    box-shadow: 2px 2px 0 var(--text);
  }
  .section-label.toggle:hover { background: var(--surface-2); }
  .section-label .chev { transition: transform .15s ease; font-size: 10px; color: var(--text-muted); }
  .section-label.is-open .chev { transform: rotate(90deg); }
  .win-rate-badge {
    margin-left: auto; font-size: 11px; font-weight: 700;
    background: var(--gold); padding: 3px 10px; border-radius: 999px;
    color: var(--text); border: 1.5px solid var(--text);
  }
  .collapsible-content {
    display: grid; grid-template-rows: 0fr; opacity: 0;
    transition: grid-template-rows .25s ease, opacity .2s ease;
  }
  .collapsible-content > div { overflow: hidden; }
  .collapsible-content.is-open { grid-template-rows: 1fr; opacity: 1; }
  .brawler-card {
    transition: transform .12s ease, border-color .12s ease;
  }
  .brawler-card:hover { transform: translateY(-3px); border-color: var(--blue); }
  .top-brawler-crown {
    position: absolute; top: -8px; left: -6px; font-size: 15px;
    filter: drop-shadow(0 1px 2px rgba(30,42,74,.35));
  }
  .copy-tag-btn {
    background: none; border: none; color: var(--text-muted);
    cursor: pointer; font-size: 12px; padding: 0; margin-left: 4px;
    vertical-align: middle;
  }
  .copy-tag-btn:hover { color: var(--blue); }
  .club-chip {
    display: inline-flex; align-items: center; gap: 5px;
    background: var(--surface-2); border: 1.5px solid var(--border); border-radius: 10px; padding: 4px 10px;
    font-size: 11px; color: var(--text); margin-top: 6px;
    cursor: pointer; transition: background .12s ease, color .12s ease, border-color .12s ease;
  }
  .club-chip:hover { background: var(--blue); color: #fff; border-color: var(--blue); }
  .sort-row { display: flex; align-items: center; gap: 8px; margin: 4px 0 8px; font-size: 12px; color: var(--text-muted); }
  .sort-row select {
    background: var(--surface); color: var(--text); border: 1.5px solid var(--text);
    border-radius: 8px; padding: 4px 8px; font-size: 12px;
  }
  .brawler-detail-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 8px; }
  .brawler-card { background: var(--surface); border: 2px solid var(--text); border-radius: 14px; padding: 8px; position: relative; box-shadow: 2px 2px 0 var(--text); }
  .brawler-card-head { display: flex; align-items: center; gap: 8px; }
  .brawler-avatar-wrap { position: relative; flex-shrink: 0; }
  .brawler-avatar-wrap img { width: 40px; height: 40px; border-radius: 9px; background: var(--surface-2); display: block; }
  .power-level-badge {
    position: absolute; bottom: -4px; right: -4px;
    display: flex; align-items: center; justify-content: center;
    width: 18px; height: 18px; border-radius: 50%;
    background: radial-gradient(circle at 30% 30%, #ffe9a8, var(--gold-dark));
    color: var(--text); font-weight: 800; font-size: 10px;
    border: 2px solid var(--surface);
  }
  .brawler-name-col { min-width: 0; }
  .brawler-name-col .name { font-size: 12.5px; font-weight: 700; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .brawler-name-col .trophy-line { font-size: 11px; color: var(--amber); font-weight: 600; display: flex; align-items: center; gap: 3px; }

  .rank-badge {
    display: inline-flex; align-items: center; gap: 3px;
    font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 999px;
    border: 1.5px solid rgba(30,42,74,0.15);
  }
  .rank-bronze   { background: #6b3e26; color: #ffd9b3; }
  .rank-silver   { background: #5a5f6b; color: #eef1f5; }
  .rank-gold     { background: #7a5c12; color: #ffe28a; }
  .rank-diamond  { background: #1e5f6b; color: #b6f2ff; }
  .rank-mythic   { background: #6b1e4f; color: #ffb3e0; }
  .rank-legendary{ background: #4a1e6b; color: #dcb3ff; }
  .rank-masters  { background: linear-gradient(90deg,#ff4d8d,#ffcd3c,#4fd6ff); color: #1a1a1a; }

  .item-icon-row { display: flex; gap: 3px; flex-wrap: wrap; margin-top: 6px; }
  .item-icon { width: 22px; height: 22px; border-radius: 5px; background: var(--surface-2); border: 1.5px solid var(--border); }
  .item-icon-fallback {
    display: inline-flex; align-items: center; justify-content: center;
    height: 22px; padding: 0 6px; border-radius: 5px;
    background: var(--surface-3); border: 1.5px dashed var(--text-muted);
    font-size: 9px; color: var(--text); text-align: center; font-weight: 600;
  }

  /* ---------- Griglia "tutti i brawler" ---------- */
  .brawler-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(76px, 1fr)); gap: 8px; }
  .brawler-item {
    text-align: center; font-size: 10.5px; color: var(--text-muted);
    background: var(--surface); border: 1.5px solid var(--border);
    border-radius: 10px; padding: 6px 4px;
  }
  .brawler-item img { width: 44px; height: 44px; border-radius: 9px; background: var(--surface-2); }
  .brawler-item div { margin-top: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

  /* ---------- Battaglie recenti ---------- */
  .battle-list { display: flex; flex-direction: column; gap: 6px; }
  .battle-row {
    display: flex; align-items: center; gap: 10px;
    background: var(--surface); border: 1.5px solid var(--border);
    border-radius: 12px; padding: 8px 12px; font-size: 12.5px;
  }
  .battle-row.is-ranked { border-color: var(--gold); background: linear-gradient(90deg, rgba(255,198,41,0.15), var(--surface)); }
  .battle-result-bar { width: 4px; height: 30px; border-radius: 3px; flex-shrink: 0; }
  .battle-result-bar.victory { background: var(--green); }
  .battle-result-bar.defeat { background: var(--magenta); }
  .battle-result-bar.draw, .battle-result-bar.unknown { background: var(--text-muted); }
  .battle-main { flex: 1; min-width: 0; }
  .battle-mode { font-weight: 700; color: var(--text); }
  .battle-map { color: var(--text-muted); font-size: 11px; }
  .battle-ranked-tag {
    font-size: 9px; font-weight: 800; background: var(--gold); color: var(--text);
    padding: 1px 6px; border-radius: 999px; margin-left: 6px; vertical-align: middle;
  }
  .battle-side { text-align: right; flex-shrink: 0; }
  .battle-trophy { font-weight: 700; }
  .battle-trophy.pos { color: var(--green); }
  .battle-trophy.neg { color: var(--magenta-dark); }
  .battle-time { font-size: 10.5px; color: var(--text-muted); }

  .ranked-summary {
    background: linear-gradient(135deg, rgba(255,198,41,0.18), var(--surface));
    border: 2px solid var(--gold); border-radius: 12px;
    padding: 10px 14px; margin-bottom: 10px; font-size: 12.5px;
  }
  .ranked-summary .win { color: var(--green); font-weight: 700; }
  .ranked-summary .loss { color: var(--magenta-dark); font-weight: 700; }
  .ranked-summary-empty {
    background: var(--surface); border-color: var(--border); color: var(--text-muted);
  }

  .page { display: none; }
  .page.is-active { display: block; }

  /* ---------- Pagina Club ---------- */
  .club-header {
    background: var(--surface); border: 2.5px solid var(--text); border-radius: 16px;
    padding: 16px; margin-bottom: 14px; box-shadow: 4px 4px 0 var(--text);
  }
  .club-header h2 { font-family: 'Baloo 2', sans-serif; font-size: 19px; margin: 0 0 4px; color: var(--text); }
  .club-header .desc { font-size: 12.5px; color: var(--text-muted); margin: 6px 0 10px; }
  .club-header .stat-pills { margin-left: 0; }
  .member-list { display: flex; flex-direction: column; gap: 6px; }
  .member-row {
    display: flex; align-items: center; gap: 10px;
    background: var(--surface); border: 1.5px solid var(--border);
    border-radius: 12px; padding: 8px 12px; cursor: pointer;
    transition: border-color .12s ease, transform .1s ease;
  }
  .member-row:hover { border-color: var(--blue); transform: translateX(3px); }
  .member-role {
    font-size: 9px; font-weight: 800; text-transform: uppercase;
    padding: 2px 7px; border-radius: 999px; background: var(--surface-2); color: var(--text-muted);
  }
  .member-role.president { background: var(--gold); color: var(--text); }
  .member-role.vicePresident { background: var(--blue); color: #fff; }
  .member-role.senior { background: var(--surface-3); color: var(--text); }
  .member-name { font-weight: 600; font-size: 13px; flex: 1; min-width: 0; color: var(--text); }
  .member-trophies { font-size: 12px; color: var(--amber); font-weight: 700; }

  /* ---------- Ultimi cercati (localStorage) ---------- */
  .chip-remove-btn {
    position: absolute; top: -8px; right: -8px;
    width: 22px; height: 22px; display: flex; align-items: center; justify-content: center;
    background: var(--surface); border: 2px solid var(--text); border-radius: 50%;
    color: var(--text-muted); font-size: 11px; cursor: pointer; line-height: 1;
    box-shadow: 2px 2px 0 var(--text);
  }
  .chip-remove-btn:hover { color: var(--magenta-dark); }

  /* ---------- Ricerca per nome / autocomplete ---------- */
  .search-input-wrap { position: relative; width: 220px; }
  .search-input-wrap input { width: 100%; }
  .suggestions-box {
    position: absolute; top: calc(100% + 6px); left: 0; right: 0;
    background: var(--surface); border: 2px solid var(--text); border-radius: 14px;
    z-index: 30; text-align: left; display: none; max-height: 260px; overflow-y: auto;
    box-shadow: 4px 4px 0 var(--text);
  }
  .suggestions-box.is-open { display: block; }
  .suggestion-item {
    display: flex; align-items: center; gap: 10px; padding: 8px 12px; cursor: pointer;
  }
  .suggestion-item:hover, .suggestion-item.is-active { background: var(--surface-2); }
  .suggestion-item img { width: 26px; height: 26px; border-radius: 50%; background: var(--surface-2); flex-shrink: 0; }
  .suggestion-item .s-name { font-size: 12.5px; font-weight: 600; color: var(--text); }
  .suggestion-item .s-tag { font-size: 10.5px; color: var(--text-muted); font-family: monospace; }
  .suggestion-empty { padding: 10px 12px; font-size: 12px; color: var(--text-muted); }
</style>
</head>
<body>

<div class="topbar">
  <h1>🥊 BS Lookup</h1>
  <select class="page-switcher" id="pageSwitcher" onchange="cambiaPagina(this.value)">
    <option value="home">🔍 Cerca Giocatore</option>
    <option value="club">🛡️ Cerca Club</option>
    <option value="brawlers">📋 Tutti i Brawler</option>
  </select>
  <a href="?logout=1" class="btn btn-secondary" style="padding:8px 12px; font-size:12px; text-decoration:none; display:inline-flex; align-items:center;" title="Esci dall'area protetta">🔒 Esci</a>
</div>

<div class="content">

  <!-- ============== PAGINA: HOME / CERCA GIOCATORE ============== -->
  <div class="page is-active" id="page-home">

    <div class="hero-search">
      <h2>Cerca un giocatore</h2>
      <p>Inserisci il player tag per vedere trofei, brawler, gear e battaglie recenti</p>
      <div class="hero-search-row">
        <div class="search-input-wrap">
          <input type="text" id="playerTag" placeholder="#2PP oppure nome giocatore" autocomplete="off"
                 oninput="gestisciInputRicerca(this.value)"
                 onkeydown="gestisciTastoRicerca(event)"
                 onfocus="gestisciInputRicerca(this.value)">
          <div class="suggestions-box" id="suggestionsBox"></div>
        </div>
        <button class="btn btn-primary" onclick="cercaGiocatore()">Cerca</button>
      </div>
    </div>

    <!--
      ============================================================
      PERSONE SALVATE — per aggiungere qualcuno, COPIA una riga
      <div class="saved-player-chip" data-tag="#TAG"></div> qui sotto
      e cambia solo il tag. Foto profilo, nome e trofei si caricano
      da soli: non serve scrivere altro.
      ============================================================
    -->
    <div class="saved-players-bar" id="savedPlayers">
      <div class="saved-player-chip" data-tag="#7404704"></div>
      <div class="saved-player-chip" data-tag="#2PP"></div>
      <!-- Aggiungi qui altre righe come queste due -->
    </div>

    <!--
      ============================================================
      ULTIMI CERCATI — generata automaticamente dal localStorage,
      non serve modificare nulla qui a mano.
      ============================================================
    -->
    <div class="section-label" id="recentSearchesLabel" style="display:none;">
      <span>⏱️ Ultimi cercati</span>
      <button class="btn btn-secondary" style="margin-left:auto; padding:4px 10px; font-size:11px;" onclick="cancellaRicercheRecenti()">Cancella tutto</button>
    </div>
    <div class="saved-players-bar" id="recentSearches"></div>

    <div id="result"></div>
  </div>

  <!-- ============== PAGINA: CERCA CLUB ============== -->
  <div class="page" id="page-club">
    <div class="hero-search">
      <h2>Cerca un club</h2>
      <p>Inserisci il tag del club per vedere trofei richiesti e tutti i membri</p>
      <div class="hero-search-row">
        <input type="text" id="clubTag" placeholder="#2RJLGLJ" onkeydown="if(event.key==='Enter') cercaClub()">
        <button class="btn btn-primary" onclick="cercaClub()">Cerca</button>
      </div>
    </div>
    <div id="clubResult"></div>
  </div>

  <!-- ============== PAGINA: TUTTI I BRAWLER ============== -->
  <div class="page" id="page-brawlers">
    <div id="brawlersResult"></div>
  </div>

</div>

<script>
// ============================================================
// NAVIGAZIONE PAGINE
// ============================================================
function cambiaPagina(pagina) {
  document.querySelectorAll('.page').forEach(p => p.classList.remove('is-active'));
  document.getElementById('page-' + pagina).classList.add('is-active');
  if (pagina === 'brawlers' && !document.getElementById('brawlersResult').dataset.loaded) {
    mostraBrawler();
  }
}

// ============================================================
// UTILITY ICONE (con fallback a catena, senza bug di escaping)
// ============================================================
function escapeAttr(str) {
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;')
    .replace(/</g, '&lt;');
}

function iconWithFallbackChain(urls, name) {
  const safeName = escapeAttr(name);
  const attrChain = JSON.stringify(urls).replace(/"/g, '&quot;').replace(/'/g, '&#39;');
  return `
    <span title="${safeName}">
      <img class="item-icon" src="${urls[0]}" alt="${safeName}"
           data-fallback-chain='${attrChain}' data-fallback-index="0" data-fallback-name="${safeName}"
           onerror="tryNextFallback(this)">
    </span>
  `;
}

function tryNextFallback(img) {
  const chain = JSON.parse(img.getAttribute('data-fallback-chain').replace(/&quot;/g, '"').replace(/&#39;/g, "'"));
  let idx = parseInt(img.getAttribute('data-fallback-index'), 10) + 1;
  if (idx < chain.length) {
    img.setAttribute('data-fallback-index', idx);
    img.src = chain[idx];
  } else {
    const name = img.getAttribute('data-fallback-name');
    img.outerHTML = `<span class="item-icon-fallback" title="${name}">${name}</span>`;
  }
}

function starPowerIcon(sp) {
  return iconWithFallbackChain([`https://cdn.brawlify.com/star-powers/borderless/${sp.id}.png`], sp.name);
}
function gadgetIcon(g) {
  return iconWithFallbackChain([`https://cdn.brawlify.com/gadgets/borderless/${g.id}.png`], g.name);
}
// Le icone dei Gear sono locali (cartella assets/gears/), perché non esiste
// un catalogo pubblico verificato su CDN esterni per questo tipo di icone.
// Se il file locale manca o non è ancora stato caricato, si torna comunque
// al badge testuale di riserva (nessun errore visibile).
const GEAR_ICON_MAP = [
  { match: /shield/i,       slug: 'scudo',         label: 'Scudo' },
  { match: /vision/i,       slug: 'visione',       label: 'Visione' },
  { match: /damage/i,       slug: 'danno',         label: 'Danno' },
  { match: /speed/i,        slug: 'velocita',      label: 'Velocità' },
  { match: /gadget/i,       slug: 'gadget',        label: 'Gadget' },
  { match: /health|regen/i, slug: 'rigenerazione', label: 'Rigenerazione' },
];
function gearIcon(g) {
  const info = GEAR_ICON_MAP.find(x => x.match.test(g.name || ''));
  const displayName = info ? info.label : g.name;
  const label = `${displayName} · lvl ${g.level ?? '?'}`;
  if (!info) {
    return `<span class="item-icon-fallback" title="${escapeAttr(label)}">⚙️ ${escapeAttr(g.name)}</span>`;
  }
  return iconWithFallbackChain([`assets/gears/${info.slug}.png`], label);
}

function rankBadge(rank) {
  const r = rank ?? 0;
  let cls = 'rank-bronze', label = 'Bronzo';
  if (r >= 31) { cls = 'rank-masters'; label = 'Master'; }
  else if (r >= 26) { cls = 'rank-legendary'; label = 'Leggendario'; }
  else if (r >= 21) { cls = 'rank-mythic'; label = 'Mitico'; }
  else if (r >= 16) { cls = 'rank-diamond'; label = 'Diamante'; }
  else if (r >= 11) { cls = 'rank-gold'; label = 'Oro'; }
  else if (r >= 6) { cls = 'rank-silver'; label = 'Argento'; }
  return `<span class="rank-badge ${cls}" title="Rank ${r} · fascia ${label} (basata sui trofei di questo brawler)">R${r}</span>`;
}

// ============================================================
// TEMPO RELATIVO (per battaglie recenti)
// ============================================================
function tempoRelativo(battleTimeStr) {
  // Formato API: "20260828T131438.000Z"
  const iso = battleTimeStr.replace(
    /^(\d{4})(\d{2})(\d{2})T(\d{2})(\d{2})(\d{2})/,
    '$1-$2-$3T$4:$5:$6'
  );
  const data = new Date(iso);
  if (isNaN(data.getTime())) return '—';

  const diffSec = Math.floor((Date.now() - data.getTime()) / 1000);
  if (diffSec < 60) return 'adesso';
  if (diffSec < 3600) return `${Math.floor(diffSec / 60)} min fa`;
  if (diffSec < 86400) return `${Math.floor(diffSec / 3600)} ore fa`;
  if (diffSec < 2592000) return `${Math.floor(diffSec / 86400)} giorni fa`;
  return data.toLocaleDateString('it-IT');
}

// Tipi di partita che consideriamo "Ranked" nel log battaglie ufficiale.
const TIPI_RANKED = ['ranked', 'soloRanked', 'teamRanked'];

function renderBattleLog(items) {
  if (!items || !items.length) {
    return '<p class="loading">Nessuna battaglia recente trovata.</p>';
  }

  const rows = items.map(item => {
    const battle = item.battle || {};
    const isRanked = TIPI_RANKED.includes(battle.type);
    const result = battle.result || (battle.rank ? `#${battle.rank}` : 'sconosciuto');
    const resultClass = result === 'victory' ? 'victory' : result === 'defeat' ? 'defeat' : result === 'draw' ? 'draw' : 'unknown';
    const resultLabel = result === 'victory' ? 'Vittoria' : result === 'defeat' ? 'Sconfitta' : result === 'draw' ? 'Pareggio' : result;
    const trophyChange = battle.trophyChange;
    const trophyHtml = (trophyChange !== undefined && trophyChange !== null)
      ? `<div class="battle-trophy ${trophyChange >= 0 ? 'pos' : 'neg'}">${trophyChange >= 0 ? '+' : ''}${trophyChange} 🏆</div>`
      : '';

    return `
      <div class="battle-row ${isRanked ? 'is-ranked' : ''}">
        <div class="battle-result-bar ${resultClass}"></div>
        <div class="battle-main">
          <div class="battle-mode">${escapeAttr(item.event?.mode || battle.mode || 'Modalità sconosciuta')}
            ${isRanked ? '<span class="battle-ranked-tag">RANKED</span>' : ''}
          </div>
          <div class="battle-map">${escapeAttr(item.event?.map || '—')} · ${resultLabel}</div>
        </div>
        <div class="battle-side">
          ${trophyHtml}
          <div class="battle-time">${tempoRelativo(item.battleTime)}</div>
        </div>
      </div>
    `;
  }).join('');

  return `<div class="battle-list">${rows}</div>`;
}

// ============================================================
// PAGINA: CERCA CLUB
// ============================================================
function cercaClubTag(tag) {
  cambiaPagina('club');
  document.getElementById('pageSwitcher').value = 'club';
  document.getElementById('clubTag').value = tag;
  cercaClub();
}

async function cercaClub() {
  const tag = document.getElementById('clubTag').value.trim();
  const resultDiv = document.getElementById('clubResult');

  if (!tag) {
    resultDiv.innerHTML = '<p class="error">Inserisci un tag club valido.</p>';
    return;
  }

  resultDiv.innerHTML = '<p class="loading">Caricamento…</p>';

  try {
    const res = await fetch(`api.php?action=club&tag=${encodeURIComponent(tag)}`);
    const rawText = await res.text();

    let json;
    try {
      json = JSON.parse(rawText);
    } catch (parseErr) {
      resultDiv.innerHTML = `<p class="error">Il server non ha risposto con dati validi.</p>`;
      return;
    }

    if (!json.ok) {
      resultDiv.innerHTML = `<p class="error">Errore: ${json.error}</p>`;
      return;
    }

    const c = json.data;
    const members = c.members || [];
    const roleLabel = { president: 'Presidente', vicePresident: 'Vice Pres.', senior: 'Senior', member: 'Membro' };

    const membersHtml = [...members]
      .sort((a, b) => (b.trophies || 0) - (a.trophies || 0))
      .map(m => `
        <div class="member-row" onclick="cercaGiocatoreTag('${m.tag}')">
          <span class="member-role ${m.role}">${roleLabel[m.role] || m.role}</span>
          <span class="member-name">${escapeAttr(m.name)}</span>
          <span class="member-trophies">🏆 ${m.trophies}</span>
        </div>
      `).join('');

    resultDiv.innerHTML = `
      <div class="club-header">
        <h2>🛡️ ${escapeAttr(c.name)}</h2>
        <div class="desc">${escapeAttr(c.description || '')}</div>
        <div class="stat-pills">
          <span class="pill highlight">🏆 <span class="n">${c.trophies}</span></span>
          <span class="pill">Min. richiesti <span class="n">${c.requiredTrophies}</span></span>
          <span class="pill">👥 <span class="n">${members.length}</span>/30</span>
          <span class="pill">${c.type === 'open' ? 'Aperto' : c.type === 'inviteOnly' ? 'Su invito' : 'Chiuso'}</span>
        </div>
      </div>
      <div class="section-label">Membri (clicca per vedere il profilo)</div>
      <div class="member-list">${membersHtml}</div>
    `;
  } catch (err) {
    resultDiv.innerHTML = `<p class="error">Errore di connessione: ${err.message}</p>`;
  }
}

// ============================================================
// SEZIONI PIEGHEVOLI (Brawler / Battaglie recenti)
// ============================================================
function toggleSection(id) {
  const content = document.getElementById(id);
  const toggle = document.getElementById(id === 'brawlerWrap' ? 'brawlerToggle' : 'battleLogToggle');
  content.classList.toggle('is-open');
  toggle.classList.toggle('is-open');
}

async function caricaBattleLog(tag, containerId) {
  const container = document.getElementById(containerId);
  container.innerHTML = '<p class="loading">Caricamento battaglie…</p>';

  try {
    const res = await fetch(`api.php?action=battlelog&tag=${encodeURIComponent(tag)}`);
    const rawText = await res.text();
    const json = JSON.parse(rawText);

    if (!json.ok) {
      container.innerHTML = `<p class="error">Errore: ${json.error}</p>`;
      document.getElementById('winRateBadge').textContent = 'N/D';
      return;
    }

    // Statistiche generali (tutte le modalita' con esito netto).
    const items = json.data.items || [];
    const decise = items.filter(it => it.battle && (it.battle.result === 'victory' || it.battle.result === 'defeat'));
    const vittorie = decise.filter(it => it.battle.result === 'victory').length;

    // Statistiche SOLO Ranked: sottoinsieme delle stesse partite, filtrato per tipo.
    const battagliteRanked = items.filter(it => it.battle && TIPI_RANKED.includes(it.battle.type));
    const rankedDecise = battagliteRanked.filter(it => it.battle.result === 'victory' || it.battle.result === 'defeat');
    const rankedVittorie = rankedDecise.filter(it => it.battle.result === 'victory').length;

    const rankedSummaryHtml = battagliteRanked.length > 0
      ? `<div class="ranked-summary">
          🏅 <b>Ranked</b> (ultime ${battagliteRanked.length} partite):
          ${rankedDecise.length > 0
            ? `<span class="win">${rankedVittorie}V</span> · <span class="loss">${rankedDecise.length - rankedVittorie}S</span>
               · <b>${Math.round((rankedVittorie / rankedDecise.length) * 100)}%</b> win rate`
            : 'nessun esito netto disponibile'}
        </div>`
      : `<div class="ranked-summary ranked-summary-empty">🏅 Nessuna partita Ranked tra le ultime ${items.length} scaricate.</div>`;

    container.innerHTML = rankedSummaryHtml + renderBattleLog(items);

    // Percentuale vittoria generale: calcolata SOLO sulle partite scaricate (ultime ~25,
    // limite imposto dall'API stessa), e solo su quelle con esito vittoria/sconfitta
    // netto (alcune modalita' come Showdown non hanno un risultato binario).
    const badge = document.getElementById('winRateBadge');
    if (decise.length > 0) {
      const perc = Math.round((vittorie / decise.length) * 100);
      badge.textContent = `${perc}% vittorie (${vittorie}/${decise.length})`;
      badge.title = `Calcolato sulle ultime ${items.length} partite scaricate dall'API (limite massimo ~25). Escluse le modalita' senza vittoria/sconfitta netta.`;
    } else {
      badge.textContent = 'N/D';
      badge.title = 'Nessuna partita con esito vittoria/sconfitta tra le ultime scaricate.';
    }
  } catch (err) {
    container.innerHTML = `<p class="error">Errore di connessione: ${err.message}</p>`;
    const badge = document.getElementById('winRateBadge');
    if (badge) badge.textContent = 'N/D';
  }
}

// ============================================================
// PAGINA: TUTTI I BRAWLER
// ============================================================
async function mostraBrawler() {
  const resultDiv = document.getElementById('brawlersResult');
  resultDiv.dataset.loaded = '1';
  resultDiv.innerHTML = '<p class="loading">Caricamento lista brawler…</p>';

  try {
    const res = await fetch('api.php?action=brawlers');
    const rawText = await res.text();

    let json;
    try {
      json = JSON.parse(rawText);
    } catch (parseErr) {
      console.error('Risposta non-JSON ricevuta:', rawText.slice(0, 500));
      resultDiv.innerHTML = `<p class="error">Il server non ha risposto con dati validi. Controlla la console.</p>`;
      return;
    }

    if (!json.ok) {
      resultDiv.innerHTML = `<p class="error">Errore: ${json.error}</p>`;
      return;
    }

    const brawlers = json.data.items || [];

    const cardsHtml = brawlers.map(b => {
      const imgUrl = `https://cdn.brawlify.com/brawlers/borderless/${b.id}.png`;
      return `
        <div class="brawler-item">
          <img src="${imgUrl}" alt="${escapeAttr(b.name)}" onerror="this.style.visibility='hidden'">
          <div>${escapeAttr(b.name)}</div>
        </div>
      `;
    }).join('');

    resultDiv.innerHTML = `
      <div class="section-label">${brawlers.length} brawler nel gioco</div>
      <div class="brawler-grid">${cardsHtml}</div>
    `;
  } catch (err) {
    resultDiv.innerHTML = `<p class="error">Errore di connessione: ${err.message}</p>`;
  }
}

// ============================================================
// GRIGLIA BRAWLER DEL GIOCATORE (con ordinamento)
// ============================================================
let ultimoPlayerData = null;
let ordinamentoCorrente = 'trophies';

// Indice di tutti i giocatori "conosciuti" dal sito (salvati + cercati in
// precedenza): serve solo per suggerire nomi mentre si digita, perché
// l'API ufficiale di Brawl Stars non permette di cercare per nome, solo
// per tag esatto.
let giocatoriConosciuti = [];
let suggerimentoAttivo = -1;

function renderBrawlerDetails(brawlers) {
  if (!brawlers.length) {
    return '<p class="loading">Nessun dato sui brawler disponibile.</p>';
  }

  const comparatori = {
    trophies: (a, b) => (b.trophies || 0) - (a.trophies || 0),
    power: (a, b) => (b.power || 0) - (a.power || 0),
    rank: (a, b) => (b.rank || 0) - (a.rank || 0),
    name: (a, b) => a.name.localeCompare(b.name),
  };
  const ordinati = [...brawlers].sort(comparatori[ordinamentoCorrente] || comparatori.trophies);

  const cards = ordinati.map((b, idx) => {
    const imgUrl = `https://cdn.brawlify.com/brawlers/borderless/${b.id}.png`;
    const isTop = idx === 0 && ordinamentoCorrente === 'trophies' && (b.trophies || 0) > 0;

    const gadgetsHtml = (b.gadgets || []).map(g => gadgetIcon(g)).join('');
    const starPowersHtml = (b.starPowers || []).map(s => starPowerIcon(s)).join('');
    const gearsHtml = (b.gears || []).map(g => gearIcon(g)).join('');
    const allItemsHtml = gearsHtml + gadgetsHtml + starPowersHtml || '<span style="font-size:11px;color:var(--text-muted);">Nessun upgrade</span>';

    return `
      <div class="brawler-card">
        ${isTop ? '<span class="top-brawler-crown" title="Il tuo brawler piu\' forte">👑</span>' : ''}
        <div class="brawler-card-head">
          <div class="brawler-avatar-wrap">
            <img src="${imgUrl}" alt="${escapeAttr(b.name)}" onerror="this.style.visibility='hidden'">
            <span class="power-level-badge" title="Potenza ${b.power ?? '?'}">${b.power ?? '?'}</span>
          </div>
          <div class="brawler-name-col">
            <div class="name">${escapeAttr(b.name)}</div>
            <div class="trophy-line">🏆 ${b.trophies ?? '?'}</div>
            ${rankBadge(b.rank)}
          </div>
        </div>
        <div class="item-icon-row">${allItemsHtml}</div>
      </div>
    `;
  }).join('');

  return `
    <div class="sort-row">
      Ordina per:
      <select onchange="cambiaOrdinamento(this.value)">
        <option value="trophies" ${ordinamentoCorrente === 'trophies' ? 'selected' : ''}>Trofei</option>
        <option value="power" ${ordinamentoCorrente === 'power' ? 'selected' : ''}>Potenza</option>
        <option value="rank" ${ordinamentoCorrente === 'rank' ? 'selected' : ''}>Rank</option>
        <option value="name" ${ordinamentoCorrente === 'name' ? 'selected' : ''}>Nome</option>
      </select>
    </div>
    <div class="brawler-detail-grid">${cards}</div>
  `;
}

function cambiaOrdinamento(valore) {
  ordinamentoCorrente = valore;
  if (ultimoPlayerData) {
    document.getElementById('brawlerSection').innerHTML = renderBrawlerDetails(ultimoPlayerData.brawlers || []);
  }
}

// ============================================================
// ULTIMI CERCATI (localStorage)
// ============================================================
const LS_RICERCHE_RECENTI = 'bsLookup_ricercheRecenti';
const MAX_RICERCHE_RECENTI = 10;

function leggiRicercheRecenti() {
  try {
    const raw = localStorage.getItem(LS_RICERCHE_RECENTI);
    const arr = raw ? JSON.parse(raw) : [];
    return Array.isArray(arr) ? arr : [];
  } catch (e) {
    return [];
  }
}

function salvaRicercaRecente(p) {
  if (!p || !p.tag) return;
  const iconId = p.icon && p.icon.id ? p.icon.id : null;
  const club = p.club && p.club.name ? p.club.name : null;
  const entry = { tag: p.tag, name: p.name, trophies: p.trophies, iconId, club, ts: Date.now() };

  let lista = leggiRicercheRecenti().filter(x => x.tag !== entry.tag);
  lista.unshift(entry);
  lista = lista.slice(0, MAX_RICERCHE_RECENTI);

  try { localStorage.setItem(LS_RICERCHE_RECENTI, JSON.stringify(lista)); } catch (e) {}

  aggiornaGiocatoreConosciuto(entry);
  renderRicercheRecenti();
}

function rimuoviRicercaRecente(tag, evt) {
  if (evt) evt.stopPropagation();
  const lista = leggiRicercheRecenti().filter(x => x.tag !== tag);
  try { localStorage.setItem(LS_RICERCHE_RECENTI, JSON.stringify(lista)); } catch (e) {}
  renderRicercheRecenti();
}

function cancellaRicercheRecenti() {
  if (!confirm('Cancellare tutta la cronologia degli ultimi cercati?')) return;
  try { localStorage.removeItem(LS_RICERCHE_RECENTI); } catch (e) {}
  renderRicercheRecenti();
}

function renderRicercheRecenti() {
  const lista = leggiRicercheRecenti();
  const wrap = document.getElementById('recentSearches');
  const label = document.getElementById('recentSearchesLabel');
  if (!wrap || !label) return;

  if (!lista.length) {
    wrap.innerHTML = '';
    label.style.display = 'none';
    return;
  }

  label.style.display = 'flex';
  wrap.innerHTML = lista.map(p => {
    const avatarUrl = p.iconId ? `https://cdn.brawlify.com/profile-icons/regular/${p.iconId}.png` : '';
    const clubLine = p.club ? `<div class="club-line">🛡️ ${escapeAttr(p.club)}</div>` : '';
    return `
      <div class="saved-player-chip" data-tag="${escapeAttr(p.tag)}" onclick="cercaGiocatoreTag('${p.tag}')">
        <img class="avatar-sm" src="${avatarUrl}" alt="${escapeAttr(p.name)}" onerror="this.style.visibility='hidden'">
        <div class="info">
          <div class="kicker">Cercato di recente</div>
          <div class="name-row">
            <span class="name">${escapeAttr(p.name)}</span>
            <span class="tagline">${escapeAttr(p.tag)}</span>
          </div>
          ${clubLine}
        </div>
        <div class="trophy-badge">🏆 ${p.trophies ?? '?'}</div>
        <button class="chip-remove-btn" title="Rimuovi dalla cronologia" onclick="rimuoviRicercaRecente('${p.tag}', event)">✕</button>
      </div>
    `;
  }).join('');
}

// ============================================================
// RICERCA PER NOME (autocomplete sui giocatori conosciuti)
// ============================================================
function aggiornaGiocatoreConosciuto(p) {
  if (!p || !p.tag || !p.name) return;
  const iconId = (p.icon && p.icon.id) ? p.icon.id : (p.iconId ?? null);
  const entry = { tag: p.tag, name: p.name, trophies: p.trophies, iconId };
  giocatoriConosciuti = giocatoriConosciuti.filter(g => g.tag !== entry.tag);
  giocatoriConosciuti.unshift(entry);
}

function gestisciInputRicerca(valore) {
  const box = document.getElementById('suggestionsBox');
  suggerimentoAttivo = -1;
  const q = valore.trim();

  if (!q || q.startsWith('#')) {
    box.classList.remove('is-open');
    box.innerHTML = '';
    return;
  }

  const qLower = q.toLowerCase();
  const risultati = giocatoriConosciuti
    .filter(g => g.name && g.name.toLowerCase().includes(qLower))
    .slice(0, 6);

  if (!risultati.length) {
    box.innerHTML = `<div class="suggestion-empty">Nessun giocatore con questo nome nella cronologia. Prova a cercare per tag esatto (es. #2PP).</div>`;
    box.classList.add('is-open');
    return;
  }

  box.innerHTML = risultati.map(g => {
    const avatarUrl = g.iconId ? `https://cdn.brawlify.com/profile-icons/regular/${g.iconId}.png` : '';
    return `
      <div class="suggestion-item" onclick="selezionaSuggerimento('${g.tag}')">
        <img src="${avatarUrl}" alt="${escapeAttr(g.name)}" onerror="this.style.visibility='hidden'">
        <div>
          <div class="s-name">${escapeAttr(g.name)}</div>
          <div class="s-tag">${escapeAttr(g.tag)} · 🏆 ${g.trophies ?? '?'}</div>
        </div>
      </div>
    `;
  }).join('');
  box.classList.add('is-open');
}

function selezionaSuggerimento(tag) {
  chiudiSuggerimenti();
  cercaGiocatoreTag(tag);
}

function chiudiSuggerimenti() {
  const box = document.getElementById('suggestionsBox');
  if (!box) return;
  box.classList.remove('is-open');
  box.innerHTML = '';
  suggerimentoAttivo = -1;
}

function aggiornaEvidenziazioneSuggerimenti(items) {
  items.forEach((el, i) => el.classList.toggle('is-active', i === suggerimentoAttivo));
}

function gestisciTastoRicerca(event) {
  const box = document.getElementById('suggestionsBox');
  const items = box ? box.querySelectorAll('.suggestion-item') : [];

  if (event.key === 'ArrowDown' && items.length) {
    event.preventDefault();
    suggerimentoAttivo = Math.min(suggerimentoAttivo + 1, items.length - 1);
    aggiornaEvidenziazioneSuggerimenti(items);
  } else if (event.key === 'ArrowUp' && items.length) {
    event.preventDefault();
    suggerimentoAttivo = Math.max(suggerimentoAttivo - 1, 0);
    aggiornaEvidenziazioneSuggerimenti(items);
  } else if (event.key === 'Enter') {
    if (suggerimentoAttivo >= 0 && items[suggerimentoAttivo]) {
      event.preventDefault();
      items[suggerimentoAttivo].click();
    } else {
      chiudiSuggerimenti();
      cercaGiocatore();
    }
  } else if (event.key === 'Escape') {
    chiudiSuggerimenti();
  }
}

document.addEventListener('click', (e) => {
  const wrap = document.querySelector('.search-input-wrap');
  if (wrap && !wrap.contains(e.target)) chiudiSuggerimenti();
});

// ============================================================
// PERSONE SALVATE
// ============================================================
function cercaGiocatoreTag(tag) {
  cambiaPagina('home');
  document.getElementById('pageSwitcher').value = 'home';
  document.getElementById('playerTag').value = tag;
  cercaGiocatore();
  document.getElementById('result').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

async function initSavedPlayers() {
  const chips = document.querySelectorAll('#savedPlayers .saved-player-chip[data-tag]');

  for (const chip of chips) {
    const tag = chip.getAttribute('data-tag');
    chip.classList.add('is-loading');
    chip.innerHTML = `
      <div class="avatar-sm"></div>
      <div class="info">
        <div class="kicker">Giocatore salvato</div>
        <div class="name">&nbsp;</div>
        <div class="club-line">&nbsp;</div>
      </div>
    `;
    chip.onclick = () => cercaGiocatoreTag(tag);

    try {
      const res = await fetch(`api.php?action=player&tag=${encodeURIComponent(tag)}`);
      const rawText = await res.text();
      const json = JSON.parse(rawText);
      if (!json.ok) throw new Error(json.error || 'Errore');

      const p = json.data;
      const iconId = p.icon && p.icon.id ? p.icon.id : null;
      const avatarUrl = iconId ? `https://cdn.brawlify.com/profile-icons/regular/${iconId}.png` : '';
      const clubLine = p.club && p.club.name ? `<div class="club-line">🛡️ ${escapeAttr(p.club.name)}</div>` : '';

      aggiornaGiocatoreConosciuto(p);
      chip.classList.remove('is-loading');
      chip.innerHTML = `
        <img class="avatar-sm" src="${avatarUrl}" alt="${escapeAttr(p.name)}" onerror="this.style.visibility='hidden'">
        <div class="info">
          <div class="kicker">Giocatore salvato</div>
          <div class="name-row">
            <span class="name">${escapeAttr(p.name)}</span>
            <span class="tagline">${escapeAttr(p.tag)}</span>
          </div>
          ${clubLine}
        </div>
        <div class="trophy-badge">🏆 ${p.trophies}</div>
      `;
    } catch (err) {
      chip.classList.remove('is-loading');
      chip.classList.add('is-error');
      chip.innerHTML = `
        <div class="avatar-sm" style="display:flex;align-items:center;justify-content:center;font-size:16px;">⚠️</div>
        <div class="info">
          <div class="kicker">Giocatore salvato</div>
          <div class="name">${escapeAttr(tag)}</div>
          <div class="club-line">Non trovato</div>
        </div>
      `;
    }
  }
}

document.addEventListener('DOMContentLoaded', () => {
  giocatoriConosciuti = leggiRicercheRecenti().map(p => ({ tag: p.tag, name: p.name, trophies: p.trophies, iconId: p.iconId }));
  renderRicercheRecenti();
  initSavedPlayers();
});

function copiaTag(tag, btn) {
  navigator.clipboard.writeText(tag).then(() => {
    const original = btn.textContent;
    btn.textContent = '✅';
    setTimeout(() => { btn.textContent = original; }, 1200);
  }).catch(() => {
    // Fallback silenzioso se il browser blocca l'accesso agli appunti (es. http non sicuro).
  });
}

// ============================================================
// RICERCA GIOCATORE PRINCIPALE
// ============================================================
async function cercaGiocatore() {
  chiudiSuggerimenti();
  const valoreInput = document.getElementById('playerTag').value.trim();
  const resultDiv = document.getElementById('result');

  if (!valoreInput) {
    resultDiv.innerHTML = '<p class="error">Inserisci un tag o un nome valido.</p>';
    return;
  }

  let tag = valoreInput;

  // Se non è un tag (non inizia con #), provo a risolvere il nome
  // usando i giocatori già noti al sito (salvati + cercati in passato).
  if (!tag.startsWith('#')) {
    const qLower = tag.toLowerCase();
    const esatte = giocatoriConosciuti.filter(g => g.name && g.name.toLowerCase() === qLower);
    const corrispondenze = esatte.length ? esatte : giocatoriConosciuti.filter(g => g.name && g.name.toLowerCase().includes(qLower));

    if (corrispondenze.length === 1) {
      tag = corrispondenze[0].tag;
    } else if (corrispondenze.length > 1) {
      resultDiv.innerHTML = `<p class="error">Più giocatori corrispondono a "${escapeAttr(valoreInput)}": scegli un suggerimento dall'elenco mentre digiti, oppure cerca per tag esatto.</p>`;
      return;
    } else {
      resultDiv.innerHTML = `<p class="error">Nessun giocatore con questo nome nella cronologia. Cerca per tag esatto (es. #2PP).</p>`;
      return;
    }
  }

  resultDiv.innerHTML = '<p class="loading">Caricamento…</p>';

  try {
    const res = await fetch(`api.php?action=player&tag=${encodeURIComponent(tag)}`);
    const rawText = await res.text();

    let json;
    try {
      json = JSON.parse(rawText);
    } catch (parseErr) {
      console.error('Risposta non-JSON ricevuta:', rawText.slice(0, 500));
      resultDiv.innerHTML = `<p class="error">Il server non ha risposto con dati validi.
        Controlla che PHP sia attivo sul server e che api.php sia nella stessa cartella di index.html.
        (Dettagli nella console del browser)</p>`;
      return;
    }

    if (!json.ok) {
      resultDiv.innerHTML = `<p class="error">Errore: ${json.error}</p>`;
      return;
    }

    const p = json.data;
    ultimoPlayerData = p;
    salvaRicercaRecente(p);
    const safe = (v) => (v === undefined || v === null) ? 'N/D' : v;

    const iconId = p.icon && p.icon.id ? p.icon.id : null;
    const iconUrl = iconId ? `https://cdn.brawlify.com/profile-icons/regular/${iconId}.png` : null;
    const avatarHtml = iconUrl
      ? `<img class="avatar" src="${iconUrl}" alt="Icona giocatore" onerror="this.style.visibility='hidden'">`
      : '';

    const clubHtml = p.club && p.club.name
      ? `<div class="club-chip" onclick="cercaClubTag('${p.club.tag}')" title="Vedi il club">🛡️ ${escapeAttr(p.club.name)}</div>`
      : '';

    resultDiv.innerHTML = `
      <div class="player-strip">
        ${avatarHtml}
        <div class="player-name-block">
          <h2>${escapeAttr(p.name)} <span class="player-level-badge" title="Livello Esperienza">${safe(p.expLevel)}</span></h2>
          <div class="tag">${safe(p.tag)}
            <button class="copy-tag-btn" onclick="copiaTag('${p.tag}', this)" title="Copia tag">📋</button>
          </div>
          ${clubHtml}
        </div>
        <div class="stat-pills">
          <span class="pill highlight">🏆 <span class="n">${safe(p.trophies)}</span></span>
          <span class="pill">🥇 Record <span class="n">${safe(p.highestTrophies)}</span></span>
          <span class="pill">3v3 <span class="n">${safe(p['3vs3Victories'])}</span></span>
          <span class="pill">Solo <span class="n">${safe(p.soloVictories)}</span></span>
        </div>
      </div>

      <div class="section-label toggle" onclick="toggleSection('brawlerWrap')" id="brawlerToggle">
        <span class="chev">▶</span> Brawler posseduti (${(p.brawlers || []).length})
      </div>
      <div class="collapsible-content" id="brawlerWrap">
        <div id="brawlerSection">${renderBrawlerDetails(p.brawlers || [])}</div>
      </div>

      <div class="section-label toggle" onclick="toggleSection('battleLogWrap')" id="battleLogToggle">
        <span class="chev">▶</span> ⚔️ Battaglie recenti
        <span class="win-rate-badge" id="winRateBadge">…</span>
      </div>
      <div class="collapsible-content" id="battleLogWrap">
        <div id="battleLogSection"><p class="loading">Caricamento…</p></div>
      </div>
    `;

    caricaBattleLog(tag, 'battleLogSection');
  } catch (err) {
    resultDiv.innerHTML = `<p class="error">Errore di connessione: ${err.message}</p>`;
  }
}
</script>

</body>
</html>
