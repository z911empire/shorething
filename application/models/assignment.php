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
	
	function get_assignment($id) {
		$sql	=	"SELECT a.id, a.label, a.filepath FROM assignment a WHERE a.id=$id LIMIT 1;";
		$query 	=	$this->db->query($sql);
		return $query->row(0);
	}
	
	function update_assignment($id, $label, $filepath, $class_id) {
		$this->label		=$label;
		$this->filepath		=$filepath;
		$this->class_id		=$class_id;
		$this->submitted	=date("Y-m-d H:i:s");		
		
		$this->db->update('assignment',$this,array('id'=>$id));
	}
	
	function delete_assignment($id) {
		$this->db->delete('assignment',array('id'=>$id));
	}
}
?>
