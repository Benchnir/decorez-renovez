<?php

class Default_Model_Ext_Member extends Default_Model_Base_Member {

    const NOT_FOUND = 1;
    const WRONG_PASSWORD = 2;
    const UNACTIVE_ACCOUNT = 3;

    /**
     * Perform authentication of a user
     * @param string $email
     * @param string $password
     */
    public static function authenticate($email, $password, $facebook = false) {
        $registry = Zend_Registry::getInstance();
        $em = $registry->entitymanager;

        $qb = $em->createQueryBuilder();
        $qb->add('select', 'm')
                ->add('from', '\Default_Model_Base_Member m')
                ->add('where', 'm.email = :email');
        $query = $qb->getQuery();
        $query->setParameter('email', $email);
        try {
            $user = $query->getSingleResult();
            
            if($facebook){
                if ($user->getFacebookId() != null)
                    return $user;
            } else {
                if($user->getIsActive()){
                    if ($user->getPassword() == md5($password.$user->getSalt()))
                        return $user;
                } else {
                    throw new Exception(self::UNACTIVE_ACCOUNT);
                }
            }
        } catch (\Doctrine\ORM\NoResultException $e) {
            throw new Exception(self::NOT_FOUND);
        }
        throw new Exception(self::NOT_FOUND);
    }
    
    /**
     * Perform authentication of an administrator
     * @param string $email
     * @param string $password
     */
    public static function authenticateAsAdmin($email, $password) {
        $registry = Zend_Registry::getInstance();
        $em = $registry->entitymanager;

        $qb = $em->createQueryBuilder();
        $qb->select('m')
                ->from('\Default_Model_Base_Member', 'm')
                ->where('m.email = :email')
                ->andWhere('m.role > 0');
        $query = $qb->getQuery();
        $query->setParameter('email', $email);
        try {
            $user = $query->getSingleResult();
            if ($user->getPassword() == sha1($password))
                return $user;
        } catch (\Doctrine\ORM\NoResultException $e) {
            throw new Exception(self::NOT_FOUND);
        }
        throw new Exception(self::NOT_FOUND);
    }
}

