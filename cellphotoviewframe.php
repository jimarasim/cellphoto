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

        $filename = $_POST['filename'];

        //show the small version if it exists
        if(file_exists("cellphoto/small_".$filename))
        {
            echo("cellphoto/small_".$filename."<br /><img src='cellphoto/small_".$filename."' /><br />");
        }
        
	//show the picture
	echo("cellphoto/".$filename."<BR /><img src='cellphoto/".$filename."' /><br />");
        
        
	
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
			if($filenode->nodeValue == $filename)
			{
				//found it...print <subject>, <from>, and <date> and <body>
				echo($filenode->nextSibling->nodeValue."<br />");
				echo($filenode->nextSibling->nextSibling->nodeValue."<br />");
				echo($filenode->nextSibling->nextSibling->nextSibling->nodeValue."<br />");
                                echo($filenode->nextSibling->nextSibling->nextSibling->nextSibling->nodeValue."<br />");
				break;
			}
	    }
	    
	}
	
	
?>
</center>
</body>
</html>