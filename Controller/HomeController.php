<?php
class HomeController extends AppController{
	public function index() {
		$display_available_lang = array('zho', 'eng');
		$this->set(compact('display_available_lang'));	
	}
}
?>