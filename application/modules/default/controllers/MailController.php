<?php

class MailController extends My_Controller_Action
{

    public function init()
    {
        parent::init();
    }
    
    public function mailnewsAction()
    {
        // Configuration
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
        
        // get the user list
        $users = $this->_em->createQueryBuilder()
                ->select('m')
                ->from('\Default_Model_Base_Member', 'm')
                ->andWhere('m.is_active = true')
                ->getQuery()
                ->getResult();
        
        // get last announcement since the last month
        $currentdate = new \DateTime();
        $lastmonth = $currentdate->sub(new DateInterval('P1M'));
        $annonces = $this->_em->createQueryBuilder()
                ->select('a')
                ->from('\Default_Model_Base_Announcement', 'a')
                ->andWhere('a.created_at > :sinceonemonth')
                ->setParameter('sinceonemonth', $lastmonth)
                ->orderBy('a.created_at', 'DESC')
                ->setMaxResults(5)
                ->getQuery()
                ->getResult();
        
        foreach ($annonces as $annonce) {
            var_dump($annonce->getDescription()); echo '<br/>';
        }
        
        // get last service soince the last month
        $profiles = $this->_em->createQueryBuilder()
                ->select('m')
                ->from('\Default_Model_Base_Member', 'm')
                ->leftJoin('m.feature', 'f')
                ->leftJoin('m.service', 's')
                ->andWhere('m.created_at > :sinceonemonth')
                ->setParameter('sinceonemonth', $lastmonth)
                ->orderBy('m.created_at', 'DESC')
                ->setMaxResults(5)
                ->getQuery()
                ->getResult();
        
        foreach ($profiles as $profil) {
            var_dump($profil->getLastname()); echo '<br/>';
        }
        
        // send a mail to each user
        foreach ($users as $user) {
            $this->html = new Zend_View();
            $this->html->setScriptPath('../application/modules/default/views/scripts/mail/');
            $this->html->member = $user;
            if($user->getRole() == 'pro'){
                $this->html->annonces = $annonces;
                $body = $this->html->render('pronews.phtml');
            } else {
                $this->html->profiles = $profiles;
                $body = $this->html->render('usernews.phtml');
            }
            My_Mail_HtmlMail::sendMail(
                '[Decorez-Renovez] Inscription', 
                $body, 
                array($user)
            ); 
        }
        $this->_helper->layout->disableLayout();
    }
    
    public function getResourceId() {
        return 'mailController';
    }
}

