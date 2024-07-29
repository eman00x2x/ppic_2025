<?php

namespace Library;

// no direct access
defined( 'ACCESS' ) or die( 'Restricted access' );

class Document {

	var $title = '';
	var $description = '';
	var $_metaTags = array();
	var $_scripts = array();
	var $_script = array();
	var $_styleSheets = array();
	var $_style = array();
	var $_generator = '';
	var $_canonical = '';
	
	var $_mime = '';
	var $_tab = "\11";
	var $_lineEnd = "\12";

	function __construct() {}
	
	function getMetaData($name, $http_equiv = false) {
		$result = '';
		$name = strtolower($name);
		if($name == 'generator') { 
			$result = $this->getGenerator();
		} elseif($name == 'description') {
			$result = $this->getDescription();
		} else {
			if ($http_equiv == true) {
				$result = @$this->_metaTags['http-equiv'][$name];
			} else {
				$result = @$this->_metaTags['standard'][$name];
			}
		}
		return $result;
	}
	
	function setMetaData($name, $content, $http_equiv = false) {
		$name = strtolower($name);
		if($name == 'generator') { 
			$this->setGenerator($content);
		} elseif($name == 'description') {
			$this->setDescription($content);
		} else {
			if ($http_equiv == true) {
				$this->_metaTags['http-equiv'][$name] = $content;
			} else {
				$this->_metaTags['standard'][$name] = $content;
			}
		}
	}

	function setFacebookMetaData($name, $content) {
		$this->_metaTags['facebook'][$name] = $content;
	}
	
	function addScript($url, $type="text/javascript") {
		$this->_scripts[$url] = $type;
	}

	/**
	 * Adds a script to the page
	 *
	 * @access   public
	 * @param	string  $content   Script
	 * @param	string  $type	Scripting mime (defaults to 'text/javascript')
	 * @return   void
	 */
	function addScriptDeclaration($content, $type = 'text/javascript')
	{
		if (!isset($this->_script[strtolower($type)])) {
			$this->_script[strtolower($type)] = $content;
		} else {
			$this->_script[strtolower($type)] .= chr(13).$content;
		}
	}

	/**
	 * Adds a linked stylesheet to the page
	 *
	 * @param	string  $url	URL to the linked style sheet
	 * @param	string  $type   Mime encoding type
	 * @param	string  $media  Media type that this stylesheet applies to
	 * @access   public
	 */
	function addStyleSheet($url, $type = 'text/css', $media = null, $attribs = array())
	{
		$this->_styleSheets[$url]['mime']		= $type;
		$this->_styleSheets[$url]['media']		= $media;
		$this->_styleSheets[$url]['attribs']	= $attribs;
	}

	 /**
	 * Adds a stylesheet declaration to the page
	 *
	 * @param	string  $content   Style declarations
	 * @param	string  $type		Type of stylesheet (defaults to 'text/css')
	 * @access   public
	 * @return   void
	 */
	function addStyleDeclaration($content, $type = 'text/css')
	{
		if (!isset($this->_style[strtolower($type)])) {
			$this->_style[strtolower($type)] = $content;
		} else {
			$this->_style[strtolower($type)] .= chr(13).$content;
		}
	}

	function setCanonical($link) {
		$this->_canonical = $link;
	}
	
	function setDescription($description) {
		$this->description = $description;
	}
	
	function getDescription() {
		return $this->description;
	}
	
	function setGenerator($generator) {
		$this->_generator = $generator;
	}
	
	function getGenerator() {
		return $this->_generator;
	}
	
	function setTitle($title) {
		$this->title = $title;
	}

	function getTitle() {
		return $this->title;
	}
	
	function setTab($string) {
		$this->_tab = $string;
	}
	
	function _getTab() {
		return $this->_tab;
	}
	
	function _getLineEnd() {
		return $this->_lineEnd;
	}
	
	function setLineEnd($style)
	{
		switch ($style) {
			case 'win':
				$this->_lineEnd = "\15\12";
				break;
			case 'unix':
				$this->_lineEnd = "\12";
				break;
			case 'mac':
				$this->_lineEnd = "\15";
				break;
			default:
				$this->_lineEnd = $style;
		}
	}
	
	function setMimeEncoding($type = 'text/html') {
		$this->_mime = strtolower($type);
	}

}


class DocumentRenderer extends Document {

	static function fetchHead($document) {
		//$document =& Factory::getDocument();
		// get line endings
		$lnEnd = $document->_getLineEnd();
		$tab = $document->_getTab();

		$tagEnd	= ' />';

		$strHtml = '';
		
		if($document->_canonical != "") {
			$strHtml .= '<link rel="canonical" href="'.$document->_canonical.'" '.$tagEnd.$lnEnd;
		}

		// Generate META tags (needs to happen as early as possible in the head)
		foreach ($document->_metaTags as $type => $tag) {
			foreach ($tag as $name => $content) {
				
				if ($type == 'http-equiv') {
					$strHtml .= $tab.'<meta http-equiv="'.$name.'" content="'.$content.'"'.$tagEnd.$lnEnd;
				} elseif ($type == 'standard') {
					$strHtml .= $tab.'<meta name="'.$name.'" content="'.str_replace('"',"'",$content).'"'.$tagEnd.$lnEnd;
				} elseif ($type == 'facebook') {
					$strHtml .= $tab.'<meta property="'.$name.'" content="'.str_replace('"',"'",$content).'"'.$tagEnd.$lnEnd;
				}
				
			}
		}

		$strHtml .= $tab.'<meta name="description" content="'.$document->getDescription().'" />'.$lnEnd;
		#$strHtml .= $tab.'<meta name="generator" content="'.$document->getGenerator().'" />'.$lnEnd;

		$strHtml .= $tab.'<title>'.htmlspecialchars($document->getTitle()).'</title>'.$lnEnd;
		
		// Generate link declarations
		//foreach ($document->_links as $link) {
		//	$strHtml .= $tab.$link.$tagEnd.$lnEnd;
		//}
		
		// Generate stylesheet links
		foreach ($document->_styleSheets as $strSrc => $strAttr ) {
		
			$strHtml .= $tab . '<link rel="stylesheet" href="'.$strSrc.'" type="'.$strAttr['mime'].'"';
			if (!is_null($strAttr['media'])){
				$strHtml .= ' media="'.$strAttr['media'].'" ';
			}
			
			$strHtml .= $tagEnd.$lnEnd;
		}
		
		// Generate stylesheet declarations
		foreach ($document->_style as $type => $content) {
		
			$strHtml .= $tab.'<style type="'.$type.'">'.$lnEnd;

			// This is for full XHTML support.
			//if ($document->_mime == 'text/html' ) {
				$strHtml .= $tab.$tab.'<!--'.$lnEnd;
			//} else {
			//	$strHtml .= $tab.$tab.'<![CDATA['.$lnEnd;
			//}

			$strHtml .= $content . $lnEnd;

			// See above note
			//if ($document->_mime == 'text/html' ) {
				$strHtml .= $tab.$tab.'//-->'.$lnEnd;
			//} else {
			//	$strHtml .= $tab.$tab.']]>'.$lnEnd;
			//}
			$strHtml .= $tab.'</style>'.$lnEnd;
		}
		
		// Generate script file links
		foreach ($document->_scripts as $strSrc => $strType) {
			$strHtml .= $tab.'<script type="'.$strType.'" src="'.$strSrc.'"></script>'.$lnEnd;
		}

		// Generate script declarations
		foreach ($document->_script as $type => $content) {
		
			$strHtml .= $tab.'<script type="'.$type.'">'.$lnEnd;

			// This is for full XHTML support.
			if ($document->_mime != 'text/html' ) {
				#$strHtml .= $tab.$tab.'<![CDATA['.$lnEnd;
				$strHtml .= $tab.$tab.'<!--'.$lnEnd;
			}

			$strHtml .= $content.$lnEnd;

			// See above note
			if ($document->_mime != 'text/html' ) {
				#$strHtml .= $tab.$tab.'// ]]>'.$lnEnd;
				$strHtml .= $tab.$tab.'//-->'.$lnEnd;
			}
			$strHtml .= $tab.'</script>'.$lnEnd;
		}
		
		return $strHtml;
	}

}


?>