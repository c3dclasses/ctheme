<?php
//------------------------------------------------------------------------------------
// file: cbodysection.php
// desc: defines the custom body csection containing the major sections of the theme
//------------------------------------------------------------------------------------

//-------------------------------------------------
// name: CBodySection
// desc: defines the body section object
//-------------------------------------------------
class CBodySection extends CSection {
	public function innerhtml() { 
		if (!$ctheme = CTheme :: getCTheme())
			return "";
		$str = $ctheme->headr();
		$str .= parent :: innerhtml();
		$str .= $ctheme->footer();	
		$str .= CHook :: fire("csection_foot") . "<!-- end div.csection_foot -->\n"; // execute the foot section in the body
		return $str;
	} // innerhtml()
	
	public function admin_body() {
	} // end admin_body()
} // end CBodySection
?>