<?php

/**
 * Description of Adapter
 *
 * @author jon
 */
class My_Auth_AdminAdapter implements Zend_Auth_Adapter_Interface {

    const NOT_FOUND_MESSAGE = "Account not found";
    const BAD_PW_MESSAGE = "Password is invalid";

    /**
     *
     * @var Model_User
     */
    protected $admin;

    /**
     *
     * @var string
     */
    protected $email;

    /**
     *
     * @var string
     */
    protected $password;

    public function __construct($email, $password) {
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Performs an authentication attempt
     *
     * @throws Zend_Auth_Adapter_Exception If authentication cannot be performed
     * @return Zend_Auth_Result
     */
    public function authenticate() {
        try {
            $this->admin = \Default_Model_Ext_Member::authenticateAsAdmin($this->email, $this->password);
        } catch (Exception $e) {
            if ($e->getMessage() == \Default_Model_Ext_Member::WRONG_PASSWORD)
                return $this->result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, self::BAD_PW_MESSAGE);
            if ($e->getMessage() == \Default_Model_Ext_Member::NOT_FOUND)
                return $this->result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, self::NOT_FOUND_MESSAGE);
        }
        return $this->result(Zend_Auth_Result::SUCCESS);
    }

    /**
     * Factory for Zend_Auth_Result
     *
     * @param integer    The Result code, see Zend_Auth_Result
     * @param mixed      The Message, can be a string or array
     * @return Zend_Auth_Result
     */
    public function result($code, $messages = array()) {
        if (!is_array($messages)) {
            $messages = array($messages);
        }

        return new Zend_Auth_Result(
                        $code,
                        $this->admin,
                        $messages
        );
    }

}

