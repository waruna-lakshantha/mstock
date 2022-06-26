<?php 

Class Item extends CI_Model {
	// Read Data from the company table
	public function read_item() {
		try
		{
			$condition = "isactive = 1";
			$this->db->select('*');
			$this->db->from('item');
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
	
	// Read Data from the company table
	public function read_item_by_no($no) {
		try
		{
			$this->db->select('*');
			//$this->db->from('item');
			$this->db->where('isactive', 1);
			$this->db->where('no', $no);
			$query = $this->db->get('item');
			
			return $query->row();
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	// Read Data from the company table
	public function read_item_by_code($no) {
		//try
		//{
			$this->db->select('*');
			//$this->db->from('item');
			$this->db->where('isactive', 1);
			$this->db->where('itemcode', $no);
			$query = $this->db->get('item');
			
			return $query->row();
		/*}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}*/
	}
	
	// Read Data from the item table all active/inactive
	public function read_item_all() {
		try
		{
			$this->db->select('no, itemcode as `Item Code`, Description, Uom, catno, isstockmaintain, isactive, 
								"-" as `Image`, "-" as `Edit`, ifnull(isasset,0) as isasset, ifnull(isservice,0) as isservice, 
								itemcode, whcode, minlevel, maxlevel, rol, roq, lt, shp');
			$this->db->from('item');
			
			return $this->db->query($this->db->get_compiled_select());			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	public function item_update($hdr) {
		
		$return_Status = array();
		
		try
		{
			
			$hdr_ass = array();			
			
			$this->db->trans_start();
			
			$no = $hdr[0]['item_no'];
			
			if ($no === "0"){
				if(!empty($this->read_item_by_code($hdr[0]['code']))){
					$this->db->trans_rollback();
					
					$return_Status[0] = "0";
					$return_Status[1] = "Item Code Already Exists";
					$return_Status[2] = "";
					$return_Status[3] = "";
					
					return $return_Status;
				}
			}
			
		
			$code = $hdr[0]['code'];
			$description = $hdr[0]['des'];
			$uom = $hdr[0]['uom'];
			$catno = $hdr[0]['item_cat'];
			$isstockmaintain = $hdr[0]['stock'];
			$isactive = $hdr[0]['active'];
			$isasset = $hdr[0]['asset'];
			$isservice = $hdr[0]['service'];
			$whcode = $hdr[0]['wherehouse'];
			$minlevel = $hdr[0]['min_level'];
			$maxlevel = $hdr[0]['max_level'];
			$rol = $hdr[0]['rol'];
			$roq = $hdr[0]['roq'];
			$lt = $hdr[0]['lt'];
			$shp = $hdr[0]['shp'];
			
			if ($no === "0"){			
				
				$hdr_ass['itemcode'] = $code;
				$hdr_ass['description'] = $description;
				$hdr_ass['uom'] = $uom;
				$hdr_ass['catno'] = $catno;
				$hdr_ass['isstockmaintain'] = $isstockmaintain;
				$hdr_ass['isactive'] = $isactive;
				$hdr_ass['isasset'] = $isasset;
				$hdr_ass['isservice'] = $isservice;
				$hdr_ass['whcode'] = $whcode;
				$hdr_ass['minlevel'] = $minlevel;
				$hdr_ass['maxlevel'] = $maxlevel;
				$hdr_ass['rol'] = $rol;
				$hdr_ass['roq'] = $roq;
				$hdr_ass['lt'] = $lt;
				$hdr_ass['shp'] = $shp;
				$hdr_ass['no'] = $no;
				
				$this->db->insert('item',$hdr_ass);
				
				$id = $this->db->insert_id();
				
				$hdr[0]['item_no'] = $id;
				$no = $id;
			}
			else 
			{			
				$this->db->set('description', $description);
				$this->db->set('uom', $uom);
				$this->db->set('catno', $catno);
				$this->db->set('isstockmaintain', $isstockmaintain);
				$this->db->set('isactive', $isactive);
				$this->db->set('isasset', $isasset);
				$this->db->set('isservice', $isservice);
				$this->db->set('whcode', $whcode);
				$this->db->set('minlevel', $minlevel);
				$this->db->set('maxlevel', $maxlevel);
				$this->db->set('rol', $rol);
				$this->db->set('roq', $roq);
				$this->db->set('lt', $lt);
				$this->db->set('shp', $shp);
				$this->db->where('no', $no);
				$this->db->update('item');
			}
			
			$this->db->trans_complete();
			
			if ($this->db->trans_status() === FALSE)
			{
				log_message('error', $e->getMessage());
				
				$this->db->trans_rollback();
				
				$return_Status[0] = "0";
				$return_Status[1] = $e->getMessage();
				$return_Status[2] = "";
				$return_Status[3] = "";
				
				return $return_Status;
			}
			else{
				
				$return_Status[0] = "1";
				$return_Status[1] = "Successfuly Updated";
				$return_Status[2] = "";
				$return_Status[3] = "";
				
				return $return_Status;
			}
			
		}
		catch(Exception $e)
		{
			
			log_message('error', $e->getMessage());
			
			$this->db->trans_rollback();
			
			$return_Status[0] = "0";
			$return_Status[1] = $e->getMessage();
			$return_Status[2] = "";
			
			return $return_Status;
			
		}
		
	}
	
}

?>