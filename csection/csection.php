<?php
//---------------------------------------------------------------------------
// file: csection.php
// desc: defines a csection object
//---------------------------------------------------------------------------

//---------------------------------------------------------------------------
// name: CSection
// desc: defines a section object
//---------------------------------------------------------------------------
class CSection extends CElement {	
	// members
	protected $m_params;	// stores the sidebar arguments during creation
	
	// construct / create / destroy
	public function CSection() { 
		parent :: CElement();
		$this->m_params = new CAttributes(); 
		$this->param("csectiontype", get_class($this));
	} // end CSection 	
	
	public function create($strid="", $strname="", $strdesc="") { 
		if($strid == "" || $strname == "")
			return false;
		$this->param("id", $strid);
		$this->param("name", $strname);
		$this->param("description", $strdesc);
		CSection_create($this->m_params);
		$this->update();	
		$classname = $this->attr("classtype");
		$this->addClass(strtolower($classname) . "-" . $this->param("name"));
		return true;
	} // end create()
	
	public function destroy() { 
		CSection_destroy($this->id()); 
		$this->m_params = NULL; 
	} // end destroy()
	
	// params / state of the section
	public function update() {
		$this->m_params->create(CSection_updateParams($this->param('id')));	
	} // end update();
	
	public function param() { 
		$ret=call_user_func_array(array($this->m_params,"attr"), func_get_args());
		return (func_num_args() == 1) ? $ret : $this;
	} // param()
	
	// getting / setting / checking	
	public function isCSection() { return $this->param("csectiontype") != ""; }
	public function getCSectionType() { return $this->param("csectiontype"); }
	public function haveCWidgetInstances() { return CSection_isActive($this->param('id')); } 
	public function getCWidgetInstances() { return CSection_getCWidgetInstances($this); }
	
	public function haveCSections() {}
	public function getCSections() {
		$csections = NULL;
		$cwidgetinstances = $this->getCWidgetInstances();
		foreach($cwidgetinstances as $id => $cwidgetinstance) {
			if(($cwidgetinstance->param('name')=="CSection") &&
				($cwidget = $cwidgetinstance->getCWidget()) && 
				($csection = $cwidget->getCSection()))
				$csections[] = $csection;
		} // end foreach()
		return $csections;
	} // end getCSections()
	
	public function innerhtml() { 
		return CSection_doBody($this);
	} // end innerhtml()
	
	public function admin_body() {}
	
	// returns lightweight csection object by id
	static public function getCSectionByName($strname) { 
		return (($celements = CElement::getCElements())) ? ($celements[$strname]) : NULL; 
	} // end getCSectionByName()
} // end CSection
?>