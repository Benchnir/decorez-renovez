<?php

/**
 * @Entity(repositoryClass="Default_Model_Comment")
 * @Table(name="comment")
 * @HasLifecycleCallbacks
 */
class Default_Model_Base_Comment {

    /**
     * @Id 
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \Default_Model_Base_Member member
     * 
     * @ManyToOne(targetEntity="\Default_Model_Base_Member", inversedBy="comments")
     * @JoinColumn(name="member_id", referencedColumnName="id")
     */
    protected $member;

    /**
     * @var \Default_Model_Base_Service service
     * 
     * @ManyToOne(targetEntity="\Default_Model_Base_Service", inversedBy="comments")
     * @JoinColumn(name="service_id", referencedColumnName="id")
     */
    protected $service;

    /** 
     * @Column(type="integer") 
     */
    protected $type;
    
    /** 
     * @Column(type="text") 
     */
    protected $message;
    
    /** 
     * @Column(type="integer") 
     */
    protected $status;
    
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
     * Set type
     *
     * @param integer $type
     * @return Default_Model_Base_Comment
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set message
     *
     * @param text $message
     * @return Default_Model_Base_Comment
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Get message
     *
     * @return text 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Default_Model_Base_Comment
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
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