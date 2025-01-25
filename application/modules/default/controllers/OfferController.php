<?php

class OfferController extends My_Controller_Action
{

    public function init()
    {
        parent::init();
        $this->_acl->allow('pro', $this->getResourceId(), 'create');
    }

    public function createAction()
    {
        $this->checkUser();
        
        /* @var $member \Default_Model_Base_Member */
        $member = $this->_em->find('\Default_Model_Base_Member', $this->_user->getId());
        $this->view->member = $member;
        
        /* @var $annonce \Default_Model_Base_Announcement */
        $annonce = $this->_em->find('\Default_Model_Base_Announcement', $this->_request->getParam('id'));
        $this->view->annonce = $annonce;
        if($annonce == null){
            $this->_redirect('annonce/index');
        }
        if(!$member->getFeature()->isPro()){
            $this->_redirect('feature/suscribepro');
        }
        $offers = $this->_em->createQueryBuilder()
            ->select('o')
            ->from('\Default_Model_Base_Offer', 'o')
            ->andWhere('o.annonce = :annonce_id')
            ->setParameter('annonce_id', $annonce->getId())
            ->andWhere('o.is_active = true')
            ->getQuery()
            ->getResult();
        if(count($offers) >= 5){
            $this->_messenger->addMessage('L\'annonce a déjà 5 offres.', My_Messenger::TYPE_ERROR);
            $this->_redirect('annonce/show/id/'.$annonce->getId());
        }
        
        $addOfferForm = new \My_Forms_AddOffer($this->_em);
        $this->view->addOfferForm = $addOfferForm;
        
        if ($this->_request->isPost())
        {
            $formData = $this->_request->getPost();
            if ($addOfferForm->isValid($formData))
            {
                $offer = new \Default_Model_Base_Offer();
                $offer->setMember($member);
                $offer->setAnnonce($annonce);
                $offer->setDescription($formData['description']);
                $offer->setPrice($formData['price']);
                $offer->setIsActive(false);
                
                $this->_em->persist($offer);
                $this->_em->flush();
                
                // TODO: Mail owner of the announcement
                
                $this->_redirect('offer/validate/id/'.$offer->getId());
            }
        }
    }
    
    public function validateAction()
    {
        /* @var $member \Default_Model_Base_Member */
        $member = $this->_em->find('\Default_Model_Base_Member', $this->_user->getId());
        $this->view->member = $member;
        
        /* @var $offer \Default_Model_Base_Offer */
        $offer = $this->_em->find('\Default_Model_Base_Offer', $this->_request->getParam('id'));
        $this->view->offer = $offer;
        
        if($offer == null){
            $this->_redirect('annonce/index');
        }
        if($offer->getMember()->getId() != $member->getId()){
            $this->_redirect('index/denied');
        }
        if(!$member->getFeature()->isPro()){
            $this->_redirect('feature/suscribepro');
        }
    }
    
    public function makevalidateAction()
    {
        // read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-validate';

        foreach ($_POST as $key => $value) {
            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";
        }

        // post back to PayPal system to validate
        $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
        $fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);

        // assign posted variables to local variables
        $item_name = $_POST['item_name'];
        $item_number = $_POST['item_number'];
        $payment_status = $_POST['payment_status'];
        $payment_amount = $_POST['mc_gross'];
        $payment_currency = $_POST['mc_currency'];
        $txn_id = $_POST['txn_id'];
        $receiver_email = $_POST['receiver_email'];
        $payer_email = $_POST['payer_email'];
        parse_str($_POST['custom'], $custom);
        
        
        if (!$fp) {
        // HTTP ERROR
        } else {
            fputs ($fp, $header . $req);
            while (!feof($fp)) {
                $res = fgets ($fp, 1024);
                // bug chez paypal, l'api renvoie toujour INVALID, mais le payment_status certifie la fin du payment
                // TODO: trouver une solution plus propre
                if (strcmp ($res, "VERIFIED") == 0 || strcmp ($res, "INVALID") == 0) {
                    // check the payment_status is Completed
                    if($payment_status == "Completed"){
                        // check that receiver_email is your Primary PayPal email
                        if($receiver_email == 'vincentb45@hotmail.fr'){
                            // check that payment_amount/payment_currency are correct
                            if($payment_currency == 'EUR'){
                                // check that payment_amount is correst
                                $db = new PDO("mysql:host=localhost;dbname=vincent", "vincent", "SourceForge");
                                $getOffer = $db->query('SELECT * FROM offer WHERE id='.$custom['offer_id'].' LIMIT 1');
                                $getOfferData = $getOffer->fetch(PDO::FETCH_ASSOC);
                                if(!empty($getOfferData['price']) && $payment_amount == round(($getOfferData['price']/1000*6), 2)){
                                    $db->query('UPDATE offer SET is_active = true WHERE id='.$custom['offer_id']);
                                }
                            }
                        }
                    }
                }
            }
            fclose ($fp);
        }
    }
    
    public function deleteAction(){
        
        /* @var $member \Default_Model_Base_Member */
        $member = $this->_em->find('\Default_Model_Base_Member', $this->_user->getId());
        $this->view->member = $member;
        
        /* @var $offer \Default_Model_Base_Offer */
        $offer = $this->_em->find('\Default_Model_Base_Offer', $this->_request->getParam('id'));
        $this->view->offer = $offer;
        
        if($offer == null){
            $this->_redirect('annonce/index');
        }
        if($offer->getMember()->getId() != $member->getId()){
            $this->_redirect('index/denied');
        }
        if(!$member->getFeature()->isPro()){
            $this->_redirect('feature/suscribepro');
        }
        $annonce_id = $offer->getAnnonce()->getId();
        $this->_em->remove($offer);
        $this->_em->flush();
        $this->_redirect('annonce/show/id/'.$annonce_id);
    }

    
    public function getResourceId() {
        return 'offerController';
    }
}