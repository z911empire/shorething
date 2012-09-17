<?php
class Assignment extends CI_Model {
	
	var $label		= '';
	var $status		= 1;
	var $sequence	= 1;
	var $filepath	= '';
	var $class_id	= 0;
	var $submitted  = '';
	
	function __construct() {
        parent::__construct();
    }

	function add_assignment($label, $filepath, $class_id) {
		$this->label		=$label;
		$this->status		=1;
		$this->sequence		=1;
		$this->filepath		=$filepath;
		$this->class_id		=$class_id;
		$this->submitted	=date("Y-m-d H:i:s");
		
		$this->db->insert('assignment',$this);
		
		# insert new rows with lowest sequence
		$new_id=$this->db->insert_id();
		$sql	=	"UPDATE assignment SET sequence=sequence+1 WHERE class_id=$class_id AND id <> $new_id;";
		$query 	=	$this->db->query($sql);
	}
	
	function get_assignment($id) {
		$sql	=	"SELECT a.id, a.label, a.status, a.sequence, a.filepath FROM assignment a WHERE a.id=$id LIMIT 1;";
		$query 	=	$this->db->query($sql);
		return $query->row(0);
	}
	
	function makelast_assignment($mover_id) {
		$sql	=	"SELECT class_id,sequence,(SELECT MAX(sequence) FROM assignment WHERE class_id=(SELECT class_id FROM assignment WHERE id=$mover_id)) AS 'maxsequence' FROM `assignment` WHERE id=$mover_id;";
		$query			=	$this->db->query($sql);
		$row			=	$query->row();
		$class_id		=	$row->class_id;
		$sequence		= 	$row->sequence;
		$max_sequence	=	$row->maxsequence;
		
		# slide all sequences back to make room for mover_id in the sequence
		$sql	=	"UPDATE assignment SET sequence=sequence-1 WHERE class_id=$class_id AND sequence>=$sequence;";
		$query 	=	$this->db->query($sql);
		# make the mover equal to the max sequence
		$sql	=	"UPDATE assignment SET sequence=$max_sequence WHERE id=$mover_id;";
		$query 	=	$this->db->query($sql);		
	}
	
	function reorder_assignments($mover_id, $moved_id) {
		$sql	= 	"SELECT class_id, sequence FROM assignment WHERE id=$moved_id;";
		$query	=	$this->db->query($sql);
		$row	=	$query->row();
		$class_id		=	$row->class_id;
		$moved_sequence	=	$row->sequence;
		
		# slide all sequences back to make room for mover_id in the sequence
		$sql	=	"UPDATE assignment SET sequence=sequence+1 WHERE class_id=$class_id AND sequence>=$moved_sequence;";
		$query 	=	$this->db->query($sql);
		# make the mover equal to the moved sequence - 1 (ie, the moved's old sequence)
		$sql	=	"UPDATE assignment SET sequence=$moved_sequence WHERE id=$mover_id;";
		$query 	=	$this->db->query($sql);
	}
	
	function update_assignment($id, $label, $status, $filepath, $class_id) {
		$this->label		=$label;
		$this->status		=$status;
		$this->filepath		=$filepath;
		$this->class_id		=$class_id;
		$this->submitted	=date("Y-m-d H:i:s");		
		
		$this->db->update('assignment',$this,array('id'=>$id));
	}
	
	function delete_assignment($id) {
		$this->db->delete('assignment',array('id'=>$id));
		$this->db->delete('folder_assignment',array('assignment_id'=>$id)); # cascade on delete no?
	}
}
?>
