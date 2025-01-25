<?php

/**
 * @Entity(repositoryClass="Default_Model_Job")
 * @Table(name="job")
 */
class Default_Model_Base_Job 
{
    /**
     * @Id 
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /** 
     * @Column(type="boolean") 
     */
    protected $is_validate;
    
    /** 
     * @Column(type="string", length=255, nullable=false) 
     */
    protected $name;
    
    /**
     * @var \Default_Model_Base_JobCategory category
     * 
     * @ManyToOne(targetEntity="\Default_Model_Base_JobCategory")
     * @JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $category;
    
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
     * Set name
     *
     * @param string $name
     * @return Default_Model_Base_Job
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Set is_validate
     *
     * @param boolean $is_validate
     * @return Default_Model_Base_Job
     */
    public function setIsValidate($isValidate)
    {
        $this->is_validate = $isValidate;
        return $this;
    }

    /**
     * Get is_validate
     *
     * @return boolean 
     */
    public function getIsValidate()
    {
        return $this->is_validate;
    }


    /**
     * Set category
     *
     * @param Default_Model_Base_JobCategory $category
     * @return Default_Model_Base_Job
     */
    public function setCategory(\Default_Model_Base_JobCategory $category = null)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * Get category
     *
     * @return Default_Model_Base_JobCategory 
     */
    public function getCategory()
    {
        return $this->category;
    }
}