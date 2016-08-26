<?php
require (preg_replace("/\/+/","/", dirname(__FILE__)."/./../../../system/library.payu/config.payu-easyplus.php"));

class ControllerPaymentPayueasyplus extends Controller {
    public function index() 
    {
        $this->load->language('payment/bluepay_redirect');

        $data['button_pay'] = $this->language->get('button_confirm');

        return $this->load->view('payment/payu_easyplus_confirm', $data);
    }
    
    public function send () 
    {
        $this->load->model('checkout/order');
        $this->language->load('payment/payu_easyplus');
        
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        
        $setTransactionData = array();
        $setTransactionData['TransactionType'] = $this->config->get('payu_easyplus_transaction_type');
        
        // Creating Basket Array
        $basket = array();
        $basket['amountInCents'] = (float) $order_info['total']*100;
        if (strpos($basket['amountInCents'],'.') !== false) {
            list($basket['amountInCents'],$tempVar) = explode(".",$basket['amountInCents'],2);          
            $basket['amountInCents'] = $basket['amountInCents']+1;
        }
        $basket['description'] = 'Order ID:' . $order_info['order_id'];
        $basket['currencyCode'] = $this->config->get('payu_easyplus_payment_currency');
        $setTransactionData = array_merge($setTransactionData, array('Basket' => $basket));
        $basket = null; 
        unset($basket);

        // Creating Customer Array
        $customer = array();
        $customer['firstName'] = $order_info['payment_firstname'];
        $customer['lastName'] = $order_info['payment_lastname'];
        $customer['mobile'] = $order_info['telephone'];
        $customer['email'] = $order_info['email'];        
        $setTransactionDataArray = array_merge($setTransactionData, array('Customer' => $customer));
        $customer = null; 
        unset($customer);
        
        //Creating Additional Information Array
        $additionalInformation = array();
        $additionalInformation['supportedPaymentMethods'] = $this->config->get('payu_easyplus_payment_methods');
        $additionalInformation['cancelUrl'] = $this->config->get('payu_easyplus_cancel_url' );
        $additionalInformation['notificationUrl'] = $this->config->get('payu_easyplus_ipn_url');
        $additionalInformation['returnUrl'] = $this->config->get('payu_easyplus_return_url');
        $additionalInformation['merchantReference'] = $order_info['order_id'];
        $setTransactionData = array_merge($setTransactionData, array('AdditionalInformation' => $additionalInformation));
        $additionalInformation = null; 
        unset($additionalInformation);
        
        //Creating a config array for RPP instantiation        
        $config = array();        
        $config['safe_key'] = $this->config->get('payu_easyplus_safe_key'); ;
        $config['api_username'] = $this->config->get('payu_easyplus_api_username'); ;
        $config['api_password'] = $this->config->get('payu_easyplus_api_password'); ;;
        
        $config['logEnable'] = true;
        $config['extended_debug'] = true;
        
        if(strtolower($this->config->get('payu_easyplus_transaction_mode')) == 'production') {
            $config['production'] = true;
            $config['logEnable'] = false;
            $config['extended_debug'] = false;
        }
        
        $json['error'] = 'Unable to contact PayU service. Please contact store administrator.';
        try {
            $payUEasyPlus = new PayUEasyPlus($config);
            $setTransactionResponse = $payUEasyPlus->doSetTransaction($setTransactionData);
            
            if(isset($setTransactionResponse['payu_easyplus_url'])) {
                $json['success'] = $setTransactionResponse['payu_easyplus_url'];
                $message = 'Redirected to PayU for payment' . "\r\n";            
                $message .= 'PayU Reference: ' . $setTransactionResponse['soap_response']['payUReference'] . "\r\n";
                $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('payu_easyplus_order_status_id'), $message, false);
            }
        } catch(Exception $e) {
            $json['error'] = $e->getMessage();            
        }
        
        if(isset($json['success'])) {
            unset($json['error']);
        }   
        
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
    public function response() 
    {
        $this->load->model('checkout/order');
        $get = $this->request->get;
        $payUReference = isset($get['PayUReference']) ? $get['PayUReference'] : $get['payUReference'];
        $transactionState = 'failure';    
        try {
            $message = '';
            
            if($payUReference) {
                //Creating get transaction soap data array
                $getTransactionData = array();
                $getTransactionData['AdditionalInformation']['payUReference'] = $payUReference;        
                $config = array();        
                $config['safe_key'] = $this->config->get('payu_easyplus_safe_key');
                $config['api_username'] = $this->config->get('payu_easyplus_api_username');
                $config['api_password'] = $this->config->get('payu_easyplus_api_password');
                $config['logEnable'] = $this->config->get('payu_easyplus_debug');
                $config['extended_debug'] = $this->config->get('payu_easyplus_extended_debug');
                if($this->config->get('payu_easylus_transaction_mode') == 'production') {
                    $config['production'] = true;
                }

                $payUEasyPlus = new PayUEasyPlus($config);
                $response = $payUEasyPlus->doGetTransaction($getTransactionData); 
                $message = $response['soap_response']['displayMessage'];
                
                if(isset($esponse['soap_response']['resultCode'])
                        && (in_array($response['soap_response']['resultCode'], array('POO5', 'EFTPRO_003', '999', '305')))
                ) {
                    $reason = $response['soap_response']['displayMessage'];
                    $message = 'Payment unsuccessful:' . "\r\n";
                    $message = "PayU Reference: " . $response['soap_response']['payUReference'] . "\r\n";
                    $message .= "Point Of Failure: " . $response['soap_response']['pointOfFailure'] . "\r\n";
                    $message .= "Result Code: " . $response['soap_esponse']['resultCode'];

                    $data['heading_title'] = "Payment Failed";
                    $data['notification_message'] = $message;
                    $data['continue'] = $this->url->link('checkout/checkout');

                    $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], 10, $message, true);
                    $this->response->setOutput($this->load->view('payment/payu_easyplus_response', $data));
                }

                //Checking the response from the SOAP call to see if successfull
                if(isset($response['soap_response']['successful']) 
                    && $response['soap_response']['successful']) 
                {                    
                    if(isset($response['soap_response']['transactionType']) 
                        && $response['soap_response']['transactionType'] == $this->config->get('payu_easyplus_transaction_type')) 
                    {                    
                        $MerchantReferenceCheck = $this->session->data['order_id'];
                        $MerchantReferenceCallBack = $response['soap_response']['merchantReference'];
                        $gatewayReference = $response['soap_response']['paymentMethodsUsed']['gatewayReference'];
                        $transactionState = 'paymentSuccessfull';
                    }                    
                }
            }            
        } catch(Exception $e) {
            $message = $e->getMessage();            
        }    
        
        //Now doing db updates for the orders 
        if($transactionState == 'paymentSuccessfull' && $MerchantReferenceCallBack == $MerchantReferenceCheck)
        {
            $message = 'Payment Successful:'."\r\n";
            $message .= 'PayU Reference: ' . $payUReference . "\r\n";
            $message .= 'Gateway Reference: ' . $gatewayReference . "\r\n";
            $message .= "Merchant Reference : " . $MerchantReferenceCallBack . "\r\n";
            $message .= "PayU Payment Status: ". $response['soap_response']['transactionState']."\r\n\r\n";
            $paymentMethod = $response['soap_response']['paymentMethodsUsed'];
            if(isset($paymentMethod)) {
                if(is_array($paymentMethod)) {
                    $message .= "Payment Method Details:";
                    foreach($paymentMethod as $key => $value) {
                        $message .= "\r\n" . "&nbsp;&nbsp;- ".$key.":".$value;
                    }
                }
            }

            $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], 2, $message, true);            
            $this->response->redirect($this->url->link('checkout/success', 'token=' . $this->session->data['token'], true));            
        } else if($transactionState == "failure") {
            $data['heading_title'] = "Payment Failed";
            $data['notification_message'] = $message;
            $data['continue'] = $this->url->link('checkout/checkout');
            
            $message = "Payment failed. Response: " . $message;
            
            $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], 10,$message, true);
            
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            $this->response->setOutput($this->load->view('payment/payu_easyplus_response', $data));
        }         
    }
    
    public function cancel() 
    {
        $orderid = $this->session->data['order_id']; 
        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($orderid);
        
        $data['heading_title'] = "Payment Cancelled";
        $data['notification_message'] = 'Payment cancelled on PayU payment page';
        $data['continue'] = $this->url->link('checkout/checkout');
        
        $message = $data['notification_message'];

        $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], 7, $message, true);

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        return $this->response->setOutput($this->load->view('payment/payu_easyplus_cancel', $data));        
    }
    
    public function ipn()
    {
        $this->load->model('checkout/order');

        $post = file_get_contents('php://input');

        $sxe = simplexml_load_string($post);
        if(empty($sxe))
            return;

        $returnData = $this->parseXMLToArray($sxe);
        if(empty($returnData))
            return;

        $order_id = (int) $returnData['MerchantReference'];
        $payUReference = $returnData['PayUReference'];
        if(isset($order_id) && !empty($order_id) && is_numeric($order_id)) {
            $order_info = $this->model_checkout_order->getOrder($orderid);
        }

        if(empty($order_info))
            return;

        //Setting a default failed trasaction state for this trasaction
        $transactionState = "failure";    
        try {
            $message = '';
            if(!empty($payUReference)) {
                //Creating get transaction soap data array
                $getTransactionData = array();
                $getTransactionData['AdditionalInformation']['payUReference'] = $payUReference;        
                $config = array();        
                $config['safe_key'] = $this->config->get('payu_easyplus_safe_key');
                $config['api_username'] = $this->config->get('payu_easyplus_api_username');
                $config['api_password'] = $this->config->get('payu_easyplus_api_password');
                $config['logEnable'] = $this->config->get('payu_easyplus_debug');
                $config['extended_debug'] = $this->config->get('payu_easyplus_extended_debug');
                if($this->config->get('payu_easylus_transaction_mode') == 'production') {
                    $config['production'] = true;
                }

                $payUEasyPlus = new PayUEasyPlus($config);
                $response = $payUEasyPlus->doGetTransaction($getTransactionData); 
                $message = $response['soap_response']['displayMessage'];
                
                if(isset($esponse['soap_response']['resultCode'])
                        && (in_array($response['soap_response']['resultCode'], array('POO5', 'EFTPRO_003', '999', '305')))
                ) {
                    $reason = $response['soap_response']['displayMessage'];
                    $message = 'Payment unsuccessful:' . "\r\n";
                    $message = "PayU Reference: " . $response['soap_response']['payUReference'] . "\r\n";
                    $message .= "Point Of Failure: " . $response['soap_response']['pointOfFailure'] . "\r\n";
                    $message .= "Result Code: " . $response['soap_esponse']['resultCode'];

                    $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], 10, $message, true);
                    return;
                }

                //Checking the response from the SOAP call to see if successfull
                if(isset($response['soap_response']['successful']) 
                    && $response['soap_response']['successful']) 
                {                    
                    if(isset($response['soap_response']['transactionType']) 
                        && $response['soap_response']['transactionType'] == $this->config->get('payu_easyplus_transaction_type')) 
                    {   
                        $transactionState = 'paymentSuccessfull';                 
                        $gatewayReference = $response['soap_response']['paymentMethodsUsed']['gatewayReference'];
                        $amountBasket = $returnData['Basket']['AmountInCents'] / 100;
                        $amountPaid = isset($returnData['PaymentMethodsUsed']['Creditcard']['AmountInCents']) ? $returnData['PaymentMethodsUsed']['Creditcard']['AmountInCents'] / 100 : $returnData['PaymentMethodsUsed']['Eft']['AmountInCents'] / 100;
                    }                    
                } else {
                    $message = $response['soap_response']['displayMessage'];
                }
            }            
        } catch(Exception $e) {
            $message = $e->getMessage();            
        }    
        
        //Now doing db updates for the orders 
        if($transactionState == 'paymentSuccessfull')
        {
            $message = "-----PAYU IPN RECIEVED---" . "\r\n";
            $message .= "Payment Successful:" . "\r\n";
            $message .= "PayU Reference: " . $payUReference . "\r\n";
            $message .= "Gateway Reference: " . $gatewayReference . "\r\n";
            $message .= "Amount Paid: " . $amountPaid . "\r\n";
            $message .= "Merchant Reference : " . $returnData['MerchantReference'] . "\r\n";
            $message .= "PayU Reference: " . $payUReference . "\r\n";
            $message .= "PayU Payment Status: ". $returnData["TransactionState"]."\r\n\r\n";

            $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], 2, $message, true);            
            return;            
        } else if($transactionState == "failure") {
            $reason = $response['soap_response']['displayMessage'];
            $message = "PayU Reference: " . $response['soap_response']['payUReference'] . "\r\n";
            $message .= "Point Of Failure: " . $response['soap_response']['pointOfFailure'] . "\r\n";
            $message .= "Result Code: " . $response['soap_response']['resultCode'];
   
            $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], 10, $message, true);
            return;
        }

    }

    public function callback() {
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($this->request->get));
    }

    protected function parseXMLToArray($xml) 
    {
        if(empty($xml))
            return false;

        $data = array();
        $data[$xml['Stage']->getName()] = $xml['Stage']->__toString();
        foreach ($xml as $element) {
            if($element->children()) {
                foreach ($element as $child) {
                    if($child->attributes()) {
                        foreach ($child->attributes() as $key => $value) {
                            $data[$element->getName()][$child->getName()][$key] = $value->__toString();
                        }
                    } else {
                        $data[$element->getName()][$child->getName()] = $child->__toString();
                    }
                }
            } else {
                $data[$element->getName()] = $element->__toString();
            }
        }

        return $data;
    }
}
?>