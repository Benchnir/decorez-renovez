<?php

/**
 * Retourne le nombre d'étoiles selon l'évaluation
 *
 */
class Zend_View_Helper_GetStarsByEvaluation {

    function getStarsByEvaluation($evaluation) {
        $html = '';
        for ($i = intval($evaluation); $i >= 1; $i--) {
            $html .= '<img src="/decorez/public/images/min_etoile.gif" alt="etoile">';
        }

        return $html;
    }

}
