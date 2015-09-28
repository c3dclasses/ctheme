<?php
//-------------------------------------------------------------------------------------------------
// file: ctheme.php
// desc: defines the theme object which is a kernal object that intergrates with CMS like wordpress
//--------------------------------------------------------------------------------------------------

// includes
include_js(relname(__FILE__) . "/ctheme.js", array("minify"=>false));	// include javascript and don't minify

//---------------------------------------------------------------------------
// name: CTheme
// desc: defines a theme object
//---------------------------------------------------------------------------
class CTheme extends CKernal {	
	protected $m_strname;	// stores the theme name
	static protected $m_ctheme = NULL;	// stores the theme object 
	
	public function CTheme() {
		parent :: CKernal();			// construct kernal object
		$this->m_strname = "";			// initialize defaults
		CTheme :: $m_ctheme = $this;	// the current theme being used	
	} // end CTheme()
	
	public function setName($strname) { $this->m_strname=$strname; }	
	public function getName() { return $this->m_strname; }	
	public function getClass() { return strtolower(get_class($this)); }
	public function getID() { return strtolower(get_class($this) . "-{$this->m_strname}"); }
	public function getCTheme() { return CTheme :: $m_ctheme; }
	public function getCForm() { global $CThemeForm; return $CThemeForm; }
	public function getCControls() { $cform = $this->getCForm(); return ($cform)?$cform->getCControls():NULL; }
	public function getCOptions() { $cform = $this->getCForm(); return ($cform)?$cform->getCOptions():NULL; }
	public function option() { $ret=call_user_func_array("CTheme_options", func_get_args()); return (func_num_args() == 1) ? $ret : $this; } 
	public function removeOption($name) { CTheme_delOptions($name);	return $this; }
	
	// ckernal override methods
	public function process() {
		$this->init();	
		if (!$this->s_main()) { $this->body();}
		else {}
		$this->unload();
		$this->deinit();
		return true;
	} // end main()
	public function body() { echo CTheme_doBody($this); return true; }
	public function head() { return CTheme_doHead($this); } 
	public function foot() { return parent :: foot() . CTheme_doFoot($this); } 
	public function admin_body() { CTheme_doAdminBody($this); } 
	
	public function load($strpath) { include_contents($strpath); return parent :: load($strpath); }
	 
	// static methods
	static public function createCTheme($strpath=NULL, $strtype="CTheme") {	
	 	$ctheme = CKernal :: createCKernal($strtype); // try to create a kernal object	
		$strname = CTheme_getThemeName();	// get the theme name
		if (!$strname || !$ctheme)	
			return NULL;
		$ctheme->setName($strname);	// store the theme name
		$ctheme->load($strpath);		// load the kernal and its objects - programs / elements
		return $ctheme;
	} // end createCTheme()
	
	static public function destroyCTheme($ctheme) {	
		CKernal :: destroyCKernal($ctheme);
	} // end destroyCTheme()
} // end CTheme

// includes
function include_theme($strid, $strpath, $strtype="CTheme") {
	return CTheme :: createCTheme($strpath, $strtype);
} // end include_theme()
?>