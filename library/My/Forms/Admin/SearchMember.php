<?php

/**
 * Formulaire de recherche de membre
 * 
 * @author Maxime FRAPPAT
 * @version 1.0
 *
 */
class My_Forms_Admin_SearchMember extends Zend_Form
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
		$this->setName('search');
		
		$email = new Zend_Form_Element_Text('email');
		$email->setLabel('Email')
		->addFilter('StripTags')
		->addFilter('StringTrim');
		
		$firstname = new Zend_Form_Element_Text('firstname');
		$firstname->setLabel('Prénom')
		->addFilter('StripTags')
		->addFilter('StringTrim');
                
                $lastname = new Zend_Form_Element_Text('lastname');
		$lastname->setLabel('Nom')
		->addFilter('StripTags')
		->addFilter('StringTrim');
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton')
		->setLabel('Rechercher');

		$this->addElements(array($email, $firstname, $lastname, $submit));
	}
}
