<?php

include 'connectors/connectDB.php';

$uName = $_POST['uName'];
$pswd = $_POST['pswd'];


//$hashedPass = password_hash("$pswd", PASSWORD_DEFAULT);
 
$sql = "SELECT pwords FROM loginInformation WHERE userName = '$uName' ";

$result = $link->query($sql);





if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $passwordVerify = $row["pwords"];
            if (password_verify($pswd,$passwordVerify)) {
                echo "worked";
                redirect("patientSearch.php", 301);
            }else{
                echo "fail";
            }
        }

        
        
    } else {
        echo "nothing found";
    }
    $result->free();
} else {
    echo "error (" . $link->errno . ") " . $link->error;
}

function redirect($url, $statusCode = 301) {
        
        
    header("Location: " . $url, true, $statusCode);
    exit();
}

//Code to add login passwords
// $sql = "INSERT INTO `loginInformation` (userName, pwords) VALUES ('$uName', '$hashedPass')";
// $stmt = $link->prepare($sql);
// $stmt->execute();

?>