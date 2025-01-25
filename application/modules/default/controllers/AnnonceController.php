<?php

class AnnonceController extends My_Controller_Action
{

    public function init()
    {
        parent::init();
        $this->_acl->allow('user', $this->getResourceId(), 'creation');
        $this->_acl->allow('user', $this->getResourceId(), 'show');
        $this->_acl->allow('user', $this->getResourceId(), 'modification');
        $this->_acl->allow('pro', $this->getResourceId(), 'show');
    }

    public function indexAction()
    {
        // Search request
        $form = new \My_Forms_SearchMiniAnnouncement($this->_em);
        $this->view->form = $form;
        
        $topannonceqb = $this->_em->createQueryBuilder()
                ->addSelect('annonce, m, avatar')
                ->from('\Default_Model_Base_Announcement', 'annonce')
                ->leftJoin('annonce.jobs', 'job')
                ->leftJoin('annonce.member', 'm')
                ->leftJoin('m.avatars', 'avatar')
                ->leftJoin('annonce.department', 'department')
                ->leftJoin('department.region', 'region')
                ->andWhere('annonce.is_visible = :visibility')
                ->setParameter('visibility', true)
                ->addOrderBy('annonce.created_at', 'ASC');
        
        $annonceqb = $this->_em->createQueryBuilder()
                ->addSelect('annonce, m, avatar')
                ->from('\Default_Model_Base_Announcement', 'annonce')
                ->leftJoin('annonce.jobs', 'job')
                ->leftJoin('annonce.member', 'm')
                ->leftJoin('m.avatars', 'avatar')
                ->leftJoin('annonce.department', 'department')
                ->leftJoin('department.region', 'region')
                ->andWhere('annonce.is_visible = :visibility')
                ->setParameter('visibility', true)
                ->addOrderBy('annonce.created_at', 'ASC');
        
        
        if ($this->_request->isPost())
        {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData))
            {
                if (isset($formData['budget']) && !(empty($formData['budget']) || $formData['budget'] == 'all')){
                    $topannonceqb->andWhere('annonce.budget = :budget')->setParameter('budget', $formData['budget']);
                    $annonceqb->andWhere('annonce.budget = :budget')->setParameter('budget', $formData['budget']);
                }
                if (isset($formData['duration']) && !(empty($formData['duration']) || $formData['duration'] == 'all')){
                    $topannonceqb->andWhere('annonce.duration = :duration')->setParameter('duration', $formData['duration']);
                    $annonceqb->andWhere('annonce.duration = :duration')->setParameter('duration', $formData['duration']);
                }
                if (isset($formData['department']) && !(empty($formData['department']) || $formData['department'] == 'all')){
                    $topannonceqb->andWhere('department = :department')->setParameter('department', $formData['department']);
                    $annonceqb->andWhere('department = :department')->setParameter('department', $formData['department']);
                }
                if (isset($formData['job']) && !empty($formData['job']) && $formData['job'] != 'all'){
                    $topannonceqb->andWhere('job.id = :job_id')->setParameter('job_id', $formData['job']);
                    $annonceqb->andWhere('job.id = :job_id')->setParameter('job_id', $formData['job']);
                }
            }
        }

        $annoncesTopList = $topannonceqb->andWhere('annonce.top_list_expire_at > :today')
                ->setParameter('today', new \DateTime())
                ->getQuery()
                ->getResult();
        $annonces = $annonceqb->andWhere('annonce.top_list_expire_at < :today or annonce.top_list_expire_at IS NULL')
                ->setParameter('today', new \DateTime())
                ->getQuery()
                ->getResult();

        $paginatorTopList = Zend_Paginator::factory($annoncesTopList);
        $paginatorTopList->setCurrentPageNumber($this->_getParam('page', 1));
        $paginatorTopList->setItemCountPerPage($this->_getParam('resultat', 2));
        
        $this->view->annoncesTopList = $paginatorTopList;

        $paginator = Zend_Paginator::factory($annonces);
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));
        $paginator->setItemCountPerPage($this->_getParam('resultat', 10));

        $this->view->annonces = $paginator;
    }

    // public show
    public function showAction()
    {
        // Get id of the announcement
        $annonceId = $this->_getParam('id', 0);
        $annonce = $this->_em->find('\Default_Model_Base_Announcement', $annonceId);
        
        if(isset($this->_user)){
            $member = $this->_em->find('\Default_Model_Base_Member', $this->_user->getId());
            $this->view->member = $member;
        }
        
        if($annonce != null){
            $this->view->annonce = $annonce;
            
            $offers = $this->_em->createQueryBuilder()
                    ->select('o')
                    ->from('\Default_Model_Base_Offer', 'o')
                    ->join('o.member', 'm')
                    ->join('o.annonce', 'a')
                    ->andWhere('a.id = :annonce_id')
                    ->setParameter('annonce_id', $annonce->getId())
                    ->andWhere('o.is_active = true')
                    ->getQuery()
                    ->getResult();
            $this->view->offers = $offers;
        } else {
            $this->_messenger->addMessage('L\'annonce n\'existe pas.', My_Messenger::TYPE_ERROR);
            $this->_redirect('annonce/index');
        }
    }
    // user administration show of his annonce
    public function detailAction()
    {
        $member = $this->_em->find('\Default_Model_Base_Member', $this->_user->getId());
        $this->view->member = $member;
        
        // Get id of the announcement
        $annonceId = $this->_getParam('id', 0);
        $annonce = $this->_em->find('\Default_Model_Base_Announcement', $annonceId);
            
        if($annonce != null && $member->getId() == $annonce->getMember()->getId()){
            $this->view->annonce = $annonce;
        } else {
            $this->_messenger->addMessage('L\'annonce n\'existe pas.', My_Messenger::TYPE_ERROR);
            $this->_redirect('annonce/index');
        }
    }
    
    public function creatememberAction()
    {
        $form = new \My_Forms_AddMemberAnnouncement($this->_em);
        $this->view->form = $form;
        
        if ($this->_request->isPost()){
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)){
                // Création
                $member = new \Default_Model_Base_Member();

                // Required
                $member->setFirstName($formData['firstname']);
                $member->setLastName($formData['lastname']);
                $member->setEmail($formData['email']);
                $member->setSalt(md5(uniqid(rand(), true)));
                $member->setPassword(md5($formData['password'].$member->getSalt()));
                $member->setAvatar(My_Controller_Action::AVATAR_BASE);
                $member->setIsActive(false);
                $member->setRole('user');

                // Optionnal
                if (isset($formData['phone'])){
                    $member->setPhone($formData['phone']);
                }
                if (isset($formData['fax'])){
                    $member->setFax($formData['fax']);
                }
                $this->_em->persist($member);
                
                // Création de l'annonce
                $annonce = new \Default_Model_Base_Announcement();

                // Required
                $annonce->setDescription($formData['description']);
                $annonce->setBudget($formData['budget']);
                $annonce->setDuration($formData['duration']);
                $annonce->setIsVisible(true);
                $annonce->setIsImage(false);
                
                $jobList = array();
                foreach ($formData['jobs'] as $job)
                    $jobList[] = intval($job);
                $jobs = $this->_em->createQueryBuilder()
                        ->select('j')
                        ->from('\Default_Model_Base_Job', 'j')
                        ->andWhere('j.id in (:ids)')
                        ->setParameter('ids', $jobList)
                        ->getQuery()
                        ->getResult();
                
                foreach ($jobs as $job)
                {
                    $annonce->addDefault_Model_Base_Job($job);
                }
                
                if (isset($formData['department'])){
                    $department = $this->_em->find('\Default_Model_Base_Department', $formData['department']);
                    if($department != null){
                        $annonce->setDepartment($department);
                    }
                }

                // Bidirectionnal relation, so we need to add a link in both way
                $annonce->setMember($member);

                $this->_em->persist($annonce);
                
                $this->_em->flush();
                
                $this->_redirect('index/connexion');
            }
        }
    }
    
    public function creationAction()
    {
        $this->checkUser();

        $member = $this->_em->find('\Default_Model_Base_Member', $this->_user->getId());

        $form = new \My_Forms_AddAnnouncement($this->_em);
        $this->view->form = $form;

        $annonces = $this->_em->createQueryBuilder()
                ->select('a')
                ->from('\Default_Model_Base_Announcement', 'a')
                ->andWhere('a.member = :member_id')
                ->setParameter('member_id', $member->getId())
                ->getQuery()
                ->getResult();
        if (count($annonces) >= My_Controller_Action::MAX_ANNOUNCEMENT_PER_MEMBER)
            $this->view->max_announcement = true;

        $errors = false;
        if (!$this->view->max_announcement && $this->_request->isPost())
        {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData))
            {

                // Department exists ?
                $department = $this->_em->find('\Default_Model_Base_Department', $formData['department']);
                if($department == null){
                    $this->_messenger->addMessage('Le département n\'est pas valide.', My_Messenger::TYPE_ERROR);
                    $errors = true;
                }

                // Budget is valid ?
                if(!in_array($formData['budget'], array(
                    \Default_Model_Base_Budget::SMALL,
                    \Default_Model_Base_Budget::MEDIUM,
                    \Default_Model_Base_Budget::LARGE,
                    \Default_Model_Base_Budget::HUGE,
                ))){
                    $this->_messenger->addMessage('Le budget n\'est pas valide.', My_Messenger::TYPE_ERROR);
                    $errors = true;
                }

                // Duration is valid ?
                if(!in_array($formData['duration'], array(
                    \Default_Model_Base_Duration::SMALL,
                    \Default_Model_Base_Duration::MEDIUM,
                    \Default_Model_Base_Duration::LARGE,
                    \Default_Model_Base_Duration::HUGE,
                ))){
                    $this->_messenger->addMessage('La durée n\'est pas valide.', My_Messenger::TYPE_ERROR);
                    $errors = true;
                }

                // Jobs are valid ?
                $jobList = array();
                foreach ($formData['jobs'] as $job)
                    $jobList[] = intval($job);

                $jobs = $this->_em->createQueryBuilder()
                        ->select('j')
                        ->from('\Default_Model_Base_Job', 'j')
                        ->andWhere('j.id in (:ids)')
                        ->setParameter('ids', $jobList)
                        ->getQuery()
                        ->getResult();

                if ($jobs == null || count($jobs) != count($formData['jobs']))
                {
                    $this->_messenger->addMessage('Les métiers ne sont pas valides.', My_Messenger::TYPE_ERROR);
                    $errors = true;
                }

                if ($errors === false)
                {
                    $member = $this->_em->find('\Default_Model_Base_Member', $this->_user->getId());

                    // Création de l'annonce
                    $annonce = new \Default_Model_Base_Announcement();

                    // Required
                    $annonce->setDescription($formData['description']);
                    $annonce->setBudget($formData['budget']);
                    $annonce->setDuration($formData['duration']);
                    $annonce->setIsVisible(true);
                    $annonce->setIsImage(false);
                    foreach ($jobs as $job)
                    {
                        $annonce->addDefault_Model_Base_Job($job);
                    }
                    
                    $picture_dir = $_SERVER["DOCUMENT_ROOT"] . Zend_Registry::get('assetBasePath').'annonces';
                    $adapter = new My_File_Transfer_Adapter_Http();
                    $adapter->setDestination($picture_dir);

                    foreach($adapter->getFileInfo() as $file => $info) {
                        if($adapter->isUploaded($file)) {
                            $name   = md5(uniqid(rand(), true)) . $info['name'];
                            $fname  = $picture_dir . '/'. $name;
                            
                            if(move_uploaded_file($info['tmp_name'], $fname)){
                                $$file = new \Default_Model_Base_AnnouncementPicture();
                                $$file->setPath($name);
                                $$file->setAnnonce($annonce);
                                $this->_em->persist($$file);
                            }
                        }
                    }

                    // Bidirectionnal relation, so we need to add a link in both way
                    $annonce->setMember($member);
                    $annonce->setDepartment($department);

                    $this->_em->persist($annonce);
                    $this->_em->flush();

                    $this->_messenger->addMessage('L\'annonce a bien été créée !', My_Messenger::TYPE_OK);
                    $this->_redirect('membre/index');
                }
            }
            else
            {
                $this->_messenger->addMessage('Certains champs ne sont pas renseignés correctement.', My_Messenger::TYPE_ERROR);
                $errors = true;
            }
        }
    }

    public function modificationAction()
    {
        $this->checkUser();

        // Get id of the announcement
        $annonceId = $this->_getParam('id', 0);
        if (intval($annonceId) == 0)
        {
            $this->_messenger->addMessage('L\'annonce est introuvable.', My_Messenger::TYPE_ERROR);
            return;
        }

        // Retreive the announcement
        $qb = $this->_em->createQueryBuilder();
        $qb->add('select', 'a')
                ->add('from', '\Default_Model_Base_Announcement a')
                ->add('where', 'a.id = :id');
        $query = $qb->getQuery();
        $query->setParameter('id', $annonceId);

        try
        {
            $annonce = $query->getSingleResult();
            $this->view->annonce = $annonce;
        }
        catch (\Doctrine\ORM\NoResultException $e)
        {
            $this->_messenger->addMessage('L\'annonce est introuvable.', My_Messenger::TYPE_ERROR);
        }
        
        $pictures = $this->_em->createQueryBuilder()
                ->select('p')
                ->from('\Default_Model_Base_AnnouncementPicture', 'p')
                ->andWhere('p.annonce = :annonce_id')
                ->setParameter('annonce_id', $annonce->getId())
                ->orderBy('p.id')
                ->getQuery()
                ->getResult();
        $this->view->pictures = $pictures;
        $form = new \My_Forms_EditAnnouncement($this->_em);
        $this->view->form = $form;

        $errors = false;
        if ($this->_request->isPost())
        {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData))
            {

                // Department exists ?
                $department = $this->_em->find('\Default_Model_Base_Department', $formData['department']);
                if($department == null){
                    $this->_messenger->addMessage('Le département n\'est pas valide.', My_Messenger::TYPE_ERROR);
                    $errors = true;
                }

                // Budget is valid ?
                if(!in_array($formData['budget'], array(
                    \Default_Model_Base_Budget::SMALL,
                    \Default_Model_Base_Budget::MEDIUM,
                    \Default_Model_Base_Budget::LARGE,
                    \Default_Model_Base_Budget::HUGE,
                ))){
                    $this->_messenger->addMessage('Le budget n\'est pas valide.', My_Messenger::TYPE_ERROR);
                    $errors = true;
                }

                // Duration is valid ?
                if(!in_array($formData['duration'], array(
                    \Default_Model_Base_Duration::SMALL,
                    \Default_Model_Base_Duration::MEDIUM,
                    \Default_Model_Base_Duration::LARGE,
                    \Default_Model_Base_Duration::HUGE,
                ))){
                    $this->_messenger->addMessage('La durée n\'est pas valide.', My_Messenger::TYPE_ERROR);
                    $errors = true;
                }

                // Jobs are valid ?
                $jobList = array();
                foreach ($formData['jobs'] as $job)
                    $jobList[] = intval($job);

                $jobs = $this->_em->createQueryBuilder()
                        ->select('j')
                        ->from('\Default_Model_Base_Job', 'j')
                        ->andWhere('j.id in (:ids)')
                        ->setParameter('ids', $jobList)
                        ->getQuery()
                        ->getResult();

                if ($jobs == null || count($jobs) != count($formData['jobs']))
                {
                    $this->_messenger->addMessage('Les métiers ne sont pas valides.', My_Messenger::TYPE_ERROR);
                    $errors = true;
                }

                $visibility = $formData['visibility'] == 1 ? true : false;

                if ($errors === false)
                {
                    // Required
                    $annonce->setDescription($formData['description']);
                    $annonce->setBudget($formData['budget']);
                    $annonce->setDuration($formData['duration']);
                    $annonce->setDepartment($department);
                    $annonce->setIsVisible($visibility);

                    $annonce->getJobs()->clear();
                    foreach ($jobs as $job)
                    {
                        $annonce->getJobs()->add($job);
                    }
                    
                    
                    $pictures_dir = $_SERVER["DOCUMENT_ROOT"] . Zend_Registry::get('assetBasePath').'annonces';
                    $adapter = new My_File_Transfer_Adapter_Http();
                    $adapter->setDestination($pictures_dir);
                    foreach($adapter->getFileInfo() as $file => $info) {
                        switch ($file) {
                            case 'avatar1':
                                $index = 0;
                                break;
                            case 'avatar2':
                                $index = 1;
                                break;
                            case 'avatar3':
                                $index = 2;
                                break;
                            case 'avatar4':
                                $index = 3;
                                break;
                            case 'avatar5':
                                $index = 4;
                                break;
                            case 'avatar6':
                                $index = 5;
                                break;
                            case 'avatar7':
                                $index = 6;
                                break;
                            case 'avatar8':
                                $index = 7;
                                break;
                            case 'avatar9':
                                $index = 8;
                                break;
                            case 'avatar10':
                                $index = 9;
                                break;
                        }
                        if($adapter->isUploaded($file)) {
                            $name   = md5(uniqid(rand(), true)) . $info['name'];
                            $fname  = $pictures_dir . '/'. $name;
                            
                            if(move_uploaded_file($info['tmp_name'], $fname)){
                                if(isset($pictures[$index])){
                                    $pictures[$index]->setPath($name);
                                } else {
                                    $$file = new \Default_Model_Base_AnnouncementPicture();
                                    $$file->setPath($name);
                                    $$file->setAnnonce($annonce);
                                    $this->_em->persist($$file);
                                }
                            }
                        }
                    }

                    $this->_em->merge($annonce);
                    $this->_em->flush();

                    $this->_messenger->addMessage('L\'annonce a bien été modifiée !', My_Messenger::TYPE_OK);
                    
                    $pictures = $this->_em->createQueryBuilder()
                            ->select('p')
                            ->from('\Default_Model_Base_AnnouncementPicture', 'p')
                            ->andWhere('p.annonce = :annonce_id')
                            ->setParameter('annonce_id', $annonce->getId())
                            ->orderBy('p.id')
                            ->getQuery()
                            ->getResult();
                    $this->view->pictures = $pictures;
                }
            }
            else
            {
                var_dump($form->getErrors());
                $this->_messenger->addMessage('Certains champs ne sont pas renseignés correctement.', My_Messenger::TYPE_ERROR);
                $errors = true;
            }
        }
        else
        {
            $form->getElement('description')->setValue($annonce->getDescription());
            $form->getElement('duration')->setValue($annonce->getDuration());
            $form->getElement('budget')->setValue($annonce->getBudget());
            $form->getElement('department')->setValue($annonce->getDepartment()->getId());
            $form->getElement('visibility')->setValue($annonce->getIsVisible());
            $job_ids = array();
            foreach ($annonce->getJobs()->toArray() as $job){
                $job_ids[] = $job->getId();
            }
            $form->getElement('jobs')->setValue($job_ids);
        }
    }
    
    
    public function makeurgentAction()
    {
        // read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-validate';

        foreach ($_POST as $key => $value) {
            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";
        }

        // post back to PayPal system to validate
        $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
        $fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);

        // assign posted variables to local variables
        $item_name = $_POST['item_name'];
        $item_number = $_POST['item_number'];
        $payment_status = $_POST['payment_status'];
        $payment_amount = $_POST['mc_gross'];
        $payment_currency = $_POST['mc_currency'];
        $txn_id = $_POST['txn_id'];
        $receiver_email = $_POST['receiver_email'];
        $payer_email = $_POST['payer_email'];
        parse_str($_POST['custom'], $custom);
        
        
        if (!$fp) {
        // HTTP ERROR
        } else {
            fputs ($fp, $header . $req);
            while (!feof($fp)) {
                $res = fgets ($fp, 1024);
                // bug chez paypal, l'api renvoie toujour INVALID, mais le payment_status certifie la fin du payment
                // TODO: trouver une solution plus propre
                if (strcmp ($res, "VERIFIED") == 0 || strcmp ($res, "INVALID") == 0) {
                    // check the payment_status is Completed
                    if($payment_status == "Completed"){
                        // check that receiver_email is your Primary PayPal email
                        if($receiver_email == 'paiement@decorezrenovez.fr'){
                            // check that payment_amount/payment_currency are correct
                            if($payment_currency == 'EUR'){
                                // check that payment_amount is correct
                                if(in_array($payment_amount, array("5.00", "25.00"))){
                                    
                                    $db = new PDO("mysql:host=localhost;dbname=decorezr_mysql", "decorezr_php", ")O[.DhysS+%Z");
                                    $req = $db->query('SELECT * FROM announcement WHERE id='.$custom['annonce_id'].' LIMIT 1');
                                    $d = $req->fetch(PDO::FETCH_ASSOC);
                                    if(!empty($d)){
                                        if($payment_amount == "5.00"){
                                            $intervalDate = '7 DAY';
                                        } else if($payment_amount == "25.00"){
                                            $intervalDate = '3 MONTH';
                                        }
                                        $urgentexpire = $d['urgent_expire_at'];
                                        if($urgentexpire == null || strtotime($urgentexpire) < time()){
                                            $db->query('UPDATE announcement SET urgent_expire_at = DATE_ADD(NOW(), INTERVAL '.$intervalDate.') WHERE id='.$custom['annonce_id']);
                                        } else {
                                            $db->query('UPDATE announcement SET urgent_expire_at = DATE_ADD(urgent_expire_at, INTERVAL '.$intervalDate.') WHERE id='.$custom['annonce_id']);
                                        }
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            fclose ($fp);
        }
    }
    
    public function maketoplistAction()
    {
        // read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-validate';

        foreach ($_POST as $key => $value) {
            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";
        }

        // post back to PayPal system to validate
        $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
        $fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);

        // assign posted variables to local variables
        $item_name = $_POST['item_name'];
        $item_number = $_POST['item_number'];
        $payment_status = $_POST['payment_status'];
        $payment_amount = $_POST['mc_gross'];
        $payment_currency = $_POST['mc_currency'];
        $txn_id = $_POST['txn_id'];
        $receiver_email = $_POST['receiver_email'];
        $payer_email = $_POST['payer_email'];
        parse_str($_POST['custom'], $custom);
        
        
        if (!$fp) {
        // HTTP ERROR
        } else {
            fputs ($fp, $header . $req);
            while (!feof($fp)) {
                $res = fgets ($fp, 1024);
                // bug chez paypal, l'api renvoie toujour INVALID, mais le payment_status certifie la fin du payment
                // TODO: trouver une solution plus propre
                if (strcmp ($res, "VERIFIED") == 0 || strcmp ($res, "INVALID") == 0) {
                    // check the payment_status is Completed
                    if($payment_status == "Completed"){
                        // check that receiver_email is your Primary PayPal email
                        if($receiver_email == 'paiement@decorezrenovez.fr'){
                            // check that payment_amount/payment_currency are correct
                            if($payment_currency == 'EUR'){
                                // check that payment_amount is correst
                                if(in_array($payment_amount, array("12.00", "24.00"))){
                                    
                                    $db = new PDO("mysql:host=localhost;dbname=decorezr_mysql", "decorezr_php", ")O[.DhysS+%Z");
                                    $req = $db->query('SELECT * FROM announcement WHERE id='.$custom['annonce_id'].' LIMIT 1');
                                    $d = $req->fetch(PDO::FETCH_ASSOC);
                                    if(!empty($d)){
                                        if($payment_amount == "12.00"){
                                            $intervalDate = '7 DAY';
                                        } else if($payment_amount == "24.00"){
                                            $intervalDate = '3 MONTH';
                                        }
                                        $toplistexpire = $d['top_list_expire_at'];
                                        if($toplistexpire == null || strtotime($toplistexpire) < time()){
                                            $db->query('UPDATE announcement SET top_list_expire_at = DATE_ADD(NOW(), INTERVAL '.$intervalDate.') WHERE id='.$custom['annonce_id']);
                                        } else {
                                            $db->query('UPDATE announcement SET top_list_expire_at = DATE_ADD(top_list_expire_at, INTERVAL '.$intervalDate.') WHERE id='.$custom['annonce_id']);
                                        }
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            fclose ($fp);
        }
    }
    
    public function makeimageAction()
    {
        // read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-validate';

        foreach ($_POST as $key => $value) {
            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";
        }

        // post back to PayPal system to validate
        $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
        $fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);

        // assign posted variables to local variables
        $item_name = $_POST['item_name'];
        $item_number = $_POST['item_number'];
        $payment_status = $_POST['payment_status'];
        $payment_amount = $_POST['mc_gross'];
        $payment_currency = $_POST['mc_currency'];
        $txn_id = $_POST['txn_id'];
        $receiver_email = $_POST['receiver_email'];
        $payer_email = $_POST['payer_email'];
        parse_str($_POST['custom'], $custom);
        
        
        if (!$fp) {
        // HTTP ERROR
        } else {
            fputs ($fp, $header . $req);
            while (!feof($fp)) {
                $res = fgets ($fp, 1024);
                // bug chez paypal, l'api renvoie toujour INVALID, mais le payment_status certifie la fin du payment
                // TODO: trouver une solution plus propre
                if (strcmp ($res, "VERIFIED") == 0 || strcmp ($res, "INVALID") == 0) {
                    // check the payment_status is Completed
                    if($payment_status == "Completed"){
                        // check that receiver_email is your Primary PayPal email
                        if($receiver_email == 'paiement@decorezrenovez.fr'){
                            // check that payment_amount/payment_currency are correct
                            if($payment_currency == 'EUR'){
                                // check that payment_amount is correct
                                if($payment_amount == "2.00"){
                                    $db = new PDO("mysql:host=localhost;dbname=vincent", "vincent", "SourceForge");
                                    $db->query('UPDATE announcement SET is_image = true WHERE id='.$custom['annonce_id']);
                                }
                            }
                        }
                    }
                }
            }
            fclose ($fp);
        }
    }

    public function visibiliteAction()
    {
        
    }
    
    public function getResourceId() {
        return 'annonceController';
    }
}