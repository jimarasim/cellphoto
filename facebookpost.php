<html>
<head>
    <title>cellphoto - facebook</title>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
</head>
<body>
<script>
    
    var intervalFunction; //set at runtime, so can be cleared
    var timeout = 10000;
    
    
    window.fbAsyncInit = function() {
        FB.init({
          appId      : '709181395771740',
          xfbml      : false,
          version    : 'v2.0',
          status     : true
        });
        
        $('#fbstatuslist').append("<li>fb initialized</li>");
        
        checkAuthenticationStatus();
    };

    //LOADS THE SDK
    (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    
    /**
     * This method makes sure a user is authenticated, then does something if they are
     * @returns {undefined}
     */
    function checkAuthenticationStatus()
    {
//        $('#analogplaylist').empty();
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

          //print info of user authenticated
          $('#fbstatuslist').append("<li>UID:"+uid+"</li>");
          $('#fbstatuslist').append("<li>TOKEN:"+accessToken+"</li>");
          
          FB.api('/me', {fields: 'last_name,first_name'}, function(response) {
                var timestamp = new Date();
                $('#fbstatuslist').append("<li>"+response.first_name+" "+response.last_name+" "+timestamp+"</li>");
                
                //kick off the interval
                intervalFunction = setInterval(function(){intervalThread()},timeout);
                                    

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
    
    /**
     * This method will prompt the user to log into facebook, and get the 
     * required permissions from them
     * @param {type} statusMessage
     * @returns {undefined}     */
    function loginToFacebook()
    {
        FB.login(function(response) {
            if (response.authResponse) {
              $('#fbstatuslist').append('Welcome!  Fetching your information.... ');
              FB.api('/me', function(response) {
                checkAuthenticationStatus();
              });
            } else {
                $('#fbstatuslist').append("<li>USER CANCELLED LOGIN OR DID NOT AUTHORIZE</li>");
            }
          }, {scope: 'publish_actions, user_groups'});
    }
    
    /**
     * This method will post a status on the users behalf, with a timestamp
     * @param {type} statusMessage
     * @returns {undefined}     */
    function postStatusToFacebook(statusMessage){
        var timestamp = new Date();
        statusMessage += '('+timestamp+')';
        FB.api('/me/feed', 'post', { message: statusMessage }, function(response) {
          if (!response || response.error) {
            $('#fbstatuslist').append('<li>postStatusToFacebook Error occured:'+response.error.message+' '+timestamp+'</li>');
            stopInterval();
          } else {
            $('#fbstatuslist').append('<li>postStatusToFacebook ID: ' + response.id + ' '+timestamp+'</li>');
          }
        });
    }
    
    /** This method will post a status on the users behalf to the ombl group
     * @param {type} statusMessage
     * @returns {undefined}     */
    function postStatusToFacebookOmlb(statusMessage){
        //omlb group id: 191968037495092
        //get this by:
        //1. go to graph api explorer: https://developers.facebook.com/tools/explorer
        //2. get access token, and check user_groups
        //3. plug in the node: me/groups
        var timestamp = new Date();
        statusMessage += '('+timestamp+')';
        FB.api('/191968037495092/feed', 'post', { message: statusMessage }, function(response) {
          if (!response || response.error) {
            $('#fbstatuslist').append('<li>postStatusToFacebookOmlb Error occured:'+response.error.message+' '+timestamp+'</li>');
            stopInterval();
          } else {
            $('#fbstatuslist').append('<li>postStatusToFacebookOmlb ID: ' + response.id + ' '+timestamp+'</li>');
          }
        });
    }
    
    

    /**
     * Thread that gets executed at every timeout, once the facebook user has been authenticated
     * @returns {undefined}     */
    function intervalThread()
    {
        //postStatusToFacebookOmlb('sk8creteordie http://seattlerules.com/cellphoto/cellphoto/JAEMZBOT201404191310031.jpg');
        var timestamp = new Date();
        $('#fbstatuslist').append("<li>timer interval"+timestamp+"<input type='button' onclick='stopInterval()' value='Stop timer'  /></li>");
        $.get( "cellphoto.php?jsonImageLinks", function( data ) {
            $('#fbstatuslist').append('<li>Cellphotos:'+data+'</li>');
          });
        
    }
    
    /**
     * This function will kill the timer if any error occurs
     * @returns {undefined}     */
    function stopInterval()
    {
        var timestamp = new Date();
        $('#fbstatuslist').append('<li>Stopping timer. Refresh page to re-authenticate '+timestamp+'</li>');
        window.clearTimeout(intervalFunction);
    }
</script>
<ul id="fbstatuslist">
</ul>
</body>
</html>



