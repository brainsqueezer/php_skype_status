<?php
/**
 * The class class.phpSkypeStatus.php
 * 
 * The class.phpSkypeStatus.php class is for viewing the online status of a skype user
 * please read: http://skype.com/share/buttons/status.html
 * 
 * @package phpSkypeStatus
 * @author Bastian Gorke <b.gorke@ipunkt.biz>
 * @version 2006-03-01
 * 
 * History:
 * 	2006-03-01: bug fix
 * 				x error with getting xml/image is not longer shown
 * 	2006-02-13: initial version
 */
class	phpSkypeStatus
{
	var $skypeid;
	
	var $statusuri = "http://mystatus.skype.com/%s.xml";
	var $statusimguri = "http://mystatus.skype.com/%s/%s";
	
	var $str_status_xml = '';
	
	function phpSkypeStatus($id = ""){
		if ($id != "") {
			$this->setSkypeID($id);
		}
	}
	
	/**
	 * set the skypeid for a user to check
	 */
	function setSkypeID($id){
		$this->skypeid = $id;
	}
	
	/**
	 * get status from skype mystatus server
	 */
	function _retrieveStatus(){
		$this->str_status_xml =  @file_get_contents(sprintf($this->statusuri,$this->skypeid));
	}
	
	/**
	 * returns the unprocessed xml/rdf data
	 */
	function getXML(){
		$this->_retrieveStatus();
		return $this->str_status_xml;
	}
	
	/**
	 * returns status text in specified language. defaults to english
	 * 
	 * English	en
	 * Deutsch	de
	 * Francais	fr
	 * italian	it
	 * polish	pl
	 * Japanese	ja
	 * Spanish	es
	 * Pt		pt
	 * Pt/br	pt-br
	 * Swedish	se
	 * zh		zh-cn
	 * Cn		zh-cn
	 * Zh/cn	zh-cn
	 * hk		zh-tw
	 * tw		zh-tw
	 * Zh/tw	zh-tw
	 */
	function getText($lang = "en"){
		$match = array();
		$this->_retrieveStatus();
		$pattern = "~xml:lang=\"".strtolower($lang)."\">(.*)</~";
		preg_match($pattern,$this->str_status_xml, $match);
		return $match[1];
	}
	
	/**
	 * get the status number
	 * 
	 * 0	UNKNOWN			Not opted in or no data available.
	 * 1	OFFLINE			The user is Offline
	 * 2	ONLINE			The user is Online
	 * 3	AWAY			The user is Away
	 * 4	NOT AVAILABLE	The user is Not Available
	 * 5	DO NOT DISTURB	The user is Do Not Disturb (DND)
	 * 6	INVISIBLE		The user is Invisible or appears Offline
	 * 7	SKYPE ME		The user is in Skype Me mode
	 */
	function getNum(){
		$match = array();
		$this->_retrieveStatus();
		$pattern = "~xml:lang=\"NUM\">(\d)</~";
		preg_match($pattern,$this->str_status_xml, $match);
		return $match[1];
	}
	
	/**
	 * returns a php image ressource defined by type
	 * {@see #getImagePNG($type)}
	 * 
	 * TODO at this moment, only supports english language images
	 * 
	 * type-modes:
	 * balloon			- Balloon style
	 * bigclassic		- Big Classic Style
	 * smallclassic		- Small Classic Style
	 * smallicon		- Small Icon (transparent background)
	 * mediumicon		- Medium Icon
	 * dropdown-white	- Dropdown White Background
	 * dropdown-trans	- Dropdown Transparent Background
	 * 
	 * defaults to smallicon
	 */
	function getImageRessource($type = "smallicon"){
		$im = @ImageCreateFromPNG(sprintf($this->statusimguri,$type,$this->skypeid));
		if (!$im) return null;
		return $im;
	}
	
	/**
	 * outputs the statusimage as png to browser
	 * {@see #getImageRessource($type)}
	 * 
	 * TODO at this moment, only supports english language images
	 * 
	 * type-modes:
	 * balloon			- Balloon style
	 * bigclassic		- Big Classic Style
	 * smallclassic		- Small Classic Style
	 * smallicon		- Small Icon (transparent background)
	 * mediumicon		- Medium Icon
	 * dropdown-white	- Dropdown White Background
	 * dropdown-trans	- Dropdown Transparent Background
	 * 
	 * defaults to smallicon
	 */
	function getImagePNG($type = "smallicon"){
		$png = $this->getImageRessource($type);
		@imagepng($png);
	}
}
?>