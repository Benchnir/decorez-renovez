<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="member")
 * @HasLifecycleCallbacks
 */
class Default_Model_Base_Member implements Zend_Acl_Role_Interface
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @Column(type="bigint", nullable=true)
     */
    protected $facebook_id;
    
    /** 
     * @Column(type="string", length=255, nullable=false) 
     */
    protected $lastname;
    
    /** 
     * @Column(type="string", length=255, nullable=false) 
     */
    protected $firstname;
    
    /** 
     * @Column(type="string", length=255, nullable=false, unique=true) 
     */
    protected $email;
    
    /** 
     * @Column(type="string", length=255, nullable=false) 
     */
    protected $password;
    
    /** 
     * @Column(type="string", length=255, nullable=false) 
     */
    protected $salt;
    
    /** 
     * @Column(type="string", length=255, nullable=true) 
     */
    protected $phone;
    
    /** 
     * @Column(type="string", length=255, nullable=true) 
     */
    protected $fax;
    
    /** 
     * @Column(type="string", length=255, nullable=true) 
     */
    protected $siret;
    
    /** 
     * @Column(type="string", length=255, nullable=true) 
     */
    protected $company_name;
    
    /**
     * @var \Default_Model_Base_Department department
     * 
     * @ManyToOne(targetEntity="\Default_Model_Base_Department")
     * @JoinColumn(name="department_id", referencedColumnName="id", nullable=true)
     */
    protected $department;
    
    /**
     * @OneToOne(targetEntity="\Default_Model_Base_Member")
     * @JoinColumn(name="sponsor_id", referencedColumnName="id")
     **/
    private $sponsor;
    
    /**
     * @OneToMany(targetEntity="\Default_Model_Base_MemberAvatar", mappedBy="member")
     */
    protected $avatars;
    
    /**
     * @OneToMany(targetEntity="\Default_Model_Base_Comment", mappedBy="member")
     */
    protected $comments;
    
    /**
     * @OneToOne(targetEntity="\Default_Model_Base_Service", mappedBy="member")
     **/
    private $service;
    
    /**
     * @OneToOne(targetEntity="\Default_Model_Base_Feature", mappedBy="member")
     **/
    private $feature;
    
    /** 
     * @Column(type="boolean") 
     */
    protected $is_active;
    
    /** 
     * @Column(type="string", length=255, nullable=true) 
     */
    protected $role;
    
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
        $this->announcements = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set facebook_id
     *
     * @param string $lfacebookId
     * @return Default_Model_Base_Member
     */
    public function setFacebookId($facebookId)
    {
        $this->facebook_id = $facebookId;
        return $this;
    }

    /**
     * Get facebook_id
     *
     * @return string 
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return Default_Model_Base_Member
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return Default_Model_Base_Member
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Default_Model_Base_Member
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Default_Model_Base_Member
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Default_Model_Base_Member
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Default_Model_Base_Member
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set fax
     *
     * @param string $fax
     * @return Default_Model_Base_Member
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
        return $this;
    }

    /**
     * Get fax
     *
     * @return string 
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set siret
     *
     * @param string $siret
     * @return Default_Model_Base_Member
     */
    public function setSiret($siret)
    {
        $this->siret = $siret;
        return $this;
    }

    /**
     * Get siret
     *
     * @return string 
     */
    public function getSiret()
    {
        return $this->siret;
    }

    /**
     * Set company_name
     *
     * @param string $companyName
     * @return Default_Model_Base_Member
     */
    public function setCompanyName($companyName)
    {
        $this->company_name = $companyName;
        return $this;
    }

    /**
     * Get company_name
     *
     * @return string 
     */
    public function getCompanyName()
    {
        return $this->company_name;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     * @return Default_Model_Base_Member
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * Get avatar
     *
     * @return string 
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set department
     *
     * @param Default_Model_Base_Department $department
     * @return Default_Model_Base_Member
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
     * Set avatar
     *
     * @param string $avatar
     * @return Default_Model_Base_Member
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * Get avatar
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }
    
    /**
     * give role for acl
     * @return string
     */
    public function getRoleId() {
        $role = $this->getRole();
        if(!in_array($role, array('user', 'pro', 'admin'))){
            return 'anonymous';
        }
        return $role;
    }

    /**
     * Set is_active
     *
     * @param boolean $isActive
     * @return Default_Model_Base_Service
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
     * Set sponsor
     *
     * @param Default_Model_Base_Member $member
     * @return Default_Model_Base_Member
     */
    public function setSponsor(\Default_Model_Base_Member $member = null)
    {
        $this->sponsor = $member;
        return $this;
    }

    /**
     * Get sponsor
     *
     * @return Default_Model_Base_Member 
     */
    public function getSponsor()
    {
        return $this->sponsor;
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
     * Add avatar
     *
     * @param \Default_Model_Base_MemberAvatar $avatar
     */
    public function addDefault_Model_Base_MemberAvatar(\Default_Model_Base_MemberAvatar $avatar)
    {
        $this->avatars[] = $avatar;
    }

    /**
     * Get avatars
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getAvatars()
    {
        return $this->avatars;
    }


    /**
     * Set service
     *
     * @param Default_Model_Base_Service $service
     * @return Default_Model_Base_Member
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
     * Set service
     *
     * @param Default_Model_Base_Feature $feature
     * @return Default_Model_Base_Member
     */
    public function setFeature(\Default_Model_Base_Feature $feature = null)
    {
        $this->feature = $feature;
        return $this;
    }

    /**
     * Get service
     *
     * @return Default_Model_Base_Feature 
     */
    public function getFeature()
    {
        return $this->feature;
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

    /**
     * Get name farmat like this : Maxime F.
     * @return string name
     */
    public function getSplitName()
    {
        return (ucfirst(strtolower($this->firstname)) . ' ' . strtoupper($this->lastname[0]) . '.');
    }
}