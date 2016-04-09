<?php 
//----------------------------------------------------------------
// file: cwidgetmemory.php
// desc: defines the theme memory object 
//----------------------------------------------------------------
class CWidgetMemory extends CMemory{
	protected $m_strtype;
	protected $m_inum;
		
	// construct() / open() / close()
	public function CWidgetMemory(){ 
		parent :: CMemory(); 
		$this->m_strtype="";
		$this->m_inum=-1;
	} // end CArrayMemory()
	
	public function open($strpath, $params=NULL){
		if(!$strpath)
			return false;
		$typenum = explode("/",$strpath);
		if(!$typenum || !isset($typenum[0]) || !isset($typenum[1]))
			return false; 
		$type = $typenum[0];
		$num = $typenum[1];
		if(!$widgets=get_option($type) || !isset($widgets[$num]) || !parent :: open($strpath, $params)) {
		   return false;
		} // end if  
		$this->m_strtype=$type;
		$this->m_inum=$num;  
		return true;
	} // end open()
	
	public function close(){
		$this->save(); 
	} // end close()
	
	// create() / retrive() / update() / delete()
	public function create( $strname, $value, $strtype="NA" ){ 
		if(CWidget_option($this->m_strtype, $this->m_inum, $strname) != "")
			return NULL;
		CWidget_option($this->m_strtype, $this->m_inum, $strname, $value);
		return ( $this->save() ) ? array( "m_strname"=>$strname, "m_value"=>$value, "m_strtype"=>$strtype ) : NULL;
	} // end create()

	public function retrieve( $strname ){ 
		$value = CWidget_option($this->m_strtype, $this->m_inum, $strname);
		return ( $this->restore() == FALSE || $value == "" ) ?
			NULL : array("m_strname"=>$strname, "m_value"=>$value, "m_strtype"=>"NA" );
	} // end retrieve()
	
	public function update( $strname, $value, $strtype="NA" ){ 
		if( CWidget_option($this->m_strtype, $this->m_inum, $strname) == "" )
			return NULL;
		CWidget_option($this->m_strtype, $this->m_inum, $strname, $value);
		return ( $this->save() ) ? array( "m_strname"=>$strname, "m_value"=>$value, "m_strtype"=>$strtype ) : NULL;
	} // end update()

	public function delete( $strname ){ 
		if( CWidget_option($this->m_strtype, $this->m_inum, $strname) == "" )
			return false;
		CWidget_removeOption($this->m_strtype, $this->m_inum, $strname);
		return $this->save(); // update the file
	} // end delete()
	
	// misc. methods
	public function error(){
	} // end error()
	public function toString(){ 
		return print_r(CWidget_options($this->m_strtype, $this->m_inum),true); 
	} // end toString()
	public function toJSON(){ 
		return json_encode(CWidget_options($this->m_strtype, $this->m_inum)); 
	} // end toString()
	public function serialize( $value ){ 
		return ( gettype( $value ) == "object" ) ? serialize( $value ) : $value; 
	} // end serialize()
	public function unserialize( $value, $strtype ){ 
		return ( $strtype == "object" ) ? unserialize( $value ) : $value; 
	} // end unserialize()  
	public function restore(){
		return true;
	} // end restore()
	public function save(){
		return true;		
	} // end save()
} // end CWidgetMemory

function include_cwidget_memory($strid, $strpath, $params=NULL){
	return include_memory($strid, $strpath, "CWidgetMemory", $params);
} // end include_cwidget_memory()
?>