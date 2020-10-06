<?php

use SebastianBergmann\RecursionContext\Exception;

App::uses('Component', 'Controller');

	class ApiComponent extends Component {
		
		/**
		 * Components
		 *
		 * @var array
		 */
		public $components = array(
			'Session', 'Flash', 'RequestHandler'
		);

		/**
		 * Instances
		 */
		protected $controller;
		protected $model;

		private $params = array();
		private $status = false;

		/**
		 * Public variables
		 */
		// final result set
		private $result;

		// available languages of the App, defined in Env.php
		private $available_languages;

		// lagnauge of current request
		private $language;

		// Member token and its expiry
		private $token;
        private $token_expiry;

        private $is_save_log = false;
        // Device token
        private $error_data;
        private $log_data;
        private $new_data;
		private $old_data;

		/**
		 * Is called before the controller’s beforeFilter method.
		 * 
		 */
		public function initialize(Controller $controller) {
			parent::initialize($controller);

			$this->controller = $controller;
			$this->model = $controller->modelClass;

			$this->available_languages = Environment::read('api.available_languages');
		}

		/**
		 * Is called after the controller’s beforeFilter method 
		 * but before the controller executes the current action handler.
		 * 
		 */
		public function startup(Controller $controller) {
			parent::startup($controller);
		}

		/**
		 * Is called after the controller executes the requested action’s logic,
		 * but before the controller’s renders views and layout.
		 * 
		 */
		public function beforeRender(Controller $controller) {
			parent::beforeRender($controller);

		}

		/**
		 * Is called before output is sent to the browser.
		 * 
		 */
		public function shutdown(Controller $controller) {
			parent::shutdown($controller);

		}

		/**
		 * Is called before output is sent to the browser.
		 * 
		 */
		public function beforeRedirect(Controller $controller, $url = "", $status = null, $exit = true) {
			parent::beforeRedirect($controller, $url, $status, $exit);

		}

		/**
		 * set the token and its expiry privately
		 */
		private function __set_token( $token = "" ){
			if( isset($token) && !empty($token) ){
				$this->token = $token;
				$this->token_expiry = date('Y-m-d H:i:s', strtotime(Environment::read('api.token_expiry')));
			}
		}

		/**
		 * Public function to initialize the final result of API
		 */
		public function init_result() {
			$this->result = array(
				'status' => false,
				'message' => __("please_provide_information"),
				'params' => array(),
			);
		}
        
        /**
		 * Public function to set the output in the final result of API
		 */
		public function get_result(){
			return array(
                'status' => $this->status,
                'params' => $this->params
            );
		}

		public function set_result($status = false, $message, $params = array() ){
			// set the final result
			$this->result = array(
				'status' => $status,
			    'message' => $message,
				'params' => $params,
			);
		}

		public function set_post_params( $url_params, $params ){
            $this->log_data = array(
                'plugin' => $url_params["plugin"],
                'controller' => $url_params["controller"],
                'action' => $url_params["action"],
                'received_params' => json_encode($params)
            );
		}

		public function set_old_data( $data ){
			$this->old_data = $data;
		}

		public function set_new_data( $data ){
			$this->new_data = $data;
		}

		public function set_save_log( $data ){
			$this->is_save_log = $data;
		}

		public function set_error_log( $data ){
			$this->error_data = $data;
		}

		/**
		 * Public function to output the result with RequestHandler
		 */
		public function output( $values = array() ){
			if( empty($values) ){
				$values = $this->result;
            }
            
            if ($this->is_save_log){
                if($values['status'] == true){
                    $this->log_data['success'] = json_encode(array(
                        'message' => $values['message'],
                        'params' => $values['params']
                    ), JSON_UNESCAPED_UNICODE);	// for chinese character
                }else{
                    if($this->error_data){
                        $this->log_data['error'] = gettype($this->error_data) == 'string' ? $this->error_data : json_encode($this->error_data);
                    }else{
                        $this->log_data['error'] = json_encode(array(
                            'message' => $values['message'],
                            'params' => $values['params']
                        ));
                    }
				}
    
                if($this->new_data){
                    $this->log_data['new_data'] = json_encode($this->new_data);
                }
    
                if($this->old_data){
                    $this->log_data['old_data'] = json_encode($this->old_data);
                }
    
                if($this->log_data){
                    try{
                        $objLogApi = ClassRegistry::init('Log.LogApi');
                        if(!$objLogApi->save($this->log_data)){
                            CakeLog::write('SystemAPI', "------ START Add Log Failed. " . json_encode($objLogApi->invalidFields()) . " ------ \r\n");
                            CakeLog::write('SystemAPI', "------ DATA Log Failed: " . json_encode($this->log_data) . " \r\n");
                            CakeLog::write('SystemAPI', "------ END Add Log Failed ----------------------------------------------- \r\n");
                        }
                    }catch(Exception $e){
                        CakeLog::write('SystemAPI', "------ START Add Log Failed. " . json_encode($e->getMessage()) . " ------ \r\n");
                        CakeLog::write('SystemAPI', "------ DATA Log Failed: " . json_encode($this->log_data) . " \r\n");
                        CakeLog::write('SystemAPI', "------ END Add Log Failed ----------------------------------------------- \r\n");
                    }
                }
            }
            // end inseart log data

			$values = $this->replace_null($values);

			return $this->controller->set(array(
				'result' => $values,
				'_serialize' => 'result'
			));
        }
        
		/**
		 * Public function to set the language
		 */
		public function set_language( $language = "zho" ){
			if( isset($language) && !empty($language) ){
				$this->language = $language;
				$this->Session->write('Config.language', $language);
			}
		}

		/**
		 * Public function to get the language and its suffix used in Database
		 */
		public function get_language(){
			return array(
				'language' => $this->language,
			);
        }
        
        /**
		 * Public function to set the language
		 */
		public function set_member_id($member_id = null ){
            if($member_id){
                $this->log_data['member_id'] = $member_id;
            }
        }
        
		/**
		 * Public function to get the token and its expiry
		 */
		public function get_token(){
			return array(
				'token' => $this->token,
				'token_expiry' => $this->token_expiry,
			);
		}

		/**
		 * public function to recursively replace NULL to empty string "" in APIs
		 */
		public function replace_null( $data ){
			array_walk_recursive($data, function(&$item, $key){
				if ( is_null($item) ) { $item = ""; }
			});

			return $data;
		}
	}
?>