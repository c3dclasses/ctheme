<?php
//---------------------------------------------------------
// file: cform.drv.php  
// desc: defines a controls used in the theme
//---------------------------------------------------------

// includs
include_once("coptions.drv.php");
include_once("ccontrols.drv.php");

//------------------------------------------------
// name: CWidgetForm
// desc: defines the form
//------------------------------------------------
class CWidgetForm extends CForm {
	protected $m_cwidgetinstance;
	function CWidgetForm($COptionsType="CWidgetOptions", $CControlsType="CWidgetControls"){
		parent :: CForm($COptionsType, $CControlsType);
		$this->m_cwidgetinstance=NULL;
	} // end CWidgetForm()
	
	function getCForm($id, $params, $CFormType="CWidgetForm", $COptionsType="CWidgetOptions", $CControlsType="CWidgetControls"){
		$cform = parent :: getCForm($id, $params, $CFormType, $COptionsType, $CControlsType);	
		if($CFormType == "CWidgetForm") {
			$cform->setCWidgetInstance($this->m_cwidgetinstance);
		} // end if
		return $cform;
	} // end getForm()
	
	function setCWidgetInstance($cwidgetinstance){
		$this->m_cwidgetinstance = $cwidgetinstance;
	} // end setCWidgetInstance()
		
	function getCWidgetInstance(){
		return $this->m_cwidgetinstance;
	} // end getCWidgetInstance()
} // end CContentForm

/////////////////////////////S////////
// c3dclasses to wordpress 

//-----------------------------------------------------
// name: CThemeForm_getSetting()
// desc: returns the setting of a control
//-----------------------------------------------------
function CThemeForm_getSetting($strname){ 
	global $CThemeForm_wp_customize; 
	return (!$CThemeForm_wp_customize) ? NULL : $CThemeForm_wp_customize->get_setting($strname); 
} // end CThemeForm_getSetting()

//--------------------------------------------------------------------
// name: CTheme_getControlType()
// desc: returns the control class type of a given control type name
//--------------------------------------------------------------------
$WP_Customize_Control_Type = array();
$WP_Customize_Control_Type["fileupload"]="WP_Customize_Upload_Control";
$WP_Customize_Control_Type["image"]="WP_Customize_Image_Control";
$WP_Customize_Control_Type["color"]="WP_Customize_Color_Control";
$WP_Customize_Control_Type["textarea"]="Documentation_Customize_Textarea_Control";
function CThemeForm_getControlType($strtype){
	global $WP_Customize_Control_Type;
	return ($strtype==""||!isset($WP_Customize_Control_Type[$strtype])) ? "WP_Customize_Control" : $WP_Customize_Control_Type[$strtype];
} // end CThemeForm_getControlType()

//-----------------------------------------------------
// name: CThemeForm_customizeRegister()
// desc: register the theme cusomizer form
//-----------------------------------------------------
$CThemeForm_wp_customize = NULL; // stores the wordpress theme customizer
$CThemeForm = NULL;
function CThemeForm_customizeRegister($wp_customize){
	include_once("customizecontrols.drv.php");
	global $CThemeForm_wp_customize; 
	global $CThemeForm;
	$ctheme = CTheme :: getCTheme(); 
	if( !$ctheme )
		return;
	$CThemeForm_wp_customize = $wp_customize;	// set the customizer
	$CThemeForm = new CForm("CThemeOptions","CThemeControls");
	$CThemeForm->create(NULL);	
	$ctheme->admin_body();
} // end CTheme_customizeRegister()
add_action('customize_register', 'CThemeForm_customizeRegister');
?>