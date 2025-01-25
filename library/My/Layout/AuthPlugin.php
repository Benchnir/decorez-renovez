<?php

class My_Layout_AuthPlugin extends Zend_Controller_Plugin_Abstract
{
   public function preDispatch(Zend_Controller_Request_Abstract $request)
   {
      $layout = Zend_Layout::getMvcInstance();
      $view = $layout->getView();

      $view->user = Zend_Auth::getInstance()->getIdentity();
   }
}
