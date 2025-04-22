changeEditTarget("allergy") //default, find better way

document.addEventListener('DOMContentLoaded', () => {
    const PATIENT_ID_TO_DISPLAY = 'P001';

    //get data
    const data = getPatientData(PATIENT_ID_TO_DISPLAY);
});

function onSelect(event) {
    catagory = document.getElementById("catagories").value;

    changeEditTarget(event)
}

function changeEditTarget(evt) {
    cityName = document.getElementById("catagories").value;


    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("catagories-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    document.getElementById(cityName).style.display = "block";
}