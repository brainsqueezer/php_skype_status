<?php
require_once ("class.phpSkypeStatus.php");

if (!isset ($_GET['skype'])) {
	echo "<form action=\"$PHPSELF\" method=\"GET\">";
	echo "		SkypeUser: <input type=\"text\" name=\"skype\"/>";
	echo "</form>";
	die();
}

$skypeid = $_GET['skype'];

// new status
$status = new phpSkypeStatus($skypeid);

// if param image = 1 return just the image
if ($_GET['image'] == "1") {
	$status->getImagePNG("smallicon");
}

$statusText = $status->getText();
echo<<<EOF
<html>
<head>
	<title>$skypeid status is $statusText</title>
	<style>
	a {
		color: #776644;
		font-weight: bold;
		text-decoration: none;
	}
	</style>
</head>
<body>
<script type="text/javascript" src="/js/skypeCheck.js"></script>
<h1>Status of $skypeid  is $statusText</h1>
<p>This is an advanced example for the phpSkypeStatus class.<br/>
It shows a command in dependence on the status of the user:<br/>
<a href="skype:$skypeid?userinfo" onclick="return skypeCheck();"><img name="skypestatus" src="$PHPSELF?image=1&skype=$skypeid" width="16" height="16" border="0" align="absmiddle" style="padding-bottom: 4px;" /></a>&nbsp;Skype:<br/>
EOF;

$fields = array ();
switch ($status->getNum()) {
	case 0 :
		echo "Sorry, status could not detected. See <a href=\"http://www.skype.com/share/buttons/status.html\">Information about SkypeWebStatus</a>";
		break;
	case 2 :
		// ONLINE
	case 7 :
		// SKYPE ME
		$fields[] = array ("call", "Call");
		$fields[] = array ("sendfile", "Send a file");
	case 3 :
		// AWAY
	case 4 :
		// NOT AVAILABLE
		$fields[] = array ("chat", "Send a textmessage");
	case 5 :
		// DO NOT DISTURB
	case 1 :
		// OFFLINE
	case 6 :
		// INVISIBLE
		$fields[] = array ("add", "Add to contact list");
		$fields[] = array ("userinfo", "Show Skype profile");
		break;
	default :
		$fields = array ();
		break;
}
drawSelectBox($fields);
echo "</p>";
echo "</body></html>";

/*
 * function to draw with special fields
 */
function drawSelectBox($fields = array ()) {
	if (empty ($fields))
		return;
	foreach ($fields as $field) {
		echo "<a href=\"skype:{$GLOBALS['skypeid']}?{$field[0]}\" onclick=\"return skypeCheck();\">{$field[1]}</a><br/>\n";
	}
}
?>