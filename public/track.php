<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable('/home/diysell/ukrposhta');
$dotenv->load();

$token = $_ENV['UKRPOSHTA_TOKEN'] ?? '';
if (empty($token)) {
    http_response_code(500);
    echo json_encode(["error" => "Token not found"]);
    exit;
}

if (empty($_GET['barcode'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing barcode"]);
    exit;
}

$barcode = preg_replace('/[^A-Z0-9]/i', '', $_GET['barcode']);
$url = "https://www.ukrposhta.ua/status-tracking/0.0.1/statuses?barcode=" . urlencode($barcode) . "&lang=en";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token",
    "Content-Type: application/json"
]);

$response = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($code === 200) {
    echo $response;
} else {
    http_response_code($code);
    echo json_encode(["error" => "Failed to fetch data", "code" => $code]);
}
