<?php 

Class Uom extends CI_Model {
	// Read Data from the company table
	public function read_uom() {
		try
		{
			$condition = "isactive = 1";
			$this->db->select('*');
			$this->db->from('uom');
			$this->db->where($condition);
			$this->db->order_by('uom');
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