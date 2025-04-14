const testPatient ={ //template format for data storage
    name: {first: 'First', last: 'Last'},
    patientInfo: {birthdate: '1/01/2000', 
        gender: 'Male', 
        birthplace: 'Canberra, ACT'},
    allergies: ['allergy1', 'allergy2'],
    conditions: ['condition1', 'condition2'],
    medications: ['medication1', 'medication2'],
    vaccinations: ['vaccination1', 'vaccination2']
}

async function loadPatientData(patiendId) {
    //load from db, save to dictionary
    const patientNameBox = document.querySelector('.header-grid section h2');
    patientNameBox.innerHTML=`${testPatient.name.first} ${testPatient.name.last}`
    
    const patientInfoBox = document.querySelector('.patient-info article');
    displayDict(testPatient.patientInfo, patientInfoBox);

    const allergiesBox = document.querySelector('.allergies article');
    displayArray(testPatient.allergies, allergiesBox);

    const conditionsBox = document.querySelector('.conditions article');
    displayArray(testPatient.conditions, conditionsBox);

    const medicationsBox = document.querySelector('.medications article');
    displayArray(testPatient.medications, medicationsBox);
    
    const vaccinationsBox = document.querySelector('.vaccinations article');
    displayArray(testPatient.vaccinations, vaccinationsBox);
}

function capitaliseFirstLetter(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function displayDict(dictionary, selector) {
    selector.innerHTML = Object.entries(dictionary)
        .map(([key, value]) => `<div><strong>${capitaliseFirstLetter(key)}:</strong> ${value}</div>`)
        .join('');
}

function displayArray(array, selector) {
    selector.innerHTML = array.join('<br>');
}

document.addEventListener('DOMContentLoaded', function() {
    loadPatientData();
});