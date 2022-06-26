<?php 

Class Vendor extends CI_Model {
	// Read Data from the company table
	public function read_vendor_all_active() {
		try
		{
			$this->db->select('*');
			$this->db->from('vendor');
			$this->db->where('isactive', 1);
			$this->db->where('name <> ""');	
			$query = $this->db->get();
			
			return $query->result();
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	// Read Data from the company table
	public function read_vendor_active_by_type($type) {
		try
		{
			$this->db->select('*');
			$this->db->from('vendor');
			$this->db->where('isactive', 1);
			$this->db->where('name <> ""');			
			
			if($type === 'L'){
				$this->db->where('localpurchase', 1);
			}
			
			if($type === 'I'){
				$this->db->where('import', 1);
			}
			
			$this->db->order_by('name', 'ASC');
						
			$query = $this->db->get();
			
			return $query->result();
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
}

?>