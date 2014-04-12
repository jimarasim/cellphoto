<html>

<head>
<title>cellphotoview</title>
<style type="text/css">

body.visible
{
	background-color: #000000;
	color: #ffffff;	
}

table.invisible
{
	display:none;
}

table.visible
{
	background-color: #000000;
	color: #ffffff;
	position:absolute;
	width:100%;
	height:1050px;
	z-index:1;
}

a
{
	font-size:25px;
	font-weight:bold;
	color: #0000ff;
	/*take away the links underline*/
	text-decoration:none;  
}

a:hover
{
	color: #00ff00;	
}

table
{
	width:100%;
}

td
{
	text-align: center;
}
</style>
<script type="text/javascript">

//preload thumbnails on the page before page appears
function preload_images()
{
	var preloader;
	var img;
		
	if(document.images)
	{	
		for(img=0;img<document.images.length;img++)
		{
			preloader=new Image();
			preloader.src = document.images[img].src;
		}	
	}
	
	document.getElementById("loadingtable").className="invisible";
}

//used by movie buttons to show movies
function showDiv(id)
{
	if(document.getElementById(id).style.display=="none")
	{
		document.getElementById(id).style.display="block";
	}
	else
	{
		document.getElementById(id).style.display="none";
	}
}

function setstartimage()
{
	var startnumber = document.getElementById('selectstartimage').selectedIndex*10;
	
        //construct url
        var baseUrl=document.URL;
        if(baseUrl.indexOf("?")>-1)
        {
            baseUrl=baseUrl.substr(0,baseUrl.indexOf("?"));
        }
        
        window.location=baseUrl+"?start="+startnumber;
}

</script>
</head>
<body class="visible" onload="javascript:preload_images();">

<center>
<h3>Text photos to jaemzware@gmail.com</h3>
<?php
$cellphotodir = "cellphoto";
$files = array();


//get array of files to display
if ($handle = opendir($cellphotodir)) 
{
	while (false !== ($file = readdir($handle)))
	{
		//only display supported file extensions
		if (strpos($file,".jpg") || strpos($file,".mov") || strpos($file,".gif") || strpos($file,".3gp") || strpos($file,".png") || strpos($file,".m4a"))
		{
			//don't display "small_" versions
			if(!strpos($file,"mall_"))
			{
				$files[] = $file;
			}
		}
	}
	closedir($handle);
}

//check if any files were found
if(count($files)<=0)
{
    echo('NO IMAGES FOUND');
    return;
}

ksort($files);	

//only display 20 images at a time
$lastimage = count($files)-1;
$startimage = isset($_GET['start'])?$_GET['start']:0;
$endimage = ($lastimage>($startimage+19))?$startimage+19:$lastimage;

//provide linke for previous page of images
if(!$startimage==0)
{
	$prev = ($startimage-20>0)?$startimage-20:0;
	echo("<a href='cellphotoview.php?start=".$prev."'>prev</a>");
}
else
{
	echo("<a href='cellphotoview.php'>prev</a>");
}

//dropdown to select images being shown
echo("<select id=\"selectstartimage\" onchange=\"javascript:setstartimage();\">");
for($i=0;$i<=$lastimage;$i+=10)
{
	//make selected option the current startimage
	if($i==$startimage)
	{
		echo("<option SELECTED>".$i."</option>");
	}
	else
	{
		echo("<option>".$i."</option>");
	}
}
echo("</select>");
//show range and total of images being shown

echo(" through ".$endimage." of ".$lastimage);


//provide link for next page of images
if($endimage != $lastimage)
{
	echo("<a href='cellphotoview.php?start=".($endimage+1)."'>next</a>");
}
else
{
	echo("<a href='cellphotoview.php'>next</a>");
}

?>
</center>
<table class="visible" id="loadingtable"><tr><td valign="top">loading...</td></tr></table>
<center>
<?php

//movid counter for movie show/hide divs
$movid=0;
//max size of thumb nails
$max=120;

echo("<table>");
echo("<tr>");

foreach($files as $increment => $name)
{
	//only display 20 images at a time
	if(($increment >= $startimage)&&($increment <= $endimage))
	{	
		//display in rows of 5 
		if(($increment!=0)&&($increment%5==0))
		{
			echo("</tr><tr>");
		}

		echo("<td>");
		if((strpos($name,".jpg")) || (strpos($name,".gif")) || (strpos($name,".png")))
		{
	
			//create a thumbnail if one  doesnt exist (and the file isnt a thumb file)
			if((!file_exists($cellphotodir."/thumbs/thumb_".$name)))
			{
	
				// File and new size
				$filename = $cellphotodir."/".$name;
				
				// Get new sizes
				$sizeinfo = getimagesize($filename);
				$width=$sizeinfo[0];
				$height=$sizeinfo[1];
				
				//uncomment to change by percentage
				//$percent = 0.25;
				//$newwidth = $sizeinfo[0] * $percent;
				//$newheight = $sizeinfo[1] * $percent;
				//change to specific max
				
				if($width > $height)
				{
					$newwidth=$max;
					$newheight=($max/$width)*$height;
				}
				else if ($height > $width)
				{
					$newheight=$max;
					$newwidth=($max/$height)*$width;
				}
				else
				{
					$newwidth=$newheight=$max;
				}
	
	
				// Load
				//increase memory size in case this is a 1.0+ jpg
                ini_set('memory_limit','100M');
				$thumb = imagecreatetruecolor($newwidth, $newheight);
				if(strpos($name,".jpg"))
				{
					$source = imagecreatefromjpeg($filename);
				}
				if(strpos($name,".gif"))
				{
					$source = imagecreatefromgif($filename);				
				}
				if(strpos($name,".png"))
				{
					$source = imagecreatefrompng($filename);				
				}
	
				// Resize
				imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $sizeinfo[0], $sizeinfo[1]);
				                
				// Output
				if(strpos($name,".jpg"))
				{
					$saved = imagejpeg($thumb,$cellphotodir."/thumbs/thumb_".$name);
				}
				if(strpos($name,".gif"))
				{
					imagegif($thumb,$cellphotodir."/thumbs/thumb_".$name);				
				}
				if(strpos($name,".png"))
				{
					imagepng($thumb,$cellphotodir."/thumbs/thumb_".$name);				
				}
			}	
			
			//display the clickable thumbnail for the file			
			echo("<form target='_blank' method='post' id='".$name."' action='cellphotoviewframe.php'>");
			echo("<input type='hidden' name='filename' value='".$name."'>");
			echo("<img src='".$cellphotodir."/thumbs/thumb_".$name."' onclick='javascript:document.forms[\"".$name."\"].submit()'/>");
			echo("</form>");
				
		}

		if(strpos($name,".mov") || strpos($name,".3gp") || strpos($name,".m4a"))
		{
			//id for the movie's div tag (file name wasn't jiving)
			$movid+=1;
			
			//set width and height for each type specifically (these are values according to iphone and mom's phone(3gp)
			//set a default in case we screw up and forget to do this later for another format
			$vidWidth=640;
			$vidHeight=480;
			if(strpos($name,".mov"))
			{
				$vidWidth=361;
				$vidHeight=499;
			}
			else if(strpos($name,".3gp"))
			{
				$vidWidth=178;
				$vidHeight=163;
			}
			else if(strpos($name,".m4a"))
			{
				$vidWidth=641;
				$vidHeight=17;
			}
			
			//hide movies, and display them when button is pressed, because the scroll funny
			echo("<input type='button' style='width:".$max."px; height:".$max."px;' onclick='javascript:showDiv(\"".$movid."\");' value='Movie'/>");
			echo("<DIV id='".$movid."' style='display:none;'>");
			echo("<OBJECT CLASSID='clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B' WIDTH='".$vidWidth."' HEIGHT='".$vidHeight."' CODEBASE='http://www.apple.com/qtactivex/qtplugin.cab'>");
			echo("<PARAM name='SRC' VALUE='".$cellphotodir."/".$name."'>");
			echo("<PARAM name='AUTOPLAY' VALUE='false'>");
			echo("<PARAM name='CONTROLLER' VALUE='true'>");
			echo("<EMBED WIDTH='".$vidWidth."' HEIGHT='".$vidHeight."' SRC='".$cellphotodir."/".$name."' AUTOPLAY='false' CONTROLLER='true' PLUGINSPAGE='http://www.apple.com/quicktime/download/' />");
			echo("</OBJECT>");
			echo("</DIV>");
		}
	
		echo("</td>");	
	}
}
echo("</tr>");	
?>
</center>
</body>
</html>