<?php

/**
 * Description of MembreController
 *
 * @author Lordinaire
 */
class Admin_MembreController extends My_Controller_Action {

    public function init() {
        parent::init();
        $this->_acl->allow('admin', $this->getResourceId(), 'index');
    }

    public function indexAction() {
        $this->checkUser();
        
        $form = new My_Forms_Admin_SearchMember();
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $qb = $this->_em->createQueryBuilder();
                $qb->select('m')
                        ->from('\Default_Model_Base_Member', 'm')
                        ->orderBy('m.created_at', 'DESC');

                // Lastname
                $lastname = $this->_getParam('lastname', '');
                if (isset($lastname) && !empty($lastname))
                    $qb->andWhere($qb->expr()->like('m.lastname', ':lastname'))
                            ->setParameter('lastname', $lastname);

                // Firstname
                $firstname = $this->_getParam('firstname', '');
                if (isset($firstname) && !empty($firstname))
                    $qb->andWhere($qb->expr()->like('m.firstname', ':firstname'))
                            ->setParameter('firstname', $firstname);

                // Email
                $email = $this->_getParam('email', '');
                if (isset($email) && !empty($email))
                    $qb->andWhere($qb->expr()->like('m.email', ':email'))
                            ->setParameter('email', $email);

                $query = $qb->getQuery();
                $res = $query->getResult();

                $members = Zend_Paginator::factory($res);
                $members->setCurrentPageNumber($this->_getParam('page', 1));
                $members->setItemCountPerPage($this->_getParam('resultat', 20));

                $this->view->members = $members;
            }
        }
    }

    public function getResourceId() {
        return 'adminMembreController';
    }
}