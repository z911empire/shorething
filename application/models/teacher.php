<?php
class Teacher extends CI_Model {
	public function change_password($id, $old, $new, $confirm) {
		$sql   ="SELECT password AS 'current_password' FROM teacher WHERE id=$id;";
		$query = $this->db->query($sql);
		$row   = $query->row();
		
		if($row->current_password==sha1($old) && sha1($new)==sha1($confirm)) {
			$sql="UPDATE teacher SET password=SHA1('".$confirm."') WHERE id=$id;";
			$query = $this->db->query($sql);
			return true;
		} else {
			return false;
		}		
	}

	public function login($fullname, $password) {
		$sql="SELECT id, firstname, lastname, gender FROM teacher WHERE password=SHA1('".$password."') AND (UPPER(CONCAT(firstname,lastname))='".strtoupper(str_replace(' ', '', $fullname))."' OR UPPER(CONCAT(SUBSTRING(firstname,1,1),lastname))='".strtoupper($fullname)."') LIMIT 1;";
		$query = $this->db->query($sql);
		
		if($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	public function update_email($id, $email) {
		$email = (isset($email) && strlen($email)) ? "'".$email."'" : "NULL";
		$sql="UPDATE teacher SET email=$email WHERE id=$id;";
		$query = $this->db->query($sql);
		return true;
	}	
}
?>
