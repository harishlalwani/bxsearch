<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class FilmsController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();

/**
 * Displays a view
 *
 * @param mixed What page to display
 * @return void
 * @throws NotFoundException When the view file could not be found
 *	or MissingViewException in debug mode.
 */
	// allow user actions
	public function beforeFilter() 
	{ 
		
	}

	public function index() {
		
	}

	public function getPlaces()
	{
		$this->autoRender = false; 
		$this->layout = false;
		$user_id = $this->Auth->User('id');

		$options['conditions'] = array("id" => $user_id);
		$this->loadModel('User'); 
		$data = $this->User->find("first", $options);

		$distance = $_POST['distance'];
		$latitude = trim($data['User']['latitude']);
		$longitude = trim($data['User']['longitude']);
		$access_token = trim($data['User']['access_token']);
		$url = "https://graph.facebook.com/v2.6/search?q=restaurant&distance=$distance&type=place&center=$latitude,$longitude&access_token=".$access_token;
		$data = json_decode(file_get_contents($url));
		
		$url = "https://graph.facebook.com/v2.6/search?q=theatre&distance=$distance&type=place&center=$latitude,$longitude&access_token=".$access_token;
		$data2 = json_decode(file_get_contents($url));

		$data = array_merge($data->data,$data2->data);
		/*$data = json_decode($data);*/


		$r->data = $data;
		return json_encode($r);
	}
}
