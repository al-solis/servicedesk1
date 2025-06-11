<?php
$dsn = 'odbc:DSN=Servicedesk';
$user = 'servicedesk';
$pass = 'servicedesk123';

try {
    $pdo = new PDO($dsn, $user, $pass);
    echo "Connected successfully via ODBC DSN.";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
