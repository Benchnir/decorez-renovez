<?php

/**
 * Retourne l'avatar d'un membre
 *
 */
class Zend_View_Helper_GetAvatar
{
    function getAvatar($name, $size = 100, $maxheight = 0)
    {
    	$style = ($maxheight!=0)?'style="max-height:'.$maxheight.'px"':'';
        return '<img src="'.Zend_Registry::get('assetBasePath').'avatars/'.$name.'" width="'.$size.'" alt="avatar" class="avatar" '.$style.'>';
    }
}
