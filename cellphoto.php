<?php
    include 'cellphotomail.class'; 
    
    $jsonImageLinks=filter_input(INPUT_GET,('jsonImageLinks'));
    $faceBookAppId=filter_input(INPUT_GET,('fbai'));
    
    if(isset($jsonImageLinks)){
        getJsonImageLinks();
    }
    //return the facebook app id for sk8creteorbot
    elseif(isset($faceBookAppId)){
        
        $requestOrigin = filter_input(INPUT_SERVER, 'HTTP_REFERER');
        
        //only return app id if this request is coming from an expected origin
        if(strpos($requestOrigin,"localhost/~jameskarasim/cellphoto/facebookpost.php")!==FALSE
                ||
           strpos($requestOrigin,"seattlerules.com/cellphoto/facebookpost.php")!==FALSE
                ){
           
            echo('709181395771740');
        }
        
    }
    else{
        processPhotosOnly();
    }
        
    /**
     * This function will retrieve and save images, and return a json array of images saved
     */
    function getJsonImageLinks(){
//        echo('{"jsonarray":["key1":"value1","key2":"value2"]}');
        //get a cell photo mail object
	$myCellPhotoMail = new CellPhotoMail(null,null,null);
        
        //check for and publish photos, and return json array of images
        echo($myCellPhotoMail->getJsonImagePaths());
    }
    
    /**
     * This function will retrieve and save images, and produce html output
     */
    function processPhotosOnly(){
        //html header
        echo("<html><head><title>Sk8CreteOrBot - Cellphoto Mail Checker</title><script src='https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script></head><body>");
        
        echo("<H1><A href='cellphotoview.php'>View Cell Photos</a></h1>");
        
        
        //get a cell photo mail object
	$myCellPhotoMail = new CellPhotoMail(null,null,null);
        
        //check for and publish photos
        $myCellPhotoMail->ProcessPhotos();
        
        //html footer
        echo("</body></html>");
    }
