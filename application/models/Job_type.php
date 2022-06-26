<?php
Class Job_type extends CI_Model {
	// Read Data from the company table
	public function read_job_type() {
		try
		{
			$condition = "isactive = 1";
			$this->db->select('*');
			$this->db->from('job_type');
			$this->db->where($condition);
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