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

$loginparams = array(
                'scope' => 'read_stream, friends_likes',
                'redirect_uri' => GetCurrentUrl()
            );

$facebook = new Facebook($config);

?>

<html><head><title></title></head><body>
<?php
    try
    {
       //get facebook user credentials
        $user_profile = $facebook->api('/me','GET');
        echo "Name: " . $user_profile['name'];
    }
    catch(FacebookApiException $fex)
    {
        LoginFacebook($facebook,$loginparams);
    }
    catch(Exception $ex)
    {
        echo($ex->getMessage());
    }

    echo("FACEBOOK APP!");
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
    return ($protocol.$HTTP_HOST.$REQUEST_URI);
}

/**
 * this function logs into facebook
 */
function LoginFacebook($facebook,$loginparams)
{
    $loginurl = $facebook->getLoginUrl($loginparams);
    header("Location:".$loginurl);
}


