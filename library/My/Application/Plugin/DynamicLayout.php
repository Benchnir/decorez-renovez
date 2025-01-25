<?php

class My_Application_Plugin_DynamicLayout extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $module = $request->getModuleName();
        $layout = Zend_Layout::getMvcInstance();
        
        if (file_exists($layout->getLayoutPath() . DIRECTORY_SEPARATOR . $module . '.phtml')) {
            $layout->setLayout($module);
        } else {
            $layout->setLayout('default');
        }
    }
}