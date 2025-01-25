<?php

use \Doctrine\ORM\EntityRepository;

/**
 * Default_Model_Region
 * 
 */
class Default_Model_Region extends EntityRepository {

    public function getAllRegionsToArray() {
        $query = $this->_em->createQuery('SELECT r.id, r.name FROM \Default_Model_Base_Region r ORDER BY r.name ASC');
        $regions = $query->getResult();

        $options = array();
        foreach ($regions as $key => $value) {
            $options[$value['id']] = $value['name'];
        }
        return $options;
    }

}