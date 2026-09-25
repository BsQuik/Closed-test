<?php
require_once __DIR__ . '/config.php';

/**
 * Classe minimale per chiamare l'API ufficiale di Brawl Stars.
 */
class BrawlApi
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct(string $apiKey = BRAWL_API_KEY, string $baseUrl = BRAWL_API_BASE)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = $baseUrl;
    }

    /**
     * Esegue una GET autenticata verso l'API.
     * Ritorna un array associativo: ['ok' => bool, 'status' => int, 'data' => array|null, 'error' => string|null]
     */
    private function get(string $endpoint): array
    {
        $url = $this->baseUrl . $endpoint;

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->apiKey,
                'Accept: application/json',
            ],
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            return ['ok' => false, 'status' => 0, 'data' => null, 'error' => 'Errore di rete: ' . $curlError];
        }

        $decoded = json_decode($response, true);

        if ($httpCode !== 200) {
            $msg = $decoded['message'] ?? 'Errore sconosciuto';
            $reason = match ($httpCode) {
                400 => 'Richiesta non valida (tag malformato?)',
                403 => 'Accesso negato: la API key è sbagliata, scaduta, o l\'IP del server non corrisponde a quello registrato su developer.brawlstars.com',
                404 => 'Risorsa non trovata (player/club tag inesistente)',
                429 => 'Troppe richieste: hai superato il rate limit',
                500, 503 => 'Il servizio Brawl Stars è temporaneamente non disponibile',
                default => 'Errore HTTP ' . $httpCode,
            };
            return ['ok' => false, 'status' => $httpCode, 'data' => null, 'error' => "$reason ($msg)"];
        }

        return ['ok' => true, 'status' => 200, 'data' => $decoded, 'error' => null];
    }

    /**
     * Normalizza il tag: aggiunge # se manca, lo codifica come %23 per l'URL.
     */
    private function encodeTag(string $tag): string
    {
        $tag = trim($tag);
        $tag = strtoupper($tag);
        if (substr($tag, 0, 1) !== '#') {
            $tag = '#' . $tag;
        }
        return str_replace('#', '%23', $tag);
    }

    public function getPlayer(string $playerTag): array
    {
        return $this->get('/players/' . $this->encodeTag($playerTag));
    }

    public function getPlayerBattlelog(string $playerTag): array
    {
        return $this->get('/players/' . $this->encodeTag($playerTag) . '/battlelog');
    }

    public function getClub(string $clubTag): array
    {
        return $this->get('/clubs/' . $this->encodeTag($clubTag));
    }

    /**
     * Restituisce la lista di TUTTI i brawler del gioco.
     * Non richiede un tag: e' un endpoint "statico" con id e nome di ogni brawler.
     */
    public function getBrawlers(): array
    {
        return $this->get('/brawlers');
    }
}
