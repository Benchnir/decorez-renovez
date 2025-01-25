<?php

/**
 * Formulaire d'ajout de commentaires
 * 
 * @author Maxime FRAPPAT
 * @version 1.0
 *
 */
class My_Forms_AddComment extends My_Form {

    /**
     * Construit le formulaire d'ajout de commentaires
     *
     * @param unknown_type $options
     * @author Maxime FRAPPAT
     * @version 1.0
     * 
     * @return void
     */
    public function __construct($options = null) {
        parent::__construct($options);
        $this->setName('formAddComment')
                ->setAttrib('class', 'form');

        $type = new Zend_Form_Element_Radio('type');
        $type->setLabel('Votre avis est plutot...')
                ->setValue(My_Controller_Action::COMMENT_TYPE_NEUTRAL)
                ->addMultiOptions(array(
                    My_Controller_Action::COMMENT_TYPE_POSITIVE => 'Positif',
                    My_Controller_Action::COMMENT_TYPE_NEUTRAL => 'Neutre',
                    My_Controller_Action::COMMENT_TYPE_NEGATIVE => 'Négatif'
                ))
                ->setSeparator(' ');

        $message = new Zend_Form_Element_Textarea('message');
        $message->setLabel('Vos commentaires :')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->setAttrib('rows', '6');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton')
                ->setLabel('Soumettre');

        $this->addElements(array($type, $message, $submit));
    }

}
