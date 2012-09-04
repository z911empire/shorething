<?php
class Assignment extends CI_Model {
	
	var $label		= '';
	var $filepath	= '';
	var $class_id	= 0;
	var $submitted  = '';
	
	function __construct() {
        parent::__construct();
    }

	function add_assignment($label, $filepath, $class_id) {
		$this->label		=$label;
		$this->filepath		=$filepath;
		$this->class_id		=$class_id;
		$this->submitted	=date("Y-m-d H:i:s");
		
		$this->db->insert('assignment',$this);
	}
	
	function update_assignment($id, $label, $filepath, $class_id) {
		$this->label		=$label;
		$this->filepath		=$filepath;
		$this->class_id		=$class_id;
		$this->submitted	=date("Y-m-d H:i:s");		
		
		$this->db->update('assignment',$this, array('id'=>$id));
	}
}
?>
