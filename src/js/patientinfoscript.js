const db = {
    medicalStaff: [
        { staffId: 'S1001', name: 'dr. a b', department: 'dept1', position: 'position1' },
        { staffId: 'S1002', name: 'nurse 1', department: 'dept2', position: 'position2' },
        { staffId: 'S1003', name: 'dr. 1 2', department: 'dept3', position: 'position3' }
    ],
    patients: [
        { patientId: 'P001', name: 'first1 last2', birthdate: '1-01-2000', address: '123 str subrub', billing: 'billing1' },
        { patientId: 'P002', name: 'first2 last2', birthdate: '30-05-2010', address: 'test address2', billing: 'billing2' },
        { patientId: 'P003', name: 'first3 last3', birthdate: '30-05-2010', address: 'testadd3', billing: 'billing3' }
    ],
    episodes: [
        { episodeId: 'E201', patientId: 'P001', overseeingStaffId: 'S1001', date: '19-04-2025' },
        { episodeId: 'E202', patientId: 'P002', overseeingStaffId: 'S1002', date: '18-11-2025' },
        { episodeId: 'E203', patientId: 'P001', overseeingStaffId: 'S1001', date: '12-08-2025' },
        { episodeId: 'E204', patientId: 'P003', overseeingStaffId: 'S1003', date: '24-05-2025' }
    ],
    clinicalData: [
        { clinicalDataId: 'CD301', episodeId: 'E201', procedureDate: '19-04-2025', proceduresDone: 'procedure1', procedureResults: 'procedureresult1', symptoms: 'symptom1', diagnosis: 'diagnosis1', uidOfRelevantCharts: 'chartref1' },
        { clinicalDataId: 'CD302', episodeId: 'E202', procedureDate: '18-11-2025', proceduresDone: 'procedure2', procedureResults: 'procedureresult2', symptoms: 'symptom2', diagnosis: 'diagnosis2', uidOfRelevantCharts: 'chartref2' },
        { clinicalDataId: 'CD303', episodeId: 'E203', procedureDate: '12-08-2025', proceduresDone: 'procedure3', procedureResults: 'procedureresult3', symptoms: 'symptom3', diagnosis: 'diagnosis3', uidOfRelevantCharts: 'chartref3' },
        { clinicalDataId: 'CD304', episodeId: 'E204', procedureDate: '24-05-2025', proceduresDone: 'procedure4', procedureResults: 'procedureresult4', symptoms: 'symptom4', diagnosis: 'diagnosis4', uidOfRelevantCharts: 'chartref4' }
    ],
    medications: [
        { medicationId: 'M401', patientId: 'P001', prescribedMedication: 'med1', medicationStartDate: '19-04-2025', medicationEndDate: null, scriptOriginEpisodeId: 'E201', dosage: 'dosage1' },
        { medicationId: 'M402', patientId: 'P001', prescribedMedication: 'med2', medicationStartDate: '18-11-2025', medicationEndDate: null, scriptOriginEpisodeId: 'E201', dosage: 'dosage2' },
        { medicationId: 'M403', patientId: 'P002', prescribedMedication: 'med3', medicationStartDate: '12-08-2025', medicationEndDate: '19-04-2026', scriptOriginEpisodeId: 'E202', dosage: 'dosage3' },
        { medicationId: 'M404', patientId: 'P001', prescribedMedication: 'med4', medicationStartDate: '24-05-2025', medicationEndDate: null, scriptOriginEpisodeId: 'E203', dosage: 'dosage4' }
    ],
    conditions: [
        { conditionId: 'C501', patientId: 'P001', conditionName: 'condition1', conditionStartDate: '24-05-2024', conditionEndDate: null, clinicalDataId: null, medicationTakenId: null },
        { conditionId: 'C502', patientId: 'P001', conditionName: 'condition2', conditionStartDate: '24-05-2024', conditionEndDate: null, clinicalDataId: 'CD301', medicationTakenId: 'M401' },
        { conditionId: 'C503', patientId: 'P002', conditionName: 'condition3', conditionStartDate: '12-08-2025', conditionEndDate: '19-04-2024', clinicalDataId: 'CD302', medicationTakenId: 'M403' },
        { conditionId: 'C504', patientId: 'P001', conditionName: 'condition4', conditionStartDate: '24-05-2025', conditionEndDate: null, clinicalDataId: 'CD301', medicationTakenId: 'M401' }
    ],
    adverseReactions: [
        { reactionId: 'AR601', patientId: 'P001', medicationId: 'M402', reactionOrigin: 'reactionorigin1', reaction: 'reaction1', startDate: '12-08-2024', endDate: '2-02-2025' },
        { reactionId: 'AR602', patientId: 'P002', medicationId: 'M403', reactionOrigin: 'reactionorigin2', reaction: 'reaction2', startDate: '24-05-2024', endDate: null }
    ],
    vaccinations: [
       { vaccinationId: 'V701', patientId: 'P001', vaccineName: 'Flu Shot', dateAdministered: '15-04-2025', administeredByStaffId: 'S1002' }
    ]
};

//change for aws data retrieval (or keep as is for this example)
function getPatientData(patientId) {
    const patient = db.patients.find(p => p.patientId === patientId);
    if (!patient) return null;

    // Find associated data
    const episodes = db.episodes.filter(e => e.patientId === patientId);
    const medications = db.medications.filter(m => m.patientId === patientId);
    const conditions = db.conditions.filter(c => c.patientId === patientId);
    const adverseReactions = db.adverseReactions.filter(ar => ar.patientId === patientId);
    //clinical data associated with patient episodes
    const episodeIds = episodes.map(ep => ep.episodeId);
    const clinicalData = db.clinicalData.filter(cd => episodeIds.includes(cd.episodeId));
    const vaccinations = db.vaccinations.filter(v => v.patientId === patientId)

    return {
        patient,
        episodes,
        medications,
        conditions,
        adverseReactions,
        medicalStaff: db.medicalStaff, // Assuming we might need the full staff list
        clinicalData,
        vaccinations
    };
}


document.addEventListener('DOMContentLoaded', () => {

    //retrieve from patient search or URL parameter in a real app
    const PATIENT_ID_TO_DISPLAY = 'P001'; // Example Patient ID

    //get data
    const data = getPatientData(PATIENT_ID_TO_DISPLAY);

    function findStaffName(staffId, staffList) {
        const staff = staffList.find(s => s.staffId === staffId);
        return staff ? `${staff.name} (${staff.position || 'N/A'})` : 'Unknown Staff';
    }

    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        try {
            const parts = dateString.split('-');
            // Ensure parts has 3 elements and they are numbers
            if (parts.length !== 3 || parts.some(isNaN)) {
               console.warn("Invalid date format encountered:", dateString);
               return dateString; // Return original string if format is wrong
            }
            const [day, month, year] = parts.map(Number);
             // Basic validation for month and day
            if (month < 1 || month > 12 || day < 1 || day > 31) {
                 console.warn("Invalid date value encountered:", dateString);
                 return dateString;
            }
            // Note: Months are 0-indexed in JavaScript Date objects
            return new Date(year, month - 1, day).toLocaleDateString('en-AU', { day: '2-digit', month: '2-digit', year: 'numeric'});
        } catch (error) {
            console.error("Error formatting date:", dateString, error);
            return dateString; // Return original string on error
        }
    }

    // Get references to the HTML elements
    const headerPatientName = document.getElementById('header-patient-name'); // New header element
    const patientIdSpan = document.getElementById('patient-id');
    const patientNameSpan = document.getElementById('patient-name'); // Still useful inside the box
    const patientBirthdateSpan = document.getElementById('patient-birthdate');
    const patientAddressSpan = document.getElementById('patient-address');
    const patientBillingSpan = document.getElementById('patient-billing');

    const episodesTbody = document.getElementById('episodes-tbody');
    const noEpisodesMsg = document.getElementById('no-episodes-msg');
    const medicationsTbody = document.getElementById('medications-tbody');
    const noMedicationsMsg = document.getElementById('no-medications-msg');
    const conditionsTbody = document.getElementById('conditions-tbody');
    const noConditionsMsg = document.getElementById('no-conditions-msg');
    const reactionsTbody = document.getElementById('adverse-reactions-tbody'); // Corrected ID
    const noReactionsMsg = document.getElementById('no-reactions-msg');
    // Add references for vaccinations if you implement it
    const vaccinationsTbody = document.getElementById('vaccinations-tbody');
    const noVaccinationsMsg = document.getElementById('no-vaccinations-msg');

    const addInfoButton = document.getElementById('add-info-button');


    if (!data || !data.patient) { //if patient data exists
        document.body.innerHTML = `<h1>Error</h1><p>Patient with ID ${PATIENT_ID_TO_DISPLAY} not found.</p>`;
        console.error("Patient data is missing for ID:", PATIENT_ID_TO_DISPLAY);
        return;
    }

    //patient details
    headerPatientName.textContent = data.patient.name; //main header
    patientIdSpan.textContent = data.patient.patientId;
    patientNameSpan.textContent = data.patient.name; //patient name inside box
    patientBirthdateSpan.textContent = formatDate(data.patient.birthdate);
    patientAddressSpan.textContent = data.patient.address;
    patientBillingSpan.textContent = data.patient.billing;

    //episode table
    if (data.episodes && data.episodes.length > 0) {
        episodesTbody.innerHTML = '';
        data.episodes.forEach(episode => {
            const staffName = findStaffName(episode.overseeingStaffId, data.medicalStaff);
            //clinical data specific to episode
            const episodeClinicalData = data.clinicalData.filter(cd => cd.episodeId === episode.episodeId);
            let clinicalSummary = episodeClinicalData.map(cd => {
                 let summary = cd.diagnosis || 'No Diagnosis';
                 if (cd.proceduresDone) {
                     summary += ` (Procedure: ${cd.proceduresDone})`;
                 }
                 return summary;
             }).join('; ') || 'No clinical data for this episode';


            const row = episodesTbody.insertRow();
            row.insertCell().textContent = episode.episodeId;
            row.insertCell().textContent = formatDate(episode.date);
            row.insertCell().textContent = staffName;
            row.insertCell().textContent = clinicalSummary;
        });
        noEpisodesMsg.style.display = 'none';
    } else {
        episodesTbody.innerHTML = ''; //clear rows
        noEpisodesMsg.style.display = 'block';
    }

    //medication table
    if (data.medications && data.medications.length > 0) {
        medicationsTbody.innerHTML = '';
        data.medications.forEach(med => {
             const prescribingEpisode = data.episodes.find(ep => ep.episodeId === med.scriptOriginEpisodeId);
             const episodeText = prescribingEpisode
                ? `${prescribingEpisode.episodeId} (${formatDate(prescribingEpisode.date)})`
                : med.scriptOriginEpisodeId || 'Unknown'; //show id if episode not found

             const row = medicationsTbody.insertRow();
             row.insertCell().textContent = med.prescribedMedication || 'N/A';
             row.insertCell().textContent = med.dosage || 'N/A';
             row.insertCell().textContent = formatDate(med.medicationStartDate);
             row.insertCell().textContent = med.medicationEndDate ? formatDate(med.medicationEndDate) : 'Ongoing';
             row.insertCell().textContent = episodeText;
        });
         noMedicationsMsg.style.display = 'none';
    } else {
        medicationsTbody.innerHTML = '';
        noMedicationsMsg.style.display = 'block';
    }

     //conditions table
    if (data.conditions && data.conditions.length > 0) {
        conditionsTbody.innerHTML = '';
        data.conditions.forEach(cond => {
            const row = conditionsTbody.insertRow();
            row.insertCell().textContent = cond.conditionName || 'N/A';
            row.insertCell().textContent = formatDate(cond.conditionStartDate);
            row.insertCell().textContent = cond.conditionEndDate ? formatDate(cond.conditionEndDate) : 'Ongoing';
            row.insertCell().textContent = cond.clinicalDataId || 'N/A';
            row.insertCell().textContent = cond.medicationTakenId || 'N/A';
        });
        noConditionsMsg.style.display = 'none';
    } else {
        conditionsTbody.innerHTML = '';
        noConditionsMsg.style.display = 'block';
    }

     //allergies table
    if (data.adverseReactions && data.adverseReactions.length > 0) {
        reactionsTbody.innerHTML = '';
        data.adverseReactions.forEach(ar => {
            //find medication within patient list
            const medication = data.medications.find(m => m.medicationId === ar.medicationId);
            const medName = medication ? medication.prescribedMedication : (ar.reactionOrigin || 'Unknown Source');
            const originText = ar.medicationId ? `${medName} (ID: ${ar.medicationId})` : medName;

            const row = reactionsTbody.insertRow();
            row.insertCell().textContent = ar.reaction || 'N/A';
            row.insertCell().textContent = originText;
            row.insertCell().textContent = formatDate(ar.startDate);
            row.insertCell().textContent = ar.endDate ? formatDate(ar.endDate) : 'Ongoing';
        });
        noReactionsMsg.style.display = 'none';
    } else {
        reactionsTbody.innerHTML = '';
        noReactionsMsg.style.display = 'block';
    }

    //vaccination table 
    if (data.vaccinations && data.vaccinations.length > 0) {
        vaccinationsTbody.innerHTML = ''; //clear
        data.vaccinations.forEach(vac => {
            const staffName = findStaffName(vac.administeredByStaffId, data.medicalStaff);

            const row = vaccinationsTbody.insertRow();
            row.insertCell().textContent = vac.vaccineName || 'N/A';
            row.insertCell().textContent = formatDate(vac.dateAdministered);
            row.insertCell().textContent = vac.batchNumber || 'N/A'; //include batch if available
            row.insertCell().textContent = staffName; 
        });
        noVaccinationsMsg.style.display = 'none';
    } else {
        vaccinationsTbody.innerHTML = '';
        noVaccinationsMsg.style.display = 'block';
    }

    // Add event listener for the button (implement functionality later)
    addInfoButton.addEventListener('click', () => {
         alert('Add New Information button clicked! (Functionality not implemented yet)');
         // Example: You might redirect to another page or open a modal form
         // window.location.href = `/add-patient-info?patientId=${PATIENT_ID_TO_DISPLAY}`;
    });

});