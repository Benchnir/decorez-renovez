<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="avatar")
 * @HasLifecycleCallbacks
 */
class Default_Model_Base_MemberAvatar
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
     * @ManyToOne(targetEntity="\Default_Model_Base_Member", inversedBy="avatars")
     * @JoinColumn(name="member_id", referencedColumnName="id")
     **/
    private $member;
    
    /** 
     * @Column(type="boolean") 
     */
    protected $is_main;
    
    
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
     * Set member
     *
     * @param Default_Model_Base_Member $member
     * @return Default_Model_Base_Member
     */
    public function setMember(\Default_Model_Base_Member $member = null)
    {
        $this->member = $member;
        return $this;
    }

    /**
     * Get member
     *
     * @return \Default_Model_Base_Member 
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Set is_main
     *
     * @param boolean $isMain
     * @return \Default_Model_Base_MemberAvatar
     */
    public function setIsMain($isMain)
    {
        $this->is_main = $isMain;
        return $this;
    }

    /**
     * Get is_main
     *
     * @return boolean 
     */
    public function getIsMain()
    {
        return $this->is_main;
    }
}