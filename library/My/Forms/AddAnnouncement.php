<?php

/**
 * Formulaire de creation d'une annonce
 * 
 * @author Maxime FRAPPAT
 * @version 1.0
 *
 */
class My_Forms_AddAnnouncement extends My_Form {

    /**
     * Construit le formulaire de creation d'une annonce
     *
     * @param unknown_type $options
     * @author Maxime FRAPPAT
     * @version 1.0
     * 
     * @return void
     */
    public function __construct($em) {
        parent::__construct();
        $this->setName('inscription');

        $description = new Zend_Form_Element_Textarea('description');
        $description->setLabel('Description :')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');
        
        $departmentList = $em->getRepository('\Default_Model_Base_Department')->getAllDepartmentsToArray();
        $department = new Zend_Form_Element_Select('department');
        $department->setLabel('Département :')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->AddMultiOptions($departmentList);

        $budgetList = array(
            \Default_Model_Base_Budget::SMALL => \Default_Model_Base_Budget::getBudgetDescription(\Default_Model_Base_Budget::SMALL),
            \Default_Model_Base_Budget::MEDIUM => \Default_Model_Base_Budget::getBudgetDescription(\Default_Model_Base_Budget::MEDIUM),
            \Default_Model_Base_Budget::LARGE => \Default_Model_Base_Budget::getBudgetDescription(\Default_Model_Base_Budget::LARGE),
            \Default_Model_Base_Budget::HUGE => \Default_Model_Base_Budget::getBudgetDescription(\Default_Model_Base_Budget::HUGE),
        );
        $budget = new Zend_Form_Element_Select('budget');
        $budget->setLabel('Budget approximatif :')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->AddMultiOptions($budgetList);
                
        $durationList = array(
            \Default_Model_Base_Duration::SMALL => \Default_Model_Base_Duration::getDurationDescription(\Default_Model_Base_Duration::SMALL),
            \Default_Model_Base_Duration::MEDIUM => \Default_Model_Base_Duration::getDurationDescription(\Default_Model_Base_Duration::MEDIUM),
            \Default_Model_Base_Duration::LARGE => \Default_Model_Base_Duration::getDurationDescription(\Default_Model_Base_Duration::LARGE),
            \Default_Model_Base_Duration::HUGE => \Default_Model_Base_Duration::getDurationDescription(\Default_Model_Base_Duration::HUGE),
        );
        $duration = new Zend_Form_Element_Select('duration');
        $duration->setLabel('Durée approximative :')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->AddMultiOptions($durationList);

        $jobList = $em->getRepository('\Default_Model_Base_Job')->getAllJobsToArray();
        $jobs = new Zend_Form_Element_Multiselect('jobs');
        $jobs->setLabel('Métiers demandés :')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->AddMultiOptions($jobList);
        
        
        $avatar1 = new Zend_Form_Element_File('avatar1');
        $avatar1->setLabel('Photos')
                ->addValidator('Count', false, 1);
        
        $avatar2 = new Zend_Form_Element_File('avatar2');
        $avatar2->addValidator('Count', false, 1);
        
        $avatar3 = new Zend_Form_Element_File('avatar3');
        $avatar3->addValidator('Count', false, 1);
        
        $avatar4 = new Zend_Form_Element_File('avatar4');
        $avatar4->addValidator('Count', false, 1);

        $submit = new Zend_Form_Element_Submit('create');
        $submit->setAttrib('id', 'submitbutton')
                ->setLabel('Valider mon annonce');

        $this->addElements(array($description, $department, $budget, $duration, $jobs, $avatar1, $avatar2, $avatar3, $avatar4, $submit));
    }

}
