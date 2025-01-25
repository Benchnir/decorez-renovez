<?php

use Doctrine\ORM\EntityRepository;

/**
 * Default_Model_Comment
 * 
 */
class Default_Model_Comment extends EntityRepository {

    /**
     * Get comments by service id
     * @param type $serviceId
     * @return array of comments  
     */
    public function getCommentsByServiceId($serviceId) {
        $qb = $this->createQueryBuilder('c')
                ->where('c.service = ?1')
                ->andWhere('c.status = ?2')
                ->orderBy('c.creationDate', 'desc')
                ->setParameter(1, $serviceId)
                ->setParameter(2, My_Controller_Action::COMMENT_STATUS_ACTIVE);

        $results = $qb->getQuery()->getResult();
        if ($results != null && count($results) > 0)
            return $results;
        return null;
    }

}