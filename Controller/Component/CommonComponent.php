<?php
	App::uses('Component', 'Controller');
	App::uses('Folder', 'Utility');
	App::uses('File', 'Utility');

	class CommonComponent extends Component {
		
		/**
		 * Components
		 *
		 * @var array
		 */
		public $components = array(
			// Enable ExcelReader in PhpExcel plugin
			'PhpExcel.ExcelReader',

			// Enable PhpExcel in PhpExcel plugin
			'PhpExcel.PhpExcel',

			'Session',

			'Redis'
		);


		public function initialize(Controller $controller) {
			parent::initialize($controller);

			if (!class_exists('PHPExcel')){
				// load vendor classes if does not load before
				App::import('Vendor', 'PhpExcel.PHPExcel');
			}
		}

		public function upload_images( $image, $relative_upload_folder, $image_name_suffix = "" ){
			$message = 'File is null';
			$params = array();
			if( isset($image) && !empty($image) ){
				$upload_folder = WWW_ROOT;
				$sub_folder = 'uploads';
				$static_path = 'img' . DS . $sub_folder;

				$upload_folder .= $static_path;
				if( isset($relative_upload_folder) && !empty($relative_upload_folder) ){
					$upload_folder .= DS . $relative_upload_folder;
				} else {
					$upload_folder .= 'img' . DS . 'trash';
				}

				$folder = new Folder($upload_folder, true, 0777);

				if( $folder ){
					try{
						$file = new File( $image['name'] );
						// rename the uploaded file
						$renamed_file = $image_name_suffix . '-' . date('YmdHis') . '.' . $file->ext();
						// set the full path of uploaded file name
                        $renamed_file_full_path = $upload_folder . DS . $renamed_file;

                        list($width, $height, $type, $attr) = getimagesize( $image['tmp_name'] );

						move_uploaded_file($image['tmp_name'], $renamed_file_full_path);
						chmod($renamed_file_full_path, 0777);
						
						return array(
							'status' => true, 
							'params' => array(
								'ori_name' => $image['name'],
								're_name' => $renamed_file,
                                'path' => $sub_folder . DS . $relative_upload_folder . DS . $renamed_file,
                                'type' => $type,
                                'width' => $width,
                                'height' => $height,
                                'size' => $image['size']
							)
						);
					} catch(Exception $e) {
						$message = 'Upload file failed. ' . $e->getMessage();
						$params = array(
							're_name' => $renamed_file,
							'folder_path' => $upload_folder,
							'path' => $sub_folder . DS . $relative_upload_folder . DS . $renamed_file
						);
					}
				} else {
					$message = 'Fail to create folder.';
					$params = array(
						'folder_path' => $upload_folder,
					);
				}
			}

			return array(
				'status' => false, 
				'message' => $message,
				'params' => $params
			);
		}

		public function upload_file( $image, $relative_upload_folder, $image_name_suffix = "" ){
			$message = 'File is null';
			$params = array();
			if( isset($image) && !empty($image) ){
				//$upload_folder = WWW_ROOT . 'file/';
				$upload_folder = WWW_ROOT . 'file' . DS;

				if( isset($relative_upload_folder) && !empty($relative_upload_folder) ){
					$upload_folder .= $relative_upload_folder;
				} else {
					$upload_folder .= '';
				}

				$folder = new Folder($upload_folder, true, 0777);

				if( $folder ){
					try{
						$file = new File( $image['name'] );
						// rename the uploaded file
						$renamed_file = $image_name_suffix . '-' . date('YmdHis') . '.' . $file->ext();
						// set the full path of uploaded file name
						$renamed_file_full_path = $upload_folder . DS . $renamed_file;

						move_uploaded_file($image['tmp_name'], $renamed_file_full_path);
						chmod($renamed_file_full_path, 0777);
						
						return array(
							'status' => true, 
							'params' => array(
								'ori_name' => $image['name'],
								're_name' => $renamed_file,
								'path' => $relative_upload_folder . DS . $renamed_file
							)
						);
					} catch(Exception $e) {
						$message = 'Upload file failed. ' . $e->getMessage();
						$params = array(
							're_name' => $renamed_file,
							'folder_path' => $upload_folder,
							'path' => $relative_upload_folder . DS . $renamed_file
						);
					}
				} else {
					$message = 'Fail to create folder.';
					$params = array(
						'folder_path' => $upload_folder,
					);
				}
			}

			return array(
				'status' => false, 
				'message' => $message,
				'params' => $params
			);
		}

		public function slugify($text) {
			// replace non letter or digits by -
			$text = preg_replace('~[^\pL\d]+~u', '-', $text);
			// transliterate
			$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
			// remove unwanted characters
			$text = preg_replace('~[^-\w]+~', '', $text);
			// trim
			$text = trim($text, '-');
			// remove duplicate -
			$text = preg_replace('~-+~', '-', $text);
			// lowercase
			$text = strtolower($text);
			
			return $text;
		}

		public function get_available_language_list() {
			$available_lang = Environment::read('site.available_languages');

			$get_long_names = array_map(function($lang) {
				return __($lang.'_language');
			}, $available_lang);

			return array_combine($available_lang, $get_long_names);
		}
        
		public function generate_qrcode( $member_number){
			$upload_folder = WWW_ROOT . 'img/qrcode';

			$folder = new Folder($upload_folder, true, 0777);

			if( $folder ){
				$file_name = 'children-' . $member_number . '.png';
				$file = $upload_folder . DS . $file_name;

				QRcode::png($member_number, $file, QR_ECLEVEL_L, 8);

				return array(
					'status' => true,
					'path' => 'qrcode' . DS . $file_name,
				);
			} else {
				return array(
					'status' => false, 
					'message' => 'Fail to create folder.',
				);
			}
		}

        public function setup_export_csv($headlist, $model, $data, $conditions, $limit, $file_name, $lang){
            $file_name = $this->_encode($file_name);

			header('Content-Encoding: UTF-8'); 
			header("Content-type: application/x-msexcel; charset=utf-8");
			header('Content-Disposition: attachment; filename="' . $file_name . '.csv"');
            header('Cache-Control: max-age=0');
            // for browser down
			$fp = fopen('php://output', 'a');
			
            fputs ($fp, "\xEF\xBB\xBF"); // UTF-8 BOM !!!!!
		
            fputcsv($fp, $headlist);
            $objModel = ClassRegistry::init($model);
            $num = 0;

            return $this->set_data_csv($objModel, $fp, $data, $conditions, 1, $limit, $num, $lang);
        }

        private function set_data_csv($objModel, $fp, $data, $conditions, $page, $limit, $num, $lang){
            $list_item = $objModel->get_data_export($conditions, $page, $limit, $lang);

            $limit_csv = 100000;
            $skip = $limit * ($page - 1);
            foreach ($list_item as $row) {
                $num++;
                $item = $objModel->format_data_export($data, $row);
                
                if ($limit_csv <= ($num + $skip)) { 
                    ob_flush();
                    flush();
                    $num = 0;
                }

                fputcsv($fp, $item);
            }

            if($limit <= count($list_item)){
                return $this->set_data_csv($objModel, $fp, $data, $conditions, ($page+1), $limit, $num, $lang);
            }else{
                return $skip + count($list_item);
            }
        }

		// Covert charater set to UTF-8
		protected function _encode($str = '') {
			return iconv("UTF-8","UTF-8//TRANSLIT", html_entity_decode($str, ENT_COMPAT, 'utf-8'));
		}

		public function force_logout_affected_user($ids) {
            $myid = $this->Session->id();
			foreach($ids as $id) {
                $objAdministratorsRole = ClassRegistry::init('Administration.AdministratorsRole');
                $user_arr = $objAdministratorsRole->get_user_by_role($id);
				foreach($user_arr as $user) {
                    $session_cache = $this->Redis->get_cache_global('booster_user'.$user.'_sessionid');
					if(!empty($session_cache)) {						
						session_write_close();

						session_id($session_cache);
						session_start();
						session_destroy();

						session_id($myid);
						session_start();
					}
				}
			}
        }

        public function force_logout_affected_user_by_user_ids($user_ids) {
            $myid = $this->Session->id();
            foreach($user_ids as $user) {
                $session_cache = $this->Redis->get_cache_global('booster_user'.$user.'_sessionid');
                if(!empty($session_cache)) {						
                    session_write_close();

                    session_id($session_cache);
                    session_start();
                    session_destroy();

                    session_id($myid);
                    session_start();
                }
            }
        }

        public function get_log_data_admin(){
            $agent_info = $this->get_agent_info();

            $current_user = $this->Session->read('Administrator.current');
    
            return array(
                'remote_ip' => $this->get_client_ip(),
                'agent' => $agent_info["userAgent"],
                'browser' => $agent_info["browser"],
                'version' => $agent_info["version"],
                'platform' => $agent_info["platform"],
            );
        }

        public function get_log_data_cronjob(){
            return array(
                'remote_ip' => null,
                'agent' => null,
                'browser' => null,
                'version' => null,
                'platform' => null,
            );
        }

		public function get_agent_info() {
			$u_agent = $_SERVER['HTTP_USER_AGENT'];
			$temp = strtolower($_SERVER['HTTP_USER_AGENT']);

			$bname    = 'Unknown';
			$platform = 'Unknown';
			$version  = "";

			// Get the platform
			if (preg_match('/linux/i', $temp)) {
				$platform = 'linux';
			}
			elseif (preg_match('/macintosh|mac os x/i', $temp)) {
				$platform = 'mac';
			}
			elseif (preg_match('/windows|win32/i', $temp)) {
				$platform = 'windows';
			}
        
            $ub = '';
			// Get the name of the useragent
			if(preg_match('/msie/i',$temp) && !preg_match('/opera/i',$temp)) {
				$bname = 'Internet Explorer';
				$ub = "msie";
			}
			elseif(preg_match('/firefox/i',$temp)) {
				$bname = 'Mozilla Firefox';
				$ub = "firefox";
			}
			elseif(preg_match('/chrome/i',$temp)) {
				$bname = 'Google Chrome';
				$ub = "chrome";
			}
			elseif(preg_match('/safari/i',$temp)) {
				$bname = 'Apple Safari';
				$ub = "safari";
			}
			elseif(preg_match('/opera/i',$temp)) {
				$bname = 'Opera';
				$ub = "opera";
			}
			elseif(preg_match('/netscape/i',$temp)) {
				$bname = 'Netscape';
				$ub = "netscape";
			}
		
			$known = array('version', $ub, 'other');
			$pattern = '#(?<browser>' . join('|', $known) .')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
			preg_match_all($pattern, $temp, $matches);

			$i = count($matches['browser']);
			if ($i != 1) {
				if (strripos($temp,"version") < strripos($temp,$ub)) {
					$version = $matches['version'][0];
				}
				else {
					$version = $matches['version'][1];
				}
			}
			else {
				$version = $matches['version'][0];
			}
		
			if ($version == null || $version == "") {
				$version = "?";
			}
		
			return array(
				'userAgent' 	=> $u_agent,
				'browser'      	=> $bname,
				'version'   	=> $version,
				'platform' 		=> $platform,
			);
        }
        
        public function get_client_ip() {
			$ipaddress = '';
			if (getenv('HTTP_CLIENT_IP'))
				$ipaddress = getenv('HTTP_CLIENT_IP');

			else if(getenv('HTTP_X_FORWARDED_FOR'))
				$ipaddress = getenv('HTTP_X_FORWARDED_FOR');

			else if(getenv('HTTP_X_FORWARDED'))
				$ipaddress = getenv('HTTP_X_FORWARDED');

			else if(getenv('HTTP_FORWARDED_FOR'))
				$ipaddress = getenv('HTTP_FORWARDED_FOR');

			else if(getenv('HTTP_FORWARDED'))
				$ipaddress = getenv('HTTP_FORWARDED');

			else if(getenv('REMOTE_ADDR'))
				$ipaddress = getenv('REMOTE_ADDR');

			else
				$ipaddress = 'UNKNOWN';
			
			return $ipaddress;
        }

        public function clone_object($from_object, $to_object){
            $not_allow_keys = ['id', 'created', 'created_by', 'updated', 'updated_by'];

            foreach($to_object as $key => $value){
                if(!in_array($key, $not_allow_keys)){
                    $from_object[$key] = $value;
                }
            }

            return $from_object;
        }

        public function get_list_month(){
            return  array(
                '1' => __('jan'),
                '2' => __('feb'),
                '3' => __('mar'),
                '4' => __('apr'),
                '5' => __('may'),
                '6' => __('jun'),
                '7' => __('jul'),
                '8' => __('aug'),
                '9' => __('sep'),
                '10' => __('oct'),
                '11' => __('nov'),
                '12' => __('dec'),
            );
        }

        public function get_day_of_week(){
            return array(
                0 => __('sun'),
                1 => __('mon'),
                2 => __('tue'),
                3 => __('wed'),
                4 => __('thu'),
                5 => __('fri'),
                6 => __('sat'),
            );
        }

        public function get_list_month_names($months){
            $result = array();
            $month_names = $this->get_list_month();
            foreach($months as $value){
                if(isset($month_names[$value])){
                    array_push($result, $month_names[$value]);
                }
            }
            return $result;
        }

        public function generate_code($prefix, $id){
            $length = 4;
            return $prefix . str_pad((string)$id, $length, 0, STR_PAD_LEFT);
		}
		
		public function generate_verification_code() {
			return (substr(rand(1000000,99999999),0,4));
		}

		public function phone_validation($country_code, $phone) {
			$valid = true;

			if (($country_code == '+852') && 
				((strlen($phone) != 8) || (!in_array($phone[0], array(5,6,7,8,9))))) {
				$valid = false;
			} else if (($country_code == '+853') && 
					   ((strlen($phone) != 8) || ($phone[0] != '6'))) {
				$valid = false;
			} else if (($country_code == '+86') && 
					  ((strlen($phone) != 11) || ($phone[0] != '1'))) {
				$valid = false;
			} else if (!in_array($country_code, array('+852', '+853', '+86', '+84'))) {
				$valid = false;
			}

			return $valid;
        }

		private function getCellID($num) {
			$arr = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
					'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AK', 'AL', 'AM', 'AN', 'AO'];
			return $arr[$num];
		}

		public function export_multiple_excel ($parm_data, $parm_readable_header) {
			$export = array(
				'status' => true, 'message' => '', 'params' => array()
			);
			
			if( empty($parm_data) ){
				$export = array(
					'status' => false, 
					'message' => 'No data can be exported',
					'params' => array()
				);
			} else {
				try{
					$filename_arr = array_keys( $parm_readable_header );
					foreach ($filename_arr as $file_index => $filename) {
						// create new empty worksheet and set default font
						$this->PhpExcel->createWorksheet()->setDefaultFont('Tahoma', 12);
						$this->PhpExcel->getDefaultStyle()->getAlignment()->setWrapText(true);

						$sheet_arr = array_keys( $parm_readable_header[$filename] );
						foreach ($sheet_arr as $sheet_index => $sheet_name) {

							if ($sheet_index > 0) {
								$this->PhpExcel->createSheet();
							}

							$this->PhpExcel->setActiveSheetIndex($sheet_index);
							$this->PhpExcel->getActiveSheet()->setTitle($sheet_name);
							$this->PhpExcel->setRow(1);

							$readable_header = $parm_readable_header[$filename][$sheet_name];
							$readable_field_keys = array_keys( $readable_header );

							$excel_readable_header  = array();

							foreach ($readable_field_keys as $key => $f_key) {
								$excel_readable_header[ $key ]['label'] = $readable_header[ $f_key ]['label'];
	
								if( isset($readable_header[ $f_key ]['width']) && !empty($readable_header[ $f_key ]['width']) ){
									$excel_readable_header[ $key ]['width'] = $readable_header[ $f_key ]['width'];
								}
	
								if( isset($readable_header[ $f_key ]['filter']) && ($readable_header[ $f_key ]['filter'] == true) ){
									$excel_readable_header[ $key ]['filter'] = $readable_header[ $f_key ]['filter'];
								}
							}

							$this->PhpExcel->addTableHeader($excel_readable_header, array('name' => 'Tahoma'));

							$data = $parm_data[$filename][$sheet_name];
							foreach ($data as $value) {
								$_data = array();
	
								foreach ($readable_field_keys as $key => $f_key) {
									if( isset($value[$f_key]) ){
										if( $f_key === "status" ){
											$_data[$key] = (int) $value[$f_key];
										} else  {
											$_data[$key] = !empty($value[$f_key]) ? $value[$f_key] : ' ';
										}
	
									}
								}

								$this->PhpExcel->addTableRow($_data);
							}

							$sheet = $this->PhpExcel->getActiveSheet();
							$fill_style = array(
								'fill' => array
								(
									'type' => PHPExcel_Style_Fill::FILL_SOLID,
									'color' => array('rgb' => 'FFCC00')	// yellow	
								)
							);

							$border_style = array(
												'borders' => array
												(
													'allborders' => array
													(
														'style' => PHPExcel_Style_Border::BORDER_THIN,
														'color' => array('rgb' => '000000'),		// BLACK
													)
												)
											);

							$column =  $this->getCellID(count($readable_header) - 1);
							$row = count($data) + 1;

							// format header
							$sheet->getStyle("A1:" . $column . "1")->applyFromArray($fill_style);
							$sheet->getStyle("A1:" . $column . "1")->getFont()->setBold(false)
																->setName('Verdana')
																->setSize(14)
																->getColor()->setRGB('FF0000');

							// format header + data
							$sheet->getStyle("A1:" . $column . $row)->applyFromArray($border_style);

							// close table
							$this->PhpExcel->addTableFooter();

						} //end for each sheet

						$this->PhpExcel->addTableFooter()->output($filename.'.xls');
					} // end for each file


				} catch ( Exception $e ) {
					$export = array(
						'status' => false, 
						'message' => 'Fail to export the Excel file. Please try again.',
						'params' => array()
					);

					return $export;
				}
			}

			return $export;	
		}	
	}
?>