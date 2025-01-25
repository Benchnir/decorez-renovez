<?php
 
class My_Mail_HtmlMail extends Zend_Mail
{   
    public static function sendMail($subject=null,$body=null, array $membres){
        if(empty($membres)){
            throw new Exception('There is no email members defined');
        }
        $mail = new self();
        $mail->setBodyHtml($body,'UTF-8',Zend_Mime::MULTIPART_RELATED);
        $mail->setFrom('no-reply@decorez-renovez.fr', 'Decorez-Renovez.fr');
        foreach($membres as $membre){
            $mail->addTo($membre->getEmail(), $membre->getFirstName() . ' ' . strtoupper($membre->getLastName()));
        }
        $mail->buildHtml();
        $mail->setSubject($subject);
        $mail->send();
    }
    public function buildHtml()
    {
        // Important, without this line the example don't work!
        // The images will be attached to the email but these will be not
        // showed inline
        $this->setType(Zend_Mime::MULTIPART_RELATED);
 
        $matches = array();
        preg_match_all("#<img.*?src=['\"]file://([^'\"]+)#i",
        $this->getBodyHtml(true),
        $matches);
        $matches = array_unique($matches[1]);
 
        if (count($matches ) > 0) {
            foreach ($matches as $key => $filename) {
                if (is_readable($filename)) {
                    $at = $this->createAttachment(file_get_contents($filename));
                    $at->type = $this->mimeByExtension($filename);
                    $at->disposition = Zend_Mime::DISPOSITION_INLINE;
                    $at->encoding = Zend_Mime::ENCODING_BASE64;
                    $at->id = 'cid_' . md5_file($filename);
                    $this->setBodyHtml(str_replace('file://' . $filename,
                                       'cid:' . $at->id,
                    $this->getBodyHtml(true)),
                                       'UTF-8',
                    Zend_Mime::ENCODING_8BIT);
                }
            }
        }
    }
 
    public function mimeByExtension($filename)
    {
        if (is_readable($filename) ) {
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            switch ($extension) {
                case 'gif':
                    $type = 'image/gif';
                    break;
                case 'jpg':
                case 'jpeg':
                    $type = 'image/jpg';
                    break;
                case 'png':
                    $type = 'image/png';
                    break;
                default:
                    $type = 'application/octet-stream';
            }
        }
 
        return $type;
    }
}
