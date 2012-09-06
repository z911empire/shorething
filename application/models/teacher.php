<?php
class Teacher extends CI_Model {

	public function login($fullname, $password) {
	
		$sql="SELECT id, firstname, lastname, gender FROM teacher WHERE password=SHA1('".$password."') AND (UPPER(CONCAT(firstname,lastname))='".strtoupper(str_replace(' ', '', $fullname))."' OR UPPER(CONCAT(SUBSTRING(firstname,1,1),lastname))='".strtoupper($fullname)."') LIMIT 1;";
		$query = $this->db->query($sql);
		
		if($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
}
?>
