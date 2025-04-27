<?php
ini_set('display_errors', 1); // Turn on error displaying
error_reporting(E_ALL);     // Report all PHP errors

include 'connectDB.php';

// echo "connected<br>";
$fname = $_POST["fname"];
$lname = $_POST["lname"];
$patientAddress = $_POST["patientAddress"];
$provider = $_POST["provider"];
$dateOfBirth = $_POST["dateOfBirth"];

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