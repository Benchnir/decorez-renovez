<?php

class Default_Model_Base_Duration
{
    const SMALL = 1;
    const MEDIUM = 2;
    const LARGE = 3;
    const HUGE = 4;
    
    
    static public function getDurationDescription($budget)
    {
        switch ($budget) {
            case self::SMALL:
                return '< 1 jour';
                break;
            case self::MEDIUM:
                return 'entre 1 et 3 jours';
                break;
            case self::LARGE:
                return 'entre 3 et 7 jours';
                break;
            case self::HUGE:
                return 'Entre 7 et 14 jours';
                break;
            default:
                return '';
                break;
        }
    }
}