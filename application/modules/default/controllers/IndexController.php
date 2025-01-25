<?php

class IndexController extends My_Controller_Action
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        $user = new Zend_Session_Namespace(My_Controller_Action::USER_SESSION_NAME);

        // Search request
        $jobs = $this->_em->createQueryBuilder()
                ->select('j')
                ->from('\Default_Model_Base_Job', 'j')
                ->andWhere('j.is_validate = true')
                ->getQuery()
                ->getResult();
                
        $this->view->jobs = $jobs;

        // Region
        $regionId = intval($this->_getParam('region', 0));
        
        if ($regionId != 0)
        {
            // Region exists ?
            $qb = $this->_em->createQueryBuilder();
            $qb->add('select', 'r')
                    ->add('from', '\Default_Model_Base_Region r')
                    ->add('where', 'r.id = ?1')
                    ->setParameter(1, $regionId);
            $query = $qb->getQuery();
            try
            {
                $region = $query->getSingleResult();
                $user->region = $region;
                $this->_messenger->addMessage('Vous avez sélectionné la région :' . $region->getName(), My_Messenger::TYPE_INFO);
                $this->_redirect('/service/index');
            }
            catch (\Doctrine\ORM\NoResultException $e)
            {
                $this->_messenger->addMessage('La région n\'est pas valide.', My_Messenger::TYPE_ERROR);
            }
        }
        else
        {
            if (isset($user->region) && $user->region != null)
                $this->_messenger->addMessage('Vous avez sélectionné la région :' . $user->region->getName(), My_Messenger::TYPE_INFO);
        }
        $this->_helper->layout()->disableLayout(); 
    }

    public function connexionAction()
    {
        $form = new My_Forms_Auth();
        $this->view->form = $form;

        if ($this->getRequest()->isPost())
        {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData))
            {
                $adapter = new My_Auth_MemberAdapter($formData['email'], $formData['password']);
                $result = Zend_Auth::getInstance()->authenticate($adapter);
                if (Zend_Auth::getInstance()->hasIdentity())
                {
                    $this->_redirect('membre/index');
                }
            }
            $this->view->form = $form;
        }
    }

    public function recuperationAction()
    {
        $this->_saveToHistory();

        $form = new My_Forms_LostPassword();
        $this->view->form = $form;

        if ($this->_request->isPost())
        {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData))
            {
                $qb = $this->_em->createQueryBuilder();
                $qb->add('select', 'm')
                        ->add('from', '\Default_Model_Base_Member m')
                        ->add('where', 'm.email = ?1')
                        ->setParameter(1, $formData['email']);
                $query = $qb->getQuery();
                $membre = $query->getSingleResult();

                if ($membre != null)
                {
                    $new_pass = substr(uniqid(rand(), true), 0, 8);
                    $membre->setPassword(md5($new_pass.$membre->getSalt()));
                    $this->_em->merge($membre);
                    $this->_em->flush();

                    // Envoie du mail au membre
                    $configApplication = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
                    $configMail = array(
                        'ssl' => $configApplication->mail->ssl,
                        'port' => $configApplication->mail->port,
                        'auth' => $configApplication->mail->auth,
                        'username' => $configApplication->mail->username,
                        'password' => $configApplication->mail->password
                    );
                    $transport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $configMail);
                    Zend_Mail::setDefaultTransport($transport);

                    My_Mail_HtmlMail::sendMail(
                            '[Decorez-Renovez] Inscription', 
                            'Bonjour,<br /><br />Suite à votre demande nous avons réinitialisé votre mot de passe. Voici votre nouveau mot de passe :<br /><br />Mot de passe : ' . $new_pass . '<br /><br />L\'équipe Decorez-Renovez.fr', 
                            array($membre)
                    ); 
                    $this->_messenger->addMessage("La réinitialisation à réussie ! Vous allez recevoir un email sous 24h avec vos nouveaux identifiants.", My_Messenger::TYPE_OK);
                }
                else
                {
                    $this->_messenger->addMessage("Cette adresse email n'est pas valide !", My_Messenger::TYPE_ERROR);
                }
            }
            else
            {
                $this->_messenger->addMessage("Le formulaire n'est pas valide !", My_Messenger::TYPE_ERROR);
            }
        }
    }

    public function deconnexionAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect('/');
    }
    
    public function contactAction()
    {
        $form = new \My_Forms_Contact();
        $this->view->form = $form;

        if ($this->_request->isPost())
        {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData))
            {
                // Envoie du mail au membre
                $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
                
                $configSSL = array('ssl' => $config->mail->ssl,
                'port' => $config->mail->port,
                'auth' => $config->mail->auth,
                'username' => $config->mail->username,
                'password' => $config->mail->password);
                $transport = new Zend_Mail_Transport_Sendmail($config->mail->smtp, $configSSL);
                $mail = new Zend_Mail();
                Zend_Mail::setDefaultTransport($transport);

                $message = 'Ceci est un message envoyé par \'' . $formData['name'] . '\' (' . $formData['email'] . ') via le formulaire de contact du site travauxafaire.fr.<br /><br />';
                $message .= 'Sujet : ' . $formData['subject'] . '<br />';
                $message .= 'Message : ' . nl2br($formData['message']);

                $mail->setBodyHtml($message);
                $mail->setFrom('no-reply@travauxafaire.fr', 'Travauxafaire.fr');
                $mail->addTo($config->mail->contactAddress);
                $mail->setSubject('[Contact] ' . $formData['subject']);

                try
                {
                    $mail->send($transport);
                    $this->_messenger->addMessage('Votre message a bien été envoyé. Notre équipe fait tout sont possible pour le traiter dans les plus brefs délais.', My_Messenger::TYPE_OK);
                }
                catch (Zend_Mail_Transport_Exception $e)
                {
                    $this->_messenger->addMessage('Une erreur est survenue pendant l\'envoie de l\'email, veuillez réessayer ultérieurement.', My_Messenger::TYPE_ERROR);
                }
                catch (Zend_Mail_Protocol_Exception $e)
                {
                    $this->_messenger->addMessage('Une erreur est survenue pendant l\'envoie de l\'email, veuillez réessayer ultérieurement.', My_Messenger::TYPE_ERROR);
                }
                catch (Exception $e)
                {
                    $this->_messenger->addMessage('Une erreur est survenue pendant l\'envoie de l\'email, veuillez réessayer ultérieurement.', My_Messenger::TYPE_ERROR);
                }
            }
            else
            {
                $this->_messenger->addMessage('Certains champs ne sont pas renseignés correctement.', My_Messenger::TYPE_ERROR);
            }
        }
    }

    public function deniedAction()
    {
        
    }

    public function aProposAction()
    {
        
    }

    public function cgvAction()
    {
        
    }
    
    public function publiciteAction()
    {
        
    }
    
    public function infosLegalesAction()
    {
        
    }
    
    public function reglesDeDiffusionAction()
    {
        
    }
    
    public function partenaireAction()
    {
        
    }

    public function getResourceId() {
        return 'indexController';
    }

}

