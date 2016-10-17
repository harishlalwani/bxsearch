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

<?php
    echo $this->Html->script(array( 
            'default.js'         
            ));            
    echo $this->fetch('script');
  ?>
<script type="text/javascript">
  function FbLogout(){
    FB.logout();
    window.location = "<?php echo $this->Html->Url(array('controller' => 'Users', 'action' => 'logout')); ?>";
  }
</script>  

</head>
<body>
  <!--- Header --->
      <!-- Static navbar -->
      <nav class="navbar navbar-default top-header" role="navigation">
        <div class="container-fluid">
          <div class="navbar-header">
            <?php if ($this->Session->read('Auth.User')){ ?>
              <p class="name-email">
                
              </p>
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