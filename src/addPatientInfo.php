<?php include 'connectors/connectAddInfo.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/patientInfoStyle.css">

    <title>MediTrack</title>
</head>

<body>
<form method="POST" action="connectors/connectAddInfo.php">
    <label for="catagories">Select catagory:</label>

    <select onchange="onSelect()" name="catagories" id="catagories">
        <option value="reaction">Reaction</option>
        <option value="condition">Condition</option>
        <option value="medication">Medication</option>
        <option value="vaccination">Vaccination</option>
        <option value="clinical">Clinical Data</option>
    </select>

    <div id="data-sections-container">
        <section id="reaction" class="catagories-content">
            <h2>Reaction</h2>
            <article>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Reaction</th>
                            <th>Allergen</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                    </thead>
                    <tbody id="reaction-tbody">
                        <tr>
                            <td><input type="text" placeholder="Reaction" id="reactionInfo"></td>
                            <td><input type="text" placeholder="Reaction Trigger" id="reaction_origin"></td>
                            <td><input type="date" id="start_date"></td>
                            <td><input type="date" id="end_date"></td>

                        </tr>
                    </tbody>
                </table>
                <p id="no-reaction-msg" style="display: none;">No adverse
                    reactions recorded.</p>
            </article>
        </section>

        <section id="condition" class="catagories-content">
            <h2>Conditions</h2>
            <article>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Condition</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Clinical Data</th>
                            <th>Medication</th>
                        </tr>
                    </thead>
                    <tbody id="condition-tbody">
                        <tr>
                            <td><input type="text" placeholder="Condition Name" id="condit_name"></td>
                            <td><input type="date" id="condit_start"></td>
                            <td><input type="date" id="condit_end"></td>
                            <td><button type="button" class="add-row-btn">Add</button></td>
                            <td><button type="button" class="add-row-btn">Add</button></td>
                        </tr>
                    </tbody>
                </table>
                <p id="no-condition-msg" style="display: none;">No
                    conditions recorded.</p>
            </article>
        </section>

        <section id="medication" class="catagories-content">
            <h2>Medications</h2>
            <article>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Medication</th>
                            <th>Dosage</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                        </tr>
                    </thead>
                    <tbody id="medication-tbody">
                        <tr>
                            <td><input type="text" placeholder="Medication Name" id="med_name"></td>
                            <td><input type="text" placeholder="Dosage Amount" id="dosage"></td>
                            <td><input type="date" id="med_start"></td>
                            <td><input type="date" id="med_end"></td>
                        </tr>
                    </tbody>
                </table>
                <p id="no-medication-msg" style="display: none;">No
                    medications recorded.</p>
            </article>
        </section>

        <section id="vaccination" class="catagories-content">
            <h2>Vaccinations</h2>
            <article>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Vaccine</th>
                            <th>Date Given</th>
                            <th>End Date</th>
                        </tr>
                    </thead>
                    <tbody id="vaccination-tbody">
                        <tr>
                            <td><input type="text" placeholder="Vaccine Name" id="vaccineName"></td>
                            <td><input type="date" id="startDate"></td>
                            <td><input type="date" id="endDate"></td>
                        </tr>
                    </tbody>
                </table>
                <p id="no-vaccination-msg" style="display: none;">No
                    vaccinations recorded for this patient.</p>
            </article>
        </section>

        <section id="clinical" class="catagories-content">
            <h2>Clinical Data</h2>
            <table border="1">
                <tr>
                    <th>Procedure Date</th>
                    <th>Procedures Done</th>
                    <th>Procedures Results</th>
                    <th>Observations</th>
                    <th>Diagnosis</th>
                </tr>
                <tbody id="clinical-tbody">
                    <tr>
                        <td><input type="date" id="proced_date"></td>
                        <td><input type="text" placeholder="Procedures" id="proced_done"></td>
                        <td><input type="text" placeholder="Results" id="proced_result"></td>
                        <td><input type="text" placeholder="Observations" id="signs"></td>
                        <td><input type="text" placeholder="Diagnosis" id="diagnosis"></td>
                    </tr>
                </tbody>
            </table>
            <p id="no-clinical-msg" style="display: none;">No clinical data
                recorded for this patient.</p>
        </section>

    </div>
    <div>
        <input type="submit" id="save_button" value="submit">

    </div>
    </form>

    <script src="js/addPatientInfoScript.js"></script>
    <script src="js/patientScripts.js"></script>
    <script src="js/tempDB.js"></script>

</body>

</html>
