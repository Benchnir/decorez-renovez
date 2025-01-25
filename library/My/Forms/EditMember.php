<?php

/**
 * Formulaire de modification d'un membre
 * 
 * @author Maxime FRAPPAT
 * @version 1.0
 *
 */
class My_Forms_EditMember extends My_Form {

    /**
     * Construit le formulaire d'inscription
     *
     * @param unknown_type $options
     * @author Maxime FRAPPAT
     * @version 1.0
     * 
     * @return void
     */
    public function __construct($em, $facebookuser = false) {
        parent::__construct();
        $this->setName('formInline')
                ->setAttrib('class', 'form');

        $jobList = array_merge($em->getRepository('\Default_Model_Base_Job')->getAllJobsToArray(), array(1 => ''));
        $mainJob = new Zend_Form_Element_Select('mainJob');
        $mainJob->setLabel('Spécialité')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->AddMultiOptions($jobList)
                ->setRegisterInArrayValidator(false);
        
        $departmentList = array_merge($em->getRepository('\Default_Model_Base_Department')->getAllDepartmentsToArray(), array(1 => ''));
        $department = new Zend_Form_Element_Select('department');
        $department->setLabel('Département')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->AddMultiOptions($departmentList)
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

        $oldPassword = new Zend_Form_Element_Password('oldPassword');
        $oldPassword->setLabel('Ancien mot de passe :')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Mot de passe :')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');

        $password2 = new Zend_Form_Element_Password('password2');
        $password2->setLabel('Vérification du mot de passe :')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');
        if($facebookuser){
            $oldPassword->setAttrib('readonly',true);
            $password->setAttrib('readonly',true);
            $password2->setAttrib('readonly',true);
        }

        $phone = new Zend_Form_Element_Text('phone');
        $phone->setLabel('Téléphone :')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');
        
        
        $avatar = new Zend_Form_Element_File('avatar');
        $avatar->setLabel('Photo Principale :')
                ->addValidator('Count', false, 1);

        $submit = new Zend_Form_Element_Submit('submitEditMember');
        $submit->setAttrib('id', 'submitEditMember')
                ->setLabel('Enregister')
                ->removeDecorator('DtDdWrapper');

        $this->addElements(array($lastname, $firstname, $oldPassword, $password, $password2, $phone, $avatar, $submit));

        $this->addDisplayGroup(array('lastname', 'firstname'), 'required_group');
        $this->addDisplayGroup(array('oldPassword', 'password', 'password2'), 'password_group');
        $this->addDisplayGroup(array('phone'), 'optionnal_group');
        $this->addDisplayGroup(array('submitEditMember'), 'submit_group');
    }

}

