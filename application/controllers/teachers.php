<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Teachers extends CI_Controller {

	public function __construct() { 
		parent::__construct();
	}


	# /TEACHERS
	public function index() {
		$data['site_name']			= $this->config->item('site_name');
		$data['site_tagline']		= $this->config->item('site_tagline');		
		$data['title']				= $data['site_name'].': Teacher\'s Page';
		$data['activeLI']			= 'teachers/assignments';
		
		$this->_populateBasicData($data, true);
		
		$this->_drawIndexView($data);
	}
		# do basic user auth and start populating $data
		private function _populateBasicData(&$data, $bulk) {
			if ($this->session->userdata('logged_in')) {
				$data['firstname'] 	= $this->session->userdata('firstname');
				$data['lastname'] 	= $this->session->userdata('lastname');			
				if ($bulk) {
					$data['classes']	= $this->_loadClasses($this->session->userdata('id'));
					$data['assignments']= $this->_loadAssignments($this->session->userdata('id'));
					$data['folders']	= $this->_loadFolders($this->session->userdata('id'));
				}
				$data['navbar'] = $this->load->view('v_nav',$data,true);
			} else {
				redirect('teachers/entrance','refresh');	
			}
		}
			# get classes (and id's) for this teacher
			private function _loadClasses($teacher_id) {
				$sql	= "SELECT c.id, co.label FROM teacher t, class c, course co WHERE c.teacher_id=t.id AND t.id=$teacher_id AND c.course_id=co.id;";
				$query 	= $this->db->query($sql);
				$row	= $query->row();
				return array('id'=>$row->id, 'label'=>$row->label);
			}
			# get assignments for this teacher
			private function _loadAssignments($teacher_id) {
				$response=array();
				$sql	= "SELECT a.id, a.label, a.filepath, DATE_ADD(a.submitted, INTERVAL ".$this->config->item('time_add')." HOUR) AS submitted, t.firstname, t.lastname FROM class c, teacher t, assignment a WHERE a.class_id=c.id AND t.id=$teacher_id AND c.teacher_id=t.id ORDER BY a.sequence, a.submitted DESC;";
				$result=$this->db->query($sql);
				foreach ($result->result() as $row) {
					$folders=array();
					$folderSql="SELECT f.id, f.label FROM folder f, folder_assignment fa WHERE f.id=fa.folder_id AND fa.assignment_id=".$row->id." ORDER BY f.label ASC;";
					array_push($response,array("id"=>$row->id,"filepath"=>$row->filepath,"label"=>$row->label,"submitted"=>$row->submitted,"folders"=>$this->db->query($folderSql))); 
				}
				return $response;
			}
			# get folders created by this teacher
			private function _loadFolders($teacher_id) {
				$sql	= "SELECT f.id, f.label, COUNT(fa.folder_id) AS 'assignments_count' FROM folder f LEFT JOIN folder_assignment fa ON f.id=fa.folder_id WHERE f.teacher_id=$teacher_id GROUP BY f.label ORDER BY f.label ASC";
				return $this->db->query($sql);
			}
				
		# draw the views
		private function _drawIndexView(&$data) {
			$this->load->view('v_header', $data);
			$this->load->view('v_teachers', $data);
			$this->load->view('v_footer');
		}

	# /TEACHERS/ENTRANCE (TEACHER LOGIN PAGE)
	public function entrance() {
		$this->load->library('form_validation');
		$this->_entranceViews();
	}

		private function _entranceViews() {
			$data['site_name']			= $this->config->item('site_name');
			$data['site_tagline']		= $this->config->item('site_tagline');
			$data['title']				= $data['site_name'].': Teacher\'s Entrance';
			
			$this->load->view('v_header',$data);
			$this->load->view('v_teacherslogin');
			$this->load->view('v_footer');				
		}

/************************************
*            AUTHENTICATION
*************************************/

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
					'gender'	=> $row->gender,
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

/************************************
*            ASSIGNMENTS
*************************************/

	public function assignments($action, $id=-1) { 
		$this->load->library('form_validation');
		
		$data['site_name']			= $this->config->item('site_name');
		$data['site_tagline']		= $this->config->item('site_tagline');		
		$data['title']				= $data['site_name'].': '.ucfirst($action).' Assignment';
		$data['action']				= $action;
		$data['activeLI']			= 'teachers/assignments';		
		
		if ($action!="add") {
			# MODIFY | DELETE - only one at a time for now
			$this->load->model('assignment','',TRUE);	
			$data['assignment']		= $this->assignment->get_assignment($id);
		} 
		
		$this->_populateBasicData($data, true);
		
		$this->_drawAssignmentView($data);
	}
	
		# draw the views
		private function _drawAssignmentView($data) {
			$this->load->view('v_header', $data);
			$this->load->view('v_assignments', $data);
			$this->load->view('v_footer');
		}

	# (INTERNAL USE: PROCESS ASSIGNMENTS ACTIONS) - Great Candidate for subcontrollers
	public function assignmentsEngine() {
		$this->load->model('assignment','',TRUE);
		$this->load->library('form_validation');
		
		$action 				= ($this->input->post('action')) ? $this->input->post('action') : "add";
		$class_id 				= $this->input->post('class_id');
		$assignment_label 		= $this->input->post('assignment_label');
	
		$this->form_validation->set_rules('assignment_label', 'Assignment Name', 'trim|required|xss_clean');
		
		if ($action=="add") {
			$this->form_validation->set_rules('assignment_filepath', 'Assignment File','callback_verify_upload');
		}

		# validate the label and class if it's an 'add' or a 'modify'
		if (($action=="add" || $action=="modify") && $this->form_validation->run() == FALSE) {
			$this->assignments($action, $this->input->post('assignment_id'));
			return false;
		} 

		switch ($action) {
		case "add":
			$upload_data = $this->upload->data();
			$this->assignment->add_assignment($assignment_label,$upload_data['file_name'],$class_id);
			redirect('teachers','refresh');			
		break;
		case "delete":
			$assignment_id		 	= $this->input->post('assignment_id');
			$assignment_filepath 	= $this->input->post('filepath');
			$this->assignment->delete_assignment($assignment_id);
			if (!unlink("upload/".$assignment_filepath)) {
				error_log("File already gone.",0);
			}
			redirect('teachers','refresh');
		break;
		case "makelast":
			$mover_id				= $this->input->post('mover_id');
			$this->assignment->makelast_assignment($mover_id);
			echo "teachers";
		break;
		case "modify":
			$assignment_id		 	= $this->input->post('assignment_id');	
			$this->assignment->update_assignment($assignment_id,$assignment_label);
			redirect('teachers','refresh');	
		break;
		case "reorder":
			$mover_id				= $this->input->post('mover_id');
			$moved_id				= $this->input->post('moved_id');
			$this->assignment->reorder_assignments($mover_id, $moved_id);
			echo "teachers";
		break;
		}
	}
		public function verify_upload($assignment_filepath) {
			$config['upload_path'] 		= './upload/';
			$config['allowed_types'] 	= 'pdf|doc|docx|xls|xlsx|ppt|pptx|txt|jpeg|jpg|bmp|gif|png';
			# $config['max_size'] 		= 0; # 0 = no limit, defined in web server config (php.ini)
	
			$this->load->library('upload',$config);
			if (!$this->upload->do_upload("assignment_filepath")) {
				$this->form_validation->set_message('verify_upload', $this->upload->display_errors());
				return false;		
			} else {
				return true;
			}
			#$upload_data = $this->upload->data();	
		}
	
/**********************************
*            FOLDERS
***********************************/
	
	public function folders($action="add", $id=-1) { 
		$this->load->library('form_validation');
		$data['site_name']			= $this->config->item('site_name');
		$data['site_tagline']		= $this->config->item('site_tagline');		
		$data['title']				= $data['site_name'].': '.ucfirst($action).' Folder';
		$data['action']				= $action;
		$data['activeLI']			= 'teachers/folders';
		
		if ($action!="add") {
			# MODIFY | DELETE - only one at a time for now
			$this->load->model('folder','',TRUE);
			$data['folder']		= $this->folder->get_folder($id);
		} 
		
		$this->_populateBasicData($data, true);
		
		$this->_drawFolderView($data);
	}
	
		# draw the views
		private function _drawFolderView($data) {
			$this->load->view('v_header', $data);
			$this->load->view('v_folders', $data);
			$this->load->view('v_footer');
		}

	# (INTERNAL USE: PROCESS FOLDER ACTIONS)
	public function foldersEngine() {
		$this->load->model('folder','',TRUE);
		$this->load->library('form_validation');

		$action 			= ($this->input->post('action')) ? $this->input->post('action') : "add";
		$folder_label 		= $this->input->post('folder_label');
		$folder_teacher_id	= $this->session->userdata('id');
		$this->form_validation->set_rules('folder_label', 'Folder Name', 'trim|required|xss_clean');
		# need multiple tables for is_unique
		
		# validate the label and class
		if (($action=="add" || $action=="modify") && $this->form_validation->run() == FALSE) {
			$this->folders($action, $this->input->post('folder_id'));
			return false;
		} 

		switch ($action) {
		case "add":
			$this->folder->add_folder($folder_label,$folder_teacher_id);
			redirect('teachers/folders','refresh');			
		break;
		case "delete":
			$folder_id		 	= $this->input->post('folder_id');
			$this->folder->delete_folder($folder_id);
			redirect('teachers/folders','refresh');
		break;
		case "map":
			$assignment_id		= $this->input->post('assignment_id');
			$this->folder->map_folder($folder_label,$folder_teacher_id,$assignment_id);
			echo "teachers";
		break;	
		case "modify":
			$folder_id		 	= $this->input->post('folder_id');	
			$this->folder->update_folder($folder_id,$folder_label,$folder_teacher_id);
			redirect('teachers/folders','refresh');	
		break;
		case "unmap":
			$assignment_id		= $this->input->post('assignment_id');
			$this->folder->unmap_folder($folder_label,$folder_teacher_id,$assignment_id);
			echo "teachers";
		break;			
		}	
	}
	
}
?>
