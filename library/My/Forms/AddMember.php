<?php

/**
 * Formulaire d'inscription
 * 
 * @author Maxime FRAPPAT
 * @version 1.0
 *
 */
class My_Forms_AddMember extends My_Form {

    /**
     * Construit le formulaire d'inscription
     *
     * @param unknown_type $options
     * @author Maxime FRAPPAT
     * @version 1.0
     * 
     * @return void
     */
    public function __construct($em) {
        parent::__construct();
        $this->setName('formInline')
                ->setAttrib('class', 'form');

        $lastname = new Zend_Form_Element_Text('lastname');
        $lastname->setLabel('Nom')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->getDecorator('label')->setOption('requiredSuffix', ' * ');

        $firstname = new Zend_Form_Element_Text('firstname');
        $firstname->setLabel('Prénom')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->getDecorator('label')->setOption('requiredSuffix', ' * ');

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('E-mail')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->addValidator('regex', false, array('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/'))
                ->getDecorator('label')->setOption('requiredSuffix', ' * ');

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Mot de passe')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->getDecorator('label')->setOption('requiredSuffix', ' * ');

        $password2 = new Zend_Form_Element_Password('password2');
        $password2->setLabel('Vérification du mot de passe')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->addValidator('identical', false, array('token' => 'password'))
                ->getDecorator('label')->setOption('requiredSuffix', ' * ');

        $phone = new Zend_Form_Element_Text('phone');
        $phone->setLabel('Téléphone')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');

        $fax = new Zend_Form_Element_Text('fax');
        $fax->setLabel('Fax')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');

        $isProfessionnal = new Zend_Form_Element_Checkbox('isProfessionnal');
        $isProfessionnal->setLabel('Je suis un professionnel')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim');

        $siretOrSiren = new Zend_Form_Element_Text('siret');
        $siretOrSiren->setLabel('N°Siret ou Siren')
                ->addFilter('StripTags')
                ->addFilter('StringTrim');
        
        $departmentList = $em->getRepository('\Default_Model_Base_Department')->getAllDepartmentsToArray();
        $department = new Zend_Form_Element_Select('department');
        $department->setLabel('Votre secteur :')
                ->setRequired(false)
                ->AddMultiOptions($departmentList);

        $company = new Zend_Form_Element_Text('company_name');
        $company->setLabel('Nom de l\'entreprise')
                ->addFilter('StripTags')
                ->addFilter('StringTrim');
        
        $sponsor = new Zend_Form_Element_Text('sponsor');
        $sponsor->setLabel('Parain')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('regex', false, array('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/'));
        
        $captcha = new Zend_Form_Element_Captcha(
                'captcha',
                array('label' => 'Recopiez les caractères suivant ',
                        'captcha' => array(
                                'captcha' => 'Image',
                                'wordLen' => 6,
                                'timeout' => 300,
                                'font' => 'fonts/DejaVuSans.ttf',
                                'imgDir' => 'captchas/',
                                'imgUrl' => Zend_Registry::get('assetBasePath').'captchas',
                                'dotNoiseLevel' => 10,
                                'lineNoiseLevel' => 5
                )));
        $captcha->getDecorator('label')->setOption('requiredSuffix', ' * ');

        $cgv = new Zend_Form_Element_Checkbox('cgv');
        $cgv->setLabel('J\'accepte les conditions générales de ventes et d\'utilisation')
                ->setRequired(true)
                ->setUncheckedValue(null)
                ->addFilter('StripTags')
                ->addFilter('StringTrim');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton')
                ->setLabel('Créer mon compte');

        $this->addElements(array($lastname, $firstname, $email, $password, $password2, $phone, $fax,
            $isProfessionnal, $siretOrSiren, $department, $company, $sponsor, $captcha, $cgv, $submit));

        $this->addDisplayGroup(array('lastname', 'firstname', 'email'), 'required_group');
        $this->addDisplayGroup(array('password', 'password2'), 'password_group');
        $this->addDisplayGroup(array('phone', 'fax', 'isProfessionnal', 'siret', 'company_name', 'sponsor'), 'optionnal_group');
        $this->addDisplayGroup(array('captcha', 'cgv'), 'captcha_group');
        $this->addDisplayGroup(array('submit'), 'submit_group');
    }

}
