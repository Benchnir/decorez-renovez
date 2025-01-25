<?php

/**
 * @Entity(repositoryClass="Default_Model_JobCategory")
 * @Table(name="job_category")
 */
class Default_Model_Base_JobCategory 
{
    /**
     * @Id 
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** 
     * @Column(type="string", length=255, nullable=false) 
     */
    protected $name;
    
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
     * @return Default_Model_Base_JobCategory
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
}