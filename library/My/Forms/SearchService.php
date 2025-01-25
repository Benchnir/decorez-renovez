<?php

/**
 * Formulaire de recherche d'un service
 * 
 * @author Maxime FRAPPAT
 * @version 1.0
 *
 */
class My_Forms_SearchService extends My_Form
{

    /**
     * Construit le formulaire de recherche d'une service
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
                ->setAction('/service/index');
        
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Nom de l\'artisan :')
                ->setRequired(false)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');

        $jobList['all'] = 'Tous les métiers';
        foreach ($em->getRepository('\Default_Model_Base_Job')->getAllValidateJobs() as $job){
            $jobList[$job->getId()] = $job->getName();
        }
        $jobs = new Zend_Form_Element_Select('job');
        $jobs->setRequired(false)
                ->setLabel('Métier :')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->AddMultiOptions($jobList)
                ->setRegisterInArrayValidator(false);

        $budgetList = array(
            0 => 'tous les prix',
            1 => 'moins de 100€',
            2 => 'entre 100€ et 500€',
            3 => 'entre 500€ et 1000€',
            4 => 'plus de 1000€',
        );
        $budget = new Zend_Form_Element_Select('budget');
        $budget->setRequired(false)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty', true, array('messages' => 'Cannot be empty'))
                ->AddMultiOptions($budgetList)
                ->setRegisterInArrayValidator(false)
                ->setLabel("Prix :");
        
        $regionList = array('all' => 'Toute la France');
        foreach ($em->getRepository('\Default_Model_Base_Region')->getAllRegionsToArray() as $key => $value){
            $regionList[$key] = $value;
        }
        $region = new Zend_Form_Element_Select('region');
        $region->setLabel('Région :')
                ->setRequired(false)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->AddMultiOptions($regionList)
                ->setRegisterInArrayValidator(false);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton')
                ->setLabel('Rechercher');

        $this->addElements(array($name, $jobs, $budget, $region, $submit));
    }

}
