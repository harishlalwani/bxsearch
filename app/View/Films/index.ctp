<!-- All the files that are required -->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<link href='http://fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.2.0/css/bootstrap-slider.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.2.0/bootstrap-slider.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<?php 
  echo $this->Html->css(array(                
          'login_page.css'
          ) );

  echo $this->Html->script(array( 
            'login_page.js'           
            )); 
  echo $this->fetch('css');
  echo $this->fetch('script');
?>
<!-- Where all the magic happens -->
<!-- LOGIN FORM -->
<div class="text-center" style="padding:10px 0; height:100%">
  <p class="heading"></p>
  <div class="sub-content">
    <div id="googleMap" ></div>
    <input id="ex4" type="text" data-slider-min="2" data-slider-max="20" data-slider-step="1" data-slider-value="2" data-slider-orientation="vertical"/>
  </div>
</div>
<script type="text/javascript">
      var distance = 2;
      $(document).ready(function() {
          var markers = [];
          var map , gmarkers, longitude, latitude;
          var gmarkers = [];
          
          function getPlaces()
          {
            var dist = distance * 1000;
            return $.ajax({
              method: "POST",
              url: "<?php echo$url =  $this->Html->Url(array('controller' => 'Films', 'action' => 'getPlaces')); ?>",
              data: "distance="+dist
            });
          }

          var promise = getPlaces();
          function drawMarkers(){
            var mapMarkers;
              markers = [];
              promise.success(function (data) {
                var data = JSON.parse(data)
                    $(data.data).each(function(i, item) {
                        
                          var tdArr = [];
                          //console.log(item);
                          tdArr.push(item.name);
                          tdArr.push(parseFloat(item.location.latitude));
                          tdArr.push(parseFloat(item.location.longitude));
                          markers.push(tdArr);
                        
                        

                        //console.log(markers);
                      });

                    drawMap();
                  });

          }

          
          function drawMap(){
            for (i = 0; i < markers.length; i++) {  
                    marker = new google.maps.Marker({
                        position: new google.maps.LatLng(markers[i][1], markers[i][2]),
                        title: markers[i][0],
                        map: map
                    });
                    gmarkers.push(marker);
                    google.maps.event.addListener(marker, 'click', (function(marker, i) {
                        return function() {
                            infowindow.setContent(markers[i][0]);
                            infowindow.open(map, marker);
                        }
                    })(marker, i));
                }
          }

          function removeMarkers(){
              for(i=0; i<gmarkers.length; i++){
                  gmarkers[i].setMap(null);
              }
          }

          function clearMap(){
            console.log(markers);
            for (i = 0; i < markers.length; i++) {  
                    marker = new google.maps.Marker({
                        position: new google.maps.LatLng(markers[i][1], markers[i][2]),
                        map: map
                    });
                    marker.setMap(null);
                    
                }
          }

          function getUserLocation()
          {
            
            return $.ajax({
              method: "POST",
              url: "<?php echo $this->Html->Url(array('controller' => 'Users', 'action' => 'getUserLocation')); ?>"
            });
          }

          var promiseLocation = getUserLocation();

          function initialize() {

             promiseLocation.success(function (data) {
                var dist = distance * 1000;
                var data = JSON.parse(data);
                latitude = data.latitude;
                longitude = data.longitude;
                $(".heading").html("Theatres and Restaurants in "+ data.location);
                var mapProp = {
                      center:new google.maps.LatLng(parseFloat(latitude),parseFloat(longitude)),
                      zoom:10,
                      mapTypeId:google.maps.MapTypeId.ROADMAP
                    };
                    map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
                drawMarkers(); 
                createCircle(parseFloat(latitude),parseFloat(longitude), dist);   
                });
            
          }
          google.maps.event.addDomListener(window, 'load', initialize);

          var infowindow = new google.maps.InfoWindow(), marker, i;


          function createCircle(latitude, longitude, distance)
          {
    
            cityCircle = new google.maps.Circle({
              strokeColor: '#FF0000',
              strokeOpacity: 0.8,
              strokeWeight: 2,
              fillColor: '#FF0000',
              fillOpacity: 0.35,
              map: map,
              center: {lat : latitude,lng : longitude},
              radius: distance
            });
          }

          var mq = window.matchMedia( "(min-width: 500px)" );
          var reversed = (mq.matches ? true  :  false);
          $("#ex4").slider({ reversed : true}).on ('slideStop', function(){
            cityCircle.setMap(null);
            distance =  $("#ex4").val();
            createCircle(parseFloat(latitude), parseFloat(longitude), $("#ex4").val() * 1000);
            removeMarkers();
            markers = [];
            $.ajax({
              method: "POST",
              url: "<?php echo$url =  $this->Html->Url(array('controller' => 'Films', 'action' => 'getPlaces')); ?>",
              data: "distance="+$("#ex4").val() * 1000,
              success: function (data) {
                var data = JSON.parse(data)
                    $(data.data).each(function(i, item) {
                        var tdArr = [];
                        //console.log(item);
                        tdArr.push(item.name);
                        tdArr.push(parseFloat(item.location.latitude));
                        tdArr.push(parseFloat(item.location.longitude));
                        markers.push(tdArr);

                        //console.log(markers);
                      });

                    drawMap();
                 }   
            });
          });

                     
       });
    </script>

