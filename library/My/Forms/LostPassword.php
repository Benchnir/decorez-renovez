<?php

class My_Forms_LostPassword extends My_Form {

    function __construct($options = null) {
        parent::__construct($options);
        $this->setName('recuperation')
                ->setMethod('post');

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->addValidator('EmailAddress');

        $captcha = new Zend_Form_Element_Captcha(
                        'captcha',
                        array('label' => 'Recopiez les caractères suivant :',
                            'captcha' => array(
                                'captcha' => 'Image',
                                'wordLen' => 6,
                                'timeout' => 300,
                                'font' => 'fonts/DejaVuSans.ttf',
                                'imgDir' => 'captchas/',
                                'imgUrl' => Zend_Registry::get('assetBasePath').'captchas'
                        )));

        $submit = new Zend_Form_Element_Submit('validation');
        $submit->setLabel('Valider');
        $submit->setAttrib('id', 'submitbutton');

        $this->addElements(
                array(
                    $email,
                    $captcha,
                    $submit));
    }

}

