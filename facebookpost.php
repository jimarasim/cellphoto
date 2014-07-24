<html>
<head>
    <title>cellphoto - facebook</title>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
</head>
<body>
<script>
    window.fbAsyncInit = function() {
        FB.init({
          appId      : '709181395771740',
          xfbml      : false,
          version    : 'v2.0',
          status     : true
        });
        
        $('#fbstatuslist').append("<li>fb initialized</li>");
        
        checkLoginStatus();
    };

    //LOADS THE SDK
    (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    
    function checkLoginStatus()
    {
        $('#analogplaylist').empty();
        //CHECK if we're logged in 
        FB.getLoginStatus(function(response) {
        if (response.status === 'connected') {
          // the user is logged in and has authenticated your
          // app, and response.authResponse supplies
          // the user's ID, a valid access token, a signed
          // request, and the time the access token 
          // and signed request each expire
          var uid = response.authResponse.userID;
          var accessToken = response.authResponse.accessToken;

          $('#fbstatuslist').empty();
          $('#fbstatuslist').append("<li>UID:"+uid+"</li>");

          FB.api('/me', {fields: 'last_name,first_name'}, function(response) {
            $('#fbstatuslist').append("<li>"+response.first_name+" "+response.last_name+"</li>");
          });

        } else if (response.status === 'not_authorized') {
          // the user is logged in to Facebook, 
          // but has not authenticated your app
          $('#fbstatuslist').append("<li>UNAUTHORIZED</li>");
          $('#fbstatuslist').append("<li><input type='button' onclick='loginToFacebook()' value='Login to Facebook' /></li>");

        } else {
          $('#fbstatuslist').append("<li>NOT LOGGED IN</li>");
          $('#fbstatuslist').append("<li><input type='button' onclick='loginToFacebook()' value='Login to Facebook'  /></li>");
        }
       });
    }
    
    function loginToFacebook()
    {
        FB.login(function(response) {
            if (response.authResponse) {
              $('#fbstatuslist').append('Welcome!  Fetching your information.... ');
              FB.api('/me', function(response) {
                checkLoginStatus();
              });
            } else {
                $('#fbstatuslist').empty();
                $('#fbstatuslist').append("<li>USER CANCELLED LOGIN OR DID NOT AUTHORIZE</li>");
            }
          });
    }
</script>
<ul id="fbstatuslist">
</ul>
</body>
</html>



