<?php
//------------------------------------------------------------
// file: cwidgetinstance.php
// desc: extends the wordpress widget object
//------------------------------------------------------------

//------------------------------------------------------------
// name: CWidgetInstance
// desc: defines a ordpress widget instances object
//------------------------------------------------------------
class CWidgetInstance extends CElement{
	protected 	$m_params;			// stores the params of the widget
	protected 	$m_number;			// stores the number of the widget
	protected 	$m_instance;		// stores the current options of the widget 
	protected 	$m_previnstance;	// stores the previous options of the widget
	protected 	$m_args;			// stores the argument of the widget
	protected 	$m_cform;			// stores the form of the widget of controls and options
	static protected $m_cwidgetinstances=NULL;		// stores all of the widget instances
	
	public function CWidgetInstance() { 
		$this->m_instance = NULL;
		$this->m_previnstance = NULL;
		$this->m_args = NULL;
		$this->m_cform = new CWidgetForm("CWidgetOptions","CWidgetControls"); // controls
		$this->m_cform->setCWidgetInstance($this);
		$this->m_params = new CAttributes(); 
		$this->param("cwidgetinstancetype", get_class($this));
		parent :: CElement();
	} // end CWidgetInstance()
	
	public function create($params=NULL) { 
		if (!$params)
			return false;		
		$this->m_params->create($params);
		if(!($cwidget = $this->getWidget()) && !($cwidget = $this->param("cwidget"))){
			return false;
		}
		
		$id = $cwidget->id; 
		$widgetname = $cwidget->id_base;
		$classid = str_replace("instance","",$this->id());
		$this->m_cform->create($id,$this);
		$this->id($classid);
		$this->name($classid);
		$this->addClass($classid);		
		$this->addClass("{$id}");
		$this->classes($this->classes() . " ". $widgetname);
		$this->param('number',substr($id,strrpos($id,"-")+1));	
		CWidgetInstance :: $m_cwidgetinstances[$id]=$this; // get the instances
		return true;
	} // end create()
	
	// instances, prevInstances, args, params
	public function instance($instance=NULL) { if ($instance) $this->m_instance = $instance; return $this->m_instance; }
	public function prevInstance($instance=NULL) { if ($instance) $this->m_previnstance = $instance; return $this->m_previnstance; }
	public function args($args) { if ($args) $this->m_args = $args; return $this->m_args; }
	public function param() { $ret=call_user_func_array(array($this->m_params,"attr"), func_get_args()); return (func_num_args() == 1) ? $ret : $this; } 
	public function isActive() {
		//printbr($this->param('id'));
		return is_active_widget( false, false, $this->param('id'), true);
		//is_active_widget( false, false, $cwidget->id_base, true )
	} // end isActive()
	
	public function getWidget() { 
		if (($callback=$this->param('callback')) == NULL && isset($callback[0]) == false)
			return NULL;
		$cwidget = $callback[0];
		$cwidget->id=$this->param('id');
		$cwidget->number=$this->param('number');
		$cwidget->description=$this->param('description');
		$cwidget->classname=$this->param('classname');
		$cwidget->{"m_cwidgetinstance"} = $this;
		return $cwidget;
	} // end getCWidget()	
	
	public function getWidgetField($field) { 
		return (($widget = $this->getWidget()) ? array("id"=>$widget->get_field_id($field), 
								"name"=>$widget->get_field_name($field)) : NULL);
	} // end getWidgetField()
	function getWidgetID() { return $this->param('id'); }
	function getWidgetNumber() { return $this->param('number'); }
	function getWidgetDescription() { return $this->param('description'); }
	function getWidgetClassName() { return $this->param('classname'); }
	
	public function init() {
		parent :: init();
		$coptions = $this->m_cform->getCOptions();
		$this->addClass("cwidget_".$coptions->option("title"));			
	} // end init()

	public function update() {
		$id = $this->param('id');
		if ($params = CWidget :: updateParams($id))
			$this->m_params->create($params);
	} // end update();
	
	// admin methods
	public function admin_body() {
		if (!$ccontrols = $this->getCControls()) {
			return;
		} // end if
		echo "<p>";
		echo $ccontrols->label("title", "Name:");
		echo $ccontrols->text("title","");
		echo "</p>";
	} // end admin_body()
	
	public function admin_update() {
		return $this->instance();
	} // end admin_update()
	
	// form, controls, options
	public function getCControls() { return ($this->m_cform) ? $this->m_cform->getCControls() : NULL; }
	public function getCOptions() { return ($this->m_cform) ? $this->m_cform->getCOptions() : NULL; }
	public function getCForm() { return $this->m_cform; }
	public function option() {
		$number = $this->param('number');
		$classname = $this->param('classname');
		if (func_num_args() == 1)
			return CWidget_option($classname, $number, func_get_arg(0));
		CWidget_option($classname, $number, func_get_arg(0), func_get_arg(1));
		return $this;
	} // end option()
	public function removeOption() { 
		$number = $this->param('number');
		$classname = $this->param('classname');
		CWidget_removeOption($classname,$number,func_get_arg(0));
		return $this;
	} // end removeOption()
		
	// widget instances
	static public function getCWidgetInstances() { return CWidgetInstance :: $m_cwidgetinstances; }	
	static public function getCWidgetInstanceByName($name) {} 
	static public function getCWidgetInstanceByID($id, $params=NULL) { 
		$cwidgetintstances = CWidgetInstance :: getCWidgetInstances();	
		if ($cwidgetintstances && isset($cwidgetintstances[$id])) // is it cached
			return $cwidgetintstances[$id];	
		if ($params == NULL || ($cwidgetinstance = new CWidgetInstance()) == NULL || 
			$cwidgetinstance->create($params) == false) {
			return NULL;
		} // end if
		$cwidgetinstance->init();
		return $cwidgetinstance;	
	} // end getCWidgetInstanceByID()
} // end class CWidgetInstance
?>