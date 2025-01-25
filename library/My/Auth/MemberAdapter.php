<?php

/**
 * Description of Adapter
 *
 * @author jon
 */
class My_Auth_MemberAdapter implements Zend_Auth_Adapter_Interface {

    const NOT_FOUND_MESSAGE = "Account not found";
    const UNACTIVE_MESSAGE = "this account is not active for the moment";
    const BAD_PW_MESSAGE = "Password is invalid";

    /**
     *
     * @var Model_User
     */
    protected $user;

    /**
     *
     * @var string
     */
    protected $email;

    /**
     *
     * @var string
     */
    protected $facebook;

    /**
     *
     * @var string
     */
    protected $password;

    public function __construct($email, $password, $facebook = false) {
        $this->email = $email;
        $this->password = $password;
        $this->facebook = $facebook;
    }

    /**
     * Performs an authentication attempt
     *
     * @throws Zend_Auth_Adapter_Exception If authentication cannot be performed
     * @return Zend_Auth_Result
     */
    public function authenticate() {
        try {
            $this->user = \Default_Model_Ext_Member::authenticate($this->email, $this->password, $this->facebook);
        } catch (Exception $e) {
            $messenger = new My_Messenger();
            if ($e->getMessage() == \Default_Model_Ext_Member::WRONG_PASSWORD){
                $messenger->addMessage('L\'adresse email ou le mot de passe saisi est incorrect.', My_Messenger::TYPE_ERROR);
                return $this->result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, self::BAD_PW_MESSAGE);
            }
            if ($e->getMessage() == \Default_Model_Ext_Member::UNACTIVE_ACCOUNT){
                $messenger->addMessage('Ce compte n\'est pas encore actif', My_Messenger::TYPE_ERROR);
                return $this->result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, self::UNACTIVE_MESSAGE);
            }
            if ($e->getMessage() == \Default_Model_Ext_Member::NOT_FOUND){
                $messenger->addMessage('L\'adresse email ou le mot de passe saisi est incorrect.', My_Messenger::TYPE_ERROR);
                return $this->result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, self::NOT_FOUND_MESSAGE);
            }
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
                        $this->user,
                        $messages
        );
    }

}

