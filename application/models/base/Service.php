<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="Default_Model_Service")
 * @Table(name="service")
 * @HasLifecycleCallbacks
 */
class Default_Model_Base_Service 
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /** 
     * @Column(type="string", length=1000, nullable=true) 
     */
    protected $description;
    
    /** 
     * @Column(type="integer", nullable=true) 
     */
    protected $experience;
    
    /** 
     * @Column(type="boolean") 
     */
    protected $is_visible;

    /**
     * @var \Default_Model_Base_Job mainJob
     * 
     * @ManyToOne(targetEntity="\Default_Model_Base_ServiceJob")
     * @JoinColumn(name="main_job", referencedColumnName="id", nullable=true)
     */
    protected $main_job;

    /**
     * @var \Default_Model_Base_Member member
     * 
     * @OneToOne(targetEntity="\Default_Model_Base_Member", inversedBy="service")
     * @JoinColumn(name="member_id", referencedColumnName="id")
     */
    protected $member;
    
    /**
     * @OneToMany(targetEntity="\Default_Model_Base_ServiceJob", mappedBy="service")
     */
    protected $jobs;
    
    /**
     * @OneToMany(targetEntity="\Default_Model_Base_Comment", mappedBy="service")
     */
    protected $comments;
    
    /*********************************** department is now useless here and can be remove ******************************************/
    /**
     * @var \Default_Model_Base_Department department
     * 
     * @ManyToOne(targetEntity="\Default_Model_Base_Department")
     * @JoinColumn(name="department_id", referencedColumnName="id", nullable=true)
     */
    protected $department;

    /** 
     * @Column(type="datetime") 
     */
    protected $updated_at;
    
    public function __construct()
    {
        $this->jobs = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getMainJobName()
    {
        $main_job = $this->getMainJob();
        if($main_job != null){
            return $main_job->getJob()->getName();
        }
        return '';
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

    /**
     * Set description
     *
     * @param string $description
     * @return Default_Model_Base_Service
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
     * Set experience
     *
     * @param integer $experience
     * @return Default_Model_Base_Service
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;
        return $this;
    }

    /**
     * Get experience
     *
     * @return integer 
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * Set is_visible
     *
     * @param boolean $isVisible
     * @return Default_Model_Base_Service
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
     * Set updated_at
     *
     * @param datetime $updatedAt
     * @return Default_Model_Base_Service
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
     * Set main_job
     *
     * @param \Default_Model_Base_ServiceJob $mainJob
     * @return Default_Model_Base_Service
     */
    public function setMainJob(\Default_Model_Base_ServiceJob $mainJob = null)
    {
        $this->main_job = $mainJob;
        return $this;
    }

    /**
     * Get main_job
     *
     * @return \Default_Model_Base_ServiceJob 
     */
    public function getMainJob()
    {
        return $this->main_job;
    }

    /**
     * Set member
     *
     * @param Default_Model_Base_Member $member
     * @return Default_Model_Base_Service
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
     * Add jobs
     *
     * @param Default_Model_Base_ServiceJob $jobs
     */
    public function addDefault_Model_Base_ServiceJob(\Default_Model_Base_ServiceJob $jobs)
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
     * Add comment
     *
     * @param Default_Model_Base_Comment $comment
     */
    public function addDefault_Model_Base_Comment(\Default_Model_Base_Comment $comment)
    {
        $this->comments[] = $comment;
    }

    /**
     * Get comments
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set department
     *
     * @param Default_Model_Base_Department $department
     * @return Default_Model_Base_Service
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
     * EVENTS 
     */

    /**
     * @PrePersist()
     */
    public function _prePersist()
    {
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