<?php

namespace App\Proxies\__CG__;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class Default_Model_Base_Comment extends \Default_Model_Base_Comment implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    /** @private */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int) $this->_identifier["id"];
        }
        $this->__load();
        return parent::getId();
    }

    public function setMember(\Default_Model_Base_Member $member)
    {
        $this->__load();
        return parent::setMember($member);
    }

    public function getMember()
    {
        $this->__load();
        return parent::getMember();
    }

    public function setService(\Default_Model_Base_Service $service)
    {
        $this->__load();
        return parent::setService($service);
    }

    public function getService()
    {
        $this->__load();
        return parent::getService();
    }

    public function setType($int)
    {
        $this->__load();
        return parent::setType($int);
    }

    public function getType()
    {
        $this->__load();
        return parent::getType();
    }

    public function setTitle($string)
    {
        $this->__load();
        return parent::setTitle($string);
    }

    public function getTitle()
    {
        $this->__load();
        return parent::getTitle();
    }

    public function setMessage($string)
    {
        $this->__load();
        return parent::setMessage($string);
    }

    public function getMessage()
    {
        $this->__load();
        return parent::getMessage();
    }

    public function setStatus($int)
    {
        $this->__load();
        return parent::setStatus($int);
    }

    public function getStatus()
    {
        $this->__load();
        return parent::getStatus();
    }

    public function setReason($string)
    {
        $this->__load();
        return parent::setReason($string);
    }

    public function getReason()
    {
        $this->__load();
        return parent::getReason();
    }

    public function setIp($string)
    {
        $this->__load();
        return parent::setIp($string);
    }

    public function getIp()
    {
        $this->__load();
        return parent::getIp();
    }

    public function setValidator(\Default_Model_Base_Member $member)
    {
        $this->__load();
        return parent::setValidator($member);
    }

    public function getValidator()
    {
        $this->__load();
        return parent::getValidator();
    }

    public function setValidationDate()
    {
        $this->__load();
        return parent::setValidationDate();
    }

    public function getValidationDate()
    {
        $this->__load();
        return parent::getValidationDate();
    }

    public function setCreationDate()
    {
        $this->__load();
        return parent::setCreationDate();
    }

    public function getCreationDate()
    {
        $this->__load();
        return parent::getCreationDate();
    }

    public function setUpdateDate()
    {
        $this->__load();
        return parent::setUpdateDate();
    }

    public function getUpdateDate()
    {
        $this->__load();
        return parent::getUpdateDate();
    }

    public function _prePersist()
    {
        $this->__load();
        return parent::_prePersist();
    }

    public function _preUpdate()
    {
        $this->__load();
        return parent::_preUpdate();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'type', 'title', 'message', 'status', 'reason', 'ip', 'validationDate', 'creationDate', 'updateDate', 'member', 'service', 'validator');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields AS $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}