<?php

use \Doctrine\ORM\EntityRepository;

/**
 * Default_Model_Duration
 * 
 */
class Default_Model_JobCategory extends EntityRepository {

    public function getAllJobCategoriesToArray() {
        $query = $this->_em->createQuery('SELECT b.id, b.name FROM \Default_Model_Base_JobCategory b ORDER BY b.name ASC');
        $jobs = $query->getResult();

        $options = array();
        foreach ($jobs as $key => $value) {
            $options[$value['id']] = $value['name'];
        }
        return $options;
    }
}