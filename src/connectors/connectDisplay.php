<?php
session_start();
$patientID = $_SESSION['patID'];
ini_set('display_errors', 1); // Turn on error displaying
error_reporting(E_ALL);     // Report all PHP errors


include 'connectors/connectDB.php';

// echo "connected<br>";

$sql = "SELECT fname FROM patient WHERE patientID = '$patientID' ";
echo "work";
$result = $link->query($sql);
echo "work2";
if ($result) {
    if ($result->num_rows > 0) {
        echo "fnames :<br>";
        while($row = $result->fetch_assoc()) {
            echo htmlspecialchars($row["fname"]) . "<br>";
        }
    } else {
        echo "nothing found";
    }
    $result->free();
} else {
    echo "error (" . $link->errno . ") " . $link->error;
}

// $link->close();

// echo "Connection closed.";

session_write_close(); //used to allow other sessions to be opened 

?>