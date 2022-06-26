<?php 

Class Department extends CI_Model {	
	// Read Data from the company table
	public function read_department() {
		try
		{
			$this->db->select('*');
			$this->db->from('department');
			$this->db->where('isactive', 1);
			$query = $this->db->get();
			
			//if ($query->num_rows() >= 1) {
			return $query->result();
			//} else {
			//return false;
			//}
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	// Read Data from the department table for the loged in user
	public function read_user_department() {
		try
		{
			
			$dept;
			
			if (isset($this->session->userdata['logged_in'])) {
				$dept = ($this->session->userdata['logged_in']['dept']);
			} else {
				header("location: ".base_url()."index.php/User_Authentication/user_login_process");
			}
			
			$this->db->select('*');
			$this->db->from('department');
			$this->db->where('isactive', 1);
			$this->db->where('no', $dept);
			$query = $this->db->get();
			
			//if ($query->num_rows() >= 1) {
				return $query->result();
			//} else {
				//return false;
			//}
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	// Read Data from the department table for the loged in user
	public function read_store_department() {
		try
		{		
			
			$this->db->select('*');
			$this->db->from('department');
			$this->db->where('isactive', 1);
			$this->db->where('no', 4);
			$query = $this->db->get();
			
			//if ($query->num_rows() >= 1) {
			return $query->result();
			//} else {
			//return false;
			//}
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
}

?>