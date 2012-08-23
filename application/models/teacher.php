<?php
class Teacher extends CI_Model {

	public function login($fullname, $password) {
	
		$sql="SELECT id, firstname, lastname FROM teacher WHERE password=SHA1('".$password."') AND UPPER(CONCAT(firstname,lastname)) LIKE '".strtoupper(str_replace(' ', '', $fullname))."' LIMIT 1;";

		$query = $this->db->query($sql);
		
		if($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
}
?>
