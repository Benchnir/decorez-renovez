<?php

use \Doctrine\ORM\EntityRepository;

/**
 * Default_Model_Duration
 * 
 */
class Default_Model_Job extends EntityRepository {

    public function getAllJobsToArray() {
        $query = $this->_em->createQuery('SELECT b.id, b.name FROM \Default_Model_Base_Job b WHERE b.is_validate = TRUE ORDER BY b.name ASC');
        $jobs = $query->getResult();

        $options = array();
        foreach ($jobs as $key => $value) {
            $options[$value['id']] = $value['name'];
        }
        return $options;
    }

    public function getAllValidateJobs() {
        return $this->_em
                ->createQuery('SELECT b FROM \Default_Model_Base_Job b WHERE b.is_validate = TRUE ORDER BY b.name ASC')
                ->getResult();
    }
}