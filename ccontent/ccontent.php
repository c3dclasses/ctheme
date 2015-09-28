<?php
//-----------------------------------------------------
// file: ccontent.php
// desc: defines ccontent
//-----------------------------------------------------

// includes
include_js(dirname(__FILE__) ."/ccontent.js");

//---------------------------------------------------------------------------------
// class: CContent
// desc: a widget that contains an element object
//---------------------------------------------------------------------------------
class CContent extends CElement {	
	protected static $m_arrccontenttypes = NULL;	// stores all of the registered ccontent types
	protected static $m_ccontents = NULL;			// stores all of the ccontent instances
	protected $m_cform;								// stores the cform object of this content
	protected $m_strname;							// stores the namespaces of this control
	
	public function CContent() { 
		parent::CElement(); 
		$this->m_cform=NULL;
	} // end CContent()
	
	public function init() {} 
	public function create($cform, $params=NULL) { if (!$cform) return false; parent::create($params); $this->m_cform = $cform; return true; }
	public function innerhtml() { return "[CContent::innerhtml()]"; }
	public function admin_body() {}
	
	// cform, coptions, ccontrols
	public function getCForm() { return $this->m_cform; }	
	public function getCControls() { return ($this->m_cform)?$this->m_cform->getCControls():NULL; }	
	public function getCOptions() { return ($this->m_cform)?$this->m_cform->getCOptions():NULL; }	
	
	// ccontent
	public function addCContent($name, $ccontent) { $this->m_ccontents[$name] = $ccontent; }
	public function getCContent($name) { return $this->m_ccontents[$name]; }
	public function getCContents() { return $this->m_ccontents; }
	public function setCContents($ccontents) { $this->m_ccontents=$ccontents; }
	
	static public function getCContentTypes() { 
		return (!CContent :: $m_arrccontenttypes || !($types = array_keys(CContent :: $m_arrccontenttypes)) || count($types) < 1) ? NULL : 
		array_merge(array("NULL" => "Select a Content Type"), array_combine($types, $types));
	}
	static public function getContentTypeCallbackParams($strcontenttype) {
		$types = CContent :: $m_arrccontenttypes;
		return $types && isset($types[$strcontenttype]) ? $types[$strcontenttype] : NULL;
	} 
	static public function register($strccontenttype, $params=NULL) { 
		if (($strccontenttype == NULL || $strccontenttype == "") ||
			(class_exists($strccontenttype) == false) && 
			($params == NULL || isset($params["body"]) == false || function_exists($params["body"] == false))) // must have at least one body function 
			return false;
		CContent :: $m_arrccontenttypes[$strccontenttype] = $params;
		return true;
	} // end register()
} // end CContent()

//-----------------------------------------------------------------
// name: CContentByCallbacks
// desc: creates ccontent by callback functions
//-----------------------------------------------------------------
class CContentByCallbacks extends CContent {
	protected $m_strccontenttype;
	public function CContentByCallbacks() { parent::CElement(); $this->m_strccontenttype=""; } 
	public function setCContentType($strccontenttype) { $this->m_strccontenttype = $strccontenttype; }
	public function getCContentType() { return $this->m_strccontenttype; }
	public function doCallbackFunction($strcallback) {
		 $params = CContent :: getContentTypeCallbackParams($this->m_strccontenttype);
		 if (!$params||!isset($params[$strcallback])||!function_exists($params[$strcallback])) 
		 	return;
		$callback = $params[$strcallback];
		$callback($this);	// pass the ccontent object there
		return; 
	} // end getContentTypeCallbackParams();
	public function init() { $this->doCallbackFunction("init"); } 
	public function admin_body() { $this->doCallbackFunction("form"); } 
	public function innerhtml() { $this->doCallbackFunction("body"); }	
} // end CContentByCallbacks

// includes
function include_contents($strpath) { if ( function_exists( "includephpfilesfrompath" ) == true ) includephpfilesfrompath( $strpath, ".cnt.php" ); }
function include_content( $ccontenttype, $params=NULL ) { return CContent :: register( $ccontenttype, $params ); }
?>