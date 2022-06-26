<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends CI_Controller {

        public function __construct()
        {
                parent::__construct();
                $this->load->helper(array('form', 'url'));
                $this->load->helper('form');
                
                $this->load->library('table');
                
                $this->load->model('login_database');
                $this->load->model('item');
        }

        public function index()
        {
                //$this->load->view('upload_form', array('error' => ' ' ));
        }

        public function do_upload($filename)
        {
        		$username = ($this->session->userdata['logged_in']['username']);
        	
                $config['upload_path']          = './uploads/';
                //$config['allowed_types']        = 'gif|jpg|png';
                $config['allowed_types']        = 'jpg';
                $config['max_size']             = 100;
                $config['max_width']            = 1024;
                $config['max_height']           = 768;
                $config['file_name']            = $filename;
                $config['overwrite']            = TRUE;

                $this->load->library('upload', $config);

                if ( ! $this->upload->do_upload('userfile'))
                {
                        $error = array('error' => $this->upload->display_errors());
						
                        $data['active_menu'] = $this->login_database->read_user_menu($username,'MS');
                        $data['page'] = 9;
                        $data['error'] = $error;
                        $this->load->view('main_page', $data);
                }
                else
                {
                        $data = array('upload_data' => $this->upload->data());

                        $data['active_menu'] = $this->login_database->read_user_menu($username,'MS');
                        $data['page'] = 9;
                        
                        $data['upload_data'] = $this->upload->data();
                 
                        $this->load->view('main_page', $data);
                }
        }
}
?>