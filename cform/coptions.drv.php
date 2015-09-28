<?php
//---------------------------------------------------------
// file: coptions.drv.php
// desc: defines the options used by cform
//---------------------------------------------------------

////////////////////////
// CWidgetOptions

/*
//-----------------------------------------------
// name: CWidgetOptions
// desc: process the params
//-----------------------------------------------
class CWidgetOptions extends COptions {
	public function processParams($params) {
		$cwidgetinstance = $this->m_cform->getParams();
		$cwidget = $cwidgetinstance->getCWidget();
		$settings = $cwidget->get_settings();
		
		if ($settings && isset($settings[$cwidget->number])) {
			$params["coption-instance"]=$settings[$cwidget->number];
		}
		else {
			$id_base = $_REQUEST["id_base"];
			$widget_number = $_REQUEST["widget-number"];
			$params["coption-instance"]=$_REQUEST[$widget_number][$id_base];
			
			echo "in processParams";
		} // end else
		return CWidgetOptions_processParams($params);
	} // end processParams()
} // end CWidgetOptions

//--------------------------------------------------------
// name: CWidgetOptions_processParams()
// desc: method used to do crud on the incoming params
//--------------------------------------------------------
function CWidgetOptions_processParams($params) {
	if ($params && !isset($params["coption-operator"]))
		return;
	$op = $params["coption-operator"];
	$name = $params["coption-name"];
	$value = $params["coption-value"];
	$instance = $params["coption-instance"];
	if ($op=="get")
		return isset($instance[$name]) ? $instance[$name] : "";
	else if ($op == "set")
		$instance[$name]=$value;
	else if ($op == "remove")
		unset($instance[$name]);	
	else { 
		// add the other stuff
	} // end else
} // end CWidgetOptions_processParams()
*/

//-----------------------------------------------
// name: CWidgetOptions
// desc: process the params
//-----------------------------------------------
class CWidgetOptions extends COptions {
	public function processParams($params) {
		$params["cwidgetinstance"] = $this->m_cform->getCWidgetInstance();
		return CWidgetOptions_processParams($params);
	} // end processParams()
} // end CWidgetOptions


//--------------------------------------------------------
// name: CWidgetOptions_processParams()
// desc: method used to do crud on the incoming params
//--------------------------------------------------------
function CWidgetOptions_processParams($params) {
	if ($params && !isset($params["coption-operator"]))
		return;
	$op = $params["coption-operator"];
	$name = $params["coption-name"];
	$lname = $params["coption-lname"]; /* long name */
	$value = $params["coption-value"];
	$cwidgetinstance = $params["cwidgetinstance"];
	if ( !$cwidgetinstance )
		return;
	if ($op == "get")
		return $cwidgetinstance->option($name);		
	else if ($op == "set")
		return $cwidgetinstance->option($name,$value);
	else if ($op == "remove")
		return $cwidgetinstance->removeOption($name);	
	else { 
		// add the other stuff
	} // end else
} // end CWidgetOptions_processParams()

////////////////////////
// CThemeOptions

//-----------------------------------------------
// name: CThemeOptions
// desc: process the params
//-----------------------------------------------
class CThemeOptions extends COptions {
	public function processParams($params) {
		return CThemeOptions_processParams($params);
	} // end processParams()
} // end CThemeOptions

//--------------------------------------------------------
// name: CThemeOptions_processParams()
// desc: method used to do crud on the incoming params
//--------------------------------------------------------
function CThemeOptions_processParams($params) {
	if ($params && !isset($params["coption-operator"]))
		return;
	$op = $params["coption-operator"];
	if ($op=="get")
		return get_theme_mod($params["coption-name"]);
	else if ($op == "set")
		set_theme_mod($params["coption-name"],$params["coption-value"]); 
	else if ($op == "remove")
		remove_theme_mods($params["coption-name"]);	
	else { 
		// add the other stuff
	} // end else
} // end CThemeForm_processParams($params)
?>