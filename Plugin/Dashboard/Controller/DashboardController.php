<?php
App::uses('DashboardAppController', 'Dashboard.Controller');
/**
 * Dashboard Controller
 *
 * @property PaginatorComponent $Paginator
 */
class DashboardController extends DashboardAppController {
/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');
    private $model = 'Dashboard';

	public function beforeFilter(){
		parent::beforeFilter();

		$this->set('title_for_layout', __d('dashboard', 'dashboard'));
	}

    /**
     * admin_index method
     *
     * @return void
     */
	public function admin_index() {
    }

}
