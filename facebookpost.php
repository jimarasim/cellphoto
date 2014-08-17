<html>
<head>
    <title>Sk8CreteOrBot - Facebook Photo Publisher</title>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="facebookclient.js"></script>
    <link rel="Stylesheet" href="stylebase.css" />
</head>
<body>    
<div id="importantLinks"></div>
<table>
<tr>
<td>
User:
</td>
<td>
<input id='loginbutton' type='button' onclick='loginToFacebook()' value='Login to Facebook' style='display:none;'/>
<span id="USER"></span>
</td>
</tr>
<tr>
<td>
UID:
</td>
<td>
<span id="UID"></span>
</td>
</tr>
<tr>
<td>
ACCESSTOKEN:
</td>
<td>
<span id="ACCESSTOKEN"></span>
</td>
</tr>
<tr>
<td>
Last Update Time:
</td>
<td>
<span id="lastupdatetime"></span>
</td>
</tr>
<tr>
<td>
Last Response:
</td>
<td>
<span id="lastresponse"></span>
</td>
</tr>
<tr>
<td>
<input id='stoptimerbutton' type='button' onclick='stopInterval()' value='Stop timer' style='display:none;' />
</td>
<td>
<ul id="fbstatuslist"></ul>
</td>
</tr>
</table>
<a href='privacypolicy.html' target='_blank'>Privacy Policy</a>
<br />This site will never have advertising nor anything for sale.<br />





</body>
</html>



