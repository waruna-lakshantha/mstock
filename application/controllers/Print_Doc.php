<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Print_Doc extends CI_Controller {
	
	public function __construct() {
		parent::__construct();		
		
		// Load form helper library
		$this->load->helper('url');
		
		// Load form helper library
		$this->load->helper('form');
		
		$this->load->helper('date');
		
		// Load form validation library
		$this->load->library('form_validation');
		
		$this->load->library('table');
		
		$this->load->model('login_database');
		
	}
	
	// Show Main page - Dashboard
	public function index() {
		
		if (isset($this->session->userdata['logged_in'])) {
			$username = ($this->session->userdata['logged_in']['username']);
			$data['page'] = '5';
			$data['active_menu'] = $this->login_database->read_user_menu($username,'MS');
			$this->load->view('main_page', $data);
		} else {
			header("location: ".base_url()."index.php/User_Authentication/user_login_process");
		}
		
	}
	
	// Show pages
	public function view_doc($doc_type, $no){
		if (isset($this->session->userdata['logged_in'])) {
			$username = ($this->session->userdata['logged_in']['username']);
			if($doc_type == 1){
				$this->load->model('pr');
				$data['doc_no'] = $no;
				$this->load->view('print_mrp', $data);			
			}
			else if ($doc_type == 2){
				$this->load->model('grn');
				$data['doc_no'] = $no;
				$this->load->view('print_grn', $data);				
			}			
			else if($doc_type == 3){
				$this->load->model('mrn');
				$data['doc_no'] = $no;
				$this->load->view('print_mrn', $data);
			}
			else if ($doc_type == 4){
				$this->load->model('min');
				$data['doc_no'] = $no;
				$this->load->view('print_min', $data);
			}
			else if ($doc_type == 11){
				$this->load->model('job_requisition');
				$data['doc_no'] = $no;
				$this->load->view('print_job', $data);
			}
			else if ($doc_type == 19){
				$this->load->model('stk_adj');
				$data['doc_no'] = $no;
				$this->load->view('print_stk_adj', $data);
			}
			else{
				/*$data['page'] = '5';
				$data['active_menu'] = $this->login_database->read_user_menu($username,'MS');
				$this->load->view('main_page', $data);*/
				echo "Invalied Request";
			}
			
		} else {
			header("location: ".base_url()."index.php/User_Authentication/user_login_process");
		}
	}

    // Genrate Uniq Id
    public function generate_unique_id($maxLength = null){
        $entropy = '';
        // try ssl first
        if (function_exists('openssl_random_pseudo_bytes')) {
            $entropy = openssl_random_pseudo_bytes(64, $strong);
            // skip ssl since it wasn't using the strong algo
            if($strong !== true) {
                $entropy = '';
            }
        }

        // add some basic mt_rand/uniqid combo
        $entropy .= uniqid(mt_rand(), true);

        // try to read from the windows RNG
        if (class_exists('COM')) {
            try {
                $com = new COM('CAPICOM.Utilities.1');
                $entropy .= base64_decode($com->GetRandom(64, 0));
            } catch (Exception $ex) {
            }
        }

        // try to read from the unix RNG
        if (is_readable('/dev/urandom')) {
            $h = fopen('/dev/urandom', 'rb');
            $entropy .= fread($h, 64);
            fclose($h);
        }

        $hash = hash('whirlpool', $entropy);
        if ($maxLength) {
            return substr($hash, 0, $maxLength);
        }
        return $hash;
    }

}
?>