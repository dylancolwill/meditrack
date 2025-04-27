<?php
session_start();
$patientID = $_SESSION['patID'];
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


//Gets episode ID utilising patientID
$edpsql ="SELECT episodeID FROM episode
WHERE patientID = '$patientID'
ORDER BY episode_date desc
LIMIT 1";

$result = $link->query($edpsql);

if ($result) {
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $episodeID = ($row["episodeID"]);
        }
    } else {
        echo "nothing found";
    }
    $result->free();
} else {
    echo "error (" . $link->errno . ") " . $link->error;
}


//gets clincal data
function clincalCall($passthrough, $link){
$clinsql ="SELECT clinicalID FROM episode
WHERE patientID = '$passthrough'
ORDER BY proced_date desc
LIMIT 1";

$result = $link->query($clinsql);

if ($result) {
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $clinicalID = ($row["clinicalID"]);
            return $clinicalID;
        }
    } else {
        echo "nothing found";
    }
    $result->free();
} else {
    echo "error (" . $link->errno . ") " . $link->error;
}
}


function medicalCall($passthrough, $link){
    $medisql ="SELECT medicationID FROM episode
    WHERE patientID = '$passthrough'
    ORDER BY med_start desc
    LIMIT 1";
    
    $result = $link->query($medisql);
    
    if ($result) {
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $medicationID = ($row["clinicalID"]);
                return $medicationID;
            }
        } else {
            echo "nothing found";
        }
        $result->free();
    } else {
        echo "error (" . $link->errno . ") " . $link->error;
    }
    }

//Determines what entity the user wants to update
$catagories = $_POST['catagories'];

switch ($catagories) {
    case 'reaction': //Double check to see if this will work.
        $reaction_origin = $_POST['reaction_origin'];
        $reactionInfo = $_POST['reactionInfo'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        //var_dump($reaction_origin, $reaction_info, $start_date, $end_date, $patientID); 
        $sql = "INSERT INTO `adversereactions` (reaction_origin, reaction, start_date, end_date, patientID) VALUES ('$reaction_origin', '$reactionInfo', '$start_date', '$end_date', '$patientID')";
        push($sql, $link, $patientID);

        break;
    case 'vaccination':
        $vaccineName = $_POST['vaccineName'];
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        //var_dump($vaccineName, $startDate, $endDate,  $patientID);
        $sql = "INSERT INTO `vaccinations` (vaccination_name, vaccination_start, vaccination_end, patientID) VALUES ('$vaccineName', '$startDate', '$endDate', '$patientID')";
        push($sql, $link, $patientID);

        break;
    case 'medication':
        $med_name = $_POST['med_name'];
        $med_start = $_POST['med_start'];
        $med_end = $_POST['med_end'];
        $dosage = $_POST['dosage'];
       //var dump may not be needed this left commented to test var_dump($med_name, $med_start, $med_end, $dosage);
        $sql = "INSERT INTO `medication` (med_name, med_start, med_end, dosage, patientID, episodeID) VALUES ('$vaccineName', '$startDate', '$endDate', '$dosage', '$patientID', '$episodeID')";
        push($sql, $link, $patientID);

        break;
    case 'condition':
        $clinical = clincalCall($patientID, $link);
        $medication = medicalCall($patientID, $link);
        $condit_name = $_POST['condit_name'];
        $condit_start = $_POST['condit_start'];
        $condit_end = $_POST['condit_end'];
        //var_dump($condit_name, $condit_start, $condit_end, $clinical, $patientID, $medication);
        $sql = "INSERT INTO `conditions` (condit_name, condit_start, condit_end, clinicalID, patientID, medicationID) VALUES ('$condit_name', '$condit_start', '$condit_end', '$clinical', '$patientID', '$medication')";
        push($sql, $link, $patientID);

        break;

    case 'clinical':
        $proced_date = $_POST['proced_date'];
        $proced_done = $_POST['proced_done'];
        $preced_result = $_POST['preced_result'];
        $signs = $_POST['signs'];
        $diagnosis = $_POST['diagnosis'];
        //var_dump($proced_date, proced_done, $proced_result, $signs, $diagnosis, $episodeID);
        $sql = "INSERT INTO `clinicaldata` (proced_date, proced_done, preced_result, signs, diagnosis, episodeID) VALUES ('$proced_date', '$proced_done', '$preced_result', '$signs', '$diagnosis', '$episodeID')";
        push($sql, $link, $patientID);
        break;
    default:
      echo "error";
        break;

    //return $sql;

  }
  
  //Function to execute the SQL
  function push($sql, $link, $patientID){
  $stmt = $link->prepare($sql);

  if ($stmt === false) {
      echo "error" . htmlspecialchars(($link->error));
  } else {
      //$stmt->bind_param("sii", $episodeDate, $patientID, $staffID);
      $stmt->execute();
  }
  redirect("patientInfo.php", 301);
  exit;

  }





// echo "work";
// $sql = "SELECT fname FROM patient";
// $result = $link->query($sql);
// echo "work2";
// if ($result) {
//     if ($result->num_rows > 0) {
//         echo "fnames :<br>";
//         while($row = $result->fetch_assoc()) {
//             echo htmlspecialchars($row["fname"]) . "<br>";
//         }
//     } else {
//         echo "nothign found";
//     }
//     $result->free();
// } else {
//     echo "error (" . $link->errno . ") " . $link->error;
// }

// $link->close();

// echo "Connection closed.";


$link->close();
?>