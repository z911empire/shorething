<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Entrance extends CI_Controller {

	public function __construct() { 
		parent::__construct();
	}

	public function index() {
		$this->load->view('v_header',array('title'=>'Shorething Entrance'));
		$this->load->view('v_entrance');
		$this->load->view('v_footer');
	}

}
?>
