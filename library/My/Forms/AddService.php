<?php

/**
 * Formulaire de proposition de ses services
 * 
 * @author Maxime FRAPPAT
 * @version 1.0
 *
 */
class My_Forms_AddService extends My_Form
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
        $this->setName('inscription');

        $description = new Zend_Form_Element_Textarea('description');
        $description->setLabel('Description :')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');

        $jobList = $em->getRepository('\Default_Model_Base_Job')->getAllJobsToArray();
        $mainJob = new Zend_Form_Element_Select('mainJob');
        $mainJob->setLabel('Spécialité :')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->AddMultiOptions($jobList);

        $experience = new Zend_Form_Element_Text('experience');
        $experience->setLabel('Experience :')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton')
                ->setLabel('Créer mon compte');

        $this->addElements(array($mainJob, $experience, $description, $submit));
    }

}
