<?php

Class Currency extends CI_Model {
	// Read Data from the currencytable
	public function read_currency() {
		try
		{
			$condition = "isactive = 1";
			$this->db->select('*');
			$this->db->from('currency');
			$this->db->where($condition);
			$this->db->order_by('curcode', 'DESC');
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
	
	// Read Data from the selected currency
	public function read_selected_currency($no) {
		try
		{
			$this->db->select('*');
			$this->db->from('currency');
			$this->db->where('isactive', 1);
			$this->db->where('curcode', $no);
			$query = $this->db->get();
			
			//if ($query->num_rows() >= 1) {
			return $query->row();
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