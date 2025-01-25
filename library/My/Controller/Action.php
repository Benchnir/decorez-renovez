<?php

/**
 * Description of Action
 *
 * @author Maxime FRAPPAT
 */
class My_Controller_Action extends Zend_Controller_Action implements Zend_Acl_Resource_Interface{

    const USER_SESSION_NAME = 'user';
    const ADMIN_SESSION_NAME = 'admin';
    
    const AVATAR_BASE = 'avatar.jpg';
    
    const COMMENT_STATUS_NEW = 0;
    const COMMENT_STATUS_ACTIVE = 1;
    const COMMENT_STATUS_MODERATED = 2;
    const COMMENT_STATUS_DELETED = 3;
    
    const COMMENT_TYPE_NEGATIVE = -1;
    const COMMENT_TYPE_NEUTRAL = 0;
    const COMMENT_TYPE_POSITIVE = 1;
    
    const MAX_ANNOUNCEMENT_PER_MEMBER = 2;
    
    /**
     * My_Messenger instance
     *
     * @var My_Messenger
     */
    protected $_messenger = null;

    /**
     * @var Zend_Session_Namespace
     */
    protected $_history = null;

    public function init() {
        $registry = Zend_Registry::getInstance();
        $this->_em = $registry->entitymanager;
        if(Zend_Auth::getInstance()->hasIdentity()){
            $this->_user = Zend_Auth::getInstance()->getIdentity();
            $this->view->user = $this->_user;
        } else {
            $this->view->user = null;
        }
        $this->_messenger = new My_Messenger();
        $this->_history = new Zend_Session_Namespace('History');

        $this->initView();
        $this->view->baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();

        // Ajout des css de base
        $this->view->headLink()->prependStylesheet($this->view->baseUrl . '/public/css/style.css');
        $this->view->headLink()->prependStylesheet($this->view->baseUrl . '/public/css/messenger.css');
        $this->view->headLink()->prependStylesheet($this->view->baseUrl . '/public/css/jquery-ui.css');
        $this->view->headLink()->prependStylesheet($this->view->baseUrl . '/public/css/jquery.fancybox.css');
        $this->view->headLink()->prependStylesheet($this->view->baseUrl . '/public/css/jquery.fancybox-buttons.css');
        $this->view->headLink()->prependStylesheet($this->view->baseUrl . '/public/css/jquery.rating.css');

        // Ajout des script de base
        $this->view->headScript()->appendFile($this->view->baseUrl . '/public/js/libs/swfobject.js');
        
        $this->_acl = new Zend_Acl();
        $this->_acl->addRole('anonymous');
        $this->_acl->addRole('user', 'anonymous');
        $this->_acl->addRole('pro', 'anonymous');
        $this->_acl->addRole('admin', 'pro');
 
        $this->_acl->addResource('indexController');
        $this->_acl->addResource('membreController');
        $this->_acl->addResource('serviceController');
        $this->_acl->addResource('annonceController');
        $this->_acl->addResource('featureController');
        $this->_acl->addResource('offerController');
        $this->_acl->addResource('mailController');
        
        $this->_acl->addResource('adminIndexController');
        $this->_acl->addResource('adminMembreController');
        $this->_acl->addResource('adminCommentaireController');
        $this->_acl->addResource('adminJobController');
    }

    public function postDispatch() {
        $this->view->formMessages = $this->_messenger->getMessages();
    }

    protected function _saveToHistory() {
        $this->_history->previous = $_SERVER['REQUEST_URI'];
        return $this;
    }

    protected function _getHistory($reset = false) {
        $url = $this->_history->previous;
        if ($reset == true) {
            $this->_history->previous = null;
        }
        return $url;
    }
    
    protected function checkUser()
    {
        if(!$this->isAllowedToDisplay()){
            $this->_saveToHistory();
            $this->_redirect('/index/denied');
        }
    }
    
    protected function isAllowedToDisplay()
    {
        if(isset($this->_user)){
            return $this->_acl->isAllowed($this->_user, $this, $this->getRequest()->getActionName());
        }
        return $this->_acl->isAllowed(new Default_Model_Base_Member(), $this, $this->getRequest()->getActionName());
    }

    public function getResourceId() {
        return 'controller';
    }
}