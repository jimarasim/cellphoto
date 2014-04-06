<?php

function PostToOmlb($imageUrl)
{
	  //POST LOGIN TO PHPBB
	
	  //url to execute for curl session; to login to omlb/chickendinner phpbb
    $urllogin ="http://www.omlb.com/chickendinner/ucp.php?mode=login";
	
	  //initialize curl session
    $ch = curl_init();

	  //specify url for curl session
    curl_setopt($ch, CURLOPT_URL,$urllogin);  
      
    //setup an array of post variables needed to login
    $post_fields =array( 
         'username'   => 'sk8creteorbot', 
         'password'   => 'sk8omlb', 
         'autologin'  => 0, 
         'login'      => 'Login', 
         'redirect'   => './index.php?'
            ); 

	  //setup post parameters to send in curl session
    curl_setopt ($ch,CURLOPT_POST, TRUE);
    curl_setopt ($ch,CURLOPT_POSTFIELDS,$post_fields);
    
    //cookies file for read / write
    curl_setopt($ch, CURLOPT_COOKIE, "cellphotocookie.txt"); 
    curl_setopt($ch, CURLOPT_COOKIEJAR, "cellphotocookie.txt"); 
	  curl_setopt($ch, CURLOPT_COOKIEFILE, "cellphotocookie.txt"); 
	        
	  //return resultant page upon curl_exec
	  curl_setopt ($ch,CURLOPT_RETURNTRANSFER, TRUE);

	  //execute the curl session and get resultant web page		
    $result=curl_exec($ch);
    if($meta_refresh_start = strpos($result,"<meta http-equiv=\"refresh\""))
    {
      $meta_refresh_stop = strpos($result,">",$meta_refresh_start);
      $result = substr_replace($result,"",$meta_refresh_start,$meta_refresh_stop-$meta_refresh_start+1);
    }
    //echo("<hr size=10><h1>result</h1>".$result."<hr size=10>");

    /////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //grab the sid "sid=(32 char num)" from resultant page (needed to post later on phpbb)
	  $sid = substr($result,strpos($result,"sid=")+4,32);
    //echo("sid=".$sid."<br />");    
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    //GET HIDDEN VARS FOR POSTING REPLY
    $urlpostreplypage = "http://www.omlb.com/chickendinner/posting.php?mode=reply&f=7&t=95";
    curl_setopt($ch, CURLOPT_URL,$urlpostreplypage);  
    //follow any redirect
    curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
    //execute the curl session and get resultant page with hidden vars in it
    $result2=curl_exec($ch);
    //echo("<hr size=10><h1>result2</h1>".$result2."<hr size=10>");
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    //load dom document for parsing hidden values needed to post (generates warnings of malformed page, oh well)
    $postDOM = new DOMDocument();
    $postDOM->loadHTML($result2);
    $inputNODES = $postDOM->getElementsByTagName("input");
    foreach($inputNODES as $domElement)
    {
      //echo($domElement->getAttribute('name').":".$domElement->getAttribute('value')."<br />");
      switch($domElement->getAttribute('name'))
      {
        case "topic_cur_post_id":
          $topic_cur_post_id=$domElement->getAttribute('value');
          break;
        case "lastclick":
          $lastclick=$domElement->getAttribute('value');
          break;
        case "creation_time":
          $creation_time=$domElement->getAttribute('value');
          break;
        case "form_token":
          $form_token=$domElement->getAttribute('value');
          break;       
      }    
    }
   
    ///////////////////////////////////////////////////////////////////////////////////////////////////   
    //POST REPLY TO SPECIFIC THREAD
    //try posting a reply to my thread  
    curl_setopt ($ch,CURLOPT_REFERER,$urlpostreplypage);
 
    $urlsendreply = "http://www.omlb.com/chickendinner/posting.php?mode=reply&f=7&sid=".$sid."&t=95";  
    curl_setopt ($ch,CURLOPT_URL,$urlsendreply);
    
    if(strpos($imageUrl,".jpg") || strpos($imageUrl,".gif") || strpos($imageUrl,".png"))
    {
      $post_fields["message"]="[img]".$imageUrl."[/img]";
    }
    else if(strpos($imageUrl,".mov"))
    {
      $post_fields["message"]="[quicktime=361,499]".$imageUrl."[/quicktime]";    
    }
    else if(strpos($imageUrl,".m4a"))
    {
      $post_fields["message"]="[quicktime=641,17]".$imageUrl."[/quicktime]";    
    }
    else if(strpos($imageUrl,".3gp"))
    {
       $post_fields["message"]="[quicktime=178,163]".$imageUrl."[/quicktime]";   
    }
    else
    {
      $post_fields["message"]="[url]".$imageUrl."[/url]";
    }
    $post_fields["subject"]="bot post";
    $post_fields["post"]="Submit";
    
    $post_fields["topic_cur_post_id"]=$topic_cur_post_id;
    $post_fields["creation_time"]=$creation_time;
    //make last click look like it was 3 seconds ago
    $post_fields["lastclick"]=$lastclick;
    $post_fields["form_token"]=$form_token;

    //$post_fields["icon"]=1;
    //note, just putting these in the post enables them regardless of value
    //$post_fields["disable_bbcode"]=0;
    //$post_fields["disable_smilies"]=0;
    //$post_fields["disable_magic_url"]=0;
    //$post_fields["attach_sig"]=1;
    //$post_fields["lock_topic"]="false";
    $post_fields["fileupload"]="";
    $post_fields["filecomment"]="";
    
    $query = http_build_query($post_fields);
    //echo("QUERY:".$query."<br />");
        
    curl_setopt ($ch,CURLOPT_POSTFIELDS,$query);
    
    curl_setopt($ch,CURLOPT_HEADER,TRUE);
 
    echo("<HR>TIME OUT FOR 3 SECONDS (LIKE A HUMAN) BEFORE POSTING<HR>");
    sleep(3); 
 
    $result3=curl_exec($ch);
    
    if($result3==FALSE)
    {
      echo("FUCK curl_exec RETURNED FALSE:".curl_errno($ch)."<br />");
    }
      
    //echo("<B>CURLOPT_URL:</B> ".$urlsendreply."<br />");
    
    //$info=curl_getinfo($ch);
    //foreach($info as $key=>$value)
    //{
      //echo("<B>CURL INFO:</B> ".$key."=>".$value."<br />");
    //}
    
    //remove <meta http-equiv="refresh" from stream so we dont get redirected when we see the resultant page
    //(note: session needs to be open (this script needs to be running) to keep log in alive
    if($meta_refresh_start = strpos($result3,"<meta http-equiv=\"refresh\""))
    {
      $meta_refresh_stop = strpos($result3,">",$meta_refresh_start);
      $result3 = substr_replace($result3,"",$meta_refresh_start,$meta_refresh_stop-$meta_refresh_start+1);
    }
    
    echo("<hr size=10><h1>result3</h1>".$result3."<hr size=10>");
    
    /////////////////////////////////////////////////////////////////////////////////////////////////////
    //LOG THE FUCK OUT
    $urllogout = "http://www.omlb.com/chickendinner/ucp.php?mode=logout&sid=".$sid;
    curl_setopt($ch, CURLOPT_URL,$urllogout);  
    $result4=curl_exec($ch);
    
    //remove <meta http-equiv="refresh" from stream so we dont get redirected when we see the resultant page
    //(note: session needs to be open (this script needs to be running) to keep log in alive
    if($meta_refresh_start = strpos($result4,"<meta http-equiv=\"refresh\""))
    {
      $meta_refresh_stop = strpos($result4,">",$meta_refresh_start);
      $result4 = substr_replace($result4,"",$meta_refresh_start,$meta_refresh_stop-$meta_refresh_start+1);
    }
    
    echo("<hr size=10><h1>result4</h1>".$result4."<hr size=10>");

	  curl_close($ch);
}	
?>
