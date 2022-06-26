<?php 

Class Company extends CI_Model {
	// Read Data from the company table
	public function read_company() {
		try
		{
			$condition = "isactive = 1";
			$this->db->select('*');
			$this->db->from('company');
			$this->db->where($condition);
			$this->db->order_by('no', 'ASC');
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
	
	// Read Data from the selected company
	public function read_selected_company($no) {
		try
		{
			$this->db->select('*');
			$this->db->from('company');
			$this->db->where('isactive', 1);
			$this->db->where('no', $no);
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