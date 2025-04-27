<?php
session_start();
$patientID = $_SESSION['patID'];
echo $patientID;

include 'connectDB.php';

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
    }
    if (!$stmt->execute()) {
        die("execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

function add($result, $list = [])
{
    if ($result instanceof mysqli_result) {
        while ($row = $result->fetch_assoc()) {
            $list[] = $row;
        }
    } else {
        echo "some error";
    }
}

//patient info
$result = sqlExecute($link, "SELECT patientID, fname, lname, date_of_birth, address, provider FROM patient WHERE patientID = ?", [$patientID]);
echo"patientid from query: ".$patientID.'<br>';
var_dump($result);
if ($result && $result->num_rows > 0) {
    echo'patientbefore<br>';
    var_dump($patientData);
    echo'patientafter<br>';
    $patientData = $result->fetch_assoc();
    echo'<br>';
    var_dump($patientData);
} else {
    echo "patient retrieve error" . htmlspecialchars($patientID);
}
$result->free();

//reaction
$result = sqlExecute($link, "SELECT reaction_origin, reaction, start_date, end_date FROM adversereactions WHERE patientID = ?", [$patientID]);

if ($result instanceof mysqli_result) {
    while ($row = $result->fetch_assoc()) {
        $adverseReactions[] = $row;
    }
} else {
    echo "some error";
}
$result->free();

//condition
$result = sqlExecute($link, "SELECT condit_name, condit_start, condit_end, clinicalID, medicationID FROM conditions WHERE patientID = ?", [$patientID]);
if ($result instanceof mysqli_result) {
    while ($row = $result->fetch_assoc()) {
        $conditions[] = $row;
        // safeEcho($row);
    }
} else {
    echo "some error";
}
$result->free();

//medications
$result = sqlExecute($link, "SELECT med_name, dosage, med_start, med_end, episodeID FROM medication WHERE patientID = ?", [$patientID]);
if ($result instanceof mysqli_result) {
    while ($row = $result->fetch_assoc()) {
        $medications[] = $row;
    }
} else {
    echo "some error";
}
$result->free();

//vaccinations
$result = sqlExecute($link, "SELECT v.vaccination_name, v.vaccination_start, v.vaccination_end, CONCAT(ms.fname, ' ', ms.lname) AS staff_name FROM vaccinations v LEFT JOIN medicalstaff ms ON v.staffID = ms.staffID WHERE v.patientID = ?", [$patientID]);
if ($result instanceof mysqli_result) {
    while ($row = $result->fetch_assoc()) {
        $vaccinations[] = $row;
    }
} else {
    echo "some error";
}
$result->free();

//episodes
$result = sqlExecute($link, "SELECT e.episodeID, e.episode_date, CONCAT(ms.fname, ' ', ms.lname) AS staff_name FROM episode e LEFT JOIN medicalstaff ms ON e.staffID = ms.staffID Where e.patientID = ? ORDER BY e.episode_date DESC", [$patientID]);
if ($result instanceof mysqli_result) {
    while ($row = $result->fetch_assoc()) {
        $episodes[] = $row;
    }
} else {
    echo "some error";
}
$result->free();

//clinical
$result = sqlExecute($link, "SELECT cd.clinicalID, cd.episodeID, cd.proced_done, cd.diagnosis, e.episode_date, CONCAT(ms.fname, ' ', ms.lname) as staff_name
FROM clinicaldata cd
JOIN episode e ON cd.episodeID = e.episodeID
LEFT JOIN medicalstaff ms ON e.staffID = ms.staffID
WHERE e.patientID = ?
ORDER BY e.episode_date DESC, cd.clinicalID ASC", [$patientID]);
if ($result instanceof mysqli_result) {
    while ($row = $result->fetch_assoc()) {
        $clinicalEntries[] = $row;
    }
} else {
    echo "some error";
}
$result->free();

$updatedEpisodes = [];
foreach ($episodes as $episode) {
    $clinicalSummaries = [];
    foreach ($clinicalEntries as $entry) {
        if($entry['episodeID'] == $episode['episodeID']) {
            $summaryParts = [];
            if(!empty($entry['proced_done'])) {
                $summaryParts[] = "Procedure: ".htmlspecialchars($entry["proced_done"], ENT_QUOTES,'UTF-8');
            }
            if(!empty($entry['diagnosis'])) {
                $summaryParts[] = "Diagnosis: ".htmlspecialchars($entry["diagnosis"], ENT_QUOTES,'UTF-8');
            }

            if(!empty($summaryParts)) {
                $summaryString = implode(' - ', $summaryParts);
                $clinicalSummaries[] = $summaryString;
            }
        }
    }
    $episode['clinical_summary'] = !empty($clinicalSummaries) ? implode('<br>', $clinicalSummaries) :'None';
    $updatedEpisodes[] = $episode;
}
$episodes = $updatedEpisodes;
//     if ($resultClinical instanceof mysqli_result) {
//         while ($clinicalRow = $resultClinical->fetch_assoc()) {
//         }
//         $resultClinical->free();
//     }
//     $episode['clinical_summary'] = !empty($clinicalSummaries) ? implode('<br>', $clinicalSummaries) : 'No clinical data recorded';
//     $updatedEpisodes[] = $episode;
// }
// $episodes = $updatedEpisodes;

$link->close();

function formatDate($dateString, $format = 'd-m-Y')
{
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

function safeEcho($value, $default = 'N/A')
{
    echo (!empty($value) || $value === '0') ? htmlspecialchars($value) : $default;
}
?>