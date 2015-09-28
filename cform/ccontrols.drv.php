<?php
//---------------------------------------------------------
// file: ccontrols.drv.php
// desc: defines the options used by ccontrols
//---------------------------------------------------------

/////////////////////////
// CWidgetControls

//-------------------------------------------------
// name: CWidgetControls
// desc: sets up the control 
//-------------------------------------------------
class CWidgetControls extends CControls {
	//public function ccontent( $strtype, $strmethod, $params, $coptionstype="COptions", $ccontrolstype="CControls"){
	//	$cform = $this->m_cform->getCForm($strname="", $params=NULL, $COptionsType="COptions", $CControlsType="CControls")
	//	$strmethod( $ )
	//}
	
	public function processParams($params) { 
		$params["cwidgetinstance"] = $this->m_cform->getCWidgetInstance();
		//$params["cform-params"]=$this->m_cform->getParams();	
		return CWidgetControls_processParams($params); 
	} // end processParams
} // end CWidgetControls

function CWidgetControls_processParams($params){	
	if( !$params || !($cwidgetinstance = $params["cwidgetinstance"]) ||
		!($cwidget = $cwidgetinstance->getWidget())){
			printbr("CWidgetControls_processParams:Error");	
			return;
		}
		
	$strtype = $params["ccontrol-type"];
	$strname = $params["ccontrol-name"];
	$value = $params["ccontrol-value"];
	$params = $params["ccontrol-params"];
	$strid = $cwidget->get_field_id($strname);
	$strfieldname = $cwidget->get_field_name($strname);
	$strcontrol="";
	if($strtype == "section")
		$strcontrol = "";
	else if($strtype == "label"){
		//$strid = $cwidget->get_field_id();
		$strcontrol = "<label for=\"$strid\">$value</label>";
	}
	else if($strtype == "text")
		$strcontrol = "<input type=\"text\" class=\"widefat\" id=\"$strid\" name=\"$strfieldname\" value=\"$value\" />";
	else if($strtype == "hidden")	
		$strcontrol = "<input type=\"hidden\" class=\"widefat\" id=\"$strid\" name=\"$strfieldname\" value=\"$value\" />";		
	else if($strtype == "textarea")
		$strcontrol = "<textarea type=\"text\" class=\"widefat\" id=\"$strid\" name=\"$strfieldname\">$value</textarea>";
	else if($strtype == "checkbox")
		$strcontrol = "<input class=\"checkbox\" $checked id=\"$strid\" name=\"$strfieldname\" type=\"checkbox\" value=\"$value\" />";
	else if($strtype == "radio")
		$strcontrol = "<input class=\"radio\" $checked id=\"$strid\" name=\"$strfieldname\" type=\"radio\" value=\"$value\" />";
	else if($strtype == "button")
		$strcontrol = "<input type=\"button\" id=\"$strid\" name=\"$strfieldname\" value=\"{$value}\" />";
	else if($strtype == "submit")
		$strcontrol = "<input type=\"submit\" id=\"$strid\" name=\"$strfieldname\" value=\"{$value}\" />";
	else if($strtype == "select"){
		$selectedvalue = $value;
		$stroptions = "";
		if($options=$params["choices"]) 
			foreach($options as $name=>$value){ 
				$selected = ($selectedvalue == $name) ? "selected=''" : ""; 
				$stroptions	.= "<option {$selected} value=\"{$name}\">{$value}</option>"; 
			} // end foreach 
		$strcontrol = "<select autocomplete=\"off\" class=\"widefat\" id=\"$strid\" name=\"$strfieldname\">$stroptions</select>";
	} // end else if
	else return;
	return $strcontrol;
} // end CWidgetControls_processParams()

/////////////////////////
// CThemeControls

//-------------------------------------------------
// name: CThemeControls
// desc: sets up the control 
//-------------------------------------------------
class CThemeControls extends CControls {
	public function processParams($params){ 
		CThemeControls_processParams($params); 
		return $this; 
	} // end processParams()
} // end CThemeControls

//------------------------------------------------------
// name: CThemeForm_processParams()
// desc: processes the params of the CThemeForm control
//------------------------------------------------------
$CThemeForm_sectionName="";
$CThemeForm_labelName="";
function CThemeControls_processParams($ccontrol_params){
	global  $CThemeForm_wp_customize;
	global $CThemeForm_sectionName;
	global $CThemeForm_labelName;
	if(!$CThemeForm_wp_customize || !$ccontrol_params)
		return;	
	// get the params
	$strtype = $ccontrol_params["ccontrol-type"];
	$strname = $ccontrol_params["ccontrol-name"];
	$value = $ccontrol_params["ccontrol-value"];
	$params = $ccontrol_params["ccontrol-params"];

	// section controls
	if($strtype == "section"){
		if(!isset($params['title']))
			$params['title']=$value;
		$CThemeForm_wp_customize->add_section($strname, $params);	
		$CThemeForm_sectionName = $strname;
		//alert($strname);
		return;
	} // end if
	else if($strtype == "label"){
		$CThemeForm_labelName = $value;
		alert($CThemeForm_labelName);
		return;
	} // end if
	
	// add_setting defaults
	if(!isset($params['default']))
		$params['default']=$value;
	if(!isset($params['capability']))
		$params['capability']='edit_theme_options';
	if(!isset($params['theme_supports']))
		$params['theme_supports']='';
	if(!isset($params['theme_supports']))
		$params['transport']='postMessage';
	//$params['context']='your_setting_context'; 
	//if(isset($params['sanitized_callback']))
	//	$params['sanitized_callback']='postMessage';
	//if(!isset($params['sanitized_js_callback']))
	//	$params['transport']='postMessage';	
	if(isset($params['storage']))
		$params['type']=isset($params['storage']);
	$params['type']='theme_mod';
	$CThemeForm_wp_customize->add_setting($strname, $params);
	
	// add_control defaults
	if(!isset($params['label'])){
		$params['label']=$CThemeForm_labelName;
		$CThemeForm_labelName="";
	} // end if

	if(!isset($params['section']))
		$params['section'] = $CThemeForm_sectionName;
	$params['type']=$strtype;
	if(!isset($params['settings']))
		$params['settings']=$strname;
	//if(!isset($params['priority']))
	//	$params['priority']=5;	
	$strclasstype = CThemeForm_getControlType($strtype);
	$CThemeForm_wp_customize->add_control(new $strclasstype ($CThemeForm_wp_customize, $strname, $params));
	return;
} // end CThemeForm_control()
?>