<?php
session_start();
$patientID = $_SESSION['patID'];
echo "patientid:".$patientID;


include 'php/connectDB.php'; 

$patientData = [];
$adverseReactions = [];
$conditions = [];
$medications = [];
$vaccinations = [];
$episodes = [];

function sqlExecute($link, $sql, $params = [])
{
    $stmt = $link->prepare($sql);
    if (!$stmt) {
        die("prepare failed: (" . $link->errno . ") " . $link->error);
    }
    if (!empty([$params]) && !empty("i")) {
        $stmt->bind_param("i", ...[$params]);
        echo" testexec<br>";
    }
    if (!$stmt->execute()) {
        die("execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

function addToList($result, $list = [])
{
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $list[] = $row;
        }
    }
}

//patient info
$result = sqlExecute($link, "SELECT patientID, fname, lname, address, provider FROM patient WHERE patientID = ?", [$patientID]);
if ($result && $result->num_rows > 0) {
    $patientData = $result->fetch_assoc();
} else {
    echo "patient retrieve error" . htmlspecialchars($patientID);
}
$result->free();

//reaction
$result = sqlExecute($link, "SELECT reaction_origin, reaction, start_date, end_date FROM adversereactions WHERE patientID = ?", [$patientID]);
echo $patientID;
// addToList($result, $adverseReactions);
if ($result && $result->num_rows > 0) {
    $adverseReactions = $result->fetch_assoc();
} else {
    echo "patient retrieve error" . htmlspecialchars($patientID);
}
$result->free();

//condition
$result = sqlExecute($link, "SELECT condit_name, condit_start, condit_end, clinicalID, medicationID FROM conditions WHERE patientID = ?", [$patientID]);
addToList($result, $conditions);
$result->free();

//medications
$result = sqlExecute($link, "SELECT med_name, dosage, med_start, med_end, episodeID FROM medication WHERE patientID = ?", [$patientID]);
addToList($result, $medications);
$result->free();

//vaccinations
$result = sqlExecute($link, "SELECT v.vaccination_name, v.vaccination_start, v.vaccination_end, CONCAT(ms.fname, ' ', ms.lname) AS staff_name FROM vaccinations v LEFT JOIN medicalstaff ms ON v.staffID = ms.staffID WHERE v.patientID = ?", [$patientID]);
addToList($result, $vaccinations);
$result->free();

//episodes
$result = sqlExecute($link, "SELECT e.episodeID, e.episode_date, CONCAT(ms.fname, ' ', ms.lname) AS staff_name FROM episode e LEFT JOIN medicalstaff ms ON e.staffID = ms.staffID Where e.patientID = ? ORDER BY e.episode_date DESC", [$patientID]);
addToList($result, $episodes);
$result->free();

$updatedEpisodes = [];
foreach ($episodes as $episode) { 
    $sqlClinical = "SELECT proced_done, diagnosis FROM clinicaldata WHERE episodeID = ?";
    $resultClinical = sqlExecute($link, $sqlClinical, $episode['episodeID']); 
    $clinicalSummaries = [];
    if ($resultClinical instanceof mysqli_result) {
        while ($clinicalRow = $resultClinical->fetch_assoc()) { 
        }
        $resultClinical->free(); 
    }
    $episode['clinical_summary'] = !empty($clinicalSummaries) ? implode('<br>', $clinicalSummaries) : 'No clinical data recorded';
    $updatedEpisodes[] = $episode;
}
$episodes = $updatedEpisodes;

$link->close();


foreach ($adverseReactions as $row) {
    echo $row;
    echo"testHERE<br>";
}



function formatDate($dateString, $format = 'Y-m-d') {
    if (empty($dateString)) {
        return 'N/A';
    }
    try {
        $date = new DateTime($dateString);
        return $date->format($format);
    } catch (Exception $e) {
        return 'Invalid Date'; 
    }
}

function safeEcho($value, $default = 'N/A') {
    echo (!empty($value) || $value === '0') ? htmlspecialchars($value) : $default; 
}

?>