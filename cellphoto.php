<?php
    include 'cellphotomail.class'; 
?>
<html>
<head>
    <title>cellphoto</title>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
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
        
        
	
        
?>
</body>
</html>