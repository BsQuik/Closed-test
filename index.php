<?php
require_once __DIR__ . '/auth.php';

if (!isAuthenticated()):
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Brawl Stars — Player Lookup</title>
<link rel="stylesheet" href="style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  
</head>
<body>
  <div class="login-card accesso1">
  <h2>🔒 Pannello di Accesso</h2>
  <p>Inserisci la password per accedere al servizio</p>

  <?php if (!empty($login_error)): ?>
    <div class="error-msg"><?= htmlspecialchars($login_error) ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <input type="hidden" name="action" value="login">
    <input type="password" name="password" class="input-field" placeholder="Password nascosta" required autofocus>
    <button type="submit" class="btn-submit">Sblocca Accesso</button>
  </form>
</div>

</body>
</html>
<?php
exit;
endif;
?>
<link rel="stylesheet" href="style.css"></div>
<div class="topbar">
  <h1>🥊 BS Lookup</h1>
  <select class="page-switcher" id="pageSwitcher" onchange="cambiaPagina(this.value)">
    <option value="home">🔍 Cerca Giocatore</option>
    <option value="club">🛡️ Cerca Club</option>
    <option value="brawlers">📋 Tutti i Brawler</option>
  </select>
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
// Nessun catalogo pubblico verificato esiste per le icone dei gear: badge diretto.
function gearIcon(g) {
  const safeName = escapeAttr(g.name);
  const label = `${g.name} · lvl ${g.level ?? '?'}`;
  return `<span class="item-icon-fallback" title="${escapeAttr(label)}">⚙️ ${safeName}</span>`;
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
  const entry = { tag: p.tag, name: p.name, trophies: p.trophies, iconId, ts: Date.now() };

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
    return `
      <div class="saved-player-chip" data-tag="${escapeAttr(p.tag)}" onclick="cercaGiocatoreTag('${p.tag}')">
        <img class="avatar-sm" src="${avatarUrl}" alt="${escapeAttr(p.name)}" onerror="this.style.visibility='hidden'">
        <div class="info">
          <div class="name">${escapeAttr(p.name)}</div>
          <div class="sub">🏆 <span class="n">${p.trophies ?? '?'}</span></div>
        </div>
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
  const chips = document.querySelectorAll('.saved-player-chip[data-tag]');

  for (const chip of chips) {
    const tag = chip.getAttribute('data-tag');
    chip.classList.add('is-loading');
    chip.innerHTML = `
      <div class="avatar-sm"></div>
      <div class="info">
        <div class="name">&nbsp;</div>
        <div class="sub">&nbsp;</div>
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

      aggiornaGiocatoreConosciuto(p);
      chip.classList.remove('is-loading');
      chip.innerHTML = `
        <img class="avatar-sm" src="${avatarUrl}" alt="${escapeAttr(p.name)}" onerror="this.style.visibility='hidden'">
        <div class="info">
          <div class="name">${escapeAttr(p.name)}</div>
          <div class="sub">🏆 <span class="n">${p.trophies}</span></div>
        </div>
      `;
    } catch (err) {
      chip.classList.remove('is-loading');
      chip.classList.add('is-error');
      chip.innerHTML = `
        <div class="avatar-sm" style="display:flex;align-items:center;justify-content:center;font-size:16px;">⚠️</div>
        <div class="info">
          <div class="name">${escapeAttr(tag)}</div>
          <div class="sub">Non trovato</div>
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
