<?php
require_once("class.phpSkypeStatus.php");

$skypeid="bastian.gorke";

// new status
$status = new phpSkypeStatus($skypeid);

// if param image = 1 return just the image
if ($_GET['image'] == "1"){
	$status->getImagePNG("smallclassic");
}
echo "<html><head><title>".$skypeid." status is ".$status->getText()."</title></head><body>";
echo "<h1>".$skypeid." status is ".$status->getText()."</h1>";
echo "<p>This is a simple example for the phpSkypeStatus class. As you can see my status is: <img src=\"".$PHPSELF."?image=1\" /></p>";
echo "<p>In my language german this status is named ".$status->getText("de")."</p>";
echo "</body></html>";
?>