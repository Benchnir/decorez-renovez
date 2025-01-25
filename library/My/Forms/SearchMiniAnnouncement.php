<?php

/**
 * Formulaire de recherche d'une annonce
 * 
 * @author Maxime FRAPPAT
 * @version 1.0
 *
 */
class My_Forms_SearchMiniAnnouncement extends My_Form
{

    /**
     * Construit le formulaire de recherche d'une annonce
     *
     * @param unknown_type $options
     * @author Maxime FRAPPAT
     * @version 1.0
     * 
     * @return void
     */
    public function __construct($em)
    {
        parent::__construct();
        $this->setName('search')
                ->setAction('/annonce/index');

        $jobList['all'] = 'Tous les métiers';
        foreach ($em->getRepository('\Default_Model_Base_Job')->getAllValidateJobs() as $job){
            $jobList[$job->getId()] = $job->getName();
        }
        $jobs = new Zend_Form_Element_Select('job');
        $jobs->setRequired(false)
                ->setLabel('Métiers :')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->AddMultiOptions($jobList)
                ->setRegisterInArrayValidator(false);

        $budgetList = array(
            'all' => '-',
            Default_Model_Base_Budget::SMALL => Default_Model_Base_Budget::getBudgetDescription(Default_Model_Base_Budget::SMALL),
            Default_Model_Base_Budget::MEDIUM => Default_Model_Base_Budget::getBudgetDescription(Default_Model_Base_Budget::MEDIUM),
            Default_Model_Base_Budget::LARGE => Default_Model_Base_Budget::getBudgetDescription(Default_Model_Base_Budget::LARGE),
            Default_Model_Base_Budget::HUGE => Default_Model_Base_Budget::getBudgetDescription(Default_Model_Base_Budget::HUGE),
        );
        $budget = new Zend_Form_Element_Select('budget');
        $budget->setLabel('Mon budget :')
                ->setRequired(false)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty', true, array('messages' => 'Cannot be empty'))
                ->AddMultiOptions($budgetList)
                ->setRegisterInArrayValidator(false);

        $durationList = array(
            'all' => '-',
            Default_Model_Base_Duration::SMALL => Default_Model_Base_Duration::getDurationDescription(Default_Model_Base_Duration::SMALL),
            Default_Model_Base_Duration::MEDIUM => Default_Model_Base_Duration::getDurationDescription(Default_Model_Base_Duration::MEDIUM),
            Default_Model_Base_Duration::LARGE => Default_Model_Base_Duration::getDurationDescription(Default_Model_Base_Duration::LARGE),
            Default_Model_Base_Duration::HUGE => Default_Model_Base_Duration::getDurationDescription(Default_Model_Base_Duration::HUGE),
        );
        $duration = new Zend_Form_Element_Select('duration');
        $duration->setLabel('La durée :')
                ->setRequired(false)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->AddMultiOptions($durationList)
                ->setRegisterInArrayValidator(false);

        $departmentList = array('all' => 'Toute la France');
        foreach ($em->getRepository('\Default_Model_Base_Department')->getAllDepartmentsToArray() as $key => $value){
            $departmentList[$key] = $value;
        }
        $department = new Zend_Form_Element_Select('department');
        $department->setLabel('Localisation :')
                ->setRequired(false)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->AddMultiOptions($departmentList)
                ->setRegisterInArrayValidator(false);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton')
                ->setLabel('Rechercher');

        $this->addElements(array($jobs, $budget, $duration, $department, $submit));
    }

}
