<?php
session_start();
$patientID = $_SESSION['patID'];
ini_set('display_errors', 1); // Turn on error displaying
error_reporting(E_ALL);      // Report all PHP errors

include 'connectDB.php';

// echo "connected<br>";

//Gets episode ID utilising patientID
$edpsql = "SELECT episodeID FROM episode
WHERE patientID = '$patientID'
ORDER BY episode_date desc
LIMIT 1";

$result = $link->query($edpsql);
$episodeID = null; // Initialize episodeID

if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $episodeID = ($row["episodeID"]);
        }
    } else {
        echo "nothing found"; // Consider how to handle if no episode exists
    }
    $result->free();
} else {
    echo "error (" . $link->errno . ") " . $link->error;
}


//gets clincal data
function clincalCall($passthrough, $link)
{
    if ($passthrough === null) return null; // Added check
    $clinsql = "SELECT clinicalID FROM clinicaldata
WHERE episodeID = '$passthrough'
ORDER BY proced_date desc
LIMIT 1";

    $result = $link->query($clinsql);
    $clinicalID = null; // Initialize

    if ($result) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                 $clinicalID = ($row["clinicalID"]);
                 // return $clinicalID; // Should return after loop
            }
        } else {
            echo "nothing found";
        }
        $result->free();
        return $clinicalID; // Return found ID or null
    } else {
        echo "error (" . $link->errno . ") " . $link->error;
        return null; // Return null on error
    }
}

//medical
function medicalCall($passthrough, $link)
{
    $medisql = "SELECT medicationID FROM medication
    WHERE patientID = '$passthrough'
    ORDER BY med_start desc
    LIMIT 1";

    $result = $link->query($medisql);
    $medicationID = null; // Initialize

    if ($result) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $medicationID = ($row["medicationID"]);
                // return $medicationID; // Should return after loop
            }
        } else {
            echo "nothing found";
        }
        $result->free();
        return $medicationID; // Return found ID or null
    } else {
        echo "error (" . $link->errno . ") " . $link->error;
        return null; // Return null on error
    }
}

//Determines what entity the user wants to update
$catagories = $_POST['catagories'];

switch ($catagories) {
    case 'reaction': //Double check to see if this will work.
        $reaction_origin = $_POST['reaction_origin'];
        $reactionInfo = $_POST['reactionInfo'];
        $start_date = $_POST['start_date'];
        //$end_date = $_POST['end_date']; // Keep original commented line if desired
        // Handle empty end date: If not empty, quote it; otherwise, use the literal NULL
        $end_date_sql = !empty($_POST['end_date']) ? "'" . $link->real_escape_string($_POST['end_date']) . "'" : "NULL";

        //var_dump($reaction_origin, $reaction_info, $start_date, $end_date, $patientID);
        // Use the prepared $end_date_sql variable in the query
        $sql = "INSERT INTO `adversereactions` (reaction_origin, reaction, start_date, end_date, patientID) VALUES ('{$link->real_escape_string($reaction_origin)}', '{$link->real_escape_string($reactionInfo)}', '{$link->real_escape_string($start_date)}', $end_date_sql, '{$link->real_escape_string($patientID)}')";
        push($sql, $link, $patientID);

        break; // Added missing break
    case 'vaccination':
        $vaccineName = $_POST['vaccineName'];
        $startDate = $_POST['startDate'];
        //$endDate = $_POST['endDate']; // Keep original commented line if desired
        // Handle empty end date
        $endDate_sql = !empty($_POST['endDate']) ? "'" . $link->real_escape_string($_POST['endDate']) . "'" : "NULL";

        //var_dump($vaccineName, $startDate, $endDate,  $patientID);
        // Use the prepared $endDate_sql variable
        $sql = "INSERT INTO `vaccinations` (vaccination_name, vaccination_start, vaccination_end, patientID) VALUES ('{$link->real_escape_string($vaccineName)}', '{$link->real_escape_string($startDate)}', $endDate_sql, '{$link->real_escape_string($patientID)}')";
        push($sql, $link, $patientID);

        break; // Added missing break
    case 'medication':
        $med_name = $_POST['med_name'];
        // echo $med_name;

        $med_start = $_POST['med_start'];
        //$med_end = $_POST['med_end']; // Keep original commented line if desired
        $dosage = $_POST['dosage'];
        // Handle empty end date
        $med_end_sql = !empty($_POST['med_end']) ? "'" . $link->real_escape_string($_POST['med_end']) . "'" : "NULL";

        //var dump may not be needed this left commented to test var_dump($med_name, $med_start, $med_end, $dosage);
        if ($episodeID === null) { // Check if episodeID was found
             echo "Error: Cannot add medication without a valid episode.";
             // Consider adding an exit or redirect here
        } else {
            // Use the prepared $med_end_sql variable
            $sql = "INSERT INTO `medication` (med_name, med_start, med_end, dosage, patientID, episodeID) VALUES ('{$link->real_escape_string($med_name)}', '{$link->real_escape_string($med_start)}', $med_end_sql, '{$link->real_escape_string($dosage)}', '{$link->real_escape_string($patientID)}', '{$link->real_escape_string($episodeID)}')";
            push($sql, $link, $patientID);
        }
        break; // Added missing break
    case 'condition':
        $clinical = clincalCall($episodeID, $link);
        $medication = medicalCall($patientID, $link); // Assuming this is correct logic
        $condit_name = $_POST['condit_name'];
        $condit_start = $_POST['condit_start'];
        //$condit_end = $_POST['condit_end']; // Keep original commented line if desired
        // Handle empty end date
        $condit_end_sql = !empty($_POST['condit_end']) ? "'" . $link->real_escape_string($_POST['condit_end']) . "'" : "NULL";

        // Handle potentially NULL IDs for insertion
        $clinical_sql = ($clinical !== null) ? "'".$link->real_escape_string($clinical)."'" : "NULL";
        $medication_sql = ($medication !== null) ? "'".$link->real_escape_string($medication)."'" : "NULL";


        //var_dump($condit_name, $condit_start, $condit_end, $clinical, $patientID, $medication);
        // Use prepared variables $condit_end_sql, $clinical_sql, $medication_sql
        $sql = "INSERT INTO `conditions` (condit_name, condit_start, condit_end, clinicalID, patientID, medicationID) VALUES ('{$link->real_escape_string($condit_name)}', '{$link->real_escape_string($condit_start)}', $condit_end_sql, $clinical_sql, '{$link->real_escape_string($patientID)}', $medication_sql)";
        push($sql, $link, $patientID);

        break; // Added missing break

    case 'clinical':
        $proced_date = $_POST['proced_date'];
        $proced_done = $_POST['proced_done'];
        $preced_result = $_POST['preced_result'];
        $signs = $_POST['signs'];
        $diagnosis = $_POST['diagnosis'];
        //var_dump($proced_date, proced_done, $proced_result, $signs, $diagnosis, $episodeID);
         if ($episodeID === null) { // Check if episodeID was found
             echo "Error: Cannot add clinical data without a valid episode.";
             // Consider adding an exit or redirect here
        } else {
            $sql = "INSERT INTO `clinicaldata` (proced_date, proced_done, preced_result, signs, diagnosis, episodeID) VALUES ('{$link->real_escape_string($proced_date)}', '{$link->real_escape_string($proced_done)}', '{$link->real_escape_string($preced_result)}', '{$link->real_escape_string($signs)}', '{$link->real_escape_string($diagnosis)}', '{$link->real_escape_string($episodeID)}')";
            push($sql, $link, $patientID);
        }
        break; // Added missing break
    default:
        echo "error";
        break; // Added missing break

    //return $sql; // This was commented out, leaving it as is

}

//Function to execute the SQL
function push($sql, $link, $patientID) // $patientID might not be needed here if push is generic
{
    // The prepare/execute pattern here doesn't prevent SQL injection
    // because the $sql string already contains the concatenated data.
    // It essentially just checks syntax before executing the raw string.
    $stmt = $link->prepare($sql);

    if ($stmt === false) {
        echo "error" . htmlspecialchars(($link->error));
        // Consider not redirecting on error
    } else {
        //$stmt->bind_param("sii", $episodeDate, $patientID, $staffID); // Original comment
        $stmt->execute();
        $stmt->close(); // Close statement after execution
        // Redirect only on success?
        redirect("patientInfo.php", 301);
        // exit; // exit() is called inside redirect()
    }
    // If prepare failed, the redirect below will still happen
    // redirect("patientInfo.php", 301); // Maybe remove this if only redirecting on success
}


function redirect($url, $statusCode = 301)
{
    header("Location: " . $url, true, $statusCode);
    exit(); // Crucial: stop script execution after redirect header
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

// $link->close(); // Should be closed after all operations are done

// echo "Connection closed.";


$link->close(); // Close connection at the end of the script
?>