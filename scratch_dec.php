<?php
function dec($c) {
    $key = str_pad('kamus', 16, chr(0));
    return openssl_decrypt(base64_decode($c), 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
}
echo "HOST: " . dec("UfjQfR828ZwrcH220jPelA==") . "\n";
echo "HOST2: " . dec("mLrFWnbgMWkiCg26BpVcYw==") . "\n";
echo "DB: " . dec("AZHNccBwSmSho84f+X8GyQ==") . "\n";
echo "USER: " . dec("O5MeIU3iQcn/BTTzoZmMPg==") . "\n";
echo "PAS: " . dec("+LJMMWgOdsh3O/vdS99sqWRlzN7o/7Y34jAFEXnSaDQ=") . "\n";
