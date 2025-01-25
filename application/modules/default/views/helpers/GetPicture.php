<?php

/**
 * Retourne l'avatar d'un membre
 *
 */
class Zend_View_Helper_GetPicture
{
    function getPicture($name)
    {
        return '<img src="'.Zend_Registry::get('assetBasePath').'annonces/'.$name.'" alt="picture" class="picture" width="50" height="50">';
    }
}
