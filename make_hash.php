<?php
// tools_make_hash.php - buat hash password (development)
$password = 'TIMPTN12CIPI'; // ganti kalau mau password lain
echo password_hash($password, PASSWORD_BCRYPT) . PHP_EOL;   