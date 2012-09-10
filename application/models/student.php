<?php
class Student extends CI_Model {

	public function login($fullname) {
	
		$sql="SELECT id, firstname, lastname FROM student WHERE REPLACE(UPPER(CONCAT(firstname,lastname)),' ','') = '".strtoupper(str_replace(' ', '', $fullname))."' LIMIT 1;";
		$query = $this->db->query($sql);
		
		if($query->num_rows() == 1) {
			$this->_recordVisit($query);
			return $query->result();
		} else {
			return false;
		}
	}
	
	private function _recordVisit(&$query) {
		$studentID = $query->row(0)->id;
		$ip		   = $this->_getRealIpAddr();
		$metricSQL = "INSERT INTO metric VALUES (NULL, 1, '$studentID', '$ip', NOW());";			
		$this->db->query($metricSQL);		
	}
	
	private function _getRealIpAddr() {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { //to check ip is pass from proxy
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
		  $ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
}
?>
