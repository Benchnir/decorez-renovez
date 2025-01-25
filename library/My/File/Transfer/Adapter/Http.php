<?php

/**
 * File transfer adapter class for the HTTP protocol
 *
 * @package   Zend_File_Transfer
 *
 */
class My_File_Transfer_Adapter_Http extends Zend_File_Transfer_Adapter_Http
{

    private $_rename = null;

    /**
     * Set the new name of the file
     *
     * @param string name
     */
    public function setRename($name)
    {
        $this->_rename = $name;
    }

    /**
     * Receive the file from the client (Upload)
     *
     * @param  string|array $files (Optional) Files to receive
     * @return bool
     */
    public function receive($files = null)
    {
        if (!$this->isValid($files))
        {
            return false;
        }

        $check = $this->_getFiles($files);
        foreach ($check as $file => $content)
        {
            $directory = '';
            $destination = $this->getDestination($file);
            if ($destination !== null)
            {
                $directory = $destination . DIRECTORY_SEPARATOR;
            }

            if ($this->_rename === null)
                $this->_rename = $content['name'];

            // Should never return false when it's tested by the upload validator
            if (!move_uploaded_file($content['tmp_name'], ($directory . $this->_rename)))
            {
                if ($content['options']['ignoreNoFile'])
                {
                    $this->_files[$file]['received'] = true;
                    $this->_files[$file]['filtered'] = true;
                    continue;
                }

                $this->_files[$file]['received'] = false;
                return false;
            }

            $this->_files[$file]['received'] = true;
            if (!$this->_filter($file))
            {
                $this->_files[$file]['filtered'] = false;
                return false;
            }

            $this->_files[$file]['filtered'] = true;
        }

        return true;
    }

}
