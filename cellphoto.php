<html>
<head>
    <title>cellphoto</title>
</head>
<body>
    <!--NOTE: /CELLPHOTO, /CELLPHOTO/CELLPHOTO, AND /CELLPHOTO/CELLPHOTO/THUMBS NEED TO BE WRITEABLE-->
<?php
	include 'cellphotomail.class'; 
	echo('CELLPHOTO<BR />');
        
	$myCellPhotoMail = new CellPhotoMail(null,null,null,null,null);
        
        $myCellPhotoMail->ProcessPhotos();
	
        
?>
</body>
</html>