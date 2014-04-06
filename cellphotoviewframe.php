<html>

<head>
    <title>cellphotoview</title>
    <style type="text/css">
	body
	{
		background-color: #000000;
		color: #ffffff;	
	}
	</style>
</head>
<body>
<center>
<?php
	//show the picture
	echo("<img src='cellphoto/".$_POST['filename']."' /><br />");
	
	//check for additional information in cellphoto.xml
	if(file_exists('cellphoto.xml'))
	{
		//load dom from cellphoto.xml and get the root
	    $DOMDOC = new DOMDocument();
	    $DOMDOC->load('cellphoto.xml');
    	
	    //document is loaded, get all <file> elements
	    $FILENODES = $DOMDOC->getElementsByTagName('file');
	    
	    //comb through FILENODES for this photo
	    foreach($FILENODES as $filenode)
	    {
			if($filenode->nodeValue == $_POST['filename'])
			{
				//found it...print <subject>, <from>, and <date>
				echo($filenode->nextSibling->nodeValue."<br />");
				echo($filenode->nextSibling->nextSibling->nodeValue."<br />");
				echo($filenode->nextSibling->nextSibling->nextSibling->nodeValue."<br />");
				break;
			}
	    }
	    
	}
	
	
?>
</center>
</body>
</html>