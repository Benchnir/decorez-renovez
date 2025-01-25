<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="Default_Model_ServiceJob")
 * @Table(name="service_job")
 * @HasLifecycleCallbacks
 */
class Default_Model_Base_ServiceJob {
    /**
     * @Id 
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** 
     * @Column(type="integer", nullable=false) 
     */
    protected $evaluation;
    
    /** 
     * @Column(name="price1", type="integer", nullable=false) 
     */
    protected $price1;
    
    /** 
     * @Column(name="price2", type="integer", nullable=false) 
     */
    protected $price2;
    
    /** 
     * @Column(name="price3", type="integer", nullable=false) 
     */
    protected $price3;
    
    /**
     * @ManyToOne(targetEntity="\Default_Model_Base_Job")
     * @JoinColumn(name="job_id", referencedColumnName="id")
     */
    protected $job;
    
    /**
     * @ManyToOne(targetEntity="\Default_Model_Base_Service", inversedBy="jobs")
     * @JoinColumn(name="service_id", referencedColumnName="id")
     */
    protected $service;  

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
     * Set evaluation
     *
     * @param integer $evaluation
     * @return Default_Model_Base_ServiceJob
     */
    public function setEvaluation($evaluation)
    {
        $this->evaluation = $evaluation;
        return $this;
    }

    /**
     * Get evaluation
     *
     * @return integer 
     */
    public function getEvaluation()
    {
        return $this->evaluation;
    }

    /**
     * Set price1
     *
     * @param integer $price1
     * @return Default_Model_Base_ServiceJob
     */
    public function setPrice1($price1)
    {
        $this->price1 = $price1;
        return $this;
    }

    /**
     * Get price1
     *
     * @return integer 
     */
    public function getPrice1()
    {
        return $this->price1;
    }

    /**
     * Set price2
     *
     * @param integer $price2
     * @return Default_Model_Base_ServiceJob
     */
    public function setPrice2($price2)
    {
        $this->price2 = $price2;
        return $this;
    }

    /**
     * Get price2
     *
     * @return integer 
     */
    public function getPrice2()
    {
        return $this->price2;
    }

    /**
     * Set price3
     *
     * @param integer $price3
     * @return Default_Model_Base_ServiceJob
     */
    public function setPrice3($price3)
    {
        $this->price3 = $price3;
        return $this;
    }

    /**
     * Get price3
     *
     * @return integer 
     */
    public function getPrice3()
    {
        return $this->price3;
    }

    /**
     * Set created_at
     *
     * @param datetime $createdAt
     * @return Default_Model_Base_ServiceJob
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
     * @return Default_Model_Base_ServiceJob
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
     * Set job
     *
     * @param Default_Model_Base_Job $job
     * @return Default_Model_Base_ServiceJob
     */
    public function setJob(\Default_Model_Base_Job $job = null)
    {
        $this->job = $job;
        return $this;
    }

    /**
     * Get job
     *
     * @return Default_Model_Base_Job 
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Set service
     *
     * @param Default_Model_Base_Service $service
     * @return Default_Model_Base_ServiceJob
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