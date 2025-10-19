<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);

$texts = isset($data['texts']) && is_array($data['texts']) ? $data['texts'] : null;
$text = isset($data['text']) ? trim($data['text']) : '';
$target = isset($data['target']) ? trim($data['target']) : 'zh';
$source = isset($data['source']) ? trim($data['source']) : 'en';

if (($texts === null || count($texts) === 0) && $text === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Missing text(s)']);
    exit;
}

$endpoint = 'https://libretranslate.com/translate';

$payload = [
    'q' => $texts ? array_values($texts) : $text,
    'source' => $source,
    'target' => $target,
    'format' => 'text'
];

$ch = curl_init($endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
$resp = curl_exec($ch);
$err = curl_error($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($err || $status >= 400) {
    http_response_code(502);
    echo json_encode(['error' => 'Translation service unavailable']);
    exit;
}

$json = json_decode($resp, true);

if ($texts) {
    if (is_array($json)) {
        $out = [];
        foreach ($json as $item) {
            $out[] = $item['translatedText'] ?? '';
        }
        echo json_encode(['translations' => $out]);
        exit;
    }
    http_response_code(500);
    echo json_encode(['error' => 'Invalid translation response']);
    exit;
}

if (isset($json['translatedText'])) {
    echo json_encode(['translatedText' => $json['translatedText']]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Invalid translation response']);
}
?>

