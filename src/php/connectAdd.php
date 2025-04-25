<?php
ini_set('display_errors', 1); // Turn on error displaying
error_reporting(E_ALL);     // Report all PHP errors



$db_host = 'database-1.cxomy0mse0pi.ap-southeast-2.rds.amazonaws.com';
$db_user = 'admin';
$db_pass = 'admin123!';
$db_name = 'medical2';
$db_port = '3306';


$fname = $_POST["fname"];
$lname = $_POST["lname"];
$patientAddress = $_POST["patientAddress"];
$provider = $_POST["provider"];
$dateOfBirth = $_POST["dateOfBirth"];

$link = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);


if ($link->connect_error) {
    die("Connection failed: (" . $link->connect_errno . ") " . $link->connect_error);
}

echo "connected<br>";


var_dump($fname, $lname, $patientAddress, $provider, $dateOfBirth);

$sql = "INSERT INTO `patient` (fname, lname, address, provider, date_of_birth) 
    VALUES ('$fname', '$lname', '$patientAddress','provider','dateOfBirth')";

$link->query($sql);


echo "work";
$sql = "SELECT fname FROM patient";
$result = $link->query($sql);
echo "work2";
if ($result) {
    if ($result->num_rows > 0) {
        echo "fnames :<br>";
        while($row = $result->fetch_assoc()) {
            echo htmlspecialchars($row["fname"]) . "<br>";
        }
    } else {
        echo "nothign found";
    }
    $result->free();
} else {
    echo "error (" . $link->errno . ") " . $link->error;
}

// $link->close();

// echo "Connection closed.";

?>