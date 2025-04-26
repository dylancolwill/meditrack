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


//Determines what entity the user wants to update
$catagories = $_POST["catagories"];

switch ($catagories) {
    case reaction: //Double check to see if this will work.
        $reaction_origin = $_POST["reaction_origin"];
        $reactionInfo = $_POST["reactionInfo"];
        $start_date = $_POST["start_date"];
        $end_date = $_POST["end_date"];

        var_dump($reaction_origin, $reaction_info, $start_date, $end_date);
        
        $sql = "INSERT INTO `adversereactions` (reaction_origin, reaction, start_date, end_date) VALUES ('$reaction_origin', '$reactionInfo', '$start_date', '$end_date')";
      break;
    case vaccination:
        $vaccineName = $_POST["vaccineName"];
        $startDate = $_POST["startDate"];
        $endDate = $_POST["endDate"];
       
        $sql = "INSERT INTO `vaccinations` (vaccination_name, vaccination_start, vaccination_end) VALUES ('$vaccineName', '$startDate', '$endDate')";
        var_dump($vaccineName, $startDate, $endDate);
        break;
    case medication:
      //code block
      break;
    case vaccination:

        break;
    case episode:
        break;

    case clinical:
        break;
    default:
      //code block
  }
  
  



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