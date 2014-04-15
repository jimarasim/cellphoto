<?php
include '../facebook-php-sdk/src/facebook.php'; 
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once("facebook.php");

$config = array(
            'appId' => '709181395771740',
            'secret' => '7c288e7bd60860cd1306263267ff17a6',
            'fileUpload' => false, // optional
            'allowSignedRequest' => false, // optional, but should be set to false for non-canvas apps
        );

$loginParams = array(
                'scope' => 'read_stream, friends_likes',
                'redirect_uri' => GetCurrentUrl()
            );

$logoutParams = array( 'next' => GetCurrentUrl().'?logout=true' );

$facebook = new Facebook($config);

?>

<html><head><title></title></head><body>
<?php
    try
    {
        //check if we just logged out
        if(isset($_GET['logout'])){
            if($_GET['logout']=='true'){
                session_destroy();   
            }
        }
        //get the user id
        $user_id = $facebook->getUser();
        
        if($user_id)
        {
            //dispaly user id
            echo("User ID:".$user_id."<br />");
            
            //get facebook user credentials
            $user_profile = $facebook->api('/me','GET');
            echo "Name: " . $user_profile['name']."<br />";
            
            //logout link
            $logoutUrl = $facebook->getLogoutUrl($logoutParams);
            echo("<a href='".$logoutUrl."'>Facebook Logout</a><br />");
        }
        else    
        {
            echo("NO USER ID<br />");
            LoginFacebook($facebook,$loginParams);
        }
    }
    catch(FacebookApiException $fex)
    {
        echo("NO PROFILE<br />");
        LoginFacebook($facebook,$loginParams);
    }
    catch(Exception $ex)
    {
        echo($ex->getMessage());
    }

?>
</body></html>

<?php
/**
 * this function gets the current url
 */
function GetCurrentUrl()
{
    $HTTPS = filter_input(INPUT_SERVER, 'HTTPS');
    $HTTP_HOST = filter_input(INPUT_SERVER, 'HTTP_HOST');
    $REQUEST_URI = filter_input(INPUT_SERVER, 'REQUEST_URI');
    
    $protocol = (!empty($HTTPS) && $HTTPS == 'on') ?'htts://':'http://';
    
    $currentUrl = $protocol.$HTTP_HOST.$REQUEST_URI;
    
    return $currentUrl;
}

/**
 * this function logs into facebook
 */
function LoginFacebook($facebook,$loginParams)
{
    $loginurl = $facebook->getLoginUrl($loginParams);
    echo("Please Login through Facebook:<a href='".$loginurl."'>Facebook Login</a><br />");
}


