
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
          $.LoadingOverlay("show");
          jQuery.ajax({
            type: 'POST',
            url: '/users/m_fbconnect',
            data: response,
            success: function(data) {
              data = eval('(' + data + ')');   
              if (data.status == 1) {   
                fbLogginCheck = true;
                $.LoadingOverlay("hide");
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