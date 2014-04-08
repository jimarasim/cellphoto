<html>
<head>
    <title>cellphoto</title>
</head>
<body>
    <!--NOTE: /CELLPHOTO, /CELLPHOTO/CELLPHOTO, AND /CELLPHOTO/CELLPHOTO/THUMBS NEED TO BE WRITEABLE-->
<?php
	include 'cellphotomail.class'; 
	
        echo("instantiate<br />");
	$myCellPhotoMail = new CellPhotoMail(null,null,null,null,null);
	
        
        echo("connect<BR />");
	$myCellPhotoMail->Connect() or exit();
	
	//$myCellPhotoMail->GetAllMessages() or exit();
		
	echo("get unseen messages<BR />");
	$myCellPhotoMail->GetUnseenMessages() or exit();
        
        echo("display message parts<BR />");
	$myCellPhotoMail->DisplayMessageParts();
	
        echo("ShowPlainAndHtmlParts");
        $myCellPhotoMail->ShowPlainAndHtmlParts();
        
        echo("save media");
	$myCellPhotoMail->SaveMedia();
        
?>
</body>
</html>