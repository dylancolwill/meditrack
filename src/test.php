<?php
// Make sure error reporting is on for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connectors/connectDB.php'; // Assumes $link (mysqli object) is created here

// Check connection (Good Practice!)
if ($link->connect_error) {
    die("Connection failed: (" . $link->errno . ") " . $link->connect_error);
}
echo "Connected successfully<br><hr>";

$patientIdToDelete = 5;

$sql = "DELETE FROM episode WHERE patientID = ?";

$stmt = $link->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $patientIdToDelete);

    if ($stmt->execute()) {
        $affected_rows = $stmt->affected_rows;
        echo "Successfully deleted " . $affected_rows . " episode(s) for patient ID " . $patientIdToDelete . ".";
    } else {
        echo "Error executing deletion: (" . $stmt->errno . ") " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Error preparing deletion statement: (" . $link->errno . ") " . $link->error;
}

$link->close();
echo "<br>Connection closed.";