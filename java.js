async function mostraBrawler() {
  const resultDiv = document.getElementById('result');
  resultDiv.innerHTML = '<p>Caricamento lista brawler...</p>';

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

    // L'API ufficiale restituisce { items: [ {id, name, ...}, ... ] }
    const brawlers = json.data.items || [];

    const cardsHtml = brawlers.map(b => {
      const imgUrl = `https://cdn.brawlify.com/brawlers/borderless/${b.id}.png`;
      return `
        <div class="brawler-item">
          <img src="${imgUrl}" alt="${b.name}" onerror="this.style.visibility='hidden'">
          <div>${b.name}</div>
        </div>
      `;
    }).join('');

    resultDiv.innerHTML = `
      <p>${brawlers.length} brawler trovati</p>
      <div class="brawler-grid">${cardsHtml}</div>
    `;
  } catch (err) {
    resultDiv.innerHTML = `<p class="error">Errore di connessione: ${err.message}</p>`;
  }
}

function renderBrawlerDetails(brawlers) {
  if (!brawlers.length) {
    return '<p>Nessun dato sui brawler disponibile.</p>';
  }

  // Ordina per trofei decrescenti, cosi' i piu' forti si vedono per primi.
  const ordinati = [...brawlers].sort((a, b) => (b.trophies || 0) - (a.trophies || 0));

  return ordinati.map(b => {
    const imgUrl = `https://cdn.brawlify.com/brawlers/borderless/${b.id}.png`;
    const gadgets = (b.gadgets || []).map(g => g.name).join(', ') || '—';
    const starPowers = (b.starPowers || []).map(s => s.name).join(', ') || '—';
    const gears = (b.gears || []).map(g => `${g.name} (lvl ${g.level ?? '?'})`).join(', ') || '—';

    return `
      <div class="brawler-card">
        <div style="display:flex; gap:10px; align-items:center;">
          <img src="${imgUrl}" alt="${b.name}" width="48" height="48"
               style="border-radius:6px; background:#333;" onerror="this.style.visibility='hidden'">
          <div>
            <strong>${b.name}</strong><br>
            <span style="font-size:12px; color:#aaa;">Potenza ${b.power ?? '?'} · Rank ${b.rank ?? '?'}</span>
          </div>
        </div>
        <div style="margin-top:8px; font-size:13px;">
          🏆 Trofei: <b>${b.trophies ?? '?'}</b> (record: ${b.highestTrophies ?? '?'})<br>
          🔧 Gear: ${gears}<br>
          🎯 Gadget: ${gadgets}<br>
          ⭐ Star Power: ${starPowers}
        </div>
      </div>
    `;
  }).join('');
}

async function cercaGiocatore() {
  const tag = document.getElementById('playerTag').value.trim();
  const resultDiv = document.getElementById('result');

  if (!tag) {
    resultDiv.innerHTML = '<p class="error">Inserisci un tag valido.</p>';
    return;
  }

  resultDiv.innerHTML = '<p>Caricamento...</p>';

  try {
    const res = await fetch(`api.php?action=player&tag=${encodeURIComponent(tag)}`);
    const rawText = await res.text();

    let json;
    try {
      json = JSON.parse(rawText);
    } catch (parseErr) {
      // Il server non ha risposto con JSON valido: probabilmente un errore PHP
      // o api.php non viene eseguito come PHP dal server (es. PHP non configurato).
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
    const safe = (v) => (v === undefined || v === null) ? 'N/D' : v;

    // L'API ufficiale da' solo l'ID numerico dell'icona (p.icon.id).
    // Costruiamo l'URL dell'immagine usando la CDN pubblica di Brawlify.
    const iconId = p.icon && p.icon.id ? p.icon.id : null;
    const iconUrl = iconId
      ? `https://cdn.brawlify.com/profile-icons/regular/${iconId}.png`
      : null;
    const iconHtml = iconUrl
      ? `<img src="${iconUrl}" alt="Icona giocatore" width="64" height="64"
           style="border-radius:50%; background:#333;"
           onerror="this.style.display='none'">`
      : '';

    resultDiv.innerHTML = `
      <div class="card">
        ${iconHtml}
        <div>
          <h2>${safe(p.name)}</h2>
          <p>Tag: ${safe(p.tag)}</p>
        </div>
      </div>
      <div style="margin-top:12px; display:flex; gap:10px; flex-wrap:wrap;">
        <span class="stat">🏆 Trofei: ${safe(p.trophies)}</span>
        <span class="stat">🥇 Record: ${safe(p.highestTrophies)}</span>
        <span class="stat">⭐ Livello Exp: ${safe(p.expLevel)}</span>
        <span class="stat">🎮 Vittorie 3v3: ${safe(p['3vs3Victories'])}</span>
        <span class="stat">🥊 Vittorie Solo: ${safe(p.soloVictories)}</span>
      </div>
      <h3 style="margin-top:24px;">Brawler posseduti (${(p.brawlers || []).length})</h3>
      <div class="brawler-detail-grid">${renderBrawlerDetails(p.brawlers || [])}</div>
    `;
  } catch (err) {
    resultDiv.innerHTML = `<p class="error">Errore di connessione: ${err.message}</p>`;
  }
}