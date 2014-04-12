<html>
<head>
    <title>cellphoto</title>
</head>
<body>
    <!--NOTE: /CELLPHOTO, /CELLPHOTO/CELLPHOTO, AND /CELLPHOTO/CELLPHOTO/THUMBS NEED TO BE WRITEABLE-->
    <H1><A href="cellphotoview.php">View Cell Photos</a></h1>
<?php
	include 'cellphotomail.class'; 
	echo('CELLPHOTO<BR />');
        
	$myCellPhotoMail = new CellPhotoMail(null,null,null,null,null);
        
        $myCellPhotoMail->ProcessPhotos();
	
        
?>
</body>
</html>