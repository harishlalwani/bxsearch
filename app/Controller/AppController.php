<?php
App::uses('Controller', 'Controller');
App::import('Core', 'Helper');
App::uses('Sanitize', 'Utility'); 

class AppController extends Controller
{
	public $helpers = array('Html','Form', 'Js','Session');
	public $components = array('Session','Auth','Cookie','RequestHandler','Email');
	private $statusCode = 200;
	
	function beforeFilter() 
	{ 	
		// allow user actions
		
		$this->Auth->loginError = "Could not login. Please try again.";
        $this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
        $this->Auth->loginRedirect = array('controller' => 'films', 'action' => 'index');
        $this->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'login');
        $this->Auth->authError = 'You must login to view this information.';
		$this->Auth->autoRedirect = false;
		$this->Auth->allow('logout');
	}
}