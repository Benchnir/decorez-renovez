<?php

/**
 * Formulaire de modification d'une annonce
 * 
 * @author Maxime FRAPPAT
 * @version 1.0
 *
 */
class My_Forms_EditAnnouncement extends My_Form {

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
        $this->setName('modification');

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
        $avatar2->setRequired(false)
                    ->addValidator('Count', false, 1);
        $avatar3 = new Zend_Form_Element_File('avatar3');
        $avatar3->setRequired(false)
                    ->addValidator('Count', false, 1);
        $avatar4 = new Zend_Form_Element_File('avatar4');
        $avatar4->setRequired(false)
                    ->addValidator('Count', false, 1);
        $avatar5 = new Zend_Form_Element_File('avatar5');
        $avatar5->setRequired(false)
                    ->addValidator('Count', false, 1);
        $avatar6 = new Zend_Form_Element_File('avatar6');
        $avatar6->setRequired(false)
                    ->addValidator('Count', false, 1);
        $avatar7 = new Zend_Form_Element_File('avatar7');
        $avatar7->setRequired(false)
                    ->addValidator('Count', false, 1);
        $avatar8 = new Zend_Form_Element_File('avatar8');
        $avatar8->setRequired(false)
                    ->addValidator('Count', false, 1);
        $avatar9 = new Zend_Form_Element_File('avatar9');
        $avatar9->setRequired(false)
                    ->addValidator('Count', false, 1);
        $avatar10 = new Zend_Form_Element_File('avatar10');
        $avatar10->setRequired(false)
                    ->addValidator('Count', false, 1);
        
        $visibilityList = array('0' => 'Ne pas afficher l\'annonce',
                                '1' => 'Afficher l\'annonce');
        $visibility = new Zend_Form_Element_Select('visibility');
        $visibility->setLabel('Visibilité :')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->AddMultiOptions($visibilityList);

        $submit = new Zend_Form_Element_Submit('modifier');
        $submit->setAttrib('id', 'submitbutton')
                ->setLabel('Modifier');

        $elements = array($description, $department, $budget, $duration, $jobs, $avatar1, $avatar2, $avatar3, $avatar4, $avatar5, $avatar6, $avatar7, $avatar8, $avatar9, $avatar10, $visibility, $submit);
        $this->addElements($elements);
    }

}
