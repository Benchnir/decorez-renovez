<?php

/**
 * @Entity(repositoryClass="Default_Model_Offer")
 * @Table(name="offer")
 * @HasLifecycleCallbacks
 */
class Default_Model_Base_Offer 
{
    /**
     * @Id 
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /** 
     * @Column(name="price", type="integer", nullable=false) 
     */
    protected $price;
    
    /** 
     * @Column(type="string", length=1000) 
     */
    protected $description;
    
    /**
     * @var \Default_Model_Base_Member member
     * 
     * @ManyToOne(targetEntity="\Default_Model_Base_Member", inversedBy="offers")
     * @JoinColumn(name="member_id", referencedColumnName="id")
     */
    protected $member;
    
    /**
     * @var \Default_Model_Base_Announcement annonce
     * 
     * @ManyToOne(targetEntity="\Default_Model_Base_Announcement")
     * @JoinColumn(name="annonce_id", referencedColumnName="id")
     */
    protected $annonce;
    
    /** 
     * @Column(type="boolean") 
     */
    protected $is_active;
    
    /** 
     * @Column(type="datetime")
     */
    protected $created_at;
    
    /** 
     * @Column(type="datetime") 
     */
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
     * Set member
     *
     * @param Default_Model_Base_Member $member
     * @return Default_Model_Base_Offer
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
     * Set annonce
     *
     * @param Default_Model_Base_Announcement $annonce
     * @return Default_Model_Base_Offer
     */
    public function setAnnonce(\Default_Model_Base_Announcement $annonce = null)
    {
        $this->annonce = $annonce;
        return $this;
    }

    /**
     * Get annonce
     *
     * @return Default_Model_Base_Announcement 
     */
    public function getAnnonce()
    {
        return $this->annonce;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Default_Model_Base_Offer
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price1
     *
     * @param integer $price
     * @return Default_Model_Base_Offer
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * Get price
     *
     * @return integer 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set is_active
     *
     * @param boolean $isActive
     * @return Default_Model_Base_Offer
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;
        return $this;
    }

    /**
     * Get is_active
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Set created_at
     *
     * @param datetime $createdAt
     * @return Default_Model_Base_Member
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
     * @return Default_Model_Base_Member
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