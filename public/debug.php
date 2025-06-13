<?php
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable('/home/diysell/ukrposhta');
$dotenv->load();

header("Content-Type: text/plain");
echo "Token from .env: " . ($_ENV['UKRPOSHTA_TOKEN'] ?? 'NOT FOUND');
