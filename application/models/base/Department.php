<?php

/**
 * @Entity(repositoryClass="Default_Model_Department")
 * @Table(name="department")
 */
class Default_Model_Base_Department {

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @Column(type="string", length=100) 
     */
    protected $name;
    
    /**
     * @var \Default_Model_Base_Region region
     * 
     * @ManyToOne(targetEntity="\Default_Model_Base_Region")
     * @JoinColumn(name="region_id", referencedColumnName="id")
     */
    protected $region;
    
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
     * @return Default_Model_Base_Department
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
     * Set region
     *
     * @param Default_Model_Base_Region $region
     * @return Default_Model_Base_Department
     */
    public function setRegion(\Default_Model_Base_Region $region = null)
    {
        $this->region = $region;
        return $this;
    }

    /**
     * Get region
     *
     * @return Default_Model_Base_Region 
     */
    public function getRegion()
    {
        return $this->region;
    }
}
