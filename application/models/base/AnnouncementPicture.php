<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="picture")
 * @HasLifecycleCallbacks
 */
class Default_Model_Base_AnnouncementPicture
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /** 
     * @Column(type="string", length=255, nullable=false) 
     */
    protected $path;
    
    /**
     * @ManyToOne(targetEntity="\Default_Model_Base_Announcement", inversedBy="pictures")
     * @JoinColumn(name="annonce_id", referencedColumnName="id")
     **/
    private $annonce;
    
    
    public function __construct()
    {
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
     * Get department
     *
     * @return Default_Model_Base_Department 
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Default_Model_Base_MemberAvatar
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }
    
    /**
     * Set annonce
     *
     * @param \Default_Model_Base_Announcement $annonce
     * @return \Default_Model_Base_AnnouncementPicture
     */
    public function setAnnonce(\Default_Model_Base_Announcement $annonce = null)
    {
        $this->annonce = $annonce;
        return $this;
    }

    /**
     * Get annonce
     *
     * @return Default_Model_Base_Member 
     */
    public function getAnnonce()
    {
        return $this->annonce;
    }
}