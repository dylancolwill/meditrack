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

function findStaffName(staffId, staffList) {
    const staff = staffList.find(s => s.staffId === staffId);
    return staff ? `${staff.name} (${staff.position || 'N/A'})` : 'Unknown Staff';
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    try {
        const parts = dateString.split('-');
        if (parts.length !== 3 || parts.some(isNaN)) {
           console.warn("invalid date format ", dateString);
        }
        const [day, month, year] = parts.map(Number);
        if (month < 1 || month > 12 || day < 1 || day > 31) {
             console.warn("invalid date value ", dateString);
             return dateString;
        }
        return new Date(year, month - 1, day).toLocaleDateString('en-AU', { day: '2-digit', month: '2-digit', year: 'numeric'});
    } catch (error) {
        console.error("error formatting ", dateString, error);
        return dateString;
    }
}