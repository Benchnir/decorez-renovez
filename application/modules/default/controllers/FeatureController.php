<?php

class FeatureController extends My_Controller_Action
{

    public function init()
    {
        parent::init();
        $this->_acl->allow('pro', $this->getResourceId(), 'suscribepro');
        $this->_acl->allow('pro', $this->getResourceId(), 'suscribeoption');
    }

    public function suscribeproAction()
    {
        $this->checkUser();
        
        /* @var $member \Default_Model_Base_Member */
        $member = $this->_em->find('\Default_Model_Base_Member', $this->_user->getId());
        $this->view->member = $member;
    }

    public function makeproAction()
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
                        if($receiver_email == 'paiement@decorezrenovez.fr'){
                            // check that payment_amount/payment_currency are correct
                            if($payment_currency == 'EUR'){
                                // check that payment_amount is correst
                                if(in_array($payment_amount, array("144.00", "89.94", "59.70"))){
                                    
                                    $db = new PDO("mysql:host=localhost;dbname=decorezr_mysql", "decorezr_php", ")O[.DhysS+%Z");
                                    $req = $db->query('SELECT * FROM feature WHERE member_id='.$custom['user_id'].' LIMIT 1');
                                    $d = $req->fetch(PDO::FETCH_ASSOC);
                                    if(!empty($d)){
                                        if($payment_amount == "144.00"){
                                            $intervalDate = '1 YEAR';
                                        } else if($payment_amount == "89.94"){
                                            $intervalDate = '6 MONTH';
                                        } else if($payment_amount == "59.70"){
                                            $intervalDate = '3 MONTH';
                                        }
                                        $proexpire = $d['pro_expire'];
                                        if($proexpire == null || strtotime($proexpire) < time()){
                                            $db->query('UPDATE feature SET pro_expire = DATE_ADD(NOW(), INTERVAL '.$intervalDate.') WHERE member_id='.$custom['user_id']);
                                        } else {
                                            $db->query('UPDATE feature SET pro_expire = DATE_ADD(pro_expire, INTERVAL '.$intervalDate.') WHERE member_id='.$custom['user_id']);
                                        }
                                    }
                                    
                                    // parrain
                                    $getMember = $db->query('SELECT * FROM member WHERE id='.$custom['user_id'].' LIMIT 1');
                                    $getMemberData = $getMember->fetch(PDO::FETCH_ASSOC);
                                    if(!empty($getMemberData) && isset($getMemberData['sponsor_id']) && $getMemberData['sponsor_id'] != null){
                                        $getSponsorFeature = $db->query('SELECT * FROM feature WHERE member_id='.$getMemberData['sponsor_id'].' LIMIT 1');
                                        $getSponsorFeatureData = $getSponsorFeature->fetch(PDO::FETCH_ASSOC);
                                        if(!empty($getSponsorFeatureData)){
                                            $proSponsorexpire = $getSponsorFeatureData['pro_expire'];
                                            if($proSponsorexpire == null || strtotime($proSponsorexpire) < time()){
                                                $db->query('UPDATE feature SET pro_expire = DATE_ADD(NOW(), INTERVAL 2 MONTH) WHERE member_id='.$getMemberData['sponsor_id']);
                                            } else {
                                                $db->query('UPDATE feature SET pro_expire = DATE_ADD(pro_expire, INTERVAL 2 MONTH) WHERE member_id='.$getMemberData['sponsor_id']);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            fclose ($fp);
        }
    }
    public function maketoplistAction()
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
                        if($receiver_email == 'paiement@decorezrenovez.fr'){
                            // check that payment_amount/payment_currency are correct
                            if($payment_currency == 'EUR'){
                                // check that payment_amount is correst
                                if(in_array($payment_amount, array("12.00", "24.00"))){
                                    
                                    $db = new PDO("mysql:host=localhost;dbname=decorezr_mysql", "decorezr_php", ")O[.DhysS+%Z");
                                    $req = $db->query('SELECT * FROM feature WHERE member_id='.$custom['user_id'].' LIMIT 1');
                                    $d = $req->fetch(PDO::FETCH_ASSOC);
                                    if(!empty($d)){
                                        if($payment_amount == "12.00"){
                                            $intervalDate = '7 DAY';
                                        } else if($payment_amount == "24.00"){
                                            $intervalDate = '3 MONTH';
                                        }
                                        $toplistexpire = $d['top_list_expire'];
                                        if($toplistexpire == null || strtotime($toplistexpire) < time()){
                                            $db->query('UPDATE feature SET top_list_expire = DATE_ADD(NOW(), INTERVAL '.$intervalDate.') WHERE member_id='.$custom['user_id']);
                                        } else {
                                            $db->query('UPDATE feature SET top_list_expire = DATE_ADD(top_list_expire, INTERVAL '.$intervalDate.') WHERE member_id='.$custom['user_id']);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            fclose ($fp);
        }
    }
    public function makepartnerAction()
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
                        if($receiver_email == 'paiement@decorezrenovez.fr'){
                            // check that payment_amount/payment_currency are correct
                            if($payment_currency == 'EUR'){
                                // check that payment_amount is correst
                                if(in_array($payment_amount, array("2.00", "10.00", "14.00"))){
                                    
                                    $db = new PDO("mysql:host=localhost;dbname=decorezr_mysql", "decorezr_php", ")O[.DhysS+%Z");
                                    $req = $db->query('SELECT * FROM feature WHERE member_id='.$custom['user_id'].' LIMIT 1');
                                    $d = $req->fetch(PDO::FETCH_ASSOC);
                                    if(!empty($d)){
                                        if($payment_amount == "2.00"){
                                            $intervalDate = '1 MONTH';
                                        } else if($payment_amount == "10.00"){
                                            $intervalDate = '6 MONTH';
                                        } else if($payment_amount == "14.00"){
                                            $intervalDate = '1 YEAR';
                                        }
                                        $partnerexpire = $d['partner_expire'];
                                        if($partnerexpire == null || strtotime($partnerexpire) < time()){
                                            $db->query('UPDATE feature SET partner_expire = DATE_ADD(NOW(), INTERVAL '.$intervalDate.') WHERE member_id='.$custom['user_id']);
                                        } else {
                                            $db->query('UPDATE feature SET partner_expire = DATE_ADD(partner_expire, INTERVAL '.$intervalDate.') WHERE member_id='.$custom['user_id']);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            fclose ($fp);
        }
    }
    
    public function suscribeoptionAction()
    {
        $this->checkUser();
        
        /* @var $member \Default_Model_Base_Member */
        $member = $this->_em->find('\Default_Model_Base_Member', $this->_user->getId());
        $this->view->member = $member;
    }
    
    public function getResourceId() {
        return 'featureController';
    }
}