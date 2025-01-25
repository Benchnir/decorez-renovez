<?php

class MembreController extends My_Controller_Action
{

    public function init()
    {
        parent::init();
        $this->_acl->allow('user', $this->getResourceId(), 'index');
        $this->_acl->allow('pro', $this->getResourceId(), 'index');
        $this->_acl->allow('user', $this->getResourceId(), 'espaceclient');
        $this->_acl->allow('pro', $this->getResourceId(), 'espacepro');
        $this->_acl->allow('user', $this->getResourceId(), 'edituser');
    }

    public function indexAction()
    {
        $this->checkUser();

        if($this->_user->getRole() == 'pro'){
            $this->_redirect('/membre/espacepro');
        } else {
            $this->_redirect('/membre/espaceclient');
        }
    }
    public function espaceproAction()
    {
        $this->checkUser();
        
        // Get member
        /* @var $member \Default_Model_Base_Member */
        $member = $this->_em->find('\Default_Model_Base_Member', $this->_user->getId());
        if(!$member->getFeature()->isPro()){
            $this->_redirect('feature/suscribepro');
        }
        $this->view->member = $member;
        
        $avatars = $this->_em->createQueryBuilder()
                ->select('a')
                ->from('\Default_Model_Base_MemberAvatar', 'a')
                ->andWhere('a.member = :member_id')
                ->setParameter('member_id', $member->getId())
                ->orderBy('a.is_main', 'DESC')
                ->setMaxResults(3)
                ->getQuery()
                ->getResult();
        $this->view->avatars = $avatars;
        
        $form = new \My_Forms_EditProMember($this->_em, $this->_user->getId());
        
        $mainJob = $member->getService()->getMainJob();
        if($mainJob != null){
            $form->getElement('mainJob')->setValue($mainJob->getId());
        }
        $form->getElement('lastname')->setValue($member->getLastname());
        $form->getElement('firstname')->setValue($member->getFirstname());
        $form->getElement('phone')->setValue($member->getPhone());
        $form->getElement('fax')->setValue($member->getFax());
        $form->getElement('siretOrSiren')->setValue($member->getSiret());
        $form->getElement('company')->setValue($member->getCompanyName());
        $form->getElement('experience')->setValue($member->getService()->getExperience());
        $form->getElement('serviceDescription')->setValue($member->getService()->getDescription());
        $this->view->memberForm = $form;
        
        $this->view->serviceJobForms = array();
        foreach($member->getService()->getJobs()->getValues() as $serviceJob){
            $serviceJobForm = new \My_Forms_EditServiceJob($this->_em);
            $serviceJobForm->getElement('job')->setValue($serviceJob->getJob()->getId());
            $serviceJobForm->getElement('evaluation')->setValue($serviceJob->getEvaluation());
            $serviceJobForm->getElement('prix2')->setValue($serviceJob->getPrice1());
            $serviceJobForm->getElement('prix10')->setValue($serviceJob->getPrice2());
            $serviceJobForm->getElement('prix30')->setValue($serviceJob->getPrice3());
            $this->view->serviceJobForms[$serviceJob->getId()] = $serviceJobForm;
        }
        
        //Service
        $this->view->service = $member->getService();
        //Feature
        $this->view->feature = $member->getFeature();
        //Comments
        $qb = $this->_em->createQueryBuilder();
        $qb->select('c')
                ->from('\Default_Model_Base_Comment', 'c')
                ->where('c.service = :serviceId')
                ->andWhere('c.status = :status')
                ->orderBy('c.created_at', 'DESC')
                ->setParameters(array('serviceId' => $member->getService()->getId(),
                    'status' => My_Controller_Action::COMMENT_STATUS_ACTIVE));

        $query = $qb->getQuery();
        $comments = $query->getResult();
        $this->view->service->commentsInfos = $this->calcCommentsInfos($comments);
    }
    public function espaceclientAction()
    {
        $this->checkUser();
        
        // Get member
        $member = $this->_em->find('\Default_Model_Base_Member', $this->_user->getId());
        $this->view->member = $member;
        
        $form = new \My_Forms_EditMember($this->_em);
        $form->getElement('lastname')->setValue($member->getLastname());
        $form->getElement('firstname')->setValue($member->getFirstname());
        $form->getElement('phone')->setValue($member->getPhone());
        $this->view->memberForm = $form;
        
        $avatars = $this->_em->createQueryBuilder()
                        ->select('a')
                        ->from('\Default_Model_Base_MemberAvatar', 'a')
                        ->andWhere('a.member = :member_id')
                        ->setParameter('member_id', $member->getId())
                        ->getQuery()
                        ->getResult();
        $this->view->avatars = $avatars;
        
        
        // list of annoucement
        $qb = $this->_em->createQueryBuilder();
        $qb->add('select', 'a')
                ->add('from', '\Default_Model_Base_Announcement a')
                ->add('where', 'a.member = ?1')
                ->setParameter(1, $this->_user->getId());
        $query = $qb->getQuery();
        $annonces = $query->getResult();

        $paginator = Zend_Paginator::factory($annonces);
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));
        $paginator->setItemCountPerPage($this->_getParam('resultat', 5));

        $this->view->annonces = $paginator;
    }

    public function inscriptionAction()
    {
        $form = new \My_Forms_AddMember($this->_em);
        $this->view->form = $form;

        $errors = false;
        if ($this->_request->isPost())
        {
            $formData = $this->_request->getPost();
            if (!$form->isValid($formData)) {
                $this->view->form = $form;
                return $this->render('inscription');
            }
            
            if ($form->isValid($formData))
            {
                // Vérification de l'email
                $qb = $this->_em->createQueryBuilder();
                $qb->add('select', 'm')
                        ->add('from', '\Default_Model_Base_Member m')
                        ->add('where', 'm.email = ?1')
                        ->setParameter(1, $formData['email']);
                $query = $qb->getQuery();
                $sameEmail = $query->getResult();

                if ($sameEmail != null)
                {
                    $this->_messenger->addMessage('Cette adresse email est déjà utilisée.', My_Messenger::TYPE_ERROR);
                    $errors = true;
                }

                if ($errors === false)
                {
                    // Création
                    $membre = new \Default_Model_Base_Member();

                    // Required
                    $membre->setFirstName($formData['firstname']);
                    $membre->setLastName($formData['lastname']);
                    $membre->setEmail($formData['email']);
                    $membre->setSalt(md5(uniqid(rand(), true)));
                    $membre->setPassword(md5($formData['password'].$membre->getSalt()));
                    $membre->setAvatar(My_Controller_Action::AVATAR_BASE);
                    $membre->setIsActive(false);

                    // Optionnal
                    if (isset($formData['phone'])){
                        $membre->setPhone($formData['phone']);
                    }
                    if (isset($formData['fax'])){
                        $membre->setFax($formData['fax']);
                    }
                    if (isset($formData['isProfessionnal']) && $formData['isProfessionnal'] == true){
                        if(isset($formData['sponsor']) && $formData['sponsor'] != null){
                            $sponsor = $this->_em->createQueryBuilder()
                                    ->select('m')
                                    ->from('\Default_Model_Base_Member', 'm')
                                    ->andWhere('m.email = :email')
                                    ->setParameter('email', $formData['sponsor'])
                                    ->getQuery()
                                    ->getOneOrNullResult();
                            if($sponsor != null && $sponsor->getRole() == 'pro'){
                                $membre->setSponsor($sponsor);
                            }
                        }
                        if (isset($formData['siret'])){
                            $membre->setSiret($formData['siret']);
                        }
                        if (isset($formData['department'])){
                            $department = $this->_em->find('\Default_Model_Base_Department', $formData['department']);
                            if($department != null){
                                $membre->setDepartment($department);
                            }
                        }
                        if (isset($formData['company_name'])){
                            $membre->setCompanyName($formData['company_name']);
                        }
                        
                        $membre->setRole('pro');
                        
                        $service = new \Default_Model_Base_Service();
                        $service->setDescription('');
                        $service->setIsVisible(true);
                        $service->setMember($membre);
                        $this->_em->persist($service);
                        
                        $feature = new \Default_Model_Base_Feature();
                        $feature->setMember($membre);
                        $this->_em->persist($feature);
                    } else {
                        $membre->setRole('user');
                    }
                    $this->_em->persist($membre);
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
                    
                    $this->html = new Zend_View();//Create view
                    $this->html->setScriptPath('../application/modules/default/views/scripts/mail/');
                    $this->html->member = $membre;
                    My_Mail_HtmlMail::sendMail(
                            '[Decorez-Renovez] Inscription', 
                            $this->html->render('validationclient.phtml'), 
                            array($membre)
                    ); 
                    
                    $this->_messenger->addMessage('Un email vous a été envoyer pour valider votre compte', My_Messenger::TYPE_OK);
                    $this->view->form = null;
                }
            }
            else
            {
                $this->_messenger->addMessage('Certains champs ne sont pas renseignés correctement.', My_Messenger::TYPE_ERROR);
                $errors = true;
            }
        }
    }
    public function validationAction()
    {
        $id = $this->_request->getParam('id');
        // Get member
        /* @var $member \Default_Model_Base_Member */
        $member = $this->_em->find('\Default_Model_Base_Member', $id);
        if($member != null){
            // validation de l'utilisateur
            $member->setIsActive(true);
            $this->_em->flush();
            
            // Envoie du mail au membre
            $mailTemplate = ($member->getRole() == 'pro')?'inscriptionpro':'inscriptionclient';
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

            $this->html = new Zend_View();//Create view
            $this->html->setScriptPath('../application/modules/default/views/scripts/mail/');
            $this->html->member = $member;
            My_Mail_HtmlMail::sendMail(
                    '[Decorez-Renovez] Inscription', 
                    $this->html->render($mailTemplate.'.phtml'), 
                    array($member)
            ); 
            $this->_messenger->addMessage('Validation réussie !', My_Messenger::TYPE_OK);
        }      
        $this->_redirect('index/connexion');
    }
    // old modification
    public function modificationAction()
    {
        $this->checkUser();
        $facebook_user = ($this->_user->getFacebookId() != null);
        $pro_user = ($this->_user->getRole() == 'pro');
        if($pro_user){
            $form = new \My_Forms_EditProMember($this->_em);
        } else {
            $form = new \My_Forms_EditMember($this->_em, $facebook_user);
        }
        $this->view->form = $form;

        $form2 = new \My_Forms_EditAvatar();
        $this->view->formAvatar = $form2;

        $membre = $this->_user;

        $errors = false;
        if ($this->_request->isPost())
        {
            $formData = $this->_request->getPost();

            if (isset($formData['submitEditMember']))
            {
                if ($form->isValid($formData))
                {
                    // Vérification du mot de passe                
                    if (!empty($formData['password']) || !empty($formData['password2']) || !empty($formData['oldPassword']))
                    {
                        if (!isset($formData['oldPassword'])
                                || empty($formData['oldPassword'])
                                || md5($formData['oldPassword'].$this->_user->getSalt()) != $this->_user->getPassword())
                        {
                            $this->_messenger->addMessage('L\'ancien mot de passe ne correspond pas.', My_Messenger::TYPE_ERROR);
                            $errors = true;
                        }
                        if (!isset($formData['password']) || !isset($formData['password2'])
                                || empty($formData['password']) || empty($formData['password2'])
                                || $formData['password'] != $formData['password2'])
                        {
                            $this->_messenger->addMessage('Les mots de passe ne correspondent pas.', My_Messenger::TYPE_ERROR);
                            $errors = true;
                        }
                        if ($errors === false)
                            $membre->setPassword(md5($formData['password'].$this->_user->getSalt()));
                    }

                    // Vérification de l'email
                    if (isset($formData['email'])
                            && !empty($formData['email'])
                            && $formData['email'] != $this->_user->getEmail())
                    {
                        $qb = $this->_em->createQueryBuilder();
                        $qb->add('select', 'm')
                                ->add('from', '\Default_Model_Base_Member m')
                                ->add('where', 'm.email = ?1')
                                ->setParameter(1, $formData['email']);
                        $query = $qb->getQuery();
                        $sameEmail = $query->getResult();

                        if ($sameEmail != null)
                        {
                            $this->_messenger->addMessage('Cette adresse email est déjà utilisée.', My_Messenger::TYPE_ERROR);
                            $errors = true;
                        }
                        if ($errors === false)
                            $membre->setEmail($formData['email']);
                    }

                    if ($errors === false)
                    {

                        // Required
                        $membre->setFirstName($formData['firstname']);
                        $membre->setLastName($formData['lastname']);

                        // Optionnal
                        if (isset($formData['phone']))
                            $membre->setPhone($formData['phone']);
                        if (isset($formData['fax']))
                            $membre->setFax($formData['fax']);
                        if (isset($formData['isProfessionnal']))
                        if (isset($formData['siretOrSiren']))
                            $membre->setSiret($formData['siretOrSiren']);
                        if (isset($formData['company']))
                            $membre->setCompanyName($formData['company']);

                        $this->_em->merge($membre);
                        $this->_em->flush();
                        $this->_messenger->addMessage('Modification réussie !', My_Messenger::TYPE_OK);
                    }
                }
                else
                {
                    $this->_messenger->addMessage('Certains champs ne sont pas renseignés correctement.', My_Messenger::TYPE_ERROR);
                    $errors = true;
                }
            }

            // Gestion de l'avatar
            if (!$errors && isset($formData['submitEditAvatar']))
            {
                if ($form2->isValid($formData))
                {
                    $avatar_dir = $_SERVER["DOCUMENT_ROOT"] . '/avatars/';

                    if ($formData['no_avatar'])
                    {
                        $membre->setAvatar(My_Controller_Action::AVATAR_BASE);
                        $this->_em->merge($membre);
                        $this->_em->flush();
                        $this->_messenger->addMessage('Votre avatar a été supprimé !', My_Messenger::TYPE_OK);
                    }
                    else
                    {
                        $new_name = 'avatar_' . $this->_user->getId() . '.jpg';
                        $adapter = new My_File_Transfer_Adapter_Http();
                        $adapter->setDestination($avatar_dir);
                        $adapter->setRename($new_name);

                        if (!$adapter->receive())
                        {
                            $messages = $adapter->getMessages();
                            foreach ($messages as $message)
                            {
                                $this->_messenger->addMessage($message, My_Messenger::TYPE_ERROR);
                            }
                            return;
                        }
                        $membre->setAvatar($new_name);
                        $this->_em->merge($membre);
                        $this->_em->flush();
                        $this->_messenger->addMessage('Votre avatar a été modifié avec succès !', My_Messenger::TYPE_OK);
                        $this->_resizeAvatar($avatar_dir, $new_name);
                    }
                }
                else
                {
                    $this->_messenger->addMessage('Certains champs ne sont pas renseignés correctement.', My_Messenger::TYPE_ERROR);
                    $errors = true;
                }
            }
        }
        else
        {
            $form->getElement('lastname')->setValue($this->_user->getLastname());
            $form->getElement('firstname')->setValue($this->_user->getFirstname());
            $form->getElement('phone')->setValue($this->_user->getPhone());
            if($pro_user){
                $form->getElement('fax')->setValue($this->_user->getFax());
                $form->getElement('siretOrSiren')->setValue($this->_user->getSiret());
                $form->getElement('company')->setValue($this->_user->getCompanyName());
            }
        }
    }

    // new modification
    public function edituserAction()
    {
        $this->checkUser();
        
        // Get member
        $member = $this->_em->find('\Default_Model_Base_Member', $this->_user->getId());
        $this->view->member = $member;
        
        $form = new \My_Forms_EditMember($this->_em);
        if ($this->_request->isPost()){
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)){
                
                $member = $this->_em->find('\Default_Model_Base_Member', $this->_user->getId());
                $member->setLastname($formData['lastname']);
                $member->setFirstname($formData['firstname']);
                $member->setPhone($formData['phone']);
                
                
                $avatar_dir = $_SERVER["DOCUMENT_ROOT"] . Zend_Registry::get('assetBasePath').'avatars';
                $adapter = new My_File_Transfer_Adapter_Http();
                $adapter->setDestination($avatar_dir);

                $avatars = $this->_em->createQueryBuilder()
                        ->select('a')
                        ->from('\Default_Model_Base_MemberAvatar', 'a')
                        ->andWhere('a.member = :member_id')
                        ->setParameter('member_id', $member->getId())
                        ->getQuery()
                        ->getResult();
                foreach($adapter->getFileInfo() as $file => $info) {
                    if($adapter->isUploaded($file)) {
                        $name   = md5(uniqid(rand(), true)) . $info['name'];
                        $fname  = $avatar_dir . '/'. $name;

                        if(move_uploaded_file($info['tmp_name'], $fname)){
                            if(isset($avatars[0])){
                                $avatars[0]->setPath($name);
                            } else {
                                $avatar = new \Default_Model_Base_MemberAvatar();
                                $avatar->setPath($name);
                                $avatar->setIsMain(true);
                                $avatar->setMember($member);
                                $this->_em->persist($$file);
                            }
                        }
                    }
                }
                $this->_em->flush();
            }
        }
        $this->_redirect('membre/index');
    }
    
    // new modification
    public function editAction()
    {
        $form = new \My_Forms_EditProMember($this->_em, $this->_user->getId());
        $errors = false;
        if ($this->_request->isPost()){
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)){
                $member = $this->_em->find('\Default_Model_Base_Member', $this->_user->getId());
                if($member != null){
                    if(isset($formData['mainJob']) && $formData['mainJob'] != null){
                        $mainjob = $this->_em->find('\Default_Model_Base_ServiceJob', $formData['mainJob']);
                        if($mainjob != null){
                            $member->getService()->setMainJob($mainjob);
                        }
                    }
                    
                    $avatar_dir = $_SERVER["DOCUMENT_ROOT"] . Zend_Registry::get('assetBasePath').'avatars';
                    $adapter = new My_File_Transfer_Adapter_Http();
                    $adapter->setDestination($avatar_dir);

                    $avatars = $this->_em->createQueryBuilder()
                            ->select('a')
                            ->from('\Default_Model_Base_MemberAvatar', 'a')
                            ->andWhere('a.member = :member_id')
                            ->setParameter('member_id', $member->getId())
                            ->getQuery()
                            ->getResult();
                    foreach($adapter->getFileInfo() as $file => $info) {
                        switch ($file){
                            case 'avatar1':
                                $index = 0;
                                break;
                            case 'avatar2':
                                $index = 1;
                                break;
                            case 'avatar3':
                                $index = 2;
                                break;
                        }
                        if($adapter->isUploaded($file)) {
                            $name   = md5(uniqid(rand(), true)) . $info['name'];
                            $fname  = $avatar_dir . '/'. $name;
//                            $adapter->addFilter(new Zend_Filter_File_Rename(array('target' => $fname, 'overwrite' => true)), null, $file);
                            
                            if(move_uploaded_file($info['tmp_name'], $fname)){
                                if(isset($avatars[$index])){
                                    $avatars[$index]->setPath($name);
                                } else {
                                    $$file = new \Default_Model_Base_MemberAvatar();
                                    $$file->setPath($name);
                                    if($file == 'avatar1'){
                                        $$file->setIsMain(true);
                                    } else {
                                        $$file->setIsMain(false);
                                    }
                                    $$file->setMember($member);
                                    $this->_em->persist($$file);
                                }
                            }
                        }
                    }
                    
                    $member->setLastname($formData['lastname']);
                    $member->setFirstname($formData['firstname']);
                    $member->setPhone($formData['phone']);
                    $member->setFax($formData['fax']);
                    $member->setSiret($formData['siretOrSiren']);
                    $member->setCompanyName($formData['company']);
                    $member->getService()->setExperience($formData['experience']);
                    $member->getService()->setDescription($formData['serviceDescription']);
                    
                    $this->_em->flush();
                    $this->_messenger->addMessage('Votre profil a été mis à jour', My_Messenger::TYPE_OK);
                }
            }
        }
        $this->_redirect('membre/index');
    }

    public function removeavatarAction()
    {
        $id = $this->_request->getParam('id');
        $avatar = $this->_em->find('\Default_Model_Base_MemberAvatar', $id);
        if($avatar != null){
            if($avatar->getMember()->getId() == $this->_user->getId()){
                $this->_em->remove($avatar);
                $this->_em->flush();
            } else {
                $this->_messenger->addMessage('Cet avatar n\'est pas à vous', My_Messenger::TYPE_ERROR);
            }
        } else {
            $this->_messenger->addMessage('Cet avatar n\'existe pas.', My_Messenger::TYPE_ERROR);
        }
        $this->_redirect('membre/index');
    }
    
    private function _resizeAvatar($avatar_dir, $name_file)
    {
        // Définition de la largeur et de la hauteur maximale
        $width = 50;
        $height = 50;

        // Cacul des nouvelles dimensions
        list($width_orig, $height_orig) = getimagesize($avatar_dir . $name_file);

        $ratio_orig = $width_orig / $height_orig;

        if ($width / $height > $ratio_orig)
        {
            $width = $height * $ratio_orig;
        }
        else
        {
            $height = $width / $ratio_orig;
        }

        // Redimensionnement
        $image_p = imagecreatetruecolor($width, $height);
        $image = imagecreatefromjpeg($avatar_dir . $name_file);

        imagecopyresampled($image_p, $image, 0, 0, 0, 0, 50, 50, $width_orig, $height_orig);
        imagejpeg($image_p, $avatar_dir . $name_file);
    }
    
    /**
     * Get informations about comments
     * @param type $comments
     * @return array 
     */
    private function calcCommentsInfos($comments)
    {
        $commentsInfos = array('note' => 0, 'pos' => 0, 'neu' => 0, 'neg' => 0, 'total' => 0);

        if ($comments != null)
            foreach ($comments as $comment)
            {
                if ($comment->getType() == My_Controller_Action::COMMENT_TYPE_NEGATIVE)
                {
                    $commentsInfos['note'] += 1;
                    $commentsInfos['neg']++;
                }
                if ($comment->getType() == My_Controller_Action::COMMENT_TYPE_NEUTRAL)
                {
                    $commentsInfos['note'] += 3;
                    $commentsInfos['neu']++;
                }
                if ($comment->getType() == My_Controller_Action::COMMENT_TYPE_POSITIVE)
                {
                    $commentsInfos['note'] += 5;
                    $commentsInfos['pos']++;
                }
                $commentsInfos['total']++;
            }
        if ($commentsInfos['total'] > 0)
            $commentsInfos['note'] = round($commentsInfos['note'] / $commentsInfos['total']);
        else
            $commentsInfos['note'] = 3;

        return $commentsInfos;
    }

    public function getResourceId() {
        return 'membreController';
    }
}