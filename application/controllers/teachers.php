<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Teachers extends CI_Controller {

	public function __construct() { 
		parent::__construct();
	}

	public function index() {
		if ($this->session->userdata('logged_in')) {
			$data['firstname'] 	= $this->session->userdata('firstname');
			$data['lastname'] 	= $this->session->userdata('lastname');			
			
			$data['classes']	= $this->_loadClasses($this->session->userdata('id'));
			$this->load->view('v_header',array('title'=>'Shorething Teacher\'s Page'));
			$this->load->view('v_teachers', $data);
			$this->load->view('v_footer');
		} else {
			redirect('teachers/entrance','refresh');	
		}
	}
	
		private function _loadClasses($teacher_id) {
			$sql	= "SELECT t.class_id, c.label FROM teacher t, class c WHERE t.id=$teacher_id AND c.id=t.class_id;";
			$query 	= $this->db->query($sql);
			$row	= $query->row();
			return array('id'=>$row->class_id, 'label'=>$row->label);
		}
	
	public function entrance() {
		$this->load->library('form_validation');
		$this->_entranceViews();
	}

		private function _entranceViews() {
			$this->load->view('v_header',array('title'=>'Shorething Teacher\'s Login Page'));
			$this->load->view('v_teacherslogin');
			$this->load->view('v_footer');				
		}

	public function verifylogin() {
		$this->load->model('teacher','',TRUE);
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('fullname', 'Full Name', 'trim|required|xss_clean');
   		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_database');

		if($this->form_validation->run() == FALSE) {
			$this->_entranceViews(); // Field validation failed.
		} else {
			redirect('teachers', 'refresh'); // Go to private teachers area
		}
	}
	
	public function logout() {
		$this->session->set_userdata('logged_in',false);
		$this->session->sess_destroy();
		redirect('teachers/entrance','refresh');	
	}
	
	public function check_database($password) {
		$fullname = $this->input->post('fullname');
		$result = $this->teacher->login($fullname, $password);
		if ($result) {
			$sess_array = array();
			foreach ($result as $row) {
				$sess_array = array(
					'id'		=> $row->id,
					'firstname'	=> $row->firstname,
					'lastname'	=> $row->lastname,				
					'logged_in'	=> true
				);
			}
			$this->session->set_userdata($sess_array);
			return true;
		} else {
			$this->form_validation->set_message('check_database', 'Invalid username or password');
			return false;
		}
	}
}
?>
