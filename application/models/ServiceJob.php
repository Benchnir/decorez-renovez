<?php

use \Doctrine\ORM\EntityRepository;

/**
 * Default_Model_ServiceJob
 * 
 */
class Default_Model_ServiceJob extends EntityRepository {

    /**
     * Get the best job
     * @param type $serviceId
     * @return ServiceJob $job 
     */
    public function getBestJobByServiceId($serviceId) {
        $qb = $this->createQueryBuilder('sj')
                ->join('sj.services', 's')
                ->where('s.id = ?1')
                ->orderBy('sj.evaluation', 'desc')
                ->setParameter(1, $serviceId);

        $results = $qb->getQuery()->getResult();
        if ($results != null && count($results) > 0)
            return $results[0];
        return null;
    }

    /**
     * Get all jobs
     * @param type $serviceId
     * @return Array of ServiceJob $jobs 
     */
    public function getAllJobsByServiceId($serviceId) {
        $qb = $this->createQueryBuilder('sj')
                ->join('sj.services', 's')
                ->where('s.id = ?1')
                ->orderBy('sj.creationDate')
                ->setParameter(1, $serviceId);

        return $qb->getQuery()->getResult();
    }

}