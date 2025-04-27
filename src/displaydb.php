<?php
include 'connectors/connectDB.php';

echo "connected<br>";

$sql = "SELECT * FROM clinicaldata";
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
