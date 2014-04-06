<html>
<head>
    <title>hotmail</title>
</head>
<body>
<?php
	include 'cellphotomail.class'; 
	
	//IMAP
	//cxn times out (imap may not be well supported yet)
	//$myCellPhotoMail = new CellPhotoMail("{m.live.com:143/ssl}INBOX","jaemzware@hotmail.com","8microsofthealth",null,null);
	//$myCellPhotoMail = new CellPhotoMail("{m.live.com:143/ssl}INBOX","jaemzware@hotmail.com","8microsofthealth",null,null);
	//$myCellPhotoMail = new CellPhotoMail("{imap.live.com:143/ssl}INBOX","jaemzware@hotmail.com","8microsofthealth",null,null);
	
	//POP3 (ssl required)
	//this works for INBOX (the only mailbox listed by GetMailBoxList)
	$myCellPhotoMail = new CellPhotoMail("{pop3.live.com:995/pop3/ssl}INBOX","jaemzware@hotmail.com","8microsofthealth",null,null);
	
	//use to get list of mailboxes (GetMailBoxList())
	//$myCellPhotoMail = new CellPhotoMail("{pop3.live.com:995/pop3/ssl}","jaemzware@hotmail.com","8microsofthealth",null,null);
	
	$myCellPhotoMail->Connect() or exit();
	
	//get a list of mail folders (NOTE: MUST OPEN CONNECTION WITHOUT INBOX (OR ANY BOX) SPECIFIED)
	//$mailBoxes = $myCellPhotoMail->GetMailBoxList();
	//foreach($mailBoxes as $index => $box)
	//{
	//	echo("Mailboxes<br />---------<br />");
	//	echo($index."=>".$box->name);
	//}
	
	$myCellPhotoMail->GetAllMessages() or exit();
		
	//$myCellPhotoMail->GetUnseenMessages() or exit();
	
	$myCellPhotoMail->DisplayMessageParts();
	
	//$myCellPhotoMail->ShowPlainAndHtmlParts();
	
	//$myCellPhotoMail->SaveMedia();
?>
</body>
</html>