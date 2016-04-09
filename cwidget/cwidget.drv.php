<?php
//-------------------------------------------------------------------
// file: cwidget.drv.php
// desc: defines the wordpress widget to c3dclasses widget driver
//-------------------------------------------------------------------

//----------------------------------------------
// name: WP_Widget_Ex
// desc: redifes a wordpress widget
//----------------------------------------------
class WP_WidgetEx extends WP_Widget {
	public function create($strclassname, $strwidgetname, $strwidgetdesc) {
	$this->WP_Widget(strtolower($strclassname),$strwidgetname,array('description'=>__($strwidgetdesc, strtolower($strclassname))));		
		return true; 
	} // end create()
	
	public function form($instance) { 
		$params = array("cwidget"=>$this);
		if (!$cwidgetinstance = CWidgetInstance :: getCWidgetInstanceByID($this->id, $params))
			return;	
		$cwidgetinstance->instance($instance);
		$cwidgetinstance->admin_body();
		$this->m_cwidgetinstance = $cwidgetinstance;
		$this->admin_body();
		return;
	} // end form()
	
	public function update($instance, $previnstance) { 
		if (!$cwidgetinstance = CWidgetInstance :: getCWidgetInstanceByID($this->id))
			return;
		$cwidgetinstance->instance($instance);
		$cwidgetinstance->prevInstance($previnstance);
		return $cwidgetinstance->admin_update();
	} // end update()
	
	public function widget($args, $instance) { 
		if (!$cwidgetinstance = CWidgetInstance :: getCWidgetInstanceByID($this->id))
			return;
		$cwidgetinstance->args($args);
		$cwidgetinstance->instance($instance);
		$cwidgetinstance->body();
		$this->m_cwidgetinstance = $cwidgetinstance;
		$this->body();
		return;
	} // end widget()
	
	public function update_callback($widget_args=1) {
		if (!$cwidgetinstance = CWidgetInstance :: getCWidgetInstanceByID($this->id))
			return;
		parent::update_callback($widget_args);
	} // end update_callback()
	
	static public function register($strclassname){
		add_action('widgets_init', function() use($strclassname) {register_widget($strclassname);}); 
	} // end register
} // end WP_WidgetEx

// C3DClassesSDK driver methods 
function CWidget_option() {
	if (func_num_args() == 3) 
		return get_widget_option(func_get_arg(0), func_get_arg(1), func_get_arg(2)); 
	update_widget_option(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3)); 
} // end CTheme_option()

function CWidget_removeOption() {
	delete_widget_option(func_get_arg(0), func_get_arg(1), func_get_arg(2));
} // end CTheme_removeOption()

function CWidget_options() {
	return get_widget_options(func_get_arg(0), func_get_arg(1));
} // end CTheme_option()

function CWidget_removeOptions() {
} // end CTheme_removeOptions()

// wordpress like methods
function get_widget_options($type, $number) {
	return ($type=="" || ($widgets = get_option($type)) == NULL || isset($widgets[$number]) == false) ? 
		NULL : $widgets[$number];
} // end get_widget_options()

function update_widget_option($type, $number, $name, $value) {
	if ($type=="" || ($widgets = get_option($type)) == NULL || isset($widgets[$number]) == false || isset($widgets[$number][$name]) == false )
		return;
 	$widgets[$number][$name] = $value;
	update_option($type, $widgets);
} // end set_widget_option()

function get_widget_option($type, $number, $name) {
	return ($type=="" || ($widgets = get_option($type)) == NULL || isset($widgets[$number]) == false || isset($widgets[$number][$name]) == false ) 
		? "" : $widgets[$number][$name];
} // end get_widget_option()

function delete_widget_option($type, $number, $name) {
	if ($type=="" || ($widgets = get_option($type)) == "" || isset($widgets[$number]) == false || isset($widgets[$number][$name]) == false)
		return;
 	unset($widgets[$number][$name]);
	update_option($type, $widgets);
} // end remove_widget_option()