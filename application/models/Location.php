<?php 

Class Location extends CI_Model {
	// Read Data from the location table
	public function read_location() {
		try
		{
			$this->db->select('*');
			$this->db->from('location');
			$this->db->where('isactive', 1);
            $this->db->order_by('location', 'ASC');
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