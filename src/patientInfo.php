<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// session_start();
include 'php/queryPatientInfo.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediTrack - <?php safeEcho($patientData['fname'] . ' ' . $patientData['lname'], 'Patient Info'); ?></title>
    <link rel="stylesheet" href="css/patientInfoStyle.css">
</head>

<body>
    <div class="container">
        <header class="header-grid">
            <h1 id="header-patient-name">
                <?php safeEcho($patientData['fname'] . ' ' . $patientData['lname'], 'Patient Information'); ?></h1>
            <a href="./addPatientInfo.php">
                <button id="add-info-button" title="Add New Information">+</button>
            </a>
        </header>

        <main class="content-grid">
            <section id="patient-info-box" class="grid-item patient-info">
                <h2>Patient Information</h2>
                <article>
                    <p><strong>ID:</strong> <span id="patient-id"><?php safeEcho($patientData['patientID']); ?></span>
                    </p>
                    <p><strong>Name:</strong> <span
                            id="patient-name"><?php safeEcho($patientData['fname'] . ' ' . $patientData['lname']); ?></span>
                    </p>
                    <p><strong>Date of Birth:</strong> <span id="patient-birthdate"><?php echo(formatDate($patientData['date_of_birth'])); ?></span></p>
                    <p><strong>Address:</strong> <span
                            id="patient-address"><?php safeEcho($patientData['address']); ?></span></p>
                    <p><strong>Billing Provider:</strong> <span
                            id="patient-billing"><?php safeEcho($patientData['provider']); ?></span></p>
                </article>
            </section>

            <section class="grid-item allergies">
                <h2>Reactions</h2>
                <article>
                    <?php if (empty($adverseReactions)): ?>
                        <p id="no-reactions-msg">No adverse reactions recorded.</p>
                    <?php else: ?>
                        <table border="1">
                            <thead>
                                <tr>
                                    <th>Reaction</th>
                                    <th>Origin (Medication)</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                </tr>
                            </thead>
                            <tbody id="adverse-reactions-tbody">
                                <?php var_dump ($adverseReactions); ?>
                                <?php foreach ($adverseReactions as $reaction): ?>
                                    <tr>
                                        <td><?php htmlspecialchars($reaction['reaction'])?></td>
                                        <td><?php safeEcho(isset($reaction['reaction_origin']) ? $reaction['reaction_origin'] : "error something") ?></td>
                                        <!-- <td><?php echo formatDate($reaction['start_date']); ?></td> -->
                                        <!-- <td><?php echo formatDate($reaction['end_date']); ?></td> -->
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </article>
            </section>

            <section class="grid-item conditions">
                <h2>Conditions</h2>
                <article>
                    <?php if (empty($conditions)): ?>
                        <p id="no-conditions-msg">No conditions recorded.</p>
                    <?php else: ?>
                        <table border="1">
                            <thead>
                                <tr>
                                    <th>Condition</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Clinical Data ID</th>
                                    <th>Medication ID</th>
                                </tr>
                            </thead>
                            <tbody id="conditions-tbody">
                                <?php foreach ($conditions as $condition): ?>
                                    <tr>
                                        <td><?php safeEcho($condition['condit_name']); ?></td>
                                        <td><?php echo formatDate($condition['condit_start']); ?></td>
                                        <td><?php echo formatDate($condition['condit_end']); ?></td>
                                        <td><?php safeEcho($condition['clinicalID'], '-'); ?></td>
                                        <td><?php safeEcho($condition['medicationID'], '-'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </article>
            </section>

            <section class="grid-item medications">
                <h2>Medications</h2>
                <article>
                    <?php if (empty($medications)): ?>
                        <p id="no-medications-msg">No medications recorded.</p>
                    <?php else: ?>
                        <table border="1">
                            <thead>
                                <tr>
                                    <th>Medication</th>
                                    <th>Dosage</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Prescribing Episode</th>
                                </tr>
                            </thead>
                            <tbody id="medications-tbody">
                                <?php foreach ($medications as $med): ?>
                                    <tr>
                                        <td><?php safeEcho($med['med_name']); ?></td>
                                        <td><?php safeEcho($med['dosage']); ?></td>
                                        <td><?php echo formatDate($med['med_start']); ?></td>
                                        <td><?php echo formatDate($med['med_end']); ?></td>
                                        <td><?php safeEcho($med['episodeID'], '-'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </article>
            </section>

            <section class="grid-item vaccinations">
                <h2>Vaccinations</h2>
                <article>
                    <?php if (empty($vaccinations)): ?>
                        <p id="no-vaccinations-msg">No vaccinations recorded for this patient.</p>
                    <?php else: ?>
                        <table border="1">
                            <thead>
                                <tr>
                                    <th>Vaccine</th>
                                    <th>Date Given</th>
                                    <th>End Date</th>
                                    <th>Administered By</th>
                                </tr>
                            </thead>
                            <tbody id="vaccinations-tbody">
                                <?php foreach ($vaccinations as $vax): ?>
                                    <tr>
                                        <td><?php safeEcho($vax['vaccination_name']); ?></td>
                                        <td><?php echo formatDate($vax['vaccination_start']); ?></td>
                                        <td><?php echo formatDate($vax['vaccination_end']); ?></td>
                                        <td><?php safeEcho($vax['staff_name'], 'Unknown Staff'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </article>
            </section>
        </main>

        <section class="episodes-area">
            <h2>Episodes</h2>
            <?php if (empty($episodes)): ?>
                <p id="no-episodes-msg">No episodes recorded for this patient.</p>
            <?php else: ?>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Episode ID</th>
                            <th>Date</th>
                            <th>Overseeing Staff</th>
                            <th>Clinical Data Summary</th>
                        </tr>
                    </thead>
                    <tbody id="episodes-tbody">
                        <?php foreach ($episodes as $episode): ?>
                            <tr>
                                <td><?php safeEcho($episode['episodeID']); ?></td>
                                <td><?php echo formatDate($episode['episode_date'], 'Y-m-d H:i'); ?></td>//show time too?
                                <td><?php safeEcho($episode['staff_name'], 'Unknown Staff'); ?></td>
                                <td><?php echo $episode['clinical_summary']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>

    </div>
</body>

</html>