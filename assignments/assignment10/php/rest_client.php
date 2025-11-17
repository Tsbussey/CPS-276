<?php
function getWeather(): array
{
    $zip = isset($_POST['zip_code']) ? trim((string)$_POST['zip_code']) : '';
    $baseUrl = 'https://russet-v8.wccnet.edu/~sshaper/assignments/assignment10_rest/get_weather_json.php';
    $url = $baseUrl . '?zip_code=' . urlencode($zip);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_CONNECTTIMEOUT => 6,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTPHEADER => ['Accept: application/json']
    ]);
    $body = curl_exec($ch);
    $curlErr = curl_error($ch);
    $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($curlErr)                 return [plainError("Request error: " . htmlspecialchars($curlErr)), ""];
    if ($httpStatus >= 400)       return [plainError("Server returned HTTP $httpStatus."), ""];
    if ($body === false || $body==='') return [plainError("Empty response from server."), ""];

    $data = json_decode($body, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return [plainError("Invalid JSON response."), rawBlock($body)];
    }

    $err = extractError($data);
    if ($err !== null) return [plainError($err), ""];  // error text above the form 

    $searched = $data['searched_city'] ?? null;
    if (!is_array($searched)) {
        return [plainError("Unexpected payload: missing searched_city."), rawBlock($body)];
    }

    $name     = (string)($searched['name'] ?? 'Unknown');
    $tempStr  = (string)($searched['temperature'] ?? '');
    $humStr   = (string)($searched['humidity'] ?? '');
    $forecast = is_array($searched['forecast'] ?? null) ? $searched['forecast'] : [];

    $searchedTemp = parseTempToInt($tempStr);

    // Collect other cities robustly (handles different API key names)
    $others = collectOtherCities($data, $name);

    // Partition by relative temperature
    $hotter = [];
    $colder = [];
    foreach ($others as $c) {
        if (!isset($c['name'], $c['temperature'])) continue;
        $t = parseTempToInt((string)$c['temperature']);
        if ($t === null || $searchedTemp === null) continue;

        $entry = [
            'name'  => (string)$c['name'],
            'temperature' => sanitizeTemp((string)$c['temperature']), // keep °
            't'     => $t,
        ];
        if ($t > $searchedTemp) $hotter[] = $entry;
        elseif ($t < $searchedTemp) $colder[] = $entry;
    }

    // Order: hotter DESC, colder ASC
    usort($hotter, fn($a,$b) => $b['t'] <=> $a['t']);
    usort($colder, fn($a,$b) => $a['t'] <=> $b['t']);

    // Cap to 3 & 3
    $hotter = array_slice($hotter, 0, 3);
    $colder = array_slice($colder, 0, 3);

    // Build HTML
    $ack = "";
    $html = '';
    $html .= searchedCityBlock($name, sanitizeTemp($tempStr), htmlspecialchars($humStr), $forecast);

    // Sentence when empty; otherwise striped table
    $html .= tableOrMessage(
        "Up to three cities where temperatures are higher than " . htmlspecialchars($name),
        $hotter,
        "There are no cities with temperatures higher than " . htmlspecialchars($name) . "."
    );
    $html .= tableOrMessage(
        "Up to three cities where temperatures are lower than " . htmlspecialchars($name),
        $colder,
        "There are no cities with temperatures lower than " . htmlspecialchars($name) . "."
    );

    return [$ack, $html];
}

/** Decode HTML entities */
function sanitizeTemp(string $s): string {
    $decoded = html_entity_decode($s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return htmlspecialchars($decoded, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/** Extract numeric temperature for comparisons. */
function parseTempToInt(?string $s): ?int {
    if ($s === null) return null;
    $decoded = html_entity_decode($s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    if (!preg_match('/-?\d+/', $decoded, $m)) return null;
    return (int)$m[0];
}

/** Plain error text shown above the form. */
function plainError(string $msg): string {
    return '<p class="mb-2">'.$msg.'</p>';
}

/** Raw payload block. */
function rawBlock(string $raw): string {
    return '<pre class="border p-2 bg-light">'.htmlspecialchars($raw).'</pre>';
}

/** Searched city section */
function searchedCityBlock(string $name, string $temp, string $humidity, array $forecast): string {
    $items = '';
    foreach ($forecast as $f) {
        $day  = htmlspecialchars((string)($f['day'] ?? ''));
        $cond = htmlspecialchars((string)($f['condition'] ?? ''));
        if ($day === '' && $cond === '') continue;
        $items .= "<li class=\"mb-1\">{$day}: {$cond}</li>";
    }
    if ($items === '') $items = '<li class="text-muted mb-1">No forecast available.</li>';

    return <<<HTML
<section class="mb-4">
  <h2 class="fs-2 mb-3">{$name}</h2>
  <p class="mb-2"><strong>Temperature:</strong> {$temp}</p>
  <p class="mb-3"><strong>Humidity:</strong> {$humidity}</p>
  <p class="mb-2"><strong>3-day forecast</strong></p>
  <ul class="mb-4">{$items}</ul>
</section>
HTML;
}

/** Title + table when rows exist; otherwise a single sentence (no table). */
function tableOrMessage(string $title, array $items, string $emptyMsg): string {
    if (empty($items)) {
        return '<p class="mb-4"><strong>'.htmlspecialchars($emptyMsg).'</strong></p>';
    }
    $rows = '';
    foreach ($items as $c) {
        $rows .= '<tr><td>'.htmlspecialchars($c['name']).'</td><td>'.$c['temperature'].'</td></tr>';
    }
    return <<<HTML
<section class="mb-4">
  <p class="mb-2"><strong>{$title}</strong></p>
  <div class="table-responsive">
    <table class="table table-striped align-middle mb-0">
      <thead><tr><th style="width:60%">City Name</th><th>Temperature</th></tr></thead>
      <tbody>{$rows}</tbody>
    </table>
  </div>
</section>
HTML;
}

/** Pull an error message from multiple possible response shapes. */
function extractError($payload): ?string {
    if ($payload === null) return "Empty response.";
    if (is_string($payload) || !is_array($payload)) return null;
    if (isset($payload['error']) && is_string($payload['error'])) return $payload['error'];
    if (count($payload) === 1) {
        $k = array_key_first($payload); $v = $payload[$k];
        if (is_string($k) && strtolower($k)==='error' && is_string($v)) return $v;
        if (is_array($v) && isset($v['error']) && is_string($v['error'])) return $v['error'];
    }
    foreach ($payload as $v) if (is_array($v) && isset($v['error']) && is_string($v['error'])) return $v['error'];
    return null;
}

/** Find a list of city rows in the payload; exclude the searched city; dedupe by name. */
function collectOtherCities(array $data, string $searchedName): array {
    $candidates = [];
    foreach (['other_cities','cities','all_cities'] as $k) {
        if (isset($data[$k]) && is_array($data[$k])) $candidates = array_merge($candidates, $data[$k]);
    }
    $stack = [$data];
    while ($stack) {
        $node = array_pop($stack);
        if (!is_array($node)) continue;
        if ($node && array_is_list($node) && looksLikeCityRow($node[0] ?? null)) {
            $candidates = array_merge($candidates, $node);
        }
        foreach ($node as $v) if (is_array($v)) $stack[] = $v;
    }
    $out = []; $seen = [];
    foreach ($candidates as $row) {
        if (!looksLikeCityRow($row)) continue;
        $n = (string)$row['name'];
        if ($n === '' || strcasecmp($n, $searchedName) === 0) continue;
        $key = strtolower($n); if (isset($seen[$key])) continue; $seen[$key] = true;
        $out[] = ['name'=>$n, 'temperature'=>(string)$row['temperature']];
    }
    return $out;
}
function looksLikeCityRow($row): bool { return is_array($row) && isset($row['name'], $row['temperature']); }


if (!function_exists('array_is_list')) {
    function array_is_list(array $array): bool {
        $i = 0; foreach ($array as $k => $_) { if ($k !== $i++) return false; } return true;
    }
}