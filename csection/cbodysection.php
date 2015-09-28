<?php
//---------------------------------------------------------------------------
// file: cbodysection.php
// desc: defines the custom body csection object
//---------------------------------------------------------------------------

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
		$str .= $ctheme->foot();	
		return $str;
	} // innerhtml()
	
	public function admin_body() {
		/*
		$ctheme = CTheme :: getCTheme();
		if (!$ctheme || !$ccontrols=$ctheme->getCForm()->getCControls())
			return;
		$ccontrols->section("cbody", "Body", array("description"=>"This is the body section."));	
		$ccontrols->text("ctheme-no-image-url", "Hello, World", array("label"=>"No Image Availiable"));
		$ccontrols->textarea("ctheme-site-style-text", "", array("label"=>"Site Styles"));
		*/
	} // end admin_body()
} // end CBodySection
?>