<?php
ini_set('display_errors', 1); // Turn on error displaying
error_reporting(E_ALL);     // Report all PHP errors


$db_host = 'database-1.cxomy0mse0pi.ap-southeast-2.rds.amazonaws.com';
$db_user = 'admin';
$db_pass = 'admin123!';
$db_name = 'medical2';
$db_port = '3306';

$link = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);


if ($link->connect_error) {
    die("Connection failed: (" . $link->connect_errno . ") " . $link->connect_error);
}

echo "connected<br>";


$sql = "SELECT * FROM patient";
$result = $link->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        echo "data :<br>";
        while($row = $result->fetch_assoc()) {
            foreach($row as $column_name => $column_value) {
                echo "<strong>" . htmlspecialchars($column_name) . ":</strong> " . htmlspecialchars($column_value) . "<br>";
            }
            echo "<hr>"; 
        }
    } else {
        echo "nothing found";
    }
    $result->free();
} else {
    echo "error: (" . $link->errno . ") " . $link->error;
}

// $link->close();

// $link->close();

// echo "Connection closed.";

?>
