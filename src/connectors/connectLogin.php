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

$uName = $_POST['uName'];
$pswd = $_POST['pswd'];


$hashedPass = password_hash("$pswd", PASSWORD_DEFAULT);


$sql = "INSERT INTO `loginInformation` (userName, pwords) VALUES ('$uName', '$hashedPass')";
$stmt = $link->prepare($sql);
$stmt-> execute();
 

//change this back in a sec

// $sql = "SELECT pwords FROM loginInformation WHERE userName = '$uName' ";
// echo "work";
// $result = $link->query($sql);
// echo "work2";
// if ($result) {
//     if ($result->num_rows > 0) {
//         while ($row = $result->fetch_assoc()) {
//             $passwordVerify = $row["pwords"];
//         }
//     } else {
//         echo "nothing found";
//     }
//     $result->free();
// } else {
//     echo "error (" . $link->errno . ") " . $link->error;
// }

// if ($passwordVerify == "$hashedPass") {
//     redirect("patientSearch.php", 301);

// }



?>