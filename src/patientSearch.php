<?php
session_start();
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

$searchTerm = '';
if (isset($_GET['search'])) {
    $searchTerm = trim($_GET['search']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" type="text/css" href="css/patientSearchStyle.css" />
</head>

<body>
    <div class="container" id="padding">
        <div class="main" id="searchBacking">
            <div class="row">
            </div>
            <div class="row">
                <form class="patientSearchForm">
                    <input type="text" placeholder="Search.." name="search" id="pSearch"
                        value="<?php echo htmlspecialchars($searchTerm); ?>">
                    <button type=" submit"><i class="fa fa-search" id="pButton"></i></button>
                </form>

            </div>
            <div class="row">
                <div class="col-lg-3 col-sm-3 col-md-3"></div>
                <button onclick="document.location='newPatient.html'" type="submit" id="newPatientButton"
                    class="col-lg-6 col-sm-5 col-md-6">New Patient</i></button>
            </div>
        </div>
    </div>

    <?php

    $patientID=0;
    //search patient
    if (!empty($searchTerm)) {
        // echo htmlspecialchars($searchTerm);
    
        $searchTerm = trim($searchTerm);

        $nameParts = explode(' ', $searchTerm);

        if (count($nameParts) >= 2) {
            $firstName = $nameParts[0];
            $lastName = $nameParts[1];
        }

        //search patient
        $sql = "SELECT patientID, `fname`, `lname`
            FROM `patient`
            WHERE LOWER(`fname`) = LOWER(?)
              AND LOWER(`lname`) = LOWER(?)";

        $stmt = $link->prepare($sql);

        if ($stmt === false) {
            echo htmlspecialchars(($link->error));
        }

        $stmt->bind_param("ss", $firstName, $lastName);

        if (!$stmt->execute()) {
            echo htmlspecialchars(($stmt->error));
        }

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<br>";
            echo "searched: " . $searchTerm;
            while ($row = $result->fetch_assoc()) {
                $patientID = $row["patientID"];
                echo "<br>id " . htmlspecialchars($patientID) . " - ";
                echo htmlspecialchars($row['fname']) . " " . htmlspecialchars($row['lname']);
            }

            $staffID = 1; //CHANGE !!!!
            $episodeDate = date('Y-m-d H:i:s');

            var_dump($staffID, $patientID, $episodeDate);

            //create episode
            $sql = "INSERT INTO `episode` (`episode_date`, `patientID`, `staffID`) VALUES (?, ?,?)";

            $stmt = $link->prepare($sql);

            if ($stmt === false) {
                echo "error" . htmlspecialchars(($link->error));
            } else {
                $stmt->bind_param("sii", $episodeDate, $patientID, $staffID);
                $stmt->execute();
            }
            $_SESSION['patID'] = $patientID;
            session_write_close();
            // header("Location: patientSearch.php");
            redirect("patientInfo.php", 301);
            exit;
        } else {
            echo "no patient found '" . htmlspecialchars($searchTerm);
        }

        $stmt->close();
    }

    $link->close();
    
    
    function redirect($url, $statusCode = 301) {
        
        
        header("Location: " . $url, true, $statusCode);
        exit();
    }

    
    ?>
</body>

</html>