/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    
    var intervalFunction; //set at runtime, so can be cleared
    var timeout = 5000;
//REMOVING , TO MAKE IT EASIER TO GET APPROVED
//    var requestedPermissions = 'publish_actions, user_photos, user_groups, user_videos';
    var requestedPermissions = 'publish_actions, user_photos';
    
    $.get( "cellphoto.php?fbai=1", function(data) {
        
        if(data){

            window.fbAsyncInit = function() {
                FB.init({
                  appId      : data,
                  xfbml      : false,
                  version    : 'v2.1',
                  status     : true
                });

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
        }
        else
        {
            document.write("access forbidden, sorry");
        }
    });


    
    /**
     * This method makes sure a user is authenticated, then does something if they are
     * @returns {undefined}
     */
    function checkAuthenticationStatus()
    {

        //CHECK if we're logged in 
        FB.getLoginStatus(function(response) {
        if (response.status === 'connected') {
          var uid = response.authResponse.userID;
          var accessToken = response.authResponse.accessToken;

            FB.api('/me', {fields: 'last_name,first_name' }, function(response) {
                
                var timestamp = new Date();
                $('#USER').text(response.first_name+" "+response.last_name+" "+timestamp);
                $('#UID').text(uid);

                //clear the log list
                $('#fbstatuslist').empty();

                //don't do anything else, unless sk8creteordie logged in
                //or stu_dfoxorp_stuverson@tfbnw.net Test@Test1
                //if(uid==="312446235582285"||uid==="1448832595399461")
                if(uid==="312446235582285")//||uid==="1448832595399461")
                {
                  
                  //schedule the intervals for checking for images, then posting them to facebook
                  intervalFunction = setInterval(function(){intervalThread();},timeout);
                  
                  //link to mailbox
                  $('#importantLinks').append("<h3><a href='https://webmail.seattlerules.com/src/login.php' target='_blank'>cellphoto@seattlerules.com mailbox</a></h3>");
                  $('#importantLinks').append("<h3><a href='cellphotoview.php' target='_blank'>cellphotoview</a></h3>");
                  
                  //show the stop timer button / hide the login button
                  $('#stoptimerbutton').css('display','block');
                  
                  //print info of user authenticated
                    $('#ACCESSTOKEN').text(accessToken);

        
                }
                else
                {
                    $('#fbstatuslist').append("<li>Text Images to: cellphoto@seattlerules.com</li>");
                }
                
                //hide the login button
                $('#loginbutton').css('display','none');
                
                //links to cellphotoview and facebook profile
                $('#importantLinks').append("<h3><a href='https://www.facebook.com/"+uid+"' target='_blank'>"+response.first_name+" "+response.last_name+"</a></h3>");


            });
          

        } else if (response.status === 'not_authorized') {
          // the user is logged in to Facebook, 
          // but has not authenticated your app
          
          $('#loginbutton').css('display','block');
          $('#stoptimerbutton').css('display','none');

        } else {
          
          $('#loginbutton').css('display','block');
          $('#stoptimerbutton').css('display','none');
        
        }
       });
    }
    
    /**
     * This method will prompt the user to log into facebook, and get the 
     * required permissions from them
     * 
     * @returns {undefined}     */
    function loginToFacebook()
    {
        FB.login(function(response) {
            if (response.authResponse) {
//              $('#fbstatuslist').append('Welcome!  Fetching your information.... ');
              FB.api('/me', function(response) {
                checkAuthenticationStatus();
              });
            } else {
                $('#fbstatuslist').append("<li>USER CANCELLED LOGIN OR DID NOT AUTHORIZE</li>");
            }
          }, {scope: requestedPermissions});
    }
    
    /**
     * Thread that gets executed at every timeout, once the facebook user has been authenticated
     * @returns {undefined}     */
    function intervalThread()
    {
        var timestamp = new Date();
        
        $.get( "cellphoto.php?jsonImageLinks=1", function(data) {
            var imageJson = jQuery.parseJSON(data);
            
            $('#lastupdatetime').text(timestamp);
            $('#lastresponse').text(imageJson);
            
            if(imageJson!==null)
            {
                $.each( imageJson, function( key, val ) {
                    var photoString = val.photo;
                    $("#fbstatuslist").append("<li>Posting:<a href='"+photoString+"' target='_blank'>"+photoString+"</a></li>");
                    
                    if((photoString.indexOf(".jpg")>-1)||
                            (photoString.indexOf(".png")>-1)||
                            (photoString.indexOf(".gif")>-1)){
                        uploadPhotoToFacebook(photoString);
//REMOVING , TO MAKE IT EASIER TO GET APPROVED
//                        uploadPhotoToFacebookOmlb(photoString); //REQUIRES USERS_GROUPS, currently cant get
                    }
//REMOVING , TO MAKE IT EASIER TO GET APPROVED
//                    else if(photoString.indexOf(".mp4")>-1){
//                        uploadVideoToFacebook(photoString); //REQUIRES USERS_VIDEOS
//                    }
                    else{
                        postStatusToFacebook(photoString);
                        //REMOVING , TO MAKE IT EASIER TO GET APPROVED
//                        postStatusToFacebookOmlb(photoString); //REQUIRES USERS_GROUPS, currently cant get
                    }
                    
                    
                  });   
            }
            else{
                if(timestamp.getMinutes()===00 ||
                   timestamp.getMinutes()===30 ){
                    facebookSessionKeepAlive();
                }
            }
          
          })
            .done(function() {
            })
            .fail(function() {
                $('#fbstatuslist').append('<li>ERROR: json ajax fetch for photos failed</li>');
                recoverFromError();
            })
            .always(function() {
                
            });
        
    }
    
    
    
    /**
     * This method will post a status on the users behalf, with a timestamp
     * @param {type} statusMessage
     * @returns {undefined}     */
    function postStatusToFacebook(statusMessage){
        var timestamp = new Date();
        
        FB.api('/me/feed', 'post', { message: statusMessage }, function(response) {
          if (!response || response.error) {
            $('#fbstatuslist').append('<li>postStatusToFacebook Error occured:'+response.error.message+' '+timestamp+'</li>');
          } else {
            $('#fbstatuslist').append('<li>postStatusToFacebook ID: ' + response.id + ' '+timestamp+'</li>');
          }
        });
    }


    
    
    /**
     * This method will upload a photo on the users behalf
     * @param {type} imageUrl
     * @returns {undefined}     */
    function uploadPhotoToFacebook(imageUrl){
        var timestamp = new Date();
        
        FB.api('/me/photos', 'post', { url: imageUrl, name: imageUrl  }, function(response) {
          if (!response || response.error) {
            $('#fbstatuslist').append('<li>uploadPhotoToFacebook Error occured:'+response.error.message+' '+timestamp+'</li>');
          } else {
            $('#fbstatuslist').append('<li>uploadPhotoToFacebook ID: ' + response.id + ' '+timestamp+'</li>');
          }
        });
    }
    
 
    
    /**
     * This method merely serves to keep the facebook session alive, so it does not timeout
     * @returns {undefined}     */
    function facebookSessionKeepAlive(){
        var timestamp = new Date();
        FB.api('/me', {fields: 'last_name'}, function(response) {
          if (!response || response.error) {
            $('#fbstatuslist').append('<li>facebookSessionKeepAlive Error occured:'+response.error.message+' '+timestamp+'</li>');
            recoverFromError();
          } 
          else
          {
              $('#fbstatuslist').append('<li>KEEPALIVE TIME:'+timestamp+' RESPONSE:'+JSON.stringify(response)+'</li>'); 
          }
        });
    }

    
    
    /** 
     * This function attempts to recover from an error
     * @returns {undefined}
     */
    function recoverFromError()
    {
        //kill the current timer
        stopInterval();
        
        //TODO SAVE ERROR INFORMAITON SOMEWHERE
        
        var timestamp = new Date();
        $('#fbstatuslist').append('<li>Re-loading page to attempt recovery '+timestamp+'</li>');
        
        //reload the page
        location.reload();
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


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////UNUSED
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////UNUSED
    
       
    
    //CANT DO THIS, REQUIRES USERS_GROUPS, WHICH IS ONLY MEANT FOR FACEBOOK CLIENTS ON PLATFORMS WHERE THERE IS NONE
    /**
     * This method will upload a photo on the users behalf to a group
     * @param {type} imageUrl
     * @returns {undefined}     */
    function uploadPhotoToFacebookOmlb(imageUrl){
        var timestamp = new Date();
        
        FB.api('/191968037495092/feed', 'post', { message: imageUrl, link:imageUrl }, function(response) {
          if (!response || response.error) {
            $('#fbstatuslist').append('<li>uploadPhotoToFacebookOmlb Error occured:'+response.error.message+' '+timestamp+'</li>');
          } else {
            $('#fbstatuslist').append('<li>uploadPhotoToFacebookOmlb ID: ' + response.id + ' '+timestamp+'</li>');
          }
        });
    }
    

    
    //CANT DO THIS, REQUIRES USERS_GROUPS, WHICH IS ONLY MEANT FOR FACEBOOK CLIENTS ON PLATFORMS WHERE THERE IS NONE
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
        FB.api('/191968037495092/feed', 'post', { message: statusMessage }, function(response) {
          if (!response || response.error) {
            $('#fbstatuslist').append('<li>postStatusToFacebookOmlb Error occured:'+response.error.message+' '+timestamp+'</li>');
          } else {
            $('#fbstatuslist').append('<li>postStatusToFacebookOmlb ID: ' + response.id + ' '+timestamp+'</li>');
          }
        });
    }
    
    /**
     * This method will run a custom facebook api call
     * @returns {undefined}     */
    function scratch(){
        FB.api('/313673912126184/photos', 'post', { url: imageUrl, message: imageUrl }, function(response) { 
          if (!response || response.error) {
            $('#fbstatuslist').append('<li>SCRATCH Error occured:'+response.error.message+' '+timestamp+'</li>');
//            recoverFromError();
          } else {
            $('#fbstatuslist').append('<li>SCRATCH ID: ' + response.id + ' '+timestamp+'</li>');
          }
        });
    }
    
    
    
    /**
     * DOESN'T WORK RIGHT
     * This method will upload a video on the users behalf doesn 
     * @param {type} videoUrl
     * @returns {undefined}     */
    function uploadVideoToFacebook(videoUrl){
        var timestamp = new Date();
        
        //DOESNT EMBED VIDEO
//        FB.api('/me/feed', 'post', { source: videoUrl }, function(response) {
//          if (!response || response.error) {
//            $('#fbstatuslist').append('<li>uploadVideoToFacebook Error occured:'+response.error.message+' '+timestamp+'</li>');
//          } else {
//            $('#fbstatuslist').append('<li>uploadVideoToFacebook ID: ' + response.id + ' '+timestamp+'</li>');
//          }
//        });
//        
//        //GET ERROR
//        FB.api('/me/videos', 'post', { source: videoUrl, name: videoUrl  }, function(response) {
//          if (!response || response.error) {
//            $('#fbstatuslist').append('<li>uploadVideoToFacebook Error occured:'+response.error.message+' '+timestamp+'</li>');
//          } else {
//            $('#fbstatuslist').append('<li>uploadVideoToFacebook ID: ' + response.id + ' '+timestamp+'</li>');
//          }
//        });
    }
    


    //THIS ALBUM DOESN'T WORK, DOESN'T HAVE can_upload
    /**
     * This method will upload a photo to sk8creteordies cover photos album
     * @param {type} imageUrl
     * @returns {undefined}     */
    function uploadPhotoToCoverPhotosAlbum(imageUrl){
        var timestamp = new Date();
        
        
        
        FB.api('/313673912126184/photos', 'post', { url: imageUrl, message: imageUrl }, function(response) { //THIS ALBUM DOESN'T WORK, DOESN'T HAVE can_upload
          if (!response || response.error) {
            $('#fbstatuslist').append('<li>uploadPhotoToCoverPhotosAlbum Error occured:'+response.error.message+' '+timestamp+'</li>');
//            recoverFromError();
          } else {
            $('#fbstatuslist').append('<li>uploadPhotoToCoverPhotosAlbum ID: ' + response.id + ' '+timestamp+'</li>');
          }
        });
    }