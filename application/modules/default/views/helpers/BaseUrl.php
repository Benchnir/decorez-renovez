<?php

/**
 * Retourne l'URI
 *
 */
class Zend_View_Helper_BaseUrl
{
    function baseUrl($path, $ext = false)
    {
        if($ext){
            return Zend_Registry::get('host').Zend_Registry::get('assetBasePath').$path;
        } else {
            return Zend_Registry::get('assetBasePath').$path;
        }
    }
}
