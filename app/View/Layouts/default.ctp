<?php
/**
 *
 * PHP 5
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
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

?>
<!DOCTYPE html>
<html>
<head>
<title> Bluebox </title>
<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->meta('title',  "Films");
		echo $this->Html->meta('description',  "Films");
		echo $this->Html->css(
								array(								
									'bootstrap3/css/bootstrap',	
							 		'main.css',
								)
							 );

    echo $this->Html->script(array( 
            'jquery-1.11.0.min.js',
            '../css/bootstrap3/js/bootstrap.js',
            'bootstrap-carousel.js'           
            ));           	


		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>

<script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=<?php echo Configure::read('GoogleMapKey'); ?>"></script>

<script>

function FbLogin(){
  var response = "";
  FB.login(function(response){
    if(response.authResponse){
      console.log(response);
      var accessToken=response.authResponse.accessToken;
      FB.api('/me', {
        fields: 'id,birthday,email,gender,location,name'
      } ,function(response){
        console.log(response);
        var uid=response.id;
        response.fb_access_token = accessToken;
        if (uid > 1)
        {
          jQuery.ajax({
            type: 'POST',
            url: '/users/m_fbconnect',
            data: response,
            success: function(data) {
              data = eval('(' + data + ')');   
              if (data.status == 1) {   
                fbLogginCheck = true;
                if(data.redirect)
                {
                  window.location = data.redirect_uri;
                } 
              }else{
                alert ('Oops. Something has happened to the connection. Please refresh your page');
              } 
            },
            error:function(){
              alert ('Oops. Something went wrong. Please try again later.');
              // $(obj).closest(".PopWrpIn").find(".please_wait").fadeOut();
            }
          });
        }
      });
    }
    else{
      alert('Could Not login, Please Try again.');
    }
  },{
    scope:'email,user_location'
  });
}

  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response.status);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      //FbLogin();
      $(".logout").removeClass("hide");
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into Facebook.';
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
  FB.init({
    appId      : '383325798472032',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.1' // use graph api version 2.5
  });

  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });

  };

  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  function FbLogout(){
    FB.logout();
    window.location = "<?php echo $this->Html->Url(array('controller' => 'Users', 'action' => 'logout')); ?>";
  }

</script>

<script> 
  var baseUrl = '<?php echo $this->webroot?>'; var WindowScrollEn = 1;
</script>

</head>
<style>
.navbar-header{
  float: right;
  margin-top: 35px;
}

</style>
<body>
  <!--- Header --->
      <!-- Static navbar -->
      <nav class="navbar navbar-default top-header" role="navigation">
        <div class="container-fluid">
          <div class="navbar-header">
            <?php if ($this->Session->read('Auth.User')){ ?>
              <p class="logout hide">
                <button type="button" class="btn btn-default btn-sm" onclick="FbLogout();">
                  <span class="glyphicon glyphicon-log-out"></span> Log out
                </button>
              </p>
            <?php } ?>
            
            
          </div>
          <!--/.nav-collapse -->
        </div><!-- /.container-fluid  -->
      </nav>
   

	<?php echo $this->fetch("content"); ?>

  <div class="footer_main">
    <div class="footer_top_bg">&nbsp;</div>
    <div class="footer_content">
       <div class="container">  
        <div class="col-lg-12">&nbsp;</div>
          <div class="row">
              <div class="col-lg-12">
                  <p class="text-center"><i> 2015 All rights reserved</i></p>
                </div>  
            
          </div>
       </div>     
    </div>
  </div>

  <!--- Footer Section --->
  
  <!--- End Footer Section ---> 

<?php	echo $this->Html->script(array(	
						'jquery-1.11.0.min.js',
						'../css/bootstrap3/js/bootstrap.js',
						'bootstrap-carousel.js'						
						));

?>
<script>
!function ($) {
$(function(){
$('#myCarousel').carousel()
})
}(window.jQuery)
</script>
</body>
</html>