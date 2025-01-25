<?php

use Doctrine\ORM\EntityRepository;

/**
 * Default_Model_Annonce
 * 
 */
class Default_Model_Announcement extends EntityRepository
{

    /**
     * Get all announcements by member id
     * @param type $memberId
     * @return Array of Announcement $announcements 
     */
    public function getAnnouncementsByMemberId($memberId)
    {
        $qb = $this->createQueryBuilder('a')
                ->where('a.member = ?1')
                ->andWhere('a.dateCreation > ?2')
                ->setParameter(1, $memberId)
                ->setParameter(2, mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")));

        return $qb->getQuery()->getResult();
    }

}