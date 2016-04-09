<?php
//-----------------------------------------------------------------------
// file: cwidgethook.drv.php
// desc: implements the c3dclasses widget object using wordpress widget
//-----------------------------------------------------------------------

//-------------------------------------------------------
// name: CWidgetHook
// desc: this defines hooking functionality
//-------------------------------------------------------
class CWidgetHook {
	// members
	public static $m_hook=NULL;	// stores the callbacks that hooks the form, widget, update, etc methods
	
	// sets the hook
	public static function hook($strname, $callback) { 
		if ($strname != "" && $callback != "" && function_exists($callback) == true)					
			CWidgetHook :: $m_hook[$strname][] = $callback;	
	} // hook()
	
	// executes the hook
	public static function doHook($strname, $cwidgetinstance) {
		if (CWidgetHook :: $m_hook == NULL || isset(CWidgetHook :: $m_hook[$strname]) == false)
			return;
		$callbacks = CWidgetHook :: $m_hook[$strname];
		foreach($callbacks as $index => $callback) {
			//$callback($cwidgetinstance);
			try {
				$callback($cwidgetinstance);
			} // end try
			catch(Exception $e) {
				alert("Exception");
			} // end catch()
		} // end foreach()
		return;
	} // end doHook()
	
	// hook callback handlers
	public static function doInitDashboard() {
		global $wp_registered_widgets;
		if ($wp_registered_widgets!=NULL) {
			foreach($wp_registered_widgets as $id => $params) {
				if (($cwidgetinstance = CWidgetInstance :: getCWidgetInstanceByID($id, $params)) != NULL) {
					$cwidget = $cwidgetinstance->getWidget();
					if(!is_active_widget( false, false, $cwidget->id_base, true )){
						continue; // skip  
					} // end if
					if (method_exists($cwidget, "init"))
						$cwidget->init(); 
					CWidgetHook :: doHook("admin_init", $cwidgetinstance);
				} // end if
			} // end foreach
		} // end if
		return;
	} // end doInitDashboard()
	
	public static function doInitSite() {
		global $wp_registered_widgets;
		if ($wp_registered_widgets!=NULL) {
			foreach($wp_registered_widgets as $id => $params) {
				if (($cwidgetinstance = CWidgetInstance :: getCWidgetInstanceByID($id, $params)) != NULL) {
					$cwidget = $cwidgetinstance->getWidget();
					if(!is_active_widget( false, false, $cwidget->id_base, true )){
						continue; // skip  
					} // end if
					if (method_exists($cwidget, "init"))
						$cwidget->init(); 
					CWidgetHook :: doHook("init", $cwidgetinstance);	
				} // end if
			} // end foreach
		} // end if
		return;
	} // end doInitSite()
	
	public static function doForm($widget) {
		if (is_active_widget( false, false, $widget->id_base, true ) && 
			$cwidgetinstance = CWidgetInstance :: getCWidgetInstanceByID($widget->id)) {
			CWidgetHook :: doHook("form", $cwidgetinstance);
			CWidgetHook :: doHook("admin_body", $cwidgetinstance);
		} // end if
	} // end doForm()
	
	public static function doUpdate($instance, $new_instance, $old_instance, $params) {
		if (($cwidgetinstance = CWidgetInstance :: getCWidgetInstanceByID($id, $params)) == NULL)
			return $new_instance;
		$cwidgetinstance->instance($new_instance);
		$cwidgetinstance->prevInstance($old_instance);
		$cwidgetinstance->param($params);
		CWidgetHook :: doHook("update",$cwidgetinstance);	
		return $new_instance;
	} // end doUpdate()
	
	public static function doBody($callback, $params) {
		$widget = $callback[0];
		$widget->id=$params[0]['widget_id'];   // set the registered instance id
		$widget->number=$params[1]['number'];
		$cwidgetinstance = CWidgetInstance :: getCWidgetInstanceByID($widget->id);
		if ($cwidgetinstance == NULL)
			return;
		CWidgetHook :: doHook("pre-body", $cwidgetinstance);
		ob_start();
		if (is_callable($callback)) 
			call_user_func_array($callback, $params);
		if ($cwidgetinstance)
			CWidgetHook :: doHook("body", $cwidgetinstance);
		$str = ob_get_contents();
		ob_end_clean();
		$cwidgetinstance->html($str);
		echo $cwidgetinstance->body();
		CWidgetHook :: doHook("post-body", $cwidgetinstance);
	} // end doBody()
} // end CWidgetHook

/////////////////
// hooks
/////////////////

CHook :: add("init", "CWidgetHook_init"); // hook to create cwidgetinstances 'init'
function CWidgetHook_init() { CWidgetHook :: doInitSite(); }

add_action('sidebar_admin_setup', 'CWidgetHook_sidebarAdminSetup');	// hook to initialize cwidgets 'init_admin'
function CWidgetHook_sidebarAdminSetup() { CWidgetHook :: doInitDashboard(); }

add_action("in_widget_form", "CWidgetHook_inWidgetForm"); // hook to add extra 'form'
function CWidgetHook_inWidgetForm($widget) { CWidgetHook :: doForm($widget); }

add_filter('dynamic_sidebar_params', 'CWidgetHook_dynamicSidebarParams', 10);	
function CWidgetHook_dynamicSidebarParams($params) {	
	global $wp_registered_widgets;
	$id=$params[0]['widget_id'];
	$callback = $wp_registered_widgets[$id]['callback'];
	$wp_registered_widgets[$id]['callback_cwidget_redirect']=$callback;
	$wp_registered_widgets[$id]['callback']='CWidgetHook_widgetCallback';
	return $params;
} // end CWidgetHook_dynamicSidebarParams()

function CWidgetHook_widgetCallback() { 
	global $wp_registered_widgets;
	$params=func_get_args();		// replace the original callback data
	$id=$params[0]['widget_id'];
	$callback=$wp_registered_widgets[$id]['callback_cwidget_redirect'];
	$wp_registered_widgets[$id]['callback'] = $callback;
	CWidgetHook :: doBody($callback, $params);	
} // end CWidget_widgetCallback()

add_filter('widget_update_callback', 'CWidgetHook_widgetUpdateCallback', 10, 3); // hook to override 'update'
function CWidgetHook_widgetUpdateCallback($instance, $new_instance, $old_instance, $widget) { 
	global $wp_registered_widgets;
	$id=$_REQUEST["widget-id"];
	$params = $wp_registered_widgets[$id];		
	return CWidgetHook :: doUpdate($instance, $new_instance, $old_instance, $params);
} // end CWidget_widgetUpdateCallback()