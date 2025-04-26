<?php
$patientData = [];
$adverseReactions = [];
$conditions = [];
$medications = [];
$vaccinations = [];
$episodes = [];

$_SESSION['patID'] = $patientID;

function sqlExecute($link, $sql, $params = $patientID)
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

function addToList($result, $list = [])
{
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $list[] = $row;
        }
    }
    $result->free();
}

//patient info
$result = sqlExecute($link, "SELECT patientID, fname, lname, address, provider FROM patient WHERE patientID = ?");
if ($result && $result->num_rows > 0) {
    $patientData = $result->fetch_assoc();
} else {
    echo "patient retrieve error" . htmlspecialchars($patientID);
}
$result->free();

//reaction
$result = sqlExecute($link, "SELECT reaction_origin, reaction, start_date, end_date FROM adversereactions WHERE patientID = ?");
addToList($result, $adverseReactions);


//condition
$result = sqlExecute($link, "SELECT condit_name, condit_start, condit_end, clinicalID, medicationID FROM conditions WHERE patientID = ?");
addToList($result, $conditions);

//medications
$result = sqlExecute($link, "SELECT med_name, dosage, med_start, med_end, episodeID FROM medication WHERE patientID = ?");
addToList($result, $medications);

//vaccinations
$result = sqlExecute($link, "SELECT v.vaccination_name, v.vaccination_start, v.vaccination_end, CONCAT(ms.fname, ' ', ms.lname) AS staff_name FROM vaccinations v LEFT JOIN medicalstaff ms ON v.staffID = ms.staffID WHERE v.patientID = ?");
addToList($result, $vaccinations);

//episodes
$result = sqlExecute($link, "SELECT e.episodeID, e.episode_date, CONCAT(ms.fname, ' ', ms.lname) AS staff_name FROM episode e LEFT JOIN medicalstaff ms ON e.staffID = ms.staffID Where e.patientID = ? ORDER BY e.episode_date DESC");
addToList($result, $vaccinations);

if ($resultEpisodes) {
    while ($row = $resultEpisodes->fetch_assoc()) {
        // Now fetch related clinical data for *this* episode
        $sqlClinical = "SELECT proced_done, diagnosis FROM clinicaldata WHERE episodeID = ?";
        $resultClinical = sqlExecute($link, $sqlClinical, [$row['episodeID']]);
        $clinicalSummaries = [];
        if ($resultClinical) {
            while ($clinicalRow = $resultClinical->fetch_assoc()) {
                $summary = [];
                if (!empty($clinicalRow['proced_done']))
                    $summary[] = "Procedure: " . htmlspecialchars($clinicalRow['proced_done']);
                if (!empty($clinicalRow['diagnosis']))
                    $summary[] = "Diagnosis: " . htmlspecialchars($clinicalRow['diagnosis']);
                if (!empty($summary))
                    $clinicalSummaries[] = implode('; ', $summary);
            }
            $resultClinical->free();
        }
        // Add clinical summary (could be multiple lines) to the episode data
        $row['clinical_summary'] = !empty($clinicalSummaries) ? implode('<br>', $clinicalSummaries) : 'No clinical data recorded';
        $episodes[] = $row;
    }
    $resultEpisodes->free();
}
$link->close();

foreach ($adverseReactions as $row) {
    echo $row;
}
?>