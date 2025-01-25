<?php

/**
 * Description of IndexController
 *
 * @author Lordinaire
 */
class Admin_JobController extends My_Controller_Action {

    public function init() {
        parent::init();
        $this->_acl->allow('admin', $this->getResourceId(), 'index');
    }

    public function indexAction() {
        $this->checkUser();
        
        $this->view->jobs = $this->_em->createQueryBuilder()
                ->select('j')
                ->from('\Default_Model_Base_Job', 'j')
                ->andWhere('j.is_validate = false')
                ->getQuery()
                ->getResult();
    }
    public function acceptAction()
    {
        $id = $this->getRequest()->getParam('id');
        $job = $this->_em->find('\Default_Model_Base_Job', $id);
        $job->setIsValidate(true);
        $this->_em->flush();
        $this->_redirect('admin/job/index');
    }
    public function refuseAction()
    {
        $id = $this->getRequest()->getParam('id');
        $job = $this->_em->find('\Default_Model_Base_Job', $id);
        $this->_em->remove($job);
        $this->_em->flush();
        $this->_redirect('admin/job/index');
    }

    public function getResourceId() {
        return 'adminJobController';
    }
}