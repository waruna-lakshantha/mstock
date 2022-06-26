<?php 

Class Wherehouse extends CI_Model {
	
	public function read_wherehouse() {
			$this->db->select('*');
			$this->db->from('wherehouse');
			$this->db->where('isactive', 1);
			$this->db->order_by('description');
			$query = $this->db->get();
			
			//if ($query->num_rows() >= 1) {
			return $query->result();
			//} else {
			//return false;
			//}
	}
	
	public function read_wherehouse_by_no($wh_no) {
		$this->db->select('*');
		$this->db->from('wherehouse');
		$this->db->where('no', $wh_no);
		$query = $this->db->get();
		
		$row = $query->row();
		
		return $row;
	}
	
}

?>