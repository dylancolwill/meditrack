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