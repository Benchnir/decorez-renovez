<?php

/**
 * Messages management class
 *
 * @author Maxime FRAPPAT
 */
class My_Messenger {

    const TYPE_OK = 'message_ok';
    const TYPE_ERROR = 'message_error';
    const TYPE_INFO = 'message_info';
    const NAMESPACE_DEFAULT = 'default';

    protected $_sessionNamespace = 'My_Messenger';

    /**
     * Adds a message to user's message stack
     *
     * @param string $message
     * @param string|null $type optional
     * @param string $namespace optional
     * @return void
     */
    public function addMessage($message, $type = My_Messenger::TYPE_INFO, $namespace = My_Messenger::NAMESPACE_DEFAULT) {
        if (is_null($type))
            $type = My_Messenger::TYPE_INFO;
        if (is_null($namespace))
            $namespace = My_Messenger::NAMESPACE_DEFAULT;
        $storage = new Zend_Session_Namespace($this->_sessionNamespace);
        if (is_null($storage->$namespace))
            $storage->$namespace = array();
        $array = $storage->$namespace;
        $array[] = array('type' => $type, 'message' => $message);
        $storage->$namespace = $array;
    }

    /**
     * Gets an array of user messages
     *
     * @param string|null $namespace optional
     * @param string|null $type optional
     * @param bool $delete	optional
     * @return array
     */
    public function getMessages($namespace = null, $type = null, $delete = true) {
        $messages = array();
        $storage = new Zend_Session_Namespace($this->_sessionNamespace);

        if (!empty($storage)) {
            if (is_null($namespace)) {
                foreach ($storage as $key => $msgs) {
                    if ($type !== null) {
                        foreach ($msgs as $msgKey => $msg) {
                            if ($msg['type'] == $type) {
                                $messages[] = $msg;
                                if ($delete)
                                    unset($storage[$key][$msgKey]);
                            }
                        }
                    }else {
                        $messages = array_merge($messages, $msgs);
                        if ($delete)
                            unset($storage->$key);
                    }
                }
            }else {
                if (isset($storage->$namespace) && is_array($storage->$namespace) && !empty($storage->$namespace)) {
                    if ($type !== null) {
                        foreach ($storage->$namespace as $msgKey => $msg) {
                            if ($msg['type'] == $type) {
                                $messages[] = $msg;
                                if ($delete)
                                    unset($storage->$namespace[$msgKey]);
                            }else {
                                $messages = array_merge($messages, $storage->$namespace);
                                if ($delete)
                                    unset($storage->$namespace);
                            }
                        }
                    }
                }
            }
        }
        return $messages;
    }

}