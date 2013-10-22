<?php

use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

class Examination extends Page
{
    protected $path = "OphCiExamination/Default/create?patient_id={patientId}";

    protected $elements = array(
        'history' => array('xpath' => "//*[@id='dropDownTextSelection_Element_OphCiExamination_History_description']//*[@value='blurred vision, ']"),
        'severity' => array('xpath' => "//*[@id='dropDownTextSelection_Element_OphCiExamination_History_description']//*[@value='mild, ']"),
        'onset' => array('xpath' => "//*[@id='dropDownTextSelection_Element_OphCiExamination_History_description']//*[@value='gradual onset, ']"),
        'eye' => array('xpath' => "//*[@id='dropDownTextSelection_Element_OphCiExamination_History_description']//*[@value='left eye, ']"),
        'duration' => array('xpath' => "//*[@id='dropDownTextSelection_Element_OphCiExamination_History_description']//*[@value='1 week, ']"),
        'openComorbidities' => array('xpath' => "//div[@id='active_elements']/div/div[4]/div/h5"),
        'addComorbidities' => array('xpath' => "//*[@id='comorbidities_unselected']/select"),
        'openVisualAcuity' => array('xpath' => "//*[@id='inactive_elements']//*[contains(text(),'Visual Acuity')]"),
        'visualAcuityUnitChange' => array('xpath' => "//*[@id='visualacuity_unit_change']"),
        'openLeftVA' => array('xpath' => "//*[@class='element Element_OphCiExamination_VisualAcuity']//*[@class='side right eventDetail']//*[@class='activeForm']//*[contains(text(),'Add')]"),
        'snellenLeft' => array('xpath' => "//select[@id='visualacuity_reading_0_value']"),
        'readingLeft' => array('xpath' => "//select[@id='visualacuity_reading_0_method_id']"),
        'openRightVA' => array('xpath' => "//*[@class='element Element_OphCiExamination_VisualAcuity']//*[@class='side left eventDetail']//*[@class='activeForm']//*[contains(text(),'Add')]"),
        'ETDRSSnellenRight' => array('xpath' => "//*[@id='visualacuity_reading_1_value']"),
        'ETDRSSnellenLeft' => array('xpath' => "//*[@id='visualacuity_reading_0_value']"),
        'ETDRSreadingRight' => array('xpath' => "//*[@id='visualacuity_reading_1_method_id']"),
        'ETDRSreadingLeft' => array('xpath' => "//*[@id='visualacuity_reading_0_method_id']"),
        'snellenRight' => array('xpath' => "//select[@id='visualacuity_reading_1_value']"),
        'readingRight' => array('xpath' => "//select[@id='visualacuity_reading_1_method_id']"),

        'openIntraocularPressure' => array('xpath' => "//*[@id='inactive_elements']//*[contains(text(), 'Intraocular Pressure')]"),
        'intraocularRight' => array('xpath' => "//*[@id='Element_OphCiExamination_IntraocularPressure_right_reading_id']"),
        'instrumentRight' => array('xpath' => "//*[@id='Element_OphCiExamination_IntraocularPressure_right_instrument_id']"),
        'intraocularLeft' => array('xpath' => "//*[@id='Element_OphCiExamination_IntraocularPressure_left_reading_id']"),
        'instrumentLeft' => array('xpath' => "//*[@id='Element_OphCiExamination_IntraocularPressure_left_instrument_id']"),

        'openDilation' => array('xpath' => "//*[@id='inactive_elements']//*[contains(text(), 'Dilation')]"),
        'dilationRight' => array('xpath' => "//select[@id='dilation_drug_right']"),
        'dropsLeft' => array('xpath' => "//select[@name='dilation_treatment[0][drops]']"),
        'dilationLeft' => array('xpath' => "//select[@id='dilation_drug_left']"),
        'dropsRight' => array('xpath' => "//select[@name='dilation_treatment[1][drops]']"),

        'expandRefraction' => array('xpath' => "//*[@id='inactive_elements']//*[@data-element-type-name='Refraction']"),

        'sphereLeft' => array('xpath' => "//*[@id='Element_OphCiExamination_Refraction_left_sphere_sign']"),
        'sphereLeftInt' => array('xpath' => "//*[@id='Element_OphCiExamination_Refraction_left_sphere_integer']"),
        'sphereLeftFraction' => array('xpath' => "//*[@id='Element_OphCiExamination_Refraction_left_sphere_fraction']"),
        'cylinderLeft' => array('xpath' => "//*[@id='Element_OphCiExamination_Refraction_left_cylinder_sign']"),
        'cylinderLeftInt' => array('xpath' => "//*[@id='Element_OphCiExamination_Refraction_left_cylinder_integer']"),
        'cylinderLeftFraction' => array('xpath' => "//*[@id='Element_OphCiExamination_Refraction_left_cylinder_fraction']"),
        'sphereLeftAxis' => array('xpath' => "//*[@id='Element_OphCiExamination_Refraction_left_axis']"),
        'sphereLeftType' => array('xpath' => "//*[@id='Element_OphCiExamination_Refraction_left_type_id']"),


        'sphereRight' => array('xpath' => "//*[@id='Element_OphCiExamination_Refraction_right_sphere_sign']"),
        'sphereRightInt' => array('xpath' => "//*[@id='Element_OphCiExamination_Refraction_right_sphere_integer']"),
        'sphereRightFraction' => array('xpath' => "//*[@id='Element_OphCiExamination_Refraction_right_sphere_fraction']"),
        'cylinderRight' => array('xpath' => "//*[@id='Element_OphCiExamination_Refraction_right_cylinder_sign']"),
        'cylinderRightInt' => array('xpath' => "//*[@id='Element_OphCiExamination_Refraction_right_cylinder_integer']"),
        'cylinderRightFraction' => array('xpath' => "//*[@id='Element_OphCiExamination_Refraction_right_cylinder_fraction']"),
        'sphereRightAxis' => array('xpath' => "//*[@id='Element_OphCiExamination_Refraction_right_axis']"),
        'sphereRightType' => array('xpath' => "//*[@id='Element_OphCiExamination_Refraction_right_type_id']"),

        'leftAdnexalComorbidity' => array('xpath' => "//*[@id='dropDownTextSelection_Element_OphCiExamination_AdnexalComorbidity_left_description']"),
        'rightAdnexalComorbidity' => array('xpath' => "//*[@id='dropDownTextSelection_Element_OphCiExamination_AdnexalComorbidity_right_description']"),

        'leftAbnormality' => array('xpath' => "//*[@id='Element_OphCiExamination_PupillaryAbnormalities_left_abnormality_id']"),
        'rightAbnormality' => array('xpath' => "//*[@id='Element_OphCiExamination_PupillaryAbnormalities_right_abnormality_id']"),

        'diagnosesLeftEye' => array('xpath' => "//*[@id='Element_OphCiExamination_Diagnoses_eye_id_1']"),
        'diagnosesRightEye' => array('xpath' => "//*[@id='Element_OphCiExamination_Diagnoses_eye_id_2']"),
        'diagnosesBothEyes' => array('xpath' => "//*[@id='Element_OphCiExamination_Diagnoses_eye_id_3']"),
        'diagnosesCommonDiagnosis' => array('xpath' => "//*[@id='DiagnosisSelection_disorder_id']"),

        'addInvestigation' => array('xpath' => "//*[@id='dropDownTextSelection_Element_OphCiExamination_Investigation_description']"),

        'riskComments' => array('xpath' => "//*[@id='Element_OphCiExamination_Risks_comments']"),

        'cataractManagementComments' => array('xpath' => "//*[@id='dropDownTextSelection_Element_OphCiExamination_Management_comments']"),
        'selectFirstEye' => array('xpath' => "//*[@id='Element_OphCiExamination_CataractManagement_eye_id_1']"),
        'selectSecondEye' => array('xpath' => "//*[@id='Element_OphCiExamination_CataractManagement_eye_id_2']"),
        'cityRoad' => array('xpath' => "//*[@id='Element_OphCiExamination_CataractManagement_city_road'][2]"),
        'satellite' => array('xpath' => "//*[@id='Element_OphCiExamination_CataractManagement_satellite'][2]"),
        'straightforward' => array('xpath' => "//*[@id='div_Element_OphCiExamination_CataractManagement_fast_track']//*[@type='checkbox']"),
        'postOpRefractiveTarget' => array('xpath' => "//*[@id='Element_OphCiExamination_CataractManagement_target_postop_refraction']"),
        'discussedWithPatientYes' => array('xpath' => "//*[@id='Element_OphCiExamination_CataractManagement_correction_discussed_1']"),
        'discussedWithPatientNo' => array('xpath' => "//*[@id='Element_OphCiExamination_CataractManagement_correction_discussed_0']"),
        'suitableForSurgeon' => array('xpath' => "//*[@id='Element_OphCiExamination_CataractManagement_suitable_for_surgeon_id']"),
        'supervisedCheckbox' => array ('xpath' => "//*[@id='Element_OphCiExamination_CataractManagement_supervised'][2]"),
        'previousRefractiveSurgeryYes' => array('xpath' => "//*[@id='Element_OphCiExamination_CataractManagement_previous_refractive_surgery_1']"),
        'previousRefractiveSurgeryNo' => array('xpath' => "//*[@id='Element_OphCiExamination_CataractManagement_previous_refractive_surgery_0']"),
        'VitrectomisedEyeYes' => array('xpath' => "//*[@id='Element_OphCiExamination_CataractManagement_vitrectomised_eye_1']"),
        'VitrectomisedEyeNo' => array('xpath' => "//*[@id='Element_OphCiExamination_CataractManagement_vitrectomised_eye_0']"),

        'laserStatusChoice' => array('xpath' => "//*[@id='Element_OphCiExamination_LaserManagement_laser_status_id']"),
        'deferralReason' => array('xpath' => "//*[@id='Element_OphCiExamination_LaserManagement_laser_deferralreason_id']"),
        'leftLaserType' => array('xpath' => "//*[@id='Element_OphCiExamination_LaserManagement_left_lasertype_id']"),
        'rightLaserType' => array('xpath' => "//*[@id='Element_OphCiExamination_LaserManagement_right_lasertype_id']"),

        'noTreatmentCheckbox' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_no_treatment'][2]"),
        'noTreatmentReason' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_no_treatment_reason_id']"),

        'rightChoroidalRetinal' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_right_diagnosis1_id']//*[@value='75971007']"),
        'rightSecondaryTo' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_right_diagnosis2_id']"),
        'leftChoroidalRetinal' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_left_diagnosis1_id']//*[@value='75971007']"),
        'leftSecondaryTo' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_left_diagnosis2_id']"),
        'rightMacularRetinal' => array ('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_right_diagnosis1_id']//*[@value='37231002']"),
        'leftMacularRetinal' => array ('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_left_diagnosis1_id']//*[@value='37231002']"),
        'rightVenousRetinalBranchOcclusion' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_right_diagnosis2_id']//*[@value='24596005']"),
        'leftDiabeticMacularOedema' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_left_diagnosis2_id']//*[@value='312912001']"),

        'expandVisualFields' => array ('xpath' => "//*[@id='inactive_elements']//*[@data-element-type-name='Visual Fields']"),
        'expandGonioscopy' => array('xpath' => "//*[@id='inactive_elements']//*[@data-element-type-name='Gonioscopy']"),
        'expandaAdnexalComorbidity' => array('xpath' => "//*[@id='inactive_elements']//*[@data-element-type-name='Adnexal Comorbidity']"),
        'expandAnteriorSegment' => array('xpath' => "//*[@id='inactive_elements']//*[@data-element-type-name='Anterior Segment']"),
        'expandPupillaryAbnormalities' => array('xpath' => "//*[@id='inactive_elements']//*[@data-element-type-name='Pupillary Abnormalities']"),
        'expandOpticDisc' => array('xpath' => "//*[@id='inactive_elements']//*[@data-element-type-name='Optic Disc']"),
        'expandPosteriorPole' => array('xpath' => "//*[@id='inactive_elements']//*[@data-element-type-name='Posterior Pole']"),
        'expandDiagnoses' => array('xpath' => "//*[@id='inactive_elements']//*[@data-element-type-name='Diagnoses']"),
        'expandInvestigation' => array('xpath' => "//*[@id='inactive_elements']//*[@data-element-type-name='Investigation']"),

        'expandClinicalManagement' => array('xpath' => "//*[@id='inactive_elements']//*[@data-element-type-name='Clinical Management']"),
        'expandCataractManagement' => array('xpath' => "//*[@id='active_elements']//*[@data-element-type-name='Cataract Management']"),
        'expandLaserManagement' => array('xpath' => "//*[@id='active_elements']//*[@data-element-type-name='Laser Management']"),
        'expandInjectionManagement' => array('xpath' => "//*[@id='active_elements']//*[@data-element-type-name='Injection Management']"),

        'rightCrtIncreaseLowerHundredYes' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_right_Answer_1_1']"),
        'rightCrtIncreaseLowerHundredNo' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_right_Answer_1_0']"),
        'rightCrtIncreaseMoreThanHundredYes' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_right_Answer_2_1']"),
        'rightCrtIncreaseMoreThanHundredNo' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_right_Answer_2_0']"),
        'rightLossOfFiveLettersYes' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_right_Answer_3_1']"),
        'rightLossOfFiveLettersNo' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_right_Answer_3_0']"),
        'rightLossOfFiveLettersHigherThanFiveYes' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_right_Answer_4_1']"),
        'rightLossOfFiveLettersHigherThanFiveNo' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_right_Answer_4_0']"),

        'leftCrtIncreaseLowerHundredYes' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_left_Answer_1_1']"),
        'leftCrtIncreaseLowerHundredNo' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_left_Answer_1_0']"),
        'leftCrtIncreaseMoreThanHundredYes' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_left_Answer_2_1']"),
        'leftCrtIncreaseMoreThanHundredNo' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_left_Answer_2_0']"),
        'leftLossOfFiveLettersYes' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_left_Answer_3_1']"),
        'leftLossOfFiveLettersNo' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_left_Answer_3_0']"),
        'leftLossOfFiveLettersHigherThanFiveYes' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_left_Answer_4_1']"),
        'leftLossOfFiveLettersHigherThanFiveNo' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_left_Answer_4_0']"),

        'rightFailedLaserYes' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_right_Answer_5_1']"),
        'rightFailedLaserNo' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_right_Answer_5_0']"),
        'rightUnsuitableForLaserYes' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_right_Answer_6_1']"),
        'rightUnsuitableForLaserNo' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_right_Answer_6_0']"),
        'rightPreviousOzurdexYes' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_right_Answer_7_1']"),
        'rightPreviousOzurdexNo' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_right_Answer_7_0']"),

        'leftCrtIncreaseMoreThanFourHundredYes' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_left_Answer_8_1']"),
        'leftCrtIncreaseMoreThanFourHundredNo' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_left_Answer_8_0']"),
        'leftFovealDamageYes' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_left_Answer_9_1']"),
        'leftFovealDamageNo' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_left_Answer_9_0']"),
        'leftFailedLaserYes' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_left_Answer_10_1']"),
        'leftFailedLaserNo' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_left_Answer_10_0']"),
        'leftUnsuitableForLaserYes' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_left_Answer_11_1']"),
        'leftUnsuitableForLaserNo' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_left_Answer_11_0']"),
        'leftPreviousAntiVEGFyes' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_left_Answer_12_1']"),
        'leftPreviousAntiVEGFno' => array('xpath' => "//*[@id='Element_OphCiExamination_InjectionManagementComplex_left_Answer_12_0']"),

        'expandRisks' => array('xpath' => "//*[@id='inactive_elements']//*[@data-element-type-name='Risks']"),
        'expandClinicOutcome' => array('xpath' => "//*[@id='inactive_elements']//*[@data-element-type-name='Clinic Outcome']"),

        'clinicalOutcomeFollowUp' => array('xpath' => "//*[@id='Element_OphCiExamination_ClinicOutcome_status_id']//*[@value='1']"),
        'clinicalOutcomeQuantity' => array('xpath' => "//*[@id='Element_OphCiExamination_ClinicOutcome_followup_quantity']"),
        'clinicalOutcomePeriod' => array('xpath' => "//*[@id='Element_OphCiExamination_ClinicOutcome_followup_period_id']"),
        'clinicalOutcomeSuitablePatientTickbox' => array('xpath' => "//*[@id='Element_OphCiExamination_ClinicOutcome_community_patient']"),
        'clinialOutcomeRole' => array('xpath' => "//*[@id='Element_OphCiExamination_ClinicOutcome_role_id']"),

        'clinicalOutcomeDischarge' => array('xpath' => "//*[@id='Element_OphCiExamination_ClinicOutcome_status_id']//*[@value='2']"),

        'expandConclusion' => array('xpath' => "//*[@id='inactive_elements']//*[@data-element-type-name='Conclusion']"),
        'conclusionOption' => array('xpath' => "//*[@id='dropDownTextSelection_Element_OphCiExamination_Conclusion_description']"),

        'saveExamination' => array('xpath' => "//*[@id='et_save']"),
    );

    public function history ()
    {
        $this->getElement('history')->click();
        $this->getElement('severity')->click();
        $this->getElement('onset')->click();
        $this->getElement('eye')->click();
        $this->getElement('duration')->click();
    }

    protected function isComorbitiesCollapsed()
    {
        return (bool) $this->find('xpath', $this->getElement('openComorbidities')->getXpath());;
    }

    public function openComorbidities ()
    {
        if ($this->isComorbitiesCollapsed()) {

            $this->getElement('openComorbidities')->click();
            $this->getSession()->wait(3000, '$.active == 0');
        }
    }

    public function addComorbiditiy ($com)
    {
        $this->getElement('addComorbidities')->selectOption($com);
    }

    protected function isVisualAcuityCollapsed()
    {
        return (bool) $this->find('xpath', $this->getElement('openVisualAcuity')->getXpath());
    }

    public function openVisualAcuity ()
    {
        if ($this->isVisualAcuityCollapsed()) {
            $this->getElement('openVisualAcuity')->click();
            $this->getSession()->wait(3000, '$.active == 0');
        }
    }

    public function selectVisualAcuity ($unit)
    {
        $this->getElement('visualAcuityUnitChange')->selectOption($unit);
        $this->getSession()->wait(5000);
    }

    public function leftVisualAcuity ($metre, $method)
    {
        $this->getElement('openLeftVA')->click();
        $this->getElement('snellenLeft')->selectOption($metre);
        $this->getElement('readingLeft')->selectOption($method);
    }

    public function rightVisualAcuity ($metre, $method)
    {
        $this->getElement('openRightVA')->click();
        $this->getElement('snellenRight')->selectOption($metre);
        $this->getElement('readingRight')->selectOption($method);
    }

    public function leftETDRS ($metre, $method)
    {
        $this->getElement('openLeftVA')->click();
        $this->getSession()->wait(3000, '$.active == 0');
        $this->getElement('ETDRSSnellenLeft')->selectOption($metre);
        $this->getElement('ETDRSreadingLeft')->selectOption($method);
    }

    public function rightETDRS ($metre, $method)
    {
        $this->getElement('openRightVA')->click();
        $this->getSession()->wait(3000, '$.active == 0');
        $this->getElement('ETDRSSnellenRight')->selectOption($metre);
        $this->getElement('ETDRSreadingRight')->selectOption($method);
    }

    protected function isIntraocularPressureCollapsed()
    {
        return (bool) $this->find('xpath', $this->getElement('openIntraocularPressure')->getXpath());
    }

    public function expandIntraocularPressure ()
{
    if ($this->isIntraocularPressureCollapsed()){
        $this->getElement('openIntraocularPressure')->click();
    }
}

    public function leftIntracocular ($pressure, $instrument)
    {
        $this->getElement('intraocularLeft')->selectOption($pressure);
        $this->getElement('instrumentLeft')->selectOption($instrument);
    }

    public function rightIntracocular ($pressure, $instrument)
    {
        $this->getElement('intraocularRight')->selectOption($pressure);
        $this->getElement('instrumentRight')->selectOption($instrument);

    }

    protected function isDilationCollapsed()
    {
        return (bool) $this->find('xpath', $this->getElement('openDilation')->getXpath());
    }

    public function openDilation ()
    {
        if ($this->isDilationCollapsed()){
            $this->getElement('openDilation')->click();
            $this->getSession()->wait(5000);
        }
    }

    public function dilationRight ($dilation, $drops)
    {
        $this->getElement('dilationRight')->selectOption($dilation);
        $this->getElement('dilationRight')->click();
        $this->getElement('dropsRight')->selectOption($drops);
    }

    public function dilationLeft ($dilation, $drops)
    {
        $this->getElement('dilationLeft')->selectOption($dilation);
        $this->getElement('dilationLeft')->click();
        $this->getElement('dropsLeft')->selectOption($drops);
    }

    protected function isRefractionCollapsed ()
    {
        return (bool) $this->find('xpath', $this->getElement('expandRefraction')->getXpath());
    }

    public function openRefraction ()
    {
        if ($this->isRefractionCollapsed()){
        $this->getElement('expandRefraction')->click();
        $this->getSession()->wait(10000);
        }
    }

    public function leftRefractionDetails ($sphere, $integer, $fraction)
    {
        $this->getElement('sphereRight')->selectOption($sphere);
        $this->getElement('sphereRightInt')->selectOption($integer);
        $this->getElement('sphereRightFraction')->selectOption($fraction);
    }

    public function leftCyclinderDetails ($cylinder, $integer, $fraction)
    {
        $this->getElement('cylinderRight')->selectOption($cylinder);
        $this->getElement('cylinderRightInt')->selectOption($integer);
        $this->getElement('cylinderRightFraction')->selectOption($fraction);
    }

    public function leftAxis ($axis)
    {
        $this->getElement('sphereRightAxis')->setValue($axis);
    }

    public function leftType ($type)
    {
        $this->getElement('sphereRightType')->selectOption($type);
    }

    public function RightRefractionDetails ($sphere, $integer, $fraction)
    {
        $this->getElement('sphereLeft')->selectOption($sphere);
        $this->getElement('sphereLeftInt')->selectOption($integer);
        $this->getElement('sphereLeftFraction')->selectOption($fraction);
    }

    public function RightCyclinderDetails ($cylinder, $integer, $fraction)
    {
        $this->getElement('cylinderLeft')->selectOption($cylinder);
        $this->getElement('cylinderLeftInt')->selectOption($integer);
        $this->getElement('cylinderLeftFraction')->selectOption($fraction);
    }

    public function RightAxis ($axis)
    {
        $this->getElement('sphereLeftAxis')->setValue($axis);
    }

    public function RightType ($type)
    {
        $this->getElement('sphereLeftType')->selectOption($type);
    }

    public function expandVisualFields ()
    {
        $this->getElement('expandVisualFields')->click();
    }

    public function expandGonioscopy ()
    {
        $this->getElement('expandGonioscopy')->click();
    }

    public function expandAdnexalComorbidity ()
    {
        $this->getElement('expandaAdnexalComorbidity')->click();
        $this->getSession()->wait(5000);
    }

    public function leftAdnexal ($left)
    {
        $this->getElement('leftAdnexalComorbidity')->setValue($left);
    }

    public function rightAdnexal ($left)
    {
        $this->getElement('rightAdnexalComorbidity')->setValue($left);
    }

    public function expandAnteriorSegment ()
    {
        $this->getElement('expandAnteriorSegment')->click();
    }

    public function expandPupillaryAbnormalities ()
    {
        $this->getElement('expandPupillaryAbnormalities')->click();
        $this->getSession()->wait(5000);
    }

    public function leftPupillaryAbnormality ($left)
    {
        $this->getElement('leftAbnormality')->setValue($left);
    }

    public function rightPupillaryAbnormality ($right)
    {
        $this->getElement('rightAbnormality')->setValue($right);
    }

    public function expandOpticDisc ()
    {
        $this->getElement('expandOpticDisc')->click();
    }

    public function expandPosteriorPole ()
    {
       $this->getElement('expandPosteriorPole')->click();
    }

    public function expandDiagnoses ()
    {
       $this->getElement('expandDiagnoses')->click();
       $this->getSession()->wait(5000);
    }

    public function diagnosesLeftEye ()
    {
       $this->getElement('diagnosesLeftEye')->click();
    }

    public function diagnosesRightEye ()
    {
        $this->getElement('diagnosesRightEye')->click();
    }

    public function diagnosesBothEyes ()
    {
        $this->getElement('diagnosesBothEyes')->click();
    }

    public function diagnosesDiagnosis ($diagnosis)
    {
        $this->getElement('diagnosesCommonDiagnosis')->setValue($diagnosis);
    }

    public function expandInvestigation ()
    {
        $this->getElement('expandInvestigation')->click();
        $this->getSession()->wait(5000);
    }

    public function addInvestigation ($investigation)
    {
        $this->getElement('addInvestigation')->setValue($investigation);
    }

    public function expandClinicalManagement ()
    {
        $this->getElement('expandClinicalManagement')->click();
        $this->getSession()->wait(5000);
    }

    public function expandCataractManagement ()
    {
        $this->getElement('expandCataractManagement')->click();
        $this->getSession()->wait(5000);
    }

    public function cataractManagementComments ($comments)
    {
        $this->getElement('cataractManagementComments')->selectOption($comments);
    }

    public function selectFirstEye ()
    {
        $this->getElement('selectFirstEye')->click();
    }

    public function selectSecondEye ()
    {
        $this->getElement('selectSecondEye')->click();
    }

    public function cityRoad ()
    {
        $this->getElement('cityRoad')->check();
    }

    public function satellite ()
    {
        $this->getElement('satellite')->check();
    }

    public function straightforward ()
    {
        $this->getElement('straightforward')->check();
    }

    public function postOpRefractiveTarget ($target)
    {
        $this->getElement('postOpRefractiveTarget')->mouseOver($target);
//        THIS ISNT WORKING UNLESS WE HAVE A SLIDER MECHANISM FOR BEHAT
    }

    public function discussedWithPatientYes ()
    {
        $this->getElement('discussedWithPatientYes')->click();
    }

    public function discussedWithPatientNo ()
    {
        $this->getElement('discussedWithPatientNo')->click();
    }

    public function suitableForSurgeon ($surgeon)
    {
        $this->getElement('suitableForSurgeon')->click();
        $this->getElement('suitableForSurgeon')->setValue($surgeon);
    }

    public function supervisedCheckbox ()
    {
        $this->getElement('supervisedCheckbox')->check();
    }

    public function previousRefractiveSurgeryYes ()
    {
        $this->getElement('previousRefractiveSurgeryYes')->click();
    }

    public function previousRefractiveSurgeryNo ()
    {
        $this->getElement('previousRefractiveSurgeryNo')->click();
    }

    public function vitrectomisedEyeYes ()
    {
        $this->getElement('VitrectomisedEyeYes')->click();
    }

    public function vitrectomisedEyeNo()
    {
        $this->getElement('VitrectomisedEyeNo')->click();
    }

    public function expandLaserManagement ()
    {
        $this->getElement('expandLaserManagement')->click();
        $this->getSession()->wait(5000);
    }

    public function laserStatusChoice ($laser)
    {
        $this->getElement('laserStatusChoice')->selectOption($laser);
    }

    public function deferralReason ($reason)
    {
        $this->getElement('deferralReason')->selectOption($reason);
    }

    public function leftLaser ($laser)
    {
        $this->getElement('leftLaserType')->selectOption($laser);
    }

    public function rightLaser ($laser)
    {
        $this->getElement('rightLaserType')->selectOption($laser);
    }

    public function expandInjectionManagement ()
    {
        $this->getElement('expandInjectionManagement')->click();
        $this->getSession()->wait(5000);
    }

    public function noTreatment ()
    {
        $this->getElement('noTreatmentCheckbox')->check();
    }

    public function noTreatmentReason ($treatment)
    {
        $this->getElement('noTreatmentReason')->selectOption($treatment);
    }

    public function rightChoroidalRetinal ()
    {
        $this->getElement('rightChoroidalRetinal')->click();
        $this->getSession()->wait(5000);
    }

    public function rightSecondaryTo ($secondary)
    {
        $this->getElement('rightSecondaryTo')->selectOption($secondary);
        $this->getSession()->wait(5000);
    }

    public function leftChoroidalRetinal ()
    {
        $this->getElement('leftChoroidalRetinal')->click();
    }

    public function leftSecondaryTo ($secondary)
    {
        $this->getElement('leftSecondaryTo')->selectOption($secondary);
        $this->getSession()->wait(5000);
    }

    public function rightCRTIncreaseLowerThanHundredYes ()
    {
        $this->getElement('rightCrtIncreaseLowerHundredYes')->click();
    }

    public function rightCRTIncreaseLowerThanHundredNo ()
    {
        $this->getElement('rightCrtIncreaseLowerHundredNo')->click();
    }

    public function rightCRTIncreaseMoreThanHundredYes ()
    {
        $this->getElement('rightCrtIncreaseMoreThanHundredYes')->click();
    }

    public function rightCRTIncreaseMoreThanHundredNo ()
    {
        $this->getElement('rightCrtIncreaseMoreThanHundredNo')->click();
    }

    public function rightLossOfFiveLettersYes ()
    {
        $this->getElement('rightLossOfFiveLettersYes')->click();
    }

    public function rightLossOfFiveLettersNo ()
    {
        $this->getElement('rightLossOfFiveLettersNo')->click();
    }

    public function rightLossOfFiveLettersHigherThanFiveYes ()
    {
        $this->getElement('rightLossOfFiveLettersHigherThanFiveYes')->click();
    }

    public function rightLossOfFiveLettersHigherThanFiveNo ()
    {
        $this->getElement('rightLossOfFiveLettersHigherThanFiveNo')->click();
    }

    public function leftCRTIncreaseLowerThanHundredYes ()
    {
        $this->getElement('leftCrtIncreaseLowerHundredYes')->click();
    }

    public function leftCRTIncreaseLowerThanHundredNo ()
    {
        $this->getElement('leftCrtIncreaseLowerHundredNo')->click();
    }

    public function leftCRTIncreaseMoreThanHundredYes ()
    {
        $this->getElement('leftCrtIncreaseMoreThanHundredYes')->click();
    }

    public function leftCRTIncreaseMoreThanHundredNo ()
    {
        $this->getElement('leftCrtIncreaseMoreThanHundredNo')->click();
    }

    public function leftLossOfFiveLettersYes ()
    {
        $this->getElement('leftLossOfFiveLettersYes')->click();
    }

    public function leftLossOfFiveLettersNo ()
    {
        $this->getElement('leftLossOfFiveLettersNo')->click();
    }

    public function leftLossOfFiveLettersHigherThanFiveYes ()
    {
        $this->getElement('leftLossOfFiveLettersHigherThanFiveYes')->click();
    }

    public function leftLossOfFiveLettersHigherThanFiveNo ()
    {
        $this->getElement('leftLossOfFiveLettersHigherThanFiveNo')->click();
    }

    public function rightMacularRetinal ()
    {
        $this->getElement('rightMacularRetinal')->click();
    }

    public function leftMacularRetinal ()
    {
        $this->getElement('leftMacularRetinal')->click();
    }

    public function rightSecondaryVenousRetinalBranchOcclusion ()
    {
        $this->getElement('rightVenousRetinalBranchOcclusion')->click();
        $this->getSession()->wait(5000);
    }

    public function leftSecondaryDiabeticMacularOedema ()
    {
        $this->getElement('leftDiabeticMacularOedema')->click();
        $this->getSession()->wait(5000);
    }

    public function leftCrtIncreaseMoreThanFourHundredYes ()
    {
        $this->getElement('leftCrtIncreaseMoreThanFourHundredYes')->click();
    }

    public function leftCrtIncreaseMoreThanFourHundredNo ()
    {
        $this->getElement('leftCrtIncreaseMoreThanFourHundredNo')->click();
    }

    public function leftFovealDamageYes ()
    {
        $this->getElement('leftFovealDamageYes')->click();
    }

    public function leftFovealDamageNo ()
    {
        $this->getElement('leftFovealDamageNo')->click();
    }

    public function leftFailedLaserYes ()
    {
        $this->getElement('leftFailedLaserYes')->click();
    }

    public function leftFailedLaserNo ()
    {
        $this->getElement('leftFailedLaserNo')->click();
    }

    public function leftUnsuitableForLaserYes ()
    {
        $this->getElement('leftUnsuitableForLaserYes')->click();
    }

    public function leftUnsuitableForLaserNo ()
    {
        $this->getElement('leftUnsuitableForLaserNo')->click();
    }

    public function leftPreviousAntiVEGFyes ()
    {
        $this->getElement('leftPreviousAntiVEGFyes')->click();
    }

    public function leftPreviousAntiVEGFno ()
    {
        $this->getElement('leftPreviousAntiVEGFno')->click();
    }

    public function rightFailedLaserYes ()
    {
        $this->getElement('rightFailedLaserYes')->click();
    }

    public function rightFailedLaserNo()
    {
        $this->getElement('rightFailedLaserNo')->click();
    }

    public function rightUnsuitableForLaserYes ()
    {
        $this->getElement('rightUnsuitableForLaserYes')->click();
    }

    public function rightUnsuitableForLaserNo ()
    {
        $this->getElement('rightUnsuitableForLaserNo')->click();
    }

    public function rightPreviousOzurdexYes ()
    {
        $this->getElement('rightPreviousOzurdexYes')->click();
    }

    public function rightPreviousOzurdexNo ()
    {
        $this->getElement('rightPreviousOzurdexNo')->click();
    }

    public function expandRisks ()
    {
        $this->getElement('expandRisks')->click();
        $this->getSession()->wait(5000);
    }

    public function riskComments ($comments)
    {
        $this->getElement('riskComments')->setValue($comments);
    }

    public function expandClinicalOutcome ()
    {
        $this->getElement('expandClinicOutcome')->click();
        $this->getSession()->wait(5000);
    }

    public function clinicalOutcomeFollowUp ()
    {
        $this->getElement('clinicalOutcomeFollowUp')->click();
    }

    public function clinicalFollowUpQuantity ($quantity)
    {
        $this->getElement('clinicalOutcomeQuantity')->selectOption($quantity);
    }

    public function clinicalFollowUpPeriod ($period)
    {
        $this->getElement('clinicalOutcomePeriod')->selectOption($period);
    }

    public function clinicalSuitablePatient ()
    {
        $this->getElement('clinicalOutcomeSuitablePatientTickbox')->check();
    }

    public function clinicalRole ($role)
    {
        $this->getElement('clinialOutcomeRole')->selectOption($role);
    }

    public function clinicalOutcomeDischarge ()
    {
        $this->getElement('clinicalOutcomeDischarge')->click();
    }

    public function expandConclusion ()
    {
        $this->getElement('expandConclusion')->click();
        $this->getSession()->wait(5000);
    }

    public function conclusionOption ($option)
    {
        $this->getElement('conclusionOption')->selectOption($option);
    }

    public function saveExamination ()
    {
        $this->getSession()->wait(10000);
        $this->getElement('saveExamination')->click();
    }

}