<?php

/**
 * Description of IndexController
 *
 * @author Lordinaire
 */
class Admin_IndexController extends My_Controller_Action {

    public function init() {
        parent::init();
        $this->_acl->allow('admin', $this->getResourceId(), 'index');
    }

    public function indexAction() {
        $this->checkUser();
    }

    public function getResourceId() {
        return 'adminIndexController';
    }
}