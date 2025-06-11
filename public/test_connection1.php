<?php
$serverName = "192.168.0.249"; // or "192.168.0.249,1433"
$connectionInfo = [
    "Database" => "Servicedesk",
    "UID" => "sa",
    "PWD" => "",
    "LoginTimeout" => 5,
    "Encrypt" => 0,  // Must be 0 (not false/"no")
    "TrustServerCertificate" => 0,  // Must be 0
    "CharacterSet" => "UTF-8"
];

$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    echo "<h2>Connection Failed</h2>";
    echo "<pre>";
    print_r(sqlsrv_errors());
    echo "</pre>";
    
    // Critical diagnostics
    echo "<h3>System Checks:</h3>";
    echo "1. ODBC Driver version: ";
    echo shell_exec('odbcinst -q -d | findstr "SQL Server"');
    echo "<br>2. PHP extensions loaded: ";
    print_r(get_loaded_extensions());
} else {
    echo "<h2 style='color:green'>Connection Successful!</h2>";
    
    // Test query
    $sql = "SELECT @@VERSION AS version";
    $stmt = sqlsrv_query($conn, $sql);
    
    if ($stmt) {
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        echo "SQL Server Version: " . $row['version'];
    }
    
    sqlsrv_close($conn);
}