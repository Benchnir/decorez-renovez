<?php

/**
 * Formulaire de contact
 * 
 * @author Maxime FRAPPAT
 * @version 1.0
 *
 */
class My_Forms_Contact extends Zend_Form
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
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->setName('contact');

        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Votre nom :')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Votre email :')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->addValidator('EmailAddress');

        $subject = new Zend_Form_Element_Text('subject');
        $subject->setLabel('Sujet :')
                ->addFilter('StripTags')
                ->addFilter('StringTrim');

        $message = new Zend_Form_Element_Textarea('message');
        $message->setLabel('Votre message')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->setAttrib('cols', '50')
                ->setAttrib('rows', '5');

        $captcha = new Zend_Form_Element_Captcha(
                        'captcha',
                        array('label' => 'Recopiez les caractères suivant :',
                            'captcha' => array(
                                'captcha' => 'Image',
                                'wordLen' => 6,
                                'timeout' => 300,
                                'font' => 'fonts/DejaVuSans.ttf',
                                'imgDir' => 'captchas/',
                                'imgUrl' => '/captchas/',
                        )));

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton')
                ->setLabel('Envoyer');

        $this->addElements(array($name, $email, $subject, $message, $captcha, $submit));
    }

}
