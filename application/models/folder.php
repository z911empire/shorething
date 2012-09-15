<?php
class Folder extends CI_Model {
	
	var $label		= '';
	var $teacher_id = 0;

	function __construct() {
        parent::__construct();
    }

	function add_folder($label, $teacher_id) {
		$this->label		=$label;
		$this->teacher_id	=$teacher_id;
		$this->db->insert('folder',$this);
	}
	
	function delete_folder($id) {
		$this->db->delete('folder',array('id'=>$id));
		$this->db->delete('folder_assignment',array('folder_id'=>$id)); # cascade on delete, no?
	}
	
	function get_folder($id) {
		$sql	=	"SELECT f.id, f.label, t.id AS 'teacher_id', t.firstname, t.lastname FROM folder f, teacher t WHERE f.id=$id AND f.teacher_id=t.id LIMIT 1;";
		$query 	=	$this->db->query($sql);
		return $query->row(0);
	}
	
	function map_folder($label, $teacher_id, $assignment_id) {
		$sql	=	"INSERT INTO folder_assignment VALUES((SELECT id FROM folder WHERE label='$label' AND teacher_id=$teacher_id),$assignment_id);";
		$query 	=	$this->db->query($sql);
	}
	
	function update_folder($id, $label, $teacher_id) {
		$this->label		=$label;
		$this->teacher_id	=$teacher_id;
		$this->db->update('folder',$this,array('id'=>$id));
	}
	
	function unmap_folder($label, $teacher_id, $assignment_id) {
		$sql	=	"DELETE FROM folder_assignment WHERE folder_id=(SELECT id FROM folder WHERE label='$label' AND teacher_id=$teacher_id) AND assignment_id=$assignment_id;";
		error_log($sql,0);
		$query 	=	$this->db->query($sql);
	}
}
?>