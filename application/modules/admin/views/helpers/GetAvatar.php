<?php

/**
 * Retourne l'avatar d'un membre
 *
 */
class Zend_View_Helper_GetAvatar
{
    function getAvatar($name)
    {
            $html = '<img src="/avatars/'. $name .'" alt="avatar" class="avatar">';
        
        return $html;
    }
}
