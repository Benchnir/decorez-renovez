<?php

/**
 * Formulaire d'upload d'un avatar
 *
 * @author Lordinaire
 * @version 0.1
 *
 */
class My_Forms_EditAvatar extends My_Form {
    /**
     * Construit le formulaire d'upload d'un avatar
     *
     * @author Lordinaire
     * @version 0.1
     *
     * @return void
     */
    public function __construct($options = null) {
        parent::__construct($options);
        $this->setName('formInline')
                ->setEnctype(Zend_Form::ENCTYPE_MULTIPART);

        $avatar = new Zend_Form_Element_File('avatar');
        $avatar->setLabel('Votre avatar')
                ->addValidator('Count', false, 1)
                ->addValidator('Size', false, '1MB')
                // @todo remplacer 'Extension' par 'MIMEType'
                ->addValidator('Extension', false, 'jpg');

        $no_avatar = new Zend_Form_Element_Checkbox('no_avatar');
        $no_avatar->setLabel('Supprimer mon avatar actuel');

        $submit = new Zend_Form_Element_Submit('submitEditAvatar');
        $submit->setAttrib('id', 'submitEditAvatar')
                ->setLabel('Enregister');

        $this->addElements(array($avatar, $no_avatar, $submit));
    }
}
