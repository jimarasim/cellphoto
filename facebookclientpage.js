/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    var skateCreteOrDiePageId = "352440234923972";
    var skateCreteOrDieMessage = "Text skate photos to cellphoto@seattlerules.com";
    var intervalFunction; //set at runtime, so can be cleared
    var timeout = 5000;
    var accessToken = 0;
//REMOVING , TO MAKE IT EASIER TO GET APPROVED
//    var requestedPermissions = 'publish_actions, user_photos, user_groups, user_videos';
    var requestedPermissions = 'publish_actions, user_photos, manage_pages';
    
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
          accessToken = response.authResponse.accessToken;

            FB.api('/me', {fields: 'last_name,first_name' }, function(response) {
                
                var timestamp = new Date();
                $('#USER').text(response.first_name+" "+response.last_name+" "+timestamp);
                $('#UID').text(uid);

                //clear the log list
                $('#fbstatuslist').empty();

                //don't do anything else, unless jim arasim logged in
                //or stu_dfoxorp_stuverson@tfbnw.net Test@Test1 uid==="1448832595399461"
                //i think this is jim arasimif(uid==="537299124")
                if(response.first_name==="SkateCrete" ||
                   (response.first_name==="Jim"&&response.last_name==="Arasim"))
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
                    var photoLink = val.photo;
                    $("#fbstatuslist").append("<li>Posting:<a href='"+photoLink+"' target='_blank'>"+photoLink+"</a></li>");
                    postStatusToFacebookPage(photoLink,skateCreteOrDieMessage);
                        
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
    function postStatusToFacebookPage(photoLink, photoMessage){
        var timestamp = new Date();
        
        var params = new Object();
        params.link=photoLink;
        params.message=photoMessage;
        params.access_token=accessToken;
        
        FB.api('/'+skateCreteOrDiePageId+'/feed', 'post', params, function(response) {
          if (!response || response.error) {
            $('#fbstatuslist').append('<li>postStatusToFacebook Error occured:'+response.error.message+' '+timestamp+'</li>');
          } else {
            var linkToPost="https://www.facebook.com/"+skateCreteOrDiePageId+"/posts/"+response.id.substring(response.id.indexOf("_")+1);
            $('#fbstatuslist').append("<li>postStatusToFacebook ID: <a href='" + linkToPost + "' target='_blank'>"+response.id + "</a> Access Token:"+accessToken+" "+timestamp+"</li>");

          }
        });
    }


    
    
    /**
     * This method will upload a photo on the users behalf
     * @param {type} imageUrl
     * @returns {undefined}     */
    function uploadPhotoToFacebookPage(imageUrl){
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


