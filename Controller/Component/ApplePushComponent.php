<?php
/**
 * Custom Component for Apple APNS push notification
 * 
 * @author Ricky Lam @ VTL
 */
	App::uses('Component', 'Controller');

	class ApplePushComponent extends Component {
		protected $push_server;

		// Apple .pem file name
		public $pem_file;

		// Apple .pem file name
		public $pem_password;

		// notify sound
		public $aps_sound = "default";

		// notify badge
		public $aps_badge = "1";

		//apple feedback url
		public $apple_feedback_url = "";

		public function initialize(Controller $controller) {
			parent::initialize($controller);
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

		public function set_credential($sandbox = true ){
        
			if ($sandbox === true) {
                $this->server_key = Environment::read('push.ios.server_key');
                $this->sender_id = Environment::read('push.ios.sender_id');
				$this->server_feedback_url = Environment::read('push.ios.server_feedback_url');

			} else {
				$this->server_key = Environment::read('push.ios.server_key');
                $this->sender_id = Environment::read('push.ios.sender_id');
				$this->server_feedback_url = Environment::read('push.ios.server_feedback_url');
            }
		}

		/**
		 * public method to push message to all device(s)
		 */
		public function push($ios_data, $message, $push_params) {
			$ch = curl_init();

			// Set POST variables
			$this->set_credential(true);
			$url = $this->server_feedback_url;
 
			$headers = array(
				'Connection: keep-alive',
				'Authorization: key=' . $this->server_key,
				'Content-Type: application/json'
			);

			$fields  = array();
	
			$failed_case = array();
			$succeed_case = array();
			try {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

				foreach ($ios_data as $ios) {
                    $language = isset($ios['language']) && $ios['language'] ? $ios['language'] : 'eng';
					$fields = array(
						'to' => $ios['device_token'],
						'notification' => array(
							'body' => $message[$language]['content'],
                            'title' => $message[$language]['title'],
                            'sound' => 'default',
						),
						'data' => isset($push_params) ? $push_params : (object)array(),
                    );
					curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

                    $result = curl_exec($ch);

					if ($result === FALSE) {
						array_push($failed_case, $ios);
					}
					else {
						array_push($succeed_case, $ios);
					}
                }

				$pushed = array(
                    'status' => true,
                    'params' => array(
                        'failed' => $failed_case,
                        'pushed_tokens' => $succeed_case,
					)
                );
			} catch (Exception $e) {
                $message = $e->getMessage();
				$pushed = array(
                    'status' => false,
                    'error_messages' => array(
                        'err' => $message,
                        'errstr' => $message,
                    ),
                    'params' => array()
                );
			} finally {
				// Close connection
				curl_close($ch);	 // Close the connection to the server
			}
	
			return $pushed;
		}
	}