<?php

class ServiceController extends My_Controller_Action
{

    public function init()
    {
        parent::init();
        $this->_acl->allow('user', $this->getResourceId(), 'index');
        $this->_acl->allow('pro', $this->getResourceId(), 'creation');
        $this->_acl->allow('pro', $this->getResourceId(), 'modification');
        $this->_acl->allow('pro', $this->getResourceId(), 'addjob');
        $this->_acl->allow('pro', $this->getResourceId(), 'newjob');
    }

    /**
     * Liste/Recherche de services
     */
    public function indexAction()
    {
        // Search request
        $form = new \My_Forms_SearchService($this->_em);
        $this->view->form = $form;
        $toplistserviceqb = $this->_em->createQueryBuilder()
                ->addSelect('s, m, c, a')
                ->from('\Default_Model_Base_Service', 's')
                ->leftJoin('s.member', 'm')
                ->leftJoin('m.avatars', 'a')
                ->leftJoin('m.feature', 'f')
                ->leftJoin('m.department', 'd')
                ->leftJoin('d.region', 'r')
                ->join('s.jobs', 'sj')
                ->leftJoin('sj.job', 'j')
                ->leftJoin('s.comments', 'c')
                ->andWhere('s.is_visible = true')
                ->andWhere('m.is_active = true');
        $serviceqb = $this->_em->createQueryBuilder()
                ->addSelect('s, m, c, a')
                ->from('\Default_Model_Base_Service', 's')
                ->leftJoin('s.member', 'm')
                ->leftJoin('m.avatars', 'a')
                ->leftJoin('m.feature', 'f')
                ->leftJoin('m.department', 'd')
                ->leftJoin('d.region', 'r')
                ->join('s.jobs', 'sj')
                ->leftJoin('sj.job', 'j')
                ->leftJoin('s.comments', 'c')
                ->andWhere('s.is_visible = true')
                ->andWhere('m.is_active = true');
        
        $regionId = $this->_request->getParam('region');
        $this->view->region = 0;
        if($regionId > 1){
            $this->view->region = $regionId;
            $toplistserviceqb->andWhere('d.region = :region')
                    ->setParameter('region', $regionId);
            $serviceqb->andWhere('d.region = :region')
                    ->setParameter('region', $regionId);
        }
        
        if ($this->_request->isPost())
        {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData))
            {
                if(isset($formData['job']) && $formData['job'] != 'all'){
                    $toplistserviceqb->andWhere('j.id = :job_id')
                            ->setParameter('job_id', $formData['job']);
                    $serviceqb->andWhere('j.id = :job_id')
                            ->setParameter('job_id', $formData['job']);
                    $this->view->askedJob = $formData['job'];
                }
                
                if(isset($formData['name']) && $formData['name'] != null){
                    $toplistserviceqb->andWhere('m.lastname LIKE :name OR m.firstname LIKE :name')
                            ->setParameter('name', '%'.$formData['name'].'%');
                    $serviceqb->andWhere('m.lastname LIKE :name OR m.firstname LIKE :name')
                            ->setParameter('name', '%'.$formData['name'].'%');
                }
                
                if(isset($formData['budget']) && in_array($formData['budget'], array(1, 2, 3, 4))){
                    switch ($formData['budget']){
                        case 1:
                            $toplistserviceqb->andWhere('sj.price1 < :price')
                                ->setParameter('price', (100/14));
                            $serviceqb->andWhere('sj.price1 < :price')
                                ->setParameter('price', (100/14));
                            break;
                        case 2:
                            $toplistserviceqb->andWhere('sj.price1 > :pricebot')
                                ->setParameter('pricebot', (100/14))
                                ->andWhere('sj.price1 < :pricetop')
                                ->setParameter('pricetop', (500/14));
                            $serviceqb->andWhere('sj.price1 > :pricebot')
                                ->setParameter('pricebot', (100/14))
                                ->andWhere('sj.price1 < :pricetop')
                                ->setParameter('pricetop', (500/14));
                            break;
                        case 3:
                            $toplistserviceqb->andWhere('sj.price1 > :pricebot')
                                ->setParameter('pricebot', (500/70))
                                ->andWhere('sj.price2 < :pricetop')
                                ->setParameter('pricetop', (1000/70));
                            $serviceqb->andWhere('sj.price2 > :pricebot')
                                ->setParameter('pricebot', (500/70))
                                ->andWhere('sj.price2 < :pricetop')
                                ->setParameter('pricetop', (1000/70));
                            break;
                        case 4:
                            $toplistserviceqb->andWhere('sj.price3 > :price')
                                ->setParameter('price', (1000/210));
                            $serviceqb->andWhere('sj.price3 > :price')
                                ->setParameter('price', (1000/210));
                            break;
                    }
                    $this->view->askedBudget = $formData['budget'];
                }
            }
        }
        
        $toplistservices = $toplistserviceqb->andWhere('f.top_list_expire > :today')
                ->setParameter('today', new \DateTime())
                ->getQuery()
                ->getResult();
        $services = $serviceqb->andWhere('f.top_list_expire < :today or f.top_list_expire IS NULL')
                ->setParameter('today', new \DateTime())
                ->getQuery()
                ->getResult();
        $this->view->commentsInfos = array();
        foreach ($toplistservices as $service) {
            /* @var $service \Default_Model_Base_Service */
            $this->view->commentsInfos[$service->getId()] = $this->calcCommentsInfos($service->getComments());
        }
        foreach ($services as $service) {
            /* @var $service \Default_Model_Base_Service */
            $this->view->commentsInfos[$service->getId()] = $this->calcCommentsInfos($service->getComments());
        }

        $toplistpaginator = Zend_Paginator::factory($toplistservices);
        $toplistpaginator->setCurrentPageNumber($this->_getParam('page', 1));
        $toplistpaginator->setItemCountPerPage($this->_getParam('resultat', 2));

        $this->view->toplistservices = $toplistpaginator;

        $paginator = Zend_Paginator::factory($services);
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));
        $paginator->setItemCountPerPage($this->_getParam('resultat', 10));

        $this->view->services = $paginator;
    }

    /**
     * Inscription au service (etape 1)
     */
    public function creationAction()
    {
        $this->checkUser();

        if ($this->_user->getService() != null)
            $this->_redirect('/service/modification');

        $form = new \My_Forms_AddService($this->_em);
        $this->view->form = $form;

        $errors = false;
        if ($this->_request->isPost())
        {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData))
            {
                // Jobs are valid ?
                $qb = $this->_em->createQueryBuilder();
                $qb->add('select', 'j')
                        ->add('from', '\Default_Model_Base_Job j')
                        ->add('where', $qb->expr()->in('j.jobId', '?1'))
                        ->setParameter(1, $formData['mainJob']);
                $query = $qb->getQuery();

                try
                {
                    $job = $query->getSingleResult();
                }
                catch (\Doctrine\ORM\NoResultException $e)
                {
                    $this->_messenger->addMessage('La spécialité n\'est pas valide.', My_Messenger::TYPE_ERROR);
                    $errors = true;
                }

                if ($errors === false)
                {
                    $member = $this->_em->find('\Default_Model_Base_Member', $this->_user->getId());

                    // Création de l'annonce
                    $service = new \Default_Model_Base_Service();

                    // Required
                    $service->setIsPartner(false);
                    $service->setDescription($formData['description']);
                    $service->setExperience($formData['experience']);
                    $service->setMainJob($job);
                    $service->setIsVisible(true);

                    // Bidirectionnal relation, so we need to add a link in both way
                    $service->setMember($member);
                    $member->setService($service);
                    $this->_user->setService($service);

                    $this->_em->persist($service);
                    $this->_em->flush();

                    $this->_messenger->addMessage('Vous proposez désormais vos services !', My_Messenger::TYPE_OK);
                    $this->_redirect('service/modification');
                }
            }
            else
            {
                $this->_messenger->addMessage('Certains champs ne sont pas renseignés correctement.', My_Messenger::TYPE_ERROR);
                $errors = true;
            }
        }
    }

    /**
     * Modification de ses services + métiers
     */
    public function modificationAction()
    {
        $this->checkUser();

        // Create forms
        $form = new \My_Forms_EditService($this->_em);
        $this->view->formService = $form;

        $member = $this->_em->find('\Default_Model_Base_Member', $this->_user->getId());
        if ($member == null || $member->getService() == null)
        {
            $this->_messenger->addMessage('Vous n\'avez pas encore proposé vos services.', My_Messenger::TYPE_INFO);
            return;
        }

        // If the user has fill the first form
        $service = $member->getService();

        $form2 = new \My_Forms_AddServiceJob($this->_em);
        $this->view->formServiceJob = $form2;

        $errors = false;
        if ($this->_request->isPost())
        {
            $formData = $this->_request->getPost();

            // Edit service profile
            if (isset($formData['submitService']))
            {
                if ($form->isValid($formData))
                {

                    // Jobs are valid ?
                    $qb = $this->_em->createQueryBuilder();
                    $qb->add('select', 'j')
                            ->add('from', '\Default_Model_Base_Job j')
                            ->add('where', $qb->expr()->in('j.jobId', '?1'))
                            ->setParameter(1, $formData['mainJob']);
                    $query = $qb->getQuery();

                    try
                    {
                        $job = $query->getSingleResult();
                    }
                    catch (\Doctrine\ORM\NoResultException $e)
                    {
                        $this->_messenger->addMessage('La spécialité n\'est pas valide.', My_Messenger::TYPE_ERROR);
                        $errors = true;
                    }

                    $visibility = $formData['visibility'] == 1 ? true : false;

                    if ($errors === false)
                    {
                        $member = $this->_em->find('\Default_Model_Base_Member', $this->_user->getId());

                        $service->setDescription($formData['description']);
                        $service->setExperience($formData['experience']);
                        $service->setIsVisible($visibility);
                        $service->setMainJob($job);

                        $this->_em->merge($service);
                        $this->_em->flush();

                        $this->_messenger->addMessage('Les informations ont été modifiées !', My_Messenger::TYPE_OK);
                    }
                }
                else
                {
                    $this->_messenger->addMessage('Certains champs ne sont pas renseignés correctement.', My_Messenger::TYPE_ERROR);
                    $errors = true;
                }
            }

            // Service jobs
            if (!$errors && isset($formData['submitServiceJob']))
            {
                if ($form2->isValid($formData))
                {

                    // Jobs are valid ?
                    $qb = $this->_em->createQueryBuilder();
                    $qb->add('select', 'j')
                            ->add('from', '\Default_Model_Base_Job j')
                            ->add('where', $qb->expr()->in('j.jobId', '?1'))
                            ->setParameter(1, $formData['job']);
                    $query = $qb->getQuery();

                    try
                    {
                        $job = $query->getSingleResult();
                    }
                    catch (\Doctrine\ORM\NoResultException $e)
                    {
                        $this->_messenger->addMessage('La métier n\'est pas valide.', My_Messenger::TYPE_ERROR);
                        $errors = true;
                    }

                    // Job already exists ?
                    $jobs = $service->getJobs();
                    foreach ($jobs as $serviceJob)
                        if ($serviceJob->getJob() == $job)
                        {
                            $this->_messenger->addMessage('Ce métier est déjà renseigné.', My_Messenger::TYPE_ERROR);
                            $errors = true;
                        }

                    if ($errors === false)
                    {
                        // Création de l'annonce
                        $serviceJob = new \Default_Model_Base_ServiceJob();

                        // Required
                        $serviceJob->setEvaluation($formData['evaluation']);
                        $serviceJob->setPrice1($formData['prix2']);
                        $serviceJob->setPrice2($formData['prix10']);
                        $serviceJob->setPrice3($formData['prix30']);
                        $serviceJob->setJob($job);

                        $service->getJobs()->add($serviceJob);

                        $this->_em->persist($serviceJob);
                        $this->_em->flush();

                        $this->_messenger->addMessage('Les informations ont été modifiées !', My_Messenger::TYPE_OK);
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
            $form->getElement('description')->setValue($service->getDescription());
            $form->getElement('experience')->setValue($service->getExperience());
//            $form->getElement('mainJob')->setValue($service->getMainJob()->getId());
        }

        // Liste des metiers
        $serviceJobs = $this->_em->getRepository('\Default_Model_Base_ServiceJob')->getAllJobsByServiceId($service->getId());

        $paginator = Zend_Paginator::factory($serviceJobs);
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));
        $paginator->setItemCountPerPage($this->_getParam('resultat', 5));

        $this->view->serviceJobs = $paginator;
    }

    public function addjobAction()
    {
        $this->checkUser();
        $this->view->addServiceJobForm = new \My_Forms_AddServiceJob($this->_em);
        
        if ($this->_request->isPost())
        {
            $formData = $this->_request->getPost();
            if ($this->view->addServiceJobForm->isValid($formData))
            {
                $serviceJob = new \Default_Model_Base_ServiceJob();
                $job = $this->_em->find('\Default_Model_Base_Job', $formData['job']);
                $serviceJob->setJob($job);
                $member = $this->_em->find('\Default_Model_Base_Member', $this->_user->getId());
                if($member->getService()->getJobs()->count() == 0){
                    $member->getService()->setMainJob($serviceJob);
                }
                $serviceJob->setService($member->getService());
                $serviceJob->setEvaluation($formData['evaluation']);
                $serviceJob->setPrice1($formData['prix2']);
                $serviceJob->setPrice2($formData['prix10']);
                $serviceJob->setPrice3($formData['prix30']);
                
                $this->_em->persist($serviceJob);
                $this->_em->flush();
                
                $this->_messenger->addMessage('Votre nouvelle compétence a bien été ajouté', My_Messenger::TYPE_OK);
                $this->_redirect('membre/index');
            }else {
                $this->view->evaluation = $formData['evaluation'];
                $this->view->price1 = $formData['prix2'];
                $this->view->price2 = $formData['prix10'];
                $this->view->price3 = $formData['prix30'];

                $this->view->errors = $this->view->addServiceJobForm->getErrors();
            }
        } 
    }
    
    public function newjobAction()
    {
        $this->view->newJobForm = new \My_Forms_AddJob($this->_em);
        
        if ($this->_request->isPost())
        {
            $formData = $this->_request->getPost();
            if ($this->view->newJobForm->isValid($formData))
            {
                $job = new \Default_Model_Base_Job();
                $job->setName($formData['name']);
                $job->setIsValidate(false);
                
                $category = $this->_em->find('\Default_Model_Base_JobCategory', $formData['category']);
                $job->setCategory($category);
                
                $this->_em->persist($job);
                $this->_em->flush();
                
                $this->_messenger->addMessage('Nous allons valider la creation de ce nouveau métier !', My_Messenger::TYPE_OK);
                $this->_redirect('membre/index');
            }
        }
    }
    
    public function deletejobAction()
    {
        $id = $this->_request->getParam('id');
        $serviceJob = $this->_em->find('\Default_Model_Base_ServiceJob', $id);
        if($serviceJob != null){
            $member = $this->_em->find('\Default_Model_Base_Member', $this->_user->getId());
            if($member->getService()->getMainJob() != null && $serviceJob->getId() == $member->getService()->getMainJob()->getId()){
                $member->getService()->setMainJob(null);
            }
            $this->_em->remove($serviceJob);
            $this->_em->flush();
        }
        $this->_redirect('membre/index');
    }
    
    public function updatejobAction()
    {
        $id = $this->_request->getParam('id');
        $form = new \My_Forms_AddServiceJob($this->_em);
        $this->view->newJobForm = $form;
        
        if ($this->_request->isPost())
        {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData))
            {
                $serviceJob = $this->_em->find('\Default_Model_Base_ServiceJob', $id);
                
                if(isset($formData['job']) && $formData['job'] != null){
                    $job = $this->_em->find('\Default_Model_Base_Job', $formData['job']);
                    if($job != null){
                        $serviceJob->setJob($job);
                    }
                }
                $serviceJob->setEvaluation($formData['evaluation']);
                $serviceJob->setPrice1($formData['prix2']);
                $serviceJob->setPrice2($formData['prix10']);
                $serviceJob->setPrice3($formData['prix30']);
                $this->_em->flush();
            }
        }
        $this->_redirect('membre/index');
    }
    
    /**
     * Gestion des métiers
     */
    public function informationAction()
    {
        $this->checkUser();

        $member = $this->_em->find('\Default_Model_Base_Member', $this->_user->getId());
        if ($member == null || $member->getService() == null)
        {
            $this->_messenger->addMessage('Vous n\'avez pas encore proposé vos services.', My_Messenger::TYPE_INFO);
            return;
        }

        $this->view->service = $member->getService();
    }

    /**
     * Augmenter sa visibilité
     */
    public function visibiliteAction()
    {
        
    }

    /**
     * Visualisation du profil
     */
    public function detailAction()
    {
        $form = new \My_Forms_SearchService($this->_em);
        $this->view->form = $form;
        
        $this->_saveToHistory();

        // Get id of the service
        $serviceId = $this->_getParam('id', 0);
        if (intval($serviceId) <= 0)
        {
            $this->_messenger->addMessage('Le membre est introuvable.', My_Messenger::TYPE_ERROR);
            return;
        }
        $service = $this->_em->find('\Default_Model_Base_Service', $serviceId);
        if ($service == null || $service->getMember() == null || $service->getMember()->getRole() != 'pro' || $service->getJobs()->count() == 0)
        {
            $this->_messenger->addMessage('Le membre est introuvable.', My_Messenger::TYPE_ERROR);
            $this->_redirect('service/index');
        }

        // Formulaire d'ajout de commentaire
        $formComment = new My_Forms_AddComment();
        $this->view->formComment = $formComment;

        if ($this->_request->isPost() && $this->_user != null)
        {
            $formData = $this->_request->getPost();

            if (isset($formData['submit']))
                if ($formComment->isValid($formData))
                {
                    // Création du commentaire
                    $member = $this->_em->find('\Default_Model_Base_Member', $this->_user->getId());
                    $comment = new \Default_Model_Base_Comment();

                    // Required
                    $comment->setType($formData['type']);
                    $comment->setMessage($formData['message']);
                    $comment->setStatus(My_Controller_Action::COMMENT_STATUS_NEW);
                    $comment->setMember($member);
                    $comment->setService($service);

                    $this->_em->persist($comment);
                    $this->_em->flush();

                    $this->_messenger->addMessage('Votre témoignage va maintenant passer par notre service de modération afin de garantir
                        le meilleur service possible. Ce processus peut prendre du temps, nous vous remercions de votre patience.', My_Messenger::TYPE_OK);
                }
                else
                {
                    $this->_messenger->addMessage('Certains champs ne sont pas renseignés correctement.', My_Messenger::TYPE_ERROR);
                }
        }

        // Selected job or best job by default
        $jobId = $this->_getParam('metier', 0);
        if (intval($jobId) == 0)
        {
            $job = $service->getJobs()->first();
        }
        else
        {
            $job = $this->_em->find('\Default_Model_Base_ServiceJob', $jobId);
            if ($job == null)
                $this->_messenger->addMessage('Le métier est introuvable.', My_Messenger::TYPE_ERROR);
        }

        // Comments
        $qb = $this->_em->createQueryBuilder();
        $qb->select('c')
                ->from('\Default_Model_Base_Comment', 'c')
                ->where('c.service = :serviceId')
                ->andWhere('c.status = :status')
                ->orderBy('c.created_at', 'DESC')
                ->setParameters(array('serviceId' => $service->getId(),
                    'status' => My_Controller_Action::COMMENT_STATUS_ACTIVE));

        $query = $qb->getQuery();
        $res = $query->getResult();
        $commentsInfos = $this->calcCommentsInfos($res);

        $comments = Zend_Paginator::factory($res);
        $comments->setCurrentPageNumber($this->_getParam('page', 1));
        $comments->setItemCountPerPage($this->_getParam('resultat', 10));

        $this->view->member = $service->getMember();
        $this->view->service = $service;
        $this->view->job = $job;
        $this->view->comments = $comments;
        $this->view->commentsInfos = $commentsInfos;
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
                if($comment->getStatus() == My_Controller_Action::COMMENT_STATUS_ACTIVE){
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
            }
        if ($commentsInfos['total'] > 0)
            $commentsInfos['note'] = round($commentsInfos['note'] / $commentsInfos['total']);
        else
            $commentsInfos['note'] = 3;

        return $commentsInfos;
    }
    
    public function reportAction()
    {
        $this->_helper->layout()->disableLayout();
        
        if ($this->getRequest()->isXmlHttpRequest())
        {
            if ($this->_request->isPost())
            {
                if(isset($_POST['author']) && isset($_POST['service']) && isset($_POST['reason']) && isset($_POST['complement'])){
                    if($_POST['author'] != null && $_POST['service'] != null && $_POST['reason'] != null && $_POST['complement'] != null){
                        $report = new \Default_Model_Base_Report();
                        $member = $this->_em->find('\Default_Model_Base_Member', $_POST['author']);
                        $report->setMember($member);
                        $service = $this->_em->find('\Default_Model_Base_Service', $_POST['service']);
                        $report->setService($service);
                        $report->setReason($_POST['reason']);
                        $report->setComplement($_POST['complement']);
                        
                        $this->_em->persist($report);
                        $this->_em->flush();
                        
                        // "ok" as result
                        echo 'ok';
                    }
                }
            }
        }
    }

    /**
     * AJAX
     */
    public function filterCommentsAction()
    {
        if ($this->_admin == null)
            return;

        if ($this->getRequest()->isXmlHttpRequest())
        {
            //$this->_helper->viewRenderer->setNoRender();
            $this->_helper->layout()->disableLayout();

            if ($this->_request->isPost())
            {
                $serviceId = intval($_POST['serviceId']);
                if ($serviceId <= 0)
                    return;

                $service = $this->_em->find('\Default_Model_Base_Service', $serviceId);
                if ($service == null)
                    return;

                $filter = intval($_POST['filter']);

                $qb = $this->_em->createQueryBuilder();
                $qb->select('c')
                        ->from('\Default_Model_Base_Comment', 'c')
                        ->where('c.service = :serviceId')
                        ->andWhere('c.status = :status')
                        ->andWhere('c.type = :type')
                        ->orderBy('c.creationDate', 'DESC')
                        ->setParameters(array('serviceId' => $service->getId(),
                            'status' => My_Controller_Action::COMMENT_STATUS_ACTIVE,
                            'type' => $filter));


                $query = $qb->getQuery();
                $res = $query->getResult();

                $comments = Zend_Paginator::factory($res);
                $comments->setCurrentPageNumber($this->_getParam('page', 1));
                $comments->setItemCountPerPage($this->_getParam('resultat', 10));

                $this->view->comments = $comments;
                $this->view->service = $service;
                //$this->render('_partials/comments.phtml');
            }
        }
    }
    
    public function getResourceId() {
        return 'serviceController';
    }
}

