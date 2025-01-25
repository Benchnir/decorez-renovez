<?php

/**
 * Formulaire d'ajout de métier dans s liste de service
 * 
 * @author Maxime FRAPPAT
 * @version 1.0
 *
 */
class My_Forms_EditServiceJob extends My_Form
{
    /**
     * Construit le formulaire
     *
     * @param unknown_type $options
     * @author Maxime FRAPPAT
     * @version 1.0
     * 
     * @return void
     */
    public function __construct($em)
    {
        parent::__construct($em);
        $this->setName('addServiceJob');

        $rangeEval = new Zend_Validate_Between(array('min' => 0, 'max' => 5));
        $rangePrix = new Zend_Validate_Between(array('min' => 0, 'max' => 9999));

        $jobList = $em->getRepository('\Default_Model_Base_Job')->getAllJobsToArray();
        $jobList['new'] = 'Ajouter un nouveau Job';
        $job = new Zend_Form_Element_Select('job');
        $job->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->AddMultiOptions($jobList)
                ->setLabel('Mes prix pour');

        $evaluation = new Zend_Form_Element_Text('evaluation');
        $evaluation->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('Int')
                ->addValidator($rangeEval)
                ->setLabel('Ma note');

        $prix2 = new Zend_Form_Element_Text('prix2');
        $prix2->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('Int')
                ->addValidator($rangePrix)
                ->setLabel('prix2');

        $prix10 = new Zend_Form_Element_Text('prix10');
        $prix10->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('Int')
                ->addValidator($rangePrix)
                ->setLabel('prix10');

        $prix30 = new Zend_Form_Element_Text('prix30');
        $prix30->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('Int')
                ->addValidator($rangePrix)
                ->setLabel('prix30');

        $this->addElements(array($job, $evaluation, $prix2, $prix10, $prix30));
    }
}
