<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Students extends CI_Controller {

	public function __construct() { 
		parent::__construct();
	}

	public function index() {
		$data['site_name']			= $this->config->item('site_name');
		$data['site_tagline']		= $this->config->item('site_tagline');		
		$data['title']				= $data['site_name'].': Student\'s Entrance';

		$this->_populateBasicData($data);
		
		$this->_drawIndexView($data);
	}
		# do basic user auth and start populating $data
		private function _populateBasicData(&$data) {
			if ($this->session->userdata('logged_in')) {			
				$data['firstname'] 			= $this->session->userdata('firstname');
				$data['lastname'] 			= $this->session->userdata('lastname');			
				$data['all_classes']		= $this->_loadAllClasses($this->session->userdata('id'));
				foreach($data['all_classes'] as $class) {
					$curclass=$class['class_id'];
					$data['all_assignments'][$curclass] = $this->_loadClassAssignments($curclass);
				}
			} else {
				redirect('students/entrance','refresh');	
			}
		}
			# get all the classes this student attends
			private function _loadAllClasses($student_id) {
				$all_classes=array();
				$sql= 	"SELECT c.id AS 'class_id', co.label, t.lastname, t.gender ".
						"FROM course co, class c, student_class sc, teacher t ".
						"WHERE co.id=c.course_id AND t.id=c.teacher_id AND sc.class_id=c.id AND sc.student_id=$student_id ".
						"ORDER BY co.label";
				$result	= $this->db->query($sql);

				foreach ($result->result() as $row) {
					array_push($all_classes,
						array('class_id'			=>$row->class_id, 
							  'course_label'		=>$row->label, 
							  'teacher_lastname'	=>$row->lastname, 
							  'teacher_gender'		=>$row->gender)
					);
				}
				return $all_classes;
			}
			# get all the assignments for each class this student attends
			private function _loadClassAssignments($class_id) {
				$class_assignments=array();
				$sql= 	"SELECT a.label, a.filepath, a.submitted ".
						"FROM assignment a, class c ".
						"WHERE a.class_id=c.id AND c.id=$class_id ".
						"ORDER BY a.sequence, a.submitted DESC";
				$result	= $this->db->query($sql);

				foreach ($result->result() as $row) {
					array_push($class_assignments,
						array('assignment_label'	=>$row->label, 
							  'assignment_filepath'	=>$row->filepath, 
							  'assignment_submitted'=>$row->submitted)
					);
				}
				return $class_assignments;
			}
				
		# draw the views
		private function _drawIndexView($data) {
			$this->load->view('v_header', $data);
			$this->load->view('v_students', $data);
			$this->load->view('v_footer');
		}
	
	# students/entrance (STUDENT LOGIN PAGE)
	public function entrance() {
		$this->load->library('form_validation');		
		$this->_entranceViews();
	}

		private function _entranceViews() {
			$data['site_name']			= $this->config->item('site_name');
			$data['site_tagline']		= $this->config->item('site_tagline');
			$data['title']				= $data['site_name'].': Student\'s Entrance';
			$data['links']				= array();
			
			# get links from the database table 'sitesetting'
			$sql= 	"SELECT ss.valueA AS 'linkurl', ss.valueB AS 'linklabel' ".
					"FROM sitesetting ss ".
					"WHERE ss.type=1 ".
					"ORDER BY ss.id;";
			$result	= $this->db->query($sql);
			
			foreach ($result->result() as $row) {
				array_push($data['links'],
					array('linkurl'				=>$row->linkurl, 
						  'linklabel'			=>$row->linklabel)
				);
			}
			
			$this->load->view('v_header',$data);
			$this->load->view('v_studentslogin');
			$this->load->view('v_footer');				
		}

	public function verifylogin() {
		$this->load->model('student','',TRUE);
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('fullname', 'Full Name', 'trim|required|xss_clean|callback_check_database');

		if($this->form_validation->run() == FALSE) {
			
			$this->_entranceViews(); // Field validation failed.
		} else {
			redirect('students', 'refresh'); // Go to private students area
		}
	}
	
	public function logout() {
		$this->session->set_userdata('logged_in',false);
		$this->session->sess_destroy();
		redirect('students/entrance','refresh');	
	}
	
	public function check_database($fullname) {
		$result = $this->student->login($fullname);
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
			$this->form_validation->set_message('check_database', 'Name not recognized.');
			return false;
		}
	}

}
?>
