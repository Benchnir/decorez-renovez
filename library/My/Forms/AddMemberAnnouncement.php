<?php

/**
 * Formulaire d'inscription
 * 
 * @author Maxime FRAPPAT
 * @version 1.0
 *
 */
class My_Forms_AddMemberAnnouncement extends My_Form {

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
        
        
        $description = new Zend_Form_Element_Textarea('description');
        $description->setLabel('Description et besoins du chantier :')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->setAttrib('rows', '9')
                ->setAttrib('cols', '45');
        
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

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton')
                ->setLabel('Créer mon compte');

        $this->addElements(array($lastname, $firstname, $email, $password, $password2, $phone, $fax,
            $description, $department, $budget, $duration, $jobs, $captcha, $submit));
    }

}
