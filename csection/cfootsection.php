<?php
//------------------------------------------------------------------------------------
// file: cfootsection.php
// desc: defines the custom foot csection containing the major sections of the theme
//------------------------------------------------------------------------------------

//-------------------------------------------------
// name: CFootSection
// desc: defines the foot section object
//-------------------------------------------------
class CFootSection extends CSection {
	public function innerhtml() { 
		if (!$ctheme = CTheme :: getCTheme())
			return "";
		$str .= parent :: innerhtml();
		$str .= $ctheme->foot();	
		return $str;
	} // innerhtml()
	
	public function admin_body() {
	} // end admin_body()
} // end CFootSection
?>