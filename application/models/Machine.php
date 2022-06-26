<?php 

Class Machine extends CI_Model {
	// Read Data from the company table
	public function read_machine() {
		try
		{
			$this->db->select('*');
			$this->db->from('machine');
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

	// Read Data from the company table
	public function read_machine_by_location($loc) {
		try
		{
			$this->db->select('*');
			$this->db->from('machine');
			$this->db->where('isactive', 1);
            $this->db->where('location', $loc);
            $this->db->order_by('machineno', 'ASC');
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