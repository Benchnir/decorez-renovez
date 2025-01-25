<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="Default_Model_Feature")
 * @Table(name="feature")
 * @HasLifecycleCallbacks
 */
class Default_Model_Base_Feature 
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /** 
     * @Column(type="datetime", nullable=true) 
     */
    protected $top_list_expire;
    
    
    /** 
     * @Column(type="datetime", nullable=true) 
     */
    protected $partner_expire;
    
    
    /** 
     * @Column(type="datetime", nullable=true) 
     */
    protected $pro_expire;

    /**
     * @var \Default_Model_Base_Member member
     * 
     * @OneToOne(targetEntity="\Default_Model_Base_Member")
     * @JoinColumn(name="member_id", referencedColumnName="id")
     */
    protected $member;
    
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function isTopList()
    {
        if($this->top_list_expire != null && $this->top_list_expire->getTimestamp() > time()){
            return true;
        }
        return false;
    }
    public function isPartner()
    {
        if($this->partner_expire != null && $this->partner_expire->getTimestamp() > time()){
            return true;
        }
        return false;
    }
    public function isPro()
    {
        if($this->pro_expire != null && $this->pro_expire->getTimestamp() > time()){
            return true;
        }
        return false;
    }

    /**
     * Set top_list_expire
     *
     * @param datetime $topListExpire
     * @return Default_Model_Base_Feature
     */
    public function setTopListExpire($topListExpire)
    {
        $this->top_list_expire = $topListExpire;
        return $this;
    }

    /**
     * Get top_list_expire
     *
     * @return datetime 
     */
    public function getTopListExpire()
    {
        return $this->top_list_expire;
    }

    /**
     * Set partner_expire
     *
     * @param datetime $partnerExpire
     * @return Default_Model_Base_Feature
     */
    public function setPartnerExpire($partnerExpire)
    {
        $this->partner_expire = $partnerExpire;
        return $this;
    }

    /**
     * Get partner_expire
     *
     * @return datetime 
     */
    public function getPartnerExpire()
    {
        return $this->partner_expire;
    }

    /**
     * Set pro_expire
     *
     * @param datetime $proExpire
     * @return Default_Model_Base_Feature
     */
    public function setProExpire($proExpire)
    {
        $this->pro_expire = $proExpire;
        return $this;
    }

    /**
     * Get pro_expire
     *
     * @return datetime 
     */
    public function getProExpire()
    {
        return $this->pro_expire;
    }

    /**
     * Set member
     *
     * @param Default_Model_Base_Member $member
     * @return Default_Model_Base_Feature
     */
    public function setMember(\Default_Model_Base_Member $member = null)
    {
        $this->member = $member;
        return $this;
    }

    /**
     * Get member
     *
     * @return Default_Model_Base_Member 
     */
    public function getMember()
    {
        return $this->member;
    }
}