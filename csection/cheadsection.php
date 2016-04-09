<?php
//------------------------------------------------------------------------------------
// file: cheadsection.php
// desc: defines the custom head csection containing the major sections of the theme
//------------------------------------------------------------------------------------

//-------------------------------------------------
// name: CHeadSection
// desc: defines the head section object
//-------------------------------------------------
class CHeadSection extends CSection {
	public function innerhtml() { 
		if (!$ctheme = CTheme :: getCTheme())
			return "";
		$str .= $ctheme->head(); 
		$str .= parent :: innerhtml();
		return $str;
	} // innerhtml()
	
	public function admin_body() {
	} // end admin_body()
} // end CHeadSection
?>