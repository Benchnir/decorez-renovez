<?php

/**
 * Formulaire de modification d'un membre
 * 
 * @author Maxime FRAPPAT
 * @version 1.0
 *
 */
class My_Forms_EditProMember extends My_Form {

    /**
     * Construit le formulaire d'inscription
     *
     * @param unknown_type $options
     * @author Maxime FRAPPAT
     * @version 1.0
     * 
     * @return void
     */
    public function __construct($em, $user_id) {
        parent::__construct();
        $this->setName('formInline')
                ->setAttrib('class', 'form');

        /* @var $user \Default_Model_Base_Member */
        $user = $em->find('\Default_Model_Base_Member', $user_id);
        $arrayServiceJobs = array();
        $userServiceJobs = $user->getService()->getJobs();
        foreach ($userServiceJobs->getValues() as $serviceJobs) {
            $arrayServiceJobs[$serviceJobs->getId()] = $serviceJobs->getJob()->getName();
        }
        $mainJob = new Zend_Form_Element_Select('mainJob');
        $mainJob->setLabel('Spécialité')
                ->setRequired(false)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->AddMultiOptions($arrayServiceJobs)
                ->setRegisterInArrayValidator(false);
        
        $lastname = new Zend_Form_Element_Text('lastname');
        $lastname->setLabel('Nom :')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');

        $firstname = new Zend_Form_Element_Text('firstname');
        $firstname->setLabel('Prénom :')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');
        
        $avatar1 = new Zend_Form_Element_File('avatar1');
        $avatar1->setLabel('Photo Principale :')
                ->addValidator('Count', false, 1);
        
        $avatar2 = new Zend_Form_Element_File('avatar2');
        $avatar2->setLabel('Photos secondaires :')
                ->addValidator('Count', false, 1);
        
        $avatar3 = new Zend_Form_Element_File('avatar3');
        $avatar3->addValidator('Count', false, 1);

        $phone = new Zend_Form_Element_Text('phone');
        $phone->setLabel('Téléphone :')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');

        $fax = new Zend_Form_Element_Text('fax');
        $fax->setLabel('Fax :')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');

        $siretOrSiren = new Zend_Form_Element_Text('siretOrSiren');
        $siretOrSiren->setLabel('N°Siret ou Siren :')
                ->addFilter('StripTags')
                ->addFilter('StringTrim');

        $company = new Zend_Form_Element_Text('company');
        $company->setLabel('Nom de l\'entreprise :')
                ->addFilter('StripTags')
                ->addFilter('StringTrim');
        
        $experience = new Zend_Form_Element_Text('experience');
        $experience->setRequired(FALSE)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('Int')
                ->setLabel('Experience :');
        
        $description = new Zend_Form_Element_Textarea('serviceDescription');
        $description->setLabel('Mes particularités en 3 mots :')
                ->setRequired(false)
                ->setAttrib('COLS', '40')
                ->setAttrib('ROWS', '15')
                ->addFilter('StripTags')
                ->addFilter('StringTrim');

        $submit = new Zend_Form_Element_Submit('submitEditMember');
        $submit->setAttrib('id', 'submitEditMember')
                ->setLabel('Enregister')
                ->removeDecorator('DtDdWrapper');

        $this->addElements(array($mainJob, $lastname, $firstname, $avatar1, $avatar2, $avatar3, $phone, $fax,
            $siretOrSiren, $company, $experience, $description, $submit));
    }

}

