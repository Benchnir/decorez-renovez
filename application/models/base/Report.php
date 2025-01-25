<?php

/**
 * @Entity()
 * @Table(name="report")
 * @HasLifecycleCallbacks
 */
class Default_Model_Base_Report {

    /**
     * @Id 
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \Default_Model_Base_Member member
     * 
     * @ManyToOne(targetEntity="\Default_Model_Base_Member")
     * @JoinColumn(name="member_id", referencedColumnName="id")
     */
    protected $member;

    /**
     * @var \Default_Model_Base_Service service
     * 
     * @ManyToOne(targetEntity="\Default_Model_Base_Service")
     * @JoinColumn(name="service_id", referencedColumnName="id")
     */
    protected $service;
    
    /** 
     * @Column(type="text") 
     */
    protected $reason;
    
    /** 
     * @Column(type="text") 
     */
    protected $complement;
    
    /** @Column(type="datetime") */
    protected $created_at;

    /** @Column(type="datetime") */
    protected $updated_at;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set message
     *
     * @param text $reason
     * @return Default_Model_Base_Report
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
        return $this;
    }

    /**
     * Get message
     *
     * @return text 
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Set message
     *
     * @param text $complement
     * @return Default_Model_Base_Report
     */
    public function setComplement($complement)
    {
        $this->complement = $complement;
        return $this;
    }

    /**
     * Get message
     *
     * @return text 
     */
    public function getComplement()
    {
        return $this->complement;
    }

    /**
     * Set created_at
     *
     * @param datetime $createdAt
     * @return Default_Model_Base_Comment
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
        return $this;
    }

    /**
     * Get created_at
     *
     * @return datetime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updated_at
     *
     * @param datetime $updatedAt
     * @return Default_Model_Base_Comment
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
        return $this;
    }

    /**
     * Get updated_at
     *
     * @return datetime 
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set member
     *
     * @param Default_Model_Base_Member $member
     * @return Default_Model_Base_Comment
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

    /**
     * Set service
     *
     * @param Default_Model_Base_Service $service
     * @return Default_Model_Base_Comment
     */
    public function setService(\Default_Model_Base_Service $service = null)
    {
        $this->service = $service;
        return $this;
    }

    /**
     * Get service
     *
     * @return Default_Model_Base_Service 
     */
    public function getService()
    {
        return $this->service;
    }
    
    /**
     * EVENTS 
     */

    /**
     * @PrePersist()
     */
    public function _prePersist()
    {
        $this->setCreatedAt(new DateTime());
        $this->setUpdatedAt(new DateTime());
    }

    /**
     * @PreUpdate()
     */
    public function _preUpdate()
    {
        $this->setUpdatedAt(new DateTime());
    }
}