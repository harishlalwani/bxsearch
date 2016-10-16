<?php
App::import('Vendor', 'Facebook/autoload.php');
App::import('Vendor', 'php-graph-sdk-master/src/Facebook/Facebook');
require_once(APP . 'Vendor' . '/php-graph-sdk-master/src/Facebook/Facebook.php');
require_once(APP . 'Vendor' . '/php-graph-sdk-master/src/Facebook/FacebookApp.php');
require_once(APP . 'Vendor' . '/php-graph-sdk-master/src/Facebook/FacebookClient.php');
require_once(APP . 'Vendor' . '/php-graph-sdk-master/src/Facebook/HttpClients/HttpClientsFactory.php'); 
require_once(APP . 'Vendor' . '/php-graph-sdk-master/src/Facebook/HttpClients/FacebookHttpClientInterface.php');
require_once(APP . 'Vendor' . '/php-graph-sdk-master/src/Facebook/HttpClients/FacebookCurlHttpClient.php');

require_once (APP . 'Vendor' . '/php-graph-sdk-master/src/Facebook/FacebookRequest.php');
require_once (APP . 'Vendor' . '/php-graph-sdk-master/src/Facebook/Exceptions/FacebookSDKException.php');
require_once(APP . 'Vendor' . '/php-graph-sdk-master/src/Facebook/autoload.php');


class UsersController extends AppController 
{	
	public $name = 'Users';
	public $components = array('RequestHandler');
	public $Facebook;
	
	// allow user actions

	public function beforeFilter() 
	{ 
		App::import('Vendor', 'php-graph-sdk-master/src/Facebook/Facebook/autoload');
	    $this->Facebook = new \Facebook\Facebook(array(
	        'app_id'     =>  '383325798472032',
	        'app_secret'    =>  'dad027a80158b03f3de8afcad6221f47'

	    ));
	    $this->Auth->allow();
	
	}
	
    /*------------------------ Login  ------------------------------*/
	
	public function login($action = null) 
	{ 
		$this->set("action", $action);
	}
   /*----------------- Login End ------------------------------*/
   
   /*this are used to fb login*/
	public function m_fbconnect($connection_type = null) {

		$this->layout = false;
				$this->autoRender = false;

		$appSecret = "dad027a80158b03f3de8afcad6221f47";
    	$appId = "383325798472032";
    	//$access_token = $_GET["access_token"];
    	$urlAuth = 'https://graph.facebook.com/oauth/access_token?grant_type=fb_exchange_token&client_id='.$appId.'&client_secret='.$appSecret.'&fb_exchange_token='.$_POST["fb_access_token"];
    	//step1
		$cSession = curl_init(); 
		//step2
		curl_setopt($cSession,CURLOPT_URL, $urlAuth);
		curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($cSession,CURLOPT_HEADER, false); 
		//step3
		$result=curl_exec($cSession);
		//print_r(curl_getinfo($cSession));
		if(curl_errno($cSession)){
		    echo 'Curl error: ' . curl_error($cSession);
		}
		//step4
		curl_close($cSession);
		//echo $result;
		$result = explode("&", $result);
		$result = explode("=", $result[0]);
		$access_token = $result[1];

		
		
		$url = "https://graph.facebook.com/v2.6/me?fields=location&access_token=".$access_token ;
		$data = file_get_contents($url);
		$data = json_decode($data);

		//print_r($data);


		/*echo $url = "https://graph.facebook.com/v2.6/search?type=place&center=17.4153059,78.4398914&access_token=".$access_token;
		$data = file_get_contents($url);
		$data = json_decode($data);

		print_r($data);*/

		$location = $data->location->name;

		$location = str_replace(" ", "+", $location);

		$url = "https://maps.googleapis.com/maps/api/geocode/json?address=$location&key=AIzaSyCOTHwL47jMs3kxPsXO1LN6r2iIrYxDS9k";
		$ldata = file_get_contents($url);
		$ldata = json_decode($ldata);
		
		$latitude = $ldata->results[0]->geometry->location->lat;
		$longitude = $ldata->results[0]->geometry->location->lng;
		
		$this->layout = false;
        $this->autoRender = false;
        $fb_data = array();
		$fb_data['User']['email'] = $_POST['email'];
		$fb_data['User']['name'] = $_POST['name'];
        $fb_data['User']['fb_id'] = $_POST['id'];
        $fb_data['User']['location'] = $data->location->name;
        $fb_data['User']['access_token'] = $access_token;
        $fb_data['User']['longitude'] = $longitude;
        $fb_data['User']['latitude'] = $latitude;
        //$fb_data['User']['fb_access_token'] = $_POST['fb_access_token'];

        $options['conditions'] = array("fb_id" => $fb_data['User']['fb_id']);

        $data = $this->User->find("first",$options);
       	
       	if(!empty($data))
       	{
       		$this->Session->write('Auth.User', $data['User']);
			$this->Auth->_loggedIn = true;
       	}	
       	else
       	{
       		$d = $this->User->save($fb_data);
       		$this->Session->write('Auth.User', $d['User']);
			$this->Auth->_loggedIn = true;
       	}	
        echo  json_encode(array('redirect'=> 1, 'redirect_uri' => '/films','status'=>1));
		// }
    }

    public function getUserLocation()
    {
    	$this->autoRender = false; 
		$this->layout = false;
		$user_id = $this->Auth->User('id');

		$options['conditions'] = array("id" => $user_id);
		$this->loadModel('User'); 
		$data = $this->User->find("first", $options);
		return json_encode($data['User']);
    }
   
  /*------------------------ Logout  ------------------------------*/
 	public function logout() 
  	{
	  	$this->Auth->logout();
	  	$this->redirect($this->Auth->logoutRedirect);
  	}
  /*------------------------ END  Logout  ------------------------------*/
    
}

?>