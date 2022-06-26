<?php

Class Login_Database extends CI_Model {

	// Insert registration data in database
	public function registration_insert($data) {
	
		// Query to check whether username already exist or not
		$condition = "user_name =" . "'" . $data['user_name'] . "'";
		$this->db->select('*');
		$this->db->from('user_login');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
		
			// Query to insert data in database
			$this->db->insert('user_login', $data);
			if ($this->db->affected_rows() > 0) {
				return true;
			}
		} else {
			return false;
		}
	}

	// Read data using username and password
	public function login($data) {
	
		$condition = "user_name =" . "'" . $data['username'] . "' AND " . "user_password =" . "'" . $data['password'] . "'";
		$this->db->select('*');
		$this->db->from('user_login');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if ($query->num_rows() == 1) {
			return true;
		} else {
			return false;
		}
	}

	// Read data from database to show data in admin page
	public function read_user_information($username) {
	
		$condition = "user_name =" . "'" . $username . "'";
		$this->db->select('*');
		$this->db->from('user_login');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	// Read user menu
	public function read_user_menu($username,$system) {
		try
		{	
			$condition = "um.userid =" . "'" . $username . "' AND um.systemid =" . "'" . $system. "'";
			$this->db->select('um.menuid, m.menuname, um.canview, um.canadd, um.canedit, um.caninactive, m.category');
			$this->db->from('usermenu um');
			$this->db->join('systemmenu m', 'um.menuid = m.menuid');
			$this->db->where($condition);
            $this->db->order_by('m.displayorder', 'ASC');

			//$this->db->limit(1);
			$query = $this->db->get();
			
			if ($query->num_rows() >= 1) {
				return $query->result();
			} else {
				return false;
			}
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return false;
		}
	}
	
	// Read user report menu
	public function read_user_menu_report($username,$system) {
		try
		{
			$condition = "um.userid =" . "'" . $username . "' AND um.systemid =" . "'" . $system. "' AND m.category = 'R'";
			$this->db->select('um.menuid, m.menuname, um.canview, um.canadd, um.canedit, um.caninactive, m.category');
			$this->db->from('usermenu um');
			$this->db->join('systemmenu m', 'um.menuid = m.menuid');
			$this->db->where($condition);
			$this->db->order_by('m.displayorder', 'ASC');
			
			//$this->db->limit(1);
			$query = $this->db->get();
			
			if ($query->num_rows() >= 1) {
				return $query->result();
			} else {
				return false;
			}
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return false;
		}
	}

}

?>