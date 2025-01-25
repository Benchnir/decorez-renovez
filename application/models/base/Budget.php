<?php

class Default_Model_Base_Budget
{
    const SMALL = 1;
    const MEDIUM = 2;
    const LARGE = 3;
    const HUGE = 4;
    
    
    static public function getBudgetDescription($budget)
    {
        switch ($budget) {
            case self::SMALL:
                return 'inférieur à 500 €';
                break;
            case self::MEDIUM:
                return 'entre 500 et 1000 €';
                break;
            case self::LARGE:
                return 'entre 1000 et 5000 €';
                break;
            case self::HUGE:
                return 'supérieur à 5000 €';
                break;
            default:
                return '';
                break;
        }
    }
}