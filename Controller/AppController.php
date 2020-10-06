<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		https://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	public $components = array(
		// Enable DebugKit toolbar (when debug is set to >= 1)
		// 'DebugKit.Toolbar' => array(
		// 	'panels' => array('ClearCache.ClearCache')
		// ),

		// Enable JSON or XML view
        'RequestHandler',
        
		// Enable SessionComponent
		'Session',

		// Enable FlashComponent
		'Flash',
        
		// Enable CookieComponent
		'Cookie',
        
        'Api',

        'ExcelSpout',
        
        'Email',

        'Sms',

        'Redis',

        'Notification',

		// Enable LogFileComponent
		'LogFile',

        'ApplePush',

        'AndroidPush',

		// Enable general functions in CommonComponent
        'Common',
		// Enable ControllerListComponent
		'ControllerList' => array( 
			'includePlugins' => array(
                'Administration',
                'Dashboard',
			)
		)
	);

	public $helpers = array( 'Session', 'Flash' );

    public $theme = "";
    
    protected $slug_language = 'eng';

	public function beforeFilter(){
        $this->layout = "default";

		// all page we don't do anything
        if($this->request->plugin == "administration" && $this->request->controller == "administrators" &&
                ($this->request->action == "admin_login" || $this->request->action == "admin_logout"
                || $this->request->action == "admin_forgot_password" || $this->request->action == "admin_change_password")){
			$this->theme = "CakeAdminLTE";
			$this->layout = 'default';
            return;
        }

        $current_user = array();
        $permissions = array();

        $this->request->addDetector('api', array(
			'callback' => function ($request) {
				return (isset($request->params['api']) && $request->params['api']);
			})
        );

		$available_language = (array)Environment::read('site.available_languages');
		$params = $this->request->params;
		
        // set and get language  *****
        if(isset($this->request->data["set_new_language"]) && $this->request->data["set_new_language"] != "" &&
                in_array($this->request->data["set_new_language"], $available_language)){
            
            $url_params = array();
            foreach($this->request->query as $key => $value){
                array_push($url_params, $key . '=' . $value);
            }

            $is_admin = true;
            if (isset($this->request->data["origin_trigger"]) && ($this->request->data["origin_trigger"] == 'frontend')) {
                $is_admin = false;
            }
            $arr_url = array(
                'plugin' => $params['plugin'],
                'controller' => $params['controller'],
                'action' => $params['action'],
                'admin' => $is_admin,
            );
            foreach($params['pass'] as $item){
                array_push($arr_url, $item);
            }
            $current_url = Router::url($arr_url, true) . ($url_params ? '?' . implode('&', $url_params) : '');

            $this->Session->write('Config.language', $this->request->data["set_new_language"]);
            
            $this->redirect($current_url);
        }

		if(!$this->Session->check('Config.language')){
            /*
                check from cache, if exists in cache then use cache and write to session 
                else use from default
            */
            $new_lang = Environment::read('site.default_language');
            
            $this->Session->write('Config.language', $new_lang);
        }

		$this->lang18 = $this->Session->read('Config.language');
		
        if ($this->lang18 && file_exists(APP . 'View' . DS . $this->lang18 . DS . $this->viewPath . DS . $this->view . $this->ext)) {
            $this->viewPath = $this->lang18 . DS . $this->viewPath;
        }		

		if ( isset($params['prefix']) && ($params['prefix'] == "admin") ) {
			$this->theme = "CakeAdminLTE";
			$this->layout = 'default';

            /***** Start Secure on web ******/
			if( !($this->Session->check('Administrator.id') && $this->Session->check('Administrator.current')) ){
                $arr_url = array(
                    'plugin' => $params['plugin'],
                    'controller' => $params['controller'],
                    'action' => $params['action'],
                    'admin' => true,
				);
				
                if($params['pass']){
                    foreach($params['pass'] as $item){
                        array_push($arr_url, $item);
                    }
                }
                
                $arr_url['?'] = $this->request->query;

                $current_url = Router::url($arr_url, true);

                return $this->redirect( Router::url( array(
                    'plugin' => 'administration',
                    'controller' => 'administrators',
                    'action' => 'login',
                    'admin' => true,
                    '?' => array('last_url' => $current_url)
                ), true));
            }

            $current_user = $this->Session->read('Administrator.current');

            $permissions = $current_user['Permission'];
            unset($current_user['Permission']);

            // check permission in action
            $action = str_replace('admin_', '', $params['action']);

            $view_actions = ['index', 'view_detail', 'export', 'get_data_select', 'export_excel'];
            $add_actions = ['copy', 'get_content_push'];
            $edit_actions = ['resetpassword', 'import', 'import_detail', 'exec_cronjob', 'get_mall_shop', 'edit_mall_shop'];
            $delete_actions = ['delete_all', 'get_delete_mall_shop', 'delete_mall_shop'];

            if(in_array($action, $view_actions)){
                $action = 'view';
			}else if(in_array($action, $add_actions)){
                $action = 'add';
            }else if(in_array($action, $edit_actions)){
                $action = 'edit';
            }else if(in_array($action, $delete_actions)){
                $action = 'delete';
            }
            
            if(!(($this->request->plugin == "dashboard" && $this->request->controller == "dashboard") || 
                ($this->request->controller == "redis" && $this->request->action == "admin_update_column_cache"))){
				$has_permission = array_filter($permissions, function($item) use($params, $action){
					return strtolower($item['p_plugin']) == strtolower($params['plugin']) && 
						strtolower($item['p_controller']) == strtolower($params['controller']) && isset($item[$action]);
                });
                
				if(!$has_permission){
					if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
						echo 'Invalid Permission'; exit;
					}else{
						throw new NotFoundException('Invalid Permission');
					}
				}
            }
        } else if ( isset($params['prefix']) && ($params['prefix'] == "api") && 
                    isset($params['action']) && (!in_array($params['action'], array(
                        'api_login',
                        'api_logout'
                    )))) {
            // login / logout cant go here

            /*
            $data = $this->request->data;            
            if (isset($data['token']) && !empty($data['token'])) {
                $token = $data['token'];
            } else {
                throw new NotFoundException(__('invalid_data'));
            }

            $cache_value = $this->Redis->get_cache('timeout', $token);
            $objSetting = ClassRegistry::init('Setting.Setting');

            if (empty($cache_value)) {
                throw new NotFoundException(__('login_has_expired'));
            } else if ($cache_value == 'family') {
                $duration = $objSetting->get_timeout('family_timeout');
                $this->Redis->set_cache('timeout', $token, '', 'family', $duration);
            } else if ($cache_value == 'member') {
                $duration = $objSetting->get_timeout('member_timeout');
                $this->Redis->set_cache('timeout', $token, '', 'member', $duration);
            }
            */
        }
        $this->ObjLog = ClassRegistry::init('Log.Log');

        $this->set(compact('current_user', 'permissions', 'available_language'));
    }
    
    protected function slugify($string){
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
    }

    public function return_json ($result){
        $this->RequestHandler->respondAs('json');
        $this->response->type('application/json');  
        $this->autoRender = false; 
        echo json_encode($result);
    }

    public function send_email_change_pass($data) {
        if (isset($data['email']) && !empty($data['email'])) {
            $template = "change_pass_notif";
            $subject = '[NannyBus] - '.__('change_pass_notif');

            $admin_email = $data['email'];

            $result_email = $this->Email->send($admin_email, $subject, $template, $data);
        }
    }
    
    protected function send_default_mail_to_manager($item_name, $item_link, $subject){
        $current_user = $this->Session->read('Administrator.current');
        $role_ids = array();
        foreach ($current_user['Role'] as $item) {
            if ($item['manage_role_id'] == 0) {
                return array(
                    'status' => true,
                );
            }
            array_push($role_ids, $item['id']);
        }

        $administrators = array();
        if(!empty($role_ids)) {
            $objAdministrator = ClassRegistry::init('Administration.Administrator');
            $administrators = $objAdministrator->get_all_manager_by_role_ids($role_ids, Environment::read('company.id'));
        }

        if(!empty($administrators)) {
            $template = "default_approved";
            $data = array(
                'name' => implode(', ', $administrators['names']),
                'item_name' => $item_name,
                'item_link' => $item_link,
                'created_name' => $current_user['name'],
            );

            $result_email = $this->Email->send($administrators['emails'], $subject, $template, $data);
            return $result_email; 
        }
        
        return array(
            'status' => true,
        );
    }
}
