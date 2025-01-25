<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="Default_Model_Announcement")
 * @Table(name="announcement")
 * @HasLifecycleCallbacks
 */
class Default_Model_Base_Announcement 
{
    /**
     * @Id 
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** 
     * @Column(type="string", length=1000) 
     */
    protected $description;
    
    /** 
     * @Column(type="boolean") 
     */
    protected $is_visible;
    
    /** 
     * @Column(type="boolean") 
     */
    protected $is_image;

    /** 
     * @Column(type="datetime", nullable=true) 
     */
    protected $urgent_expire_at;
    
    /** 
     * @Column(type="datetime", nullable=true) 
     */
    protected $top_list_expire_at;

    /** 
     * @Column(type="integer")
     */
    protected $budget;
    
    /**
     * @Column(type="integer")
     */
    protected $duration;

    /**
     * @var \Default_Model_Base_Member member
     * 
     * @ManyToOne(targetEntity="\Default_Model_Base_Member")
     * @JoinColumn(name="member_id", referencedColumnName="id")
     */
    protected $member;
    
    /**
     * @var \Default_Model_Base_AnnouncementPicture offers
     * 
     * @OneToMany(targetEntity="\Default_Model_Base_AnnouncementPicture", mappedBy="annonce")
     */
    private $pictures;
    
    /**
     * @var \Default_Model_Base_Offers offers
     * 
     * @OneToMany(targetEntity="\Default_Model_Base_Offer", mappedBy="annonce")
     */
    private $offers;
    
    /**
     * @var \Default_Model_Base_Region region
     * 
     * @ManyToOne(targetEntity="\Default_Model_Base_Department")
     * @JoinColumn(name="department_id", referencedColumnName="id")
     */
    protected $department;

    /**
     * @ManyToMany(targetEntity="\Default_Model_Base_Job")
     * @JoinTable(name="announcement_job",
     *      joinColumns={@JoinColumn(name="job_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="announcement_id", referencedColumnName="id")}
     *      )
     */
    protected $jobs;
    
    /** 
     * @Column(type="datetime") 
     */
    protected $created_at;

    /**
     * @Column(type="datetime") 
     */
    protected $updated_at;
    
    public function __construct()
    {
        $this->jobs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->offers = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function isUrgent()
    {
        if($this->urgent_expire_at != null && $this->urgent_expire_at->getTimestamp() > time()){
            return true;
        }
        return false;
    }
    
    public function isTopList()
    {
        if($this->top_list_expire_at != null && $this->top_list_expire_at->getTimestamp() > time()){
            return true;
        }
        return false;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Default_Model_Base_Announcement
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
     * Set is_visible
     *
     * @param boolean $isVisible
     * @return Default_Model_Base_Announcement
     */
    public function setIsVisible($isVisible)
    {
        $this->is_visible = $isVisible;
        return $this;
    }

    /**
     * Get is_visible
     *
     * @return boolean 
     */
    public function getIsVisible()
    {
        return $this->is_visible;
    }

    /**
     * Set is_image
     *
     * @param boolean $isImage
     * @return Default_Model_Base_Announcement
     */
    public function setIsImage($isImage)
    {
        $this->is_image = $isImage;
        return $this;
    }

    /**
     * Get is_image
     *
     * @return boolean 
     */
    public function getIsImage()
    {
        return $this->is_image;
    }

    /**
     * Set urgent_expire_at
     *
     * @param datetime $urgent_expire_at
     * @return Default_Model_Base_Announcement
     */
    public function setUrgentExpireAt($urgent_expire_at)
    {
        $this->urgent_expire_at = $urgent_expire_at;
        return $this;
    }

    /**
     * Get urgent_expire_at
     *
     * @return datetime 
     */
    public function getUrgentExpireAt()
    {
        return $this->urgent_expire_at;
    }
    
    /**
     * Set top_list_expire_at
     *
     * @param datetime $top_list_expire_at
     * @return Default_Model_Base_Announcement
     */
    public function setTopListExpireAt($top_list_expire_at)
    {
        $this->top_list_expire_at = $top_list_expire_at;
        return $this;
    }

    /**
     * Get top_list_expire_at
     *
     * @return datetime 
     */
    public function getTopListExpireAt()
    {
        return $this->top_list_expire_at;
    }

    /**
     * Set budget
     *
     * @param integer $budget
     * @return Default_Model_Base_Announcement
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;
        return $this;
    }

    /**
     * Get budget
     *
     * @return integer 
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     * @return Default_Model_Base_Announcement
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * Get duration
     *
     * @return integer 
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set created_at
     *
     * @param datetime $createdAt
     * @return Default_Model_Base_Announcement
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
     * @return Default_Model_Base_Announcement
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
     * @return Default_Model_Base_Announcement
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
     * Set department
     *
     * @param Default_Model_Base_Department $department
     * @return Default_Model_Base_Announcement
     */
    public function setDepartment(\Default_Model_Base_Department $department = null)
    {
        $this->department = $department;
        return $this;
    }

    /**
     * Get department
     *
     * @return Default_Model_Base_Department 
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Add jobs
     *
     * @param Default_Model_Base_Job $jobs
     */
    public function addDefault_Model_Base_Job(\Default_Model_Base_Job $jobs)
    {
        $this->jobs[] = $jobs;
    }

    /**
     * Get jobs
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getJobs()
    {
        return $this->jobs;
    }
    
    /**
     * Add offer
     *
     * @param \Default_Model_Base_Offer $offer
     */
    public function addDefault_Model_Base_Offer(\Default_Model_Base_Offer $offer)
    {
        $this->offers[] = $offer;
    }

    /**
     * Get offer
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getOffers()
    {
        return $this->offers;
    }
    
    /**
     * Add picture
     *
     * @param \Default_Model_Base_AnnouncementPicture $picture
     */
    public function addDefault_Model_Base_AnnouncementPicture(\Default_Model_Base_AnnouncementPicture $picture)
    {
        $this->pictures[] = $picture;
    }

    /**
     * Get pictures
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPictures()
    {
        return $this->pictures;
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