<?php
require_once __DIR__.'/../lib/jsondb.php';
header('Content-Type: text/plain; charset=utf-8');
$items = json_read(__DIR__.'/../data/peserta.json');
echo "Jumlah peserta: ".(is_array($items)?count($items):0).PHP_EOL;