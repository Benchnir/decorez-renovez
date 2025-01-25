<?php

/**
 * Formulaire d'authentification
 * 
 * @author Maxime FRAPPAT
 * @version 1.0
 *
 */
class My_Forms_Auth extends My_Form
{
	/**
	 * Construit le formulaire d'authentification
	 *
	 * @param unknown_type $options
	 * @author Maxime FRAPPAT
	 * @version 1.0
	 * 
	 * @return void
	 */
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->setName('auth');
		
		$email = new Zend_Form_Element_Text('email');
		$email->setLabel('Email')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$pass = new Zend_Form_Element_Password('password');
		$pass->setLabel('Mot de passe')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$submit = new Zend_Form_Element_Submit('login');
		$submit->setAttrib('id', 'submitbutton')
		->setLabel('Se connecter');

		$this->addElements(array($email, $pass, $submit));
	}
}
