<?php

class My_Ajax_Report
{
    private $_result = '';

    public function preDispatch()
    {
        
    }

    public function postDispatch()
    {
        
    }

    public function indexAction($params)
    {
        echo 'ok';
    }

    public function __toString()
    {
        return $this->_result;
    }

}