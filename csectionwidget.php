<?php
//---------------------------------------------------------------------------------
// file: csectionwidget.php
// desc: a widget that defines a section on the page
//---------------------------------------------------------------------------------

// includes
include_widget("CSectionWidget");

//---------------------------------------------------------------------------------
// class: CSectionWidget
// desc: a widget that defines a section on the page
//---------------------------------------------------------------------------------
class CSectionWidget extends CWidget {
	public function CSectionWidget() { 
		$this->create("CSectionWidget", "CSection", "This widget creates a section or sidebar dynamically"); 
	} // end CSectionWidget() 

	public function body() {
		if(!$this->m_cwidgetinstance || !($csection=$this->m_cwidgetinstance->param("csection")))		
			return;						
		echo $csection->innerhtml();
	} // end body()

	public function admin_body() {
		echo parent::admin_body();
		if(!$this->m_cwidgetinstance || !($ccontrols=$this->m_cwidgetinstance->getCControls()))
			return;
		echo "<p>";
		echo $ccontrols->label("csection-desc", "Description:");
		echo $ccontrols->text("csection-desc","");
		echo "</p>";
	} // end admin_body()
	
	public function init() {
		if(!$this->m_cwidgetinstance || 
			!($coptions=$this->m_cwidgetinstance->getCOptions()) ||
			($strname=$coptions->option("title")) == "") {
			return; 
		} // end if
		
		// set up the main sidebar params
		$sectionid = $this->id."-".$strname;
		$sectionname = $strname;
		$sectiondesc = $coptions->option("csection-desc");
		
		// create the section
		$csection = new CSection();
		$csection->create($sectionid, $sectionname, $sectiondesc);
		$csection->init();	
		
		// attach thie csection to this cwidgetinstance	
		$this->m_cwidgetinstance->param("csection", $csection);
		
		// make sure the class are properly defined
		$this->m_cwidgetinstance->addClass($sectionid);
		$this->m_cwidgetinstance->classes($this->m_cwidgetinstance->classes() . " csection");
		$this->m_cwidgetinstance->attr("classtype", "CSection");	
		return;	
	} // end init()
} // end CSectionWidget
?>