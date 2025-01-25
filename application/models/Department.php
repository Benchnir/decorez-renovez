<?php

use \Doctrine\ORM\EntityRepository;

/**
 * Default_Model_Region
 * 
 */
class Default_Model_Department extends EntityRepository {

    public function getAllDepartmentsToArray() {
        $query = $this->_em->createQuery('SELECT r.id, r.name FROM \Default_Model_Base_Department r ORDER BY r.id ASC');
        $deptarments = $query->getResult();

        $options = array();
        foreach ($deptarments as $key => $value) {
            $options[$value['id']] = $value['name'];
        }
        return $options;
    }

}