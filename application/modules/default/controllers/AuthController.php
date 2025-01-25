<?php

class AuthController extends My_Controller_Action
{

    public function init()
    {
        parent::init();
    }
    
    public function facebookloginAction()
    {
        $this->_helper->layout->disableLayout();
        
        $fbid = $this->getRequest()->getPost('fbid', null);
        $fname = $this->getRequest()->getPost('fname', null);
        $lname = $this->getRequest()->getPost('lname', null);
        $email = $this->getRequest()->getPost('email', null);
        
        // Vérification de l'email
        $user = $this->_em->createQueryBuilder()
            ->select('m')
            ->from('\Default_Model_Base_Member m')
            ->andWhere('m.facebook_id = :fbid')
            ->setParameter('fbid', $fbid)
            ->getQuery()
            ->getOneOrNullResult();
        
        if($user == null){
            // Vérification de l'email
            $verifEmail = $this->_em->createQueryBuilder()
                    ->select('m')
                    ->from('\Default_Model_Base_Member m')
                    ->andWhere('m.email = :email')
                    ->setParameter('email', $email)
                    ->getQuery()
                    ->getOneOrNullResult();
            if($verifEmail == null){
                $membre = new \Default_Model_Base_Member();
                $membre->setFacebookId($fbid);
                $membre->setFirstname($fname);
                $membre->setLastname($lname);
                $membre->setEmail($email);
                $membre->setSalt(md5(uniqid(rand(), true)));
                $membre->setPassword('');
                $membre->setAvatar(My_Controller_Action::AVATAR_BASE);
                $membre->setRole('user');
                $membre->setIsActive(true);

                $this->_em->persist($membre);
                $this->_em->flush();
            } else {
                $verifEmail->setFacebookId($fbid);
                $this->_em->flush();
                $email = $verifEmail->getEmail();
            }
        } else {
            $email = $user->getEmail();
        }
        
        $adapter = new My_Auth_MemberAdapter($email, '', true);
        Zend_Auth::getInstance()->authenticate($adapter);
    }
    public function facebooklogoutAction()
    {
        $this->_helper->layout->disableLayout();
        Zend_Auth::getInstance()->clearIdentity();
    }

    public function getResourceId() {
        return 'authController';
    }

}

