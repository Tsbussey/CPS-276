<?php
// ===== /php/rest_client.php =====
/**
 * getWeather(): returns [ $acknowledgementHtml, $outputHtml ]
 * Uses cURL to call:
 *   https://russet-v8.wccnet.edu/~sshaper/assignments/assignment10_rest/get_weather_json.php?zip_code={ZIP}
 * per spec (:contentReference[oaicite:13]{index=13}).
 */
function getWeather(): array
{
    $zip = isset($_POST['zip_code']) ? trim((string)$_POST['zip_code']) : '';
    $baseUrl = 'https://russet-v8.wccnet.edu/~sshaper/assignments/assignment10_rest/get_weather_json.php';
    $url = $baseUrl . '?zip_code=' . urlencode($zip);

    // Defensive: empty input still goes to API (API itself may return "No zip code provided.")
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

    if ($curlErr) {
        return [alert("danger", "Request error: " . htmlspecialchars($curlErr)), ""];
    }
    if ($httpStatus >= 400) {
        return [alert("danger", "Server returned HTTP $httpStatus."), ""];
    }
    if ($body === false || $body === '') {
        return [alert("danger", "Empty response from server."), ""];
    }

    // Try to decode JSON; API examples vary in formatting (error key variations).
    $data = json_decode($body, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        // Sometimes the service might return PHP-ish array syntax; show raw for debugging.
        return [alert("danger", "Invalid JSON response."), preBlock($body)];
    }

    // Normalize/locate error message regardless of shape
    $err = extractError($data);
    if ($err !== null) {
        return [alert("danger", htmlspecialchars($err)), ""];
    }

    // Expecting structure similar to example:
    // {
    //   "searched_city": { "name": "...", "temperature": "72°F", "humidity": "60%", "forecast":[...] },
    //   "other_cities": [ { "name":"...", "temperature":"64°F" }, ... ]
    // }
    $searched = $data['searched_city'] ?? null;
    if (!is_array($searched)) {
        return [alert("danger", "Unexpected payload: missing searched_city."), preBlock($body)];
    }

    $name = (string)($searched['name'] ?? 'Unknown');
    $tempStr = (string)($searched['temperature'] ?? '');
    $humStr  = (string)($searched['humidity'] ?? '');
    $forecast = is_array($searched['forecast'] ?? null) ? $searched['forecast'] : [];

    $searchedTemp = parseTempToInt($tempStr);
    $others = is_array($data['other_cities'] ?? null) ? $data['other_cities'] : [];

    // Partition hotter/colder relative to searched
    $hotter = [];
    $colder = [];
    foreach ($others as $c) {
        if (!isset($c['name'], $c['temperature'])) {
            continue;
        }
        $t = parseTempToInt((string)$c['temperature']);
        if ($t === null || $searchedTemp === null) {
            continue;
        }
        $entry = [
            'name' => (string)$c['name'],
            'temperature' => (string)$c['temperature'],
            't' => $t,
            'delta' => abs($t - $searchedTemp),
        ];
        if ($t > $searchedTemp) {
            $hotter[] = $entry;
        } elseif ($t < $searchedTemp) {
            $colder[] = $entry;
        }
    }

    // Sort by closeness (then temp) for a stable, sensible order
    usort($hotter, fn($a,$b) => $a['delta'] <=> $b['delta'] ?: $a['t'] <=> $b['t']);
    usort($colder, fn($a,$b) => $a['delta'] <=> $b['delta'] ?: $a['t'] <=> $b['t']);

    // Cap per spec: 3 hotter (:contentReference[oaicite:14]{index=14}), up to 5 colder (:contentReference[oaicite:15]{index=15})
    $hotter = array_slice($hotter, 0, 3);
    $colder = array_slice($colder, 0, 5);

    // Build HTML with Bootstrap
    $ack = alert("success", "Showing results for <strong>" . htmlspecialchars($name) . "</strong> (Zip: " . htmlspecialchars($zip) . ").");

    $html = '';
    $html .= searchedCityCard($name, $tempStr, $humStr, $forecast);
    $html .= listTable("Cities Hotter Than " . htmlspecialchars($name), $hotter, "No cities are hotter than the searched city.");
    $html .= listTable("Cities Colder Than " . htmlspecialchars($name), $colder, "No cities are colder than the searched city.");

    return [$ack, $html];
}

/** Extracts numeric temperature from strings like "72°F" or "-3°". */
function parseTempToInt(?string $s): ?int {
    if ($s === null) return null;
    // keep sign and digits only
    if (!preg_match('/-?\d+/', $s, $m)) return null;
    return (int)$m[0];
}

/** Bootstrap alert helper. */
function alert(string $type, string $msg): string {
    return '<div class="alert alert-' . htmlspecialchars($type) . ' alert-dismissible fade show" role="alert">'
         . $msg
         . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'
         . '</div>';
}

/** Pre block (why: show raw payload if malformed). */
function preBlock(string $raw): string {
    return '<div class="card border-danger-subtle mb-3"><div class="card-body"><pre class="mb-0">'
         . htmlspecialchars($raw)
         . '</pre></div></div>';
}

/** Card for the searched city and forecast. */
function searchedCityCard(string $name, string $temp, string $humidity, array $forecast): string {
    $rows = '';
    foreach ($forecast as $f) {
        $day = htmlspecialchars((string)($f['day'] ?? ''));
        $cond = htmlspecialchars((string)($f['condition'] ?? ''));
        $rows .= "<tr><td>{$day}</td><td>{$cond}</td></tr>";
    }
    if ($rows === '') {
        $rows = '<tr><td colspan="2" class="text-muted">No forecast available.</td></tr>';
    }

    return <<<HTML
<div class="card shadow-sm mb-4">
  <div class="card-header fw-semibold">Searched City</div>
  <div class="card-body">
    <div class="row g-3 align-items-center mb-3">
      <div class="col-md">
        <h2 class="h4 mb-1">{$name}</h2>
        <div class="small text-muted">Temperature: {$temp} &middot; Humidity: {$humidity}</div>
      </div>
    </div>
    <h3 class="h6 mb-2">3-Day Forecast</h3>
    <div class="table-responsive">
      <table class="table table-sm table-striped align-middle mb-0">
        <thead><tr><th style="width:40%">Day</th><th>Condition</th></tr></thead>
        <tbody>
          {$rows}
        </tbody>
      </table>
    </div>
  </div>
</div>
HTML;
}

/** Table for hotter/colder lists. */
function listTable(string $title, array $items, string $emptyMsg): string {
    $rows = '';
    foreach ($items as $c) {
        $rows .= '<tr>'
               . '<td>' . htmlspecialchars($c['name']) . '</td>'
               . '<td>' . htmlspecialchars($c['temperature']) . '</td>'
               . '</tr>';
    }
    if ($rows === '') {
        $rows = '<tr><td colspan="2" class="text-muted">' . htmlspecialchars($emptyMsg) . '</td></tr>';
    }

    return <<<HTML
<div class="card shadow-sm mb-4">
  <div class="card-header fw-semibold">{$title}</div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-striped align-middle mb-0">
        <thead><tr><th style="width:60%">City</th><th>Temperature</th></tr></thead>
        <tbody>{$rows}</tbody>
      </table>
    </div>
  </div>
</div>
HTML;
}

/**
 * Attempts to find an 'error' message in any of the API's listed formats,
 * including the special-case variant shown in the spec.
 * Why: API shows differently formatted "error" examples in the PDF.
 */
function extractError($payload): ?string {
    if ($payload === null) return "Empty response.";
    if (is_string($payload)) {
        // Unlikely, but be tolerant.
        return null;
    }
    if (is_array($payload)) {
        if (array_key_exists('error', $payload) && is_string($payload['error'])) {
            return $payload['error'];
        }
        // Sometimes the API might return an array with a single element containing 'error' => '...'
        // e.g., [{"error":"..."}] or ["error" => "..."] in associative array form.
        if (count($payload) === 1) {
            $only = current($payload);
            $onlyKey = key($payload);
            if (is_string($onlyKey) && strtolower($onlyKey) === 'error' && is_string($only)) {
                return $only;
            }
            if (is_array($only) && isset($only['error']) && is_string($only['error'])) {
                return $only['error'];
            }
        }
        // Scan shallowly for 'error'
        foreach ($payload as $k => $v) {
            if (is_array($v) && isset($v['error']) && is_string($v['error'])) {
                return $v['error'];
            }
        }
    }
    return null;
}
