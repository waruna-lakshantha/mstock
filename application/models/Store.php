<?php 

Class Store extends CI_Model {
	
	public function read_store_item($comno, $depto, $storeno, $itemno) {
		
		$this->db->select('*');
		$this->db->from('stockmaster');
		$this->db->where('comno', $comno);
		//$this->db->where('departmentno', $depto);
		$this->db->where('storeno', $storeno);
		$this->db->where('itemno', $itemno);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	public function read_default_store() {
		
		$this->db->select('*');
		$this->db->from('storelocation');
		$this->db->where('isactive', 1);
		$this->db->where('isdeflocation', 1);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	public function update_store_item($data) {

		if($this->read_store_item($data['comno'], $data['departmentno'], $data['storeno'], $data['itemno']) == false)
		{			
			$this->db->insert('stockmaster',$data);
		}else{
			$inqty = 'inqty + ' . $data['inqty'];
			$outqty = 'outqty + ' . $data['outqty'];
			$this->db->set('inqty', $inqty, false);
			$this->db->set('outqty', $outqty, false);
			$this->db->where('comno', $data['comno']);
			$this->db->where('storeno', $data['storeno']);
			$this->db->where('itemno', $data['itemno']);
			$this->db->update('stockmaster');
		}
		
		$this->db->set('balqty', 'inqty - outqty', false);
		$this->db->where('comno', $data['comno']);
		//$this->db->where('departmentno', $data['departmentno']);
		$this->db->where('storeno', $data['storeno']);
		$this->db->where('itemno', $data['itemno']);
		$this->db->update('stockmaster');
					
	}
	
	public function read_store() {
			$this->db->select('*');
			$this->db->from('storelocation');
			$this->db->where('isactive', 1);
			$this->db->order_by('description');
			$query = $this->db->get();
			
			//if ($query->num_rows() >= 1) {
			return $query->result();
			//} else {
			//return false;
			//}
	}
	
	public function insert_stock_in_details($data){
		$this->db->insert('stockindetail',$data);
	}
	
	public function insert_stock_transaction($data){
		$this->db->insert('stocktransaction',$data);
	}
	
	public function read_com_loc_wise_stock_balance_item($data) {
		try
		{			
			
			$condition = "stockmaster.balqty > 0 and stockmaster.itemno = ". $data[0]['itemno'];
			$this->db->select('stockmaster.comno, company.description as `Company`, stockmaster.storeno, storelocation.code as `Store Code`, 
								storelocation.description as `Store`, stockmaster.itemno, item.itemcode as `Item Code`,
								item.description as `Item`, stockmaster.balqty as `Bal Qty`, 0 as `Issue Qty`');
			$this->db->from('stockmaster');
			$this->db->join('item', 'item.no = stockmaster.itemno');
			$this->db->join('company', 'company.no = stockmaster.comno');
			$this->db->join('storelocation', 'storelocation.no = stockmaster.storeno');
			$this->db->where($condition);
			
			$sql = "select a.comno, a.`Company`, a.storeno, a.`Store Code`, ";
			$sql .= "a.`Store`, a.itemno, a.`Item Code`, ";
			$sql .= "a.`Item`, sum(a.`Bal Qty`) as `Bal Qty`, a.`Issue Qty`, sum(a.`Hold Qty`) as `Hold Qty` ";
			$sql .= "from ";
			$sql .= "(select stockmaster.comno, company.description as `Company`, stockmaster.storeno, storelocation.code as `Store Code`, ";
					$sql .= "storelocation.description as `Store`, stockmaster.itemno, item.itemcode as `Item Code`, ";
					$sql .= "item.description as `Item`, stockmaster.balqty as `Bal Qty`, 0 as `Issue Qty`, 0 as `Hold Qty` ";
					$sql .= "from stockmaster ";
					$sql .= "inner join item on item.no = stockmaster.itemno ";
					$sql .= "inner join company on company.no = stockmaster.comno ";
					$sql .= "inner join storelocation on storelocation.no = stockmaster.storeno ";
					$sql .= "union all ";
					$sql .= "select stockmaster.comno, company.description as `Company`, stockmaster.storeno, storelocation.code as `Store Code`, ";
					$sql .= "storelocation.description as `Store`, stockmaster.itemno, item.itemcode as `Item Code`, ";
					$sql .= "item.description as `Item`, stockmaster.qty * -1 as `Bal Qty`, 0 as `Issue Qty`, stockmaster.qty as `Hold Qty` ";
					$sql .= "from stockhold as stockmaster ";
					$sql .= "inner join item on item.no = stockmaster.itemno ";
					$sql .= "inner join company on company.no = stockmaster.comno ";
					$sql .= "inner join storelocation on storelocation.no = stockmaster.storeno) a ";
					$sql .= "group by a.comno, a.`Company`, a.storeno, a.`Store Code`, ";
					$sql .= "a.`Store`, a.itemno, a.`Item Code`, ";
					$sql .= "a.`Item`, a.`Issue Qty` ";
					$sql .= "having (sum(a.`Bal Qty`) > 0 or sum(a.`Hold Qty`)) > 0 and ";
					$sql .= "a.itemno = ".$data[0]['itemno'];
				
			return $this->db->query($sql);
			
			//return $this->db->query($this->db->get_compiled_select());
			
			//if ($query->num_rows() >= 1) {
			//return $query->result();
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
	
	public function read_com_loc_wise_stock_balance_all() {
		try
		{
			
			$condition = "stockmaster.balqty > 0";
			$this->db->select('stockmaster.itemno, item.itemcode as `Item Code`, item.description as `Item`, stockmaster.comno, 
								company.description as `Company`, stockmaster.storeno, storelocation.code as `Store Code`,
								storelocation.description as `Store`, stockmaster.balqty as `Bal Qty`');
			$this->db->from('stockmaster');
			$this->db->join('item', 'item.no = stockmaster.itemno');
			$this->db->join('company', 'company.no = stockmaster.comno');
			$this->db->join('storelocation', 'storelocation.no = stockmaster.storeno');
			$this->db->where($condition);
			$this->db->order_by('item.itemcode ASC, company.description ASC, storelocation.description ASC');
			
			return $this->db->query($this->db->get_compiled_select());
			
			//if ($query->num_rows() >= 1) {
			//return $query->result();
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
	
	public function insert_hold_stock($data){
		
		$return_Status = array();
		
		try
		{
			
			$dtl_ass = array();
			
			/*guid
			 comno
			 storeno
			 itemno
			 qty
			 datetime
			 user
			 doctype
			 docno
			 docref*/
			
			$this->db->where('guid', $data[0]['guid']);
			$this->db->where('docno', $data[0]['docno']);
			$this->db->where('refcomno', $data[0]['refcomno']);
			$this->db->where('departmentno', $data[0]['departmentno']);
			$this->db->where('machineno', $data[0]['machineno']);
            $this->db->where('itemno', $data[0]['itemno']);
			$this->db->delete('stockhold');
			
			$ind = 0;
			
			for ($row = 0; $row < count($data); $row++) {
				
				if ($data[$row]['qty'] > 0){
					$dtl_ass[$ind] = array ('guid' => $data[$row]['guid'],
							'comno' => $data[$row]['comno'],
							'storeno' => $data[$row]['storeno'],
							'itemno' => $data[$row]['itemno'],
							'qty' => $data[$row]['qty'],
							'datetime' => $data[$row]['datetime'],
							'user' => $data[$row]['user'],
							'doctype' => $data[$row]['doctype'],
							'docno' => $data[$row]['docno'],
							'docref' => $data[$row]['docref'],
							'refcomno' => $data[$row]['refcomno'],
							'departmentno' => $data[$row]['departmentno'],
							'machineno' => $data[$row]['machineno']						
					);
					
					$ind++;
				}
				
			}
			
			$this->db->trans_start();						
			
			$this->db->insert_batch('stockhold',$dtl_ass);
			
			$this->db->trans_complete();
			
			if ($this->db->trans_status() === FALSE)
			{
				log_message('error', $e->getMessage());
				//return 0;
				
				$return_Status[0] = "0";
				$return_Status[1] = $e->getMessage();
				$return_Status[2] = "";
				
				return $return_Status;
			}
			else{
				$return_Status[0] = "1";
				$return_Status[1] = "ok";
				$return_Status[2] = "";
				
				return $return_Status;
			}
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());

			$return_Status[0] = "0";
			$return_Status[1] = $e->getMessage();
			$return_Status[2] = "";
			
			return $return_Status;
		}
	}
	
	public function delete_hold_stock($docref){
		
		try
		{			
			
			$username = ($this->session->userdata['logged_in']['username']);
			
			$this->db->where('user', $username);
			$this->db->where('doctype', $docref);
			$this->db->delete('stockhold');
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());			
		}
	}
	
	public function delete_hold_stock_guid($guid){
		
		try
		{			
			
			$username = ($this->session->userdata['logged_in']['username']);
			
			$this->db->where('user', $username);
			$this->db->where('guid', $guid);
			$this->db->delete('stockhold');
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());			
		}
	}

	public function read_stock_in_detail($comno, $storeno, $itemno){
		$condition = "(inqty - usedqty) > 0 and comno = ".$comno." and storeno = ".$storeno." and itemno = ".$itemno;
		$this->db->select('inorder, (inqty - usedqty) as bal, unitcost');
		$this->db->where($condition);
		$this->db->order_by('inorder ASC');
		$query= $this->db->get('stockindetail');
		
		if ($query->num_rows() != 0) {
			return $query->result();
		}else{
			return false;
		}				
	}
	
	public function read_stock_bal_item($itemno){
		$this->db->select('balqty');
		$this->db->where('itemno', $itemno);
		$query= $this->db->get('stockmaster');
		
		$row = $query->row();
		
		if (isset($row))
		{
			echo $row->balqty;
		}else{
			echo '0';
		}
	}

	public function read_stock_bal_item_company($itemno, $com){
		$this->db->select('balqty');
		$this->db->where('itemno', $itemno);
        $this->db->where('comno', $com);
		$query = $this->db->get('stockmaster');
		
		$row = $query->row();
		
		if (isset($row))
		{
			return $row->balqty;
		}else{
			return '0';
		}
	}
	
	public function read_stock_bal_item_company_dept($itemno, $com, $deptno, $store){
		$this->db->select('balqty');
		$this->db->where('itemno', $itemno);
		$this->db->where('comno', $com);
		$this->db->where('departmentno', $deptno);
		$this->db->where('storeno', $store);
		
		$query = $this->db->get('stockmaster');
		
		$row = $query->row();
		
		if (isset($row))
		{
			return $row->balqty;
		}else{
			return '0';
		}
	}
	
	public function read_store_by_no($store_no) {
		$this->db->select('*');
		$this->db->from('storelocation');
		$this->db->where('no', $store_no);;
		$query = $this->db->get();
		
		$row = $query->row();
		
		return $row;
	}
	
	public function read_stock_summary_report($ComPara, $ItemPara) {
		try
		{
			
			/*select i.itemcode, i.description, i.uom, sum(s.balqty) as balqty, v.value
				from stockmaster s 
				inner join item i on s.itemno = i.no
				inner join (select itemno, FORMAT(sum((inqty - usedqty) * unitcost),2) as value
						from stockindetail
						where (inqty - usedqty) > 0
						group by itemno) v on s.itemno = v.itemno
				group by i.itemcode, i.description, i.uom*/
			
			$this->db->select('i.itemcode, i.description, i.uom, sum(s.balqty) as balqty, v.value');
			$this->db->from('stockmaster s');
			
			$this->db->join('item i', 's.itemno = i.no');
			
			$this->db->join('(select itemno, FORMAT(sum((inqty - usedqty) * unitcost),2) as value
								from stockindetail
								where (inqty - usedqty) > 0
								group by itemno) v', 's.itemno = v.itemno');
			
			$this->db->group_by(array("i.itemcode", "i.description", "i.uom"));
			
			if(count($ComPara) > 0){
				
				for ($row = 0; $row < count($ComPara); $row++) {
					$com_list[$row] = $ComPara[$row]['companyno'];
				}
				
				if(count($com_list) > 0){
					$this->db->where_in('s.comno', $com_list);
				}
				
			}
			
			if(count($ItemPara) > 0){
				
				for ($row = 0; $row < count($ItemPara); $row++) {
					$item_list[$row] = $ItemPara[$row]['itemno'];
				}
				
				if(count($item_list) > 0){
					$this->db->where_in('s.itemno', $item_list);
				}
				
			}
			
			$this->db->order_by('i.description', 'ASC');
			$query = $this->db->get();
			
			return $query->result();
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	public function read_stock_summary_company_report($Com, $ItemPara) {
		try
		{
			
			/*select i.itemcode, i.description, c.description as company, i.uom, sum(s.balqty) as balqty, v.value
				from stockmaster s 
				inner join item i on s.itemno = i.no
				inner join (select itemno, comno, FORMAT(sum((inqty - usedqty) * unitcost),2) as value
						from stockindetail
						where (inqty - usedqty) > 0
						group by itemno, comno) v on s.itemno = v.itemno and s.comno = v.comno
				inner join company c on s.comno = c.no
				group by i.itemcode, i.description, i.uom, c.description*/
			
			$this->db->select('i.itemcode, i.description, c.description as company, i.uom, sum(s.balqty) as balqty, v.value, w.whcode');
			$this->db->from('stockmaster s');
			
			$this->db->join('item i', 's.itemno = i.no');
			
			$this->db->join('(select itemno, comno, FORMAT(sum((inqty - usedqty) * unitcost),2) as value
								from stockindetail
								where (inqty - usedqty) > 0
								group by itemno, comno) v', 's.itemno = v.itemno and s.comno = v.comno');
			
			$this->db->join('company c', 's.comno = c.no');
			
			$this->db->join('wherehouse w', 'i.whcode = w.no');
			
			$this->db->where('s.comno', $Com);
			
			/*if(count($ComPara) > 0){
				
				for ($row = 0; $row < count($ComPara); $row++) {
					$com_list[$row] = $ComPara[$row]['companyno'];
				}
				
				if(count($com_list) > 0){
					$this->db->where_in('s.comno', $com_list);
				}
				
			}*/
			
			if(count($ItemPara) > 0){
			
				for ($row = 0; $row < count($ItemPara); $row++) {
					$item_list[$row] = $ItemPara[$row]['itemno'];
				}
				
				if(count($item_list) > 0){
					$this->db->where_in('s.itemno', $item_list);
				}
			
			}
			
			$this->db->group_by(array("i.itemcode", "i.description", "i.uom", "c.description", "w.whcode"));				
			
			$this->db->order_by('i.description', 'ASC');
			$query = $this->db->get();
			
			return $query->result();
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	public function read_item_bin_card_report($DateData, $ItemPara) {
		try
		{
			
			/*select i.itemcode, i.description as item, c.description as company, d.description dept,
					i.uom, t.inqty, t.outqty, t.unitcost, ((t.inqty + t.outqty) * t.unitcost) as val, t.doctype, t.docref, t.docdate
				from stocktransaction t
				inner join company c on t.comno = c.no
				inner join department d on t.departmentno = d.no
				inner join item i on t.itemno = i.no
				where i.no = 337
				order by i.itemcode, t.order*/
			
			$this->db->select('i.itemcode, i.description as item, c.description as company, d.description dept,
								i.uom, t.inqty, t.outqty, t.unitcost, ((t.inqty + t.outqty) * t.unitcost) as val, 
								t.doctype, t.docref, t.docdate');
			$this->db->from('stocktransaction t');
			
			$this->db->join('company c', 't.comno = c.no');			
			$this->db->join('department d', 't.departmentno = d.no');
			$this->db->join('item i', 't.itemno = i.no');		
			
			$isasat = (empty($DateData[0]['isasat']) ? 0 : $DateData[0]['isasat']);
			
			if ($isasat){
				$date_asat = $DateData[0]['date_asat'];
				if(isset($date_asat) && !empty($date_asat)){
					$this->db->where('t.docdate', $date_asat);
				}
			}
			
			$isperiod = (empty($DateData[0]['isperiod']) ? 0 : $DateData[0]['isperiod']);
			
			if ($isperiod){
				$date_from = $DateData[0]['date_from'];
				
				if(isset($date_from) && !empty($date_from)){
					$this->db->where('t.docdate >=', $date_from);
				}
				
				$date_to = $DateData[0]['date_to'];
				
				if(isset($date_to) && !empty($date_to)){
					$this->db->where('t.docdate <=', $date_to);
				}
			}
			
			if(count($ItemPara) > 0){
				
				for ($row = 0; $row < count($ItemPara); $row++) {
					if($row === 0){
						$item_list[$row] = $ItemPara[$row]['itemno'];
					}
				}
				
				if(count($item_list) > 0){
					$this->db->where_in('i.no', $item_list);
				}
				
			}
			
			$this->db->order_by('i.itemcode', 'ASC');
			$this->db->order_by('t.order', 'ASC');
			
			$query = $this->db->get();
			
			return $query->result();
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	public function read_item_opening_balance($Date, $itemno) {
		try
		{
			
			/*select i.itemcode, i.description as item, i.uom, 
					sum(t.inqty) as inqty, sum(t.outqty) as outqty, sum((t.inqty + t.outqty) * t.unitcost) as val
				from stocktransaction t
				inner join company c on t.comno = c.no
				inner join department d on t.departmentno = d.no
				inner join item i on t.itemno = i.no
				where i.no = 337 and
				t.docdate < '2017-08-11'*/
			
			$this->db->select('i.itemcode, i.description as item, i.uom, 
								sum(t.inqty) as inqty, sum(t.outqty) as outqty, sum((t.inqty + t.outqty) * t.unitcost) as val');
			$this->db->from('stocktransaction t');
			
			$this->db->join('company c', 't.comno = c.no');
			$this->db->join('department d', 't.departmentno = d.no');
			$this->db->join('item i', 't.itemno = i.no');
			
			$this->db->where('i.no', $itemno);			
			$this->db->where('t.docdate < ', $Date);
			
			$query = $this->db->get();
			
			return $query->result();
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	public function read_item_closing_balance($Date, $itemno) {
		try
		{
			
			/*select i.itemcode, i.description as item, i.uom, 
					sum(t.inqty) as inqty, sum(t.outqty) as outqty, sum((t.inqty + t.outqty) * t.unitcost) as val
				from stocktransaction t
				inner join company c on t.comno = c.no
				inner join department d on t.departmentno = d.no
				inner join item i on t.itemno = i.no
				where i.no = 337 and
				t.docdate > '2017-08-9'*/
			
			$this->db->select('i.itemcode, i.description as item, i.uom,
								sum(t.inqty) as inqty, sum(t.outqty) as outqty, sum((t.inqty + t.outqty) * t.unitcost) as val');
			$this->db->from('stocktransaction t');
			
			$this->db->join('company c', 't.comno = c.no');
			$this->db->join('department d', 't.departmentno = d.no');
			$this->db->join('item i', 't.itemno = i.no');
			
			$this->db->where('i.no', $itemno);
			$this->db->where('t.docdate > ', $Date);
			
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