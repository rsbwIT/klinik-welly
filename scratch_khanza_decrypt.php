<?php
function decryptAES($encrypted) {
    $key = "Bar12345Bar12345";
    $iv  = "sayangsamakhanza";
    return openssl_decrypt(base64_decode($encrypted), 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
}

$files = [
    'C:/Khanza fix 2026/setting/database.xml',
    'C:/Khanza 2026/setting/database.xml',
    'C:/Khanza Test/setting/database.xml'
];

foreach ($files as $f) {
    if (!file_exists($f)) continue;
    echo "=== FILE: $f ===\n";
    $xml = simplexml_load_file($f);
    foreach ($xml->entry as $e) {
        $k = (string)$e['key'];
        if (in_array($k, ['HOST', 'DATABASE', 'PORT', 'USER', 'PAS'])) {
            echo "$k: " . decryptAES((string)$e) . "\n";
        }
    }
}
