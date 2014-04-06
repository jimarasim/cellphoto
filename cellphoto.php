<html>
<head>
    <title>cellphoto</title>
</head>
<body>
<?php
	include 'cellphotomail.class'; 
	
	$myCellPhotoMail = new CellPhotoMail(null,null,null,null,null);
	
        echo("instantiated now<br />");
        
	$myCellPhotoMail->Connect();// or exit();
	
        echo("connected");
	//$myCellPhotoMail->GetAllMessages() or exit();
		
	$myCellPhotoMail->GetUnseenMessages() or exit();
	echo("get unseen messages");
        
	$myCellPhotoMail->DisplayMessageParts();
	
        echo("display message parts");
        
	$myCellPhotoMail->SaveMedia();
        
        echo("save media");
?>
</body>
</html>