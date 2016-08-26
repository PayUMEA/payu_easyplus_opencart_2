<?php 

//bootstrap section
require (preg_replace("/\/+/","/", dirname(__FILE__)."/../../../system/library.payu/config.payu-easyplus.php"));

class ControllerPaymentPayueasyplus extends Controller {
	private $error = array(); 

	public function index() {
        
        $this->load->language('payment/payu_easyplus');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payu_easyplus', $this->request->post);				
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], true));
		}
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');        
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_staging'] = $this->language->get('text_staging');
        $data['text_production'] = $this->language->get('text_production');
        $data['text_payment'] = $this->language->get('text_payment');
        $data['text_reserve'] = $this->language->get('text_reserve');
        $data['text_nigerian_naira'] = $this->language->get('text_nigerian_naira');
        $data['text_south_african_rand'] = $this->language->get('text_south_african_rand');

        $data['entry_payment_title'] = $this->language->get('entry_payment_title');
        $data['entry_safe_key'] = $this->language->get('entry_safe_key');
        $data['entry_api_username'] = $this->language->get('entry_api_username');
        $data['entry_api_password'] = $this->language->get('entry_api_password');
        $data['entry_transaction_mode'] = $this->language->get('entry_transaction_mode');
        $data['entry_transaction_type'] = $this->language->get('entry_transaction_type');
        $data['entry_total'] = $this->language->get('entry_total');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_payment_currency'] = $this->language->get('entry_payment_currency');
        $data['entry_payment_methods'] = $this->language->get('entry_payment_methods');
        $data['entry_debug'] = $this->language->get('entry_debug');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_extended_debug'] = $this->language->get('entry_extended_debug');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $data['help_total'] = $this->language->get('help_total');
        $data['help_debug'] = $this->language->get('help_debug');
        $data['help_transaction'] = $this->language->get('help_transaction');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        
        if (isset($this->error['payment_title'])) {
            $data['error_payment_title'] = $this->error['payment_title'];
        } else {
            $data['error_payment_title'] = '';
        }

        if (isset($this->error['safe_key'])) {
            $data['error_safe_key'] = $this->error['safe_key'];
        } else {
            $data['error_safe_key'] = '';
        }

        if (isset($this->error['api_username'])) {
            $data['error_api_username'] = $this->error['api_username'];
        } else {
            $data['error_api_username'] = '';
        }

        if (isset($this->error['api_password'])) {
            $data['error_api_password'] = $this->error['api_password'];
        } else {
            $data['error_api_password'] = '';
        }

        if (isset($this->error['payment_methods'])) {
            $data['error_payment_methods'] = $this->error['payment_methods'];
        } else {
            $data['error_payment_methods'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_payment'),
            'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('payment/payu_easyplus', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['action'] = $this->url->link('payment/payu_easyplus', 'token=' . $this->session->data['token'], 'SSL');       
        $data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['payu_easyplus_payment_title'])) {
            $data['payu_easyplus_payment_title'] = $this->request->post['payu_easyplus_payment_title'];
        } else {
            $data['payu_easyplus_payment_title'] = $this->config->get('payu_easyplus_payment_title');
        }

        if (isset($this->request->post['payu_easyplus_safe_key'])) {
            $data['payu_easyplus_safe_key'] = $this->request->post['payu_easyplus_safe_key'];
        } else {
            $data['payu_easyplus_safe_key'] = $this->config->get('payu_easyplus_safe_key');
        }

        if (isset($this->request->post['payu_easyplus_api_username'])) {
            $data['payu_easyplus_api_username'] = $this->request->post['payu_easyplus_api_username'];
        } else {
            $data['payu_easyplus_api_username'] = $this->config->get('payu_easyplus_api_username');
        }

        if (isset($this->request->post['payu_easyplus_api_password'])) {
            $data['payu_easyplus_api_password'] = $this->request->post['payu_easyplus_api_password'];
        } else {
            $data['payu_easyplus_api_password'] = $this->config->get('payu_easyplus_api_password');
        }

        if (isset($this->request->post['payu_easyplus_transaction_mode'])) {
            $data['payu_easyplus_transaction_mode'] = $this->request->post['payu_easyplus_transaction_mode'];
        } elseif($this->config->get('payu_easyplus_transaction_mode')) {
            $data['payu_easyplus_transaction_mode'] = $this->config->get('payu_easyplus_transaction_mode');
        } else {
            $data['payu_easyplus_transaction_mode'] = 'staging';
        }

        if (isset($this->request->post['payu_easyplus_transaction_type'])) {
            $data['payu_easyplus_transaction_type'] = $this->request->post['payu_easyplus_transaction_type'];
        } else {
            $data['payu_easyplus_transaction_type'] = $this->config->get('payu_easyplus_transaction_type');
        }

        if (isset($this->request->post['payu_easyplus_total'])) {
            $data['payu_easyplus_total'] = $this->request->post['payu_easyplus_total'];
        } else {
            $data['payu_easyplus_total'] = $this->config->get('payu_easyplus_total');
        }

        if (isset($this->request->post['payu_easyplus_order_status_id'])) {
            $data['payu_easyplus_order_status_id'] = $this->request->post['payu_easyplus_order_status_id'];
        } elseif ($this->config->get('payu_easyplus_order_status_id')) {
            $data['payu_easyplus_order_status_id'] = $this->config->get('payu_easyplus_order_status_id');
        } else {
            $data['payu_easyplus_order_status_id'] = 1;
        }

        if (isset($this->request->post['payu_easyplus_payment_currency'])) {
            $data['payu_easyplus_payment_currency'] = $this->request->post['payu_easyplus_payment_currency'];
        } else {
            $data['payu_easyplus_payment_currency'] = $this->config->get('payu_easyplus_payment_currency');
        }

        if (isset($this->request->post['payu_easyplus_debug'])) {
            $data['payu_easyplus_debug'] = $this->request->post['payu_easyplus_debug'];
        } else {
            $data['payu_easyplus_debug'] = $this->config->get('payu_easyplus_debug');
        }

        if (isset($this->request->post['payu_easyplus_extended_debug'])) {
            $data['payu_easyplus_extended_debug'] = $this->request->post['payu_easyplus_extended_debug'];
        } else {
            $data['payu_easyplus_extended_debug'] = $this->config->get('payu_easyplus_extended_debug');
        }

        if (isset($this->request->post['payu_easyplus_payment_currency'])) {
            $data['payu_easyplus_payment_currency'] = $this->request->post['payu_easyplus_payment_currency'];
        } else {
            $data['payu_easyplus_payment_currency'] = $this->config->get('payu_easyplus_payment_currency');
        }

        if (isset($this->request->post['payu_easyplus_sort_order'])) {
            $data['payu_easyplus_sort_order'] = $this->request->post['payu_easyplus_sort_order'];
        } elseif($this->config->get('payu_easyplus_sort_order')) {
            $data['payu_easyplus_sort_order'] = $this->config->get('payu_easyplus_sort_order');
        } else {
            $data['payu_easyplus_sort_order'] = 1;
        }     

        $payuConfig = PayUConfig::getConfig();
        if(strtolower($data['payu_easyplus_transaction_mode']) == "staging") {        
            $data['payu_easyplus_safe_key'] = $payuConfig['payu_easyplus']['safe_key']; 
            $data['payu_easyplus_api_username'] = $payuConfig['payu_easyplus']['api_username']; 
            $data['payu_easyplus_api_password'] = $payuConfig['payu_easyplus']['api_password'];
            $data['payu_easyplus_payment_title'] = $payuConfig['payment_title'];              
        }
        
        $urlDirBase = defined('HTTPS_CATALOG') ? HTTPS_CATALOG : HTTP_CATALOG;
        $urlDirBase .= 'index.php';
        
        if(!isset($this->request->post['payu_easyplus_return_url'])) {            
            $data['payu_easyplus_return_url'] = $urlDirBase."?route=payment/payu_easyplus/response";;
        }
        if(!isset($this->request->post['payu_easyplus_cancel_url'])) {            
            $data['payu_easyplus_cancel_url'] = $urlDirBase."?route=payment/payu_easyplus/cancel";;
        }
        if(!isset($this->request->post['payu_easyplus_ipn_url'])) {            
            $data['payu_easyplus_ipn_url'] = $urlDirBase."?route=payment/payu_easyplus/ipn";;
        }
        if (isset($this->request->post['payu_easyplus_status'])) {
            $data['payu_easyplus_status'] = $this->request->post['payu_easyplus_status'];
        } else {
            $data['payu_easyplus_status'] = $this->config->get('payu_easyplus_status');
        }

        if (isset($this->request->post['payu_easyplus_payment_methods'])) {
            $data['payu_easyplus_payment_methods'] = $this->request->post['payu_easyplus_payment_methods'];
        } else {
            $data['payu_easyplus_payment_methods'] = explode(',', $this->config->get('payu_easyplus_payment_methods'));
        }  
		
		$this->load->model('localisation/order_status');		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
        $data['supported_currencies'] = $payuConfig['supported_currencies'];
        $data['payment_methods'] = $payuConfig['supported_payment_methods'];

		$data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('payment/payu_easyplus', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/payu_easyplus')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

        $payuConfig = PayUConfig::getConfig();
        
        $this->request->post['payu_easyplus_debug'] = 0;
        $this->request->post['payu_easyplus_extended_debug'] = 0;
        
        if(strtolower($this->request->post['payu_easyplus_transaction_mode']) == "staging") {        
            $this->request->post['payu_easyplus_safe_key'] = $payuConfig['payu_easyplus']['safe_key']; 
            $this->request->post['payu_easyplus_api_username'] = $payuConfig['payu_easyplus']['api_username']; 
            $this->request->post['payu_easyplus_api_password'] = $payuConfig['payu_easyplus']['api_password'];
            $this->request->post['payu_easyplus_payment_title'] = $payuConfig['payment_title'];         
            $this->request->post['payu_easyplus_debug'] = 1;
            $this->request->post['payu_easyplus_extended_debug'] = 1;            
        }

        if (!$this->request->post['payu_easyplus_payment_title']) {
            $this->error['payment_title'] = $this->language->get('error_payment_title');
        }

        if (!$this->request->post['payu_easyplus_safe_key']) {
            $this->error['safe_key'] = $this->language->get('error_safe_key');
        }

        if (!$this->request->post['payu_easyplus_api_username']) {
            $this->error['api_username'] = $this->language->get('error_api_username');
        } 

        if (!$this->request->post['payu_easyplus_api_password']) {
            $this->error['api_password'] = $this->language->get('error_api_password');
        }     

        if (empty($this->request->post['payu_easyplus_payment_methods'])) {
            $this->error['payment_methods'] = $this->language->get('error_payment_methods');
        } else {
            $this->request->post['payu_easyplus_payment_methods'] = implode(',', $this->request->post['payu_easyplus_payment_methods']);
        }  

        return !$this->error; 	
	}

    public function callback() 
    {
        $this->response->addHeader('Content-Type: application/json');

        $this->response->setOutput(json_encode($this->request->get));
    }
}
?>
