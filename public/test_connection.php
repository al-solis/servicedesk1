<?php
$dsn = "Servicedesk"; // The name you gave the DSN
$user = "sa";              // Your DB username
$password = "";            // Your DB password (if any)

$conn = odbc_connect($dsn, $user, $password);

if (!$conn) {
    echo "❌ Connection failed.<br>";
    echo odbc_errormsg();
} else {
    echo "✅ Connected via ODBC DSN!<br>";

    // Example query
    $result = odbc_exec($conn, "SELECT @@VERSION AS version");

    if ($result) {
        while ($row = odbc_fetch_array($result)) {
            echo "SQL Server Version: " . $row['version'] . "<br>";
        }
    }

    odbc_close($conn);
}
?>
