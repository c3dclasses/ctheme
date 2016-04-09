<?php
//------------------------------------------------------------
// file: cwidget.php
// desc: extends the wordpress widget object
//------------------------------------------------------------

// header
include_once("cwidgetinstance.drv.php");
include_once("cwidget.drv.php");
include_once("cwidgethook.drv.php");

//----------------------------------------------
// name: CWidget
// desc: this link CWidget
//----------------------------------------------
class CWidget extends WP_WidgetEx {
	public function CWidget() { $this->create("CWidget", "CWidget", "Creates a Simple Wordpress Widget used by CTheme plugin"); } 
	public function init() {}
	public function body() {}
	public function admin_body() {}
	public static function register($strclassname) { parent :: register($strclassname); }
} // end CWidget

// includes
function include_widget($strclassname) { CWidget :: register($strclassname); }

// include this widget
include_widget("CWidget");
?>