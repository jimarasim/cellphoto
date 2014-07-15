<?php
    include 'cellphotomail.class'; 
?>
<html>
<head>
    <title>cellphoto</title>
</head>
<body>
    <!--NOTE: /CELLPHOTO, /CELLPHOTO/CELLPHOTO, AND /CELLPHOTO/CELLPHOTO/THUMBS NEED TO BE WRITEABLE-->
    <H1><A href="cellphotoview.php">View Cell Photos</a></h1>
<?php
	
	echo('CELLPHOTO<BR />');
        
        //get a cell photo mail object
	$myCellPhotoMail = new CellPhotoMail(null,null,null,null,null);
        
        //check for and publish photos
        $myCellPhotoMail->ProcessPhotos();
        
        //post to facebook (see docs, downloaded facebook sdk)
        //FacebookSession::setDefaultApplication('YOUR_APP_ID', 'YOUR_APP_SECRET');
	
        
?>
</body>
</html>