<?php
$db_host = getenv('DB_HOST');
$db_port = getenv('DB_PORT');
$db_name = getenv('DB_DATABASE');
$db_username = getenv('DB_USERNAME');
$db_pass = getenv('DB_PASSWORD');

try {
    $db = new PDO("host=$db_host;port=$db_port;dbname=$db_name", $db_username, $db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database not connected: " . $e->getMessage());
}
