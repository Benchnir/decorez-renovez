<?php

/**
 * Formulaire d'ajout de métier dans s liste de service
 * 
 * @author Maxime FRAPPAT
 * @version 1.0
 *
 */
class My_Forms_AddJob extends My_Form
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
        $this->setName('newjob');
        
        $categoryList = $em->getRepository('\Default_Model_Base_JobCategory')->getAllJobCategoriesToArray();
        $category = new Zend_Form_Element_Select('category');
        $category->setRequired(true)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty')
        ->AddMultiOptions($categoryList);

        $name = new Zend_Form_Element_Text('name');
        $name->setRequired(true)
        ->setLabel('Nom du métier')
        ->addFilter('StripTags')
        ->addFilter('StringTrim');

        $submit = new Zend_Form_Element_Submit('newJob');
        $submit->setAttrib('id', 'newJob')
        ->setLabel('Confirmer');

        $this->addElements(array($category, $name, $submit));
    }
}
