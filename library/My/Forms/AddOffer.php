<?php

/**
 * Formulaire d'ajout de métier dans s liste de service
 * 
 * @author Maxime FRAPPAT
 * @version 1.0
 *
 */
class My_Forms_AddOffer extends My_Form
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
        $this->setName('newOffer');
        
        $price = new Zend_Form_Element_Text('price');
        $price->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('Int')
                ->setLabel('Votre prix :');

        $description = new Zend_Form_Element_Textarea('description');
        $description->setLabel('Description :')
                ->setRequired(false)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Envoyer');

        $this->addElements(array($price, $description, $submit));
    }
}
