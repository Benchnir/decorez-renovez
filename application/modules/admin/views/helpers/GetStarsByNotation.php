<?php

/**
 * Retourne le nombre d'étoiles selon la notation
 *
 */
class Zend_View_Helper_GetStarsByNotation
{
    function getStarsByNotation($notation)
    {
        $html = '';
        for ($i = intval($notation); $i >= 1; $i--)
        {
            $html .= '<img src="/decorez/public/images/etoile.png" alt="etoile">';
        }
        
        return $html;
    }
}
