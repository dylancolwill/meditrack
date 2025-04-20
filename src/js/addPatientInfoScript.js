changeEditTarget("allergy") //default, find better way

function onSelect() {
    catagory = document.getElementById("catagories").value;

    changeEditTarget(catagory)
}

//change for aws data retrieval
function getPatientData(patientId) {
    const patient = db.patients.find(p => p.patientId === patientId);
    if (!patient) return null;

    //find associated data
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
        medicalStaff: db.medicalStaff,
        clinicalData,
        vaccinations
    };
}

function changeEditTarget(catagory) {
    var allergyTable = document.getElementById("allergy-table");
    var conditionTable = document.getElementById("condition-table");
    var medicationTable = document.getElementById("medication-table");
    var vaccinationTable = document.getElementById("vaccination-table");
    

    document.getElementById("catagory-title").innerHTML = catagory;

    if (catagory=="allergy") {
        allergyTable.style.display = "block";
        conditionTable.style.display = "none";
        medicationTable.style.display = "none";
        vaccinationTable.style.display = "none";
    }
    else if (catagory=="condition"){
        conditionTable.style.display = "block";
        allergyTable.style.display = "none";
        medicationTable.style.display = "none";
        vaccinationTable.style.display = "none";
    }
    else if (catagory=="medication"){
        medicationTable.style.display = "block";
        allergyTable.style.display = "none";
        conditionTable.style.display = "none";
        vaccinationTable.style.display = "none";
    }
    else if (catagory=="vaccination"){
        vaccinationTable.style.display = "block";
        allergyTable.style.display = "none";
        conditionTable.style.display = "none";
        medicationTable.style.display = "none";
    }
}