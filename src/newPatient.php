<?php
include 'php/connectDB.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['fname'], $_POST['lname'], $_POST['patientAddress'], $_POST['patientHealthcareProvider'], $_POST['dateOfBirth'])) {

        $fname = trim($_POST['fname']);
        $lname = trim($_POST['lname']);
        $address = trim($_POST['patientAddress']);
        $provider = trim($_POST['patientHealthcareProvider']);
        $dob = trim($_POST['dateOfBirth']);

        if (empty($fname) || empty($lname) || empty($address) || empty($provider) || empty($dob)) {
            $message = 'entries missing';
        } else {

            $sql = "INSERT INTO patient (fname, lname, address, provider, date_of_birth) VALUES (?, ?, ?, ?, ?)";

            if ($stmt = $link->prepare($sql)) {
                $stmt->bind_param("sssss", $fname, $lname, $address, $provider, $dob);

                if (!$stmt->execute()) {
                    $message = 'error inserting';
                }

                $stmt->close();
            } else {
                $message = 'error ' . $link->error;
            }
        }
    } else {
        $message = 'entries missing';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/patientInfoStyle.css">

    <title>MediTrack</title>
</head>

<body>
    <section id="patient">
        <h2>New Patient</h2>
        <?php if (!empty($message)): ?>
            <div
                class="message <?php echo strpos($message, 'Error') !== false || strpos($message, 'fill') !== false || strpos($message, 'incomplete') !== false ? 'error' : 'success'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <article>
                <table border="1">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Address</th>
                            <th>Healthcare Provider</th>
                            <th>Date of Birth</th>
                        </tr>
                    </thead>
                    <tbody id="patient-tbody">
                        <tr>
                            <td><input type="text" id="fname" name="fname" placeholder="First" required></td>
                            <td><input type="text" id="lname" name="lname" placeholder="Last" required></td>
                            <td><input type="text" id="patientAddress" name="patientAddress" placeholder="Address"
                                    required></td>
                            <td><input type="text" id="patientHealthcareProvider" name="patientHealthcareProvider"
                                    placeholder="Provider" required></td>
                            <td><input type="date" id="dateOfBirth" name="dateOfBirth" placeholder="YYYY-MM-DD"
                                    required></td>
                        </tr>
                    </tbody>
                </table>
            </article>
            <div>
                <button type="submit">Save Patient</button>
            </div>
        </form>
    </section>

    <script src="js/newPatient.js"></script>
    <script src="js/patientScripts.js"></script>
    <script src="js/tempDB.js"></script>
</body>

</html>

<?php
if ($link) {
    $link->close();
}
?>