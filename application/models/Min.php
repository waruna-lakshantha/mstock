<?php 

Class Min extends CI_Model {
	
	public $LocalTime = "addtime(now(),'09:30:00')";
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('store');
		$this->load->model('mrn');
	}
	
	public function min_insert($hdr) {
		
		$return_Status = array();
		
		try
		{					
			
			$hdr_ass = array();
			$dtl_ass = array();			
			
			$hdr_ass['companyno'] = $hdr[0]['company_no'];
			$hdr_ass['departmentno'] = $hdr[0]['department_no'];
			$hdr_ass['date'] = $hdr[0]['date'];
			$hdr_ass['remarks'] = $hdr[0]['remarks'];
			$hdr_ass['isactive'] = 1;
			$hdr_ass['insertuser'] = $hdr[0]['insertuser'];
			$hdr_ass['saprefno'] = $hdr[0]['sap_refno'];
			
			$this->db->trans_start();
			
			$this->db->insert('issue_hdr',$hdr_ass);
			
			$id = $this->db->insert_id();
			
			$id_reff = "I".str_pad($id, 9, '0', STR_PAD_LEFT);
			
			$this->db->set('issueno', $id_reff);
			$this->db->set('insertdatetime', $this->LocalTime, false);
			$this->db->where('no', $id);
			$this->db->update('issue_hdr');
			
			$this->db->select('*');
			$this->db->where('guid', $hdr[0]['guid']);
			$query = $this->db->get('stockhold');
			
			$issue_x = 0;				
			
			if ($this->db->affected_rows() <= 0)
			{
				$this->db->trans_rollback();				
                throw new Exception('Hold Item not found, Try Again!');
			}					
			
			foreach ($query->result() as $row)
			{
				$balqty = $row->qty;							
				
				$stock_in_result = $this->store->read_stock_in_detail($row->comno, $row->storeno, $row->itemno);							
				
				if($stock_in_result != false){
					foreach ($stock_in_result as $row_stk_in)
					{											
						
						if($balqty > 0){
							if ($row_stk_in->bal >= $balqty){
								
								$update_qty = 'usedqty + '.$balqty;
								
								$this->db->set('usedqty', $update_qty, false);
								$this->db->where('inorder', $row_stk_in->inorder);
								$this->db->update('stockindetail');															
																
								$stock_item = array();
								$stock_item['comno'] = $row->comno;
								$stock_item['departmentno'] = 4;
								$stock_item['storeno'] = $row->storeno;
								$stock_item['itemno'] = $row->itemno;
								$stock_item['inqty'] = 0;
								$stock_item['outqty'] = $balqty;

                                //throw new Exception('Stock Balance Error in MRN '.$stock_item['outqty']);                                
								
								$this->store->update_store_item($stock_item);																												
								
								if ($this->mrn->update_received_qty($balqty, $row->docno, $row->itemno, $row->refcomno, $row->departmentno, $row->machineno) <= 0){
									throw new Exception('Stock Balance Error in MRN '.$row->docref.' Item No: '.$row->itemno);
								}
								
                                $stock_tran = array();
                                $stock_tran['comno'] = $row->comno;
                                $stock_tran['departmentno'] = 4;
                                $stock_tran['storeno'] = $row->storeno;
                                $stock_tran['machineno'] = $row->machineno;
                                $stock_tran['itemno'] = $row->itemno;
                                $stock_tran['inqty'] = 0;
                                $stock_tran['outqty'] = $balqty;
                                $stock_tran['unitcost'] = $row_stk_in->unitcost;
                                $stock_tran['doctype'] = "MIN";
                                $stock_tran['docno'] = $id;
                                $stock_tran['docref'] = $id_reff;
                                
                                $stock_tran['docdate'] = mdate(date("Y-m-d h:i:sa"));
                                
                                //$stock_tran['docdate'] = $this->LocalTime;
                                
                                $this->store->insert_stock_transaction($stock_tran);

                                $line_total = $row->qty * $row_stk_in->unitcost;
                                
				                $dtl_ass[$issue_x] = array ('no' => $id,
						                'mrno' => $row->docno,
						                'itemno' => $row->itemno,
						                'qty' => $balqty,
						                'uom' => $row->uom,
						                'unitcost' => $row_stk_in->unitcost,
						                'total' => $line_total,
						                'machineno' => $row->machineno);

                                $issue_x++;                                								
								
								$balqty = 0;								
							}else{
								
								$update_qty = 'usedqty + '.$row_stk_in->bal;
								
								$this->db->set('usedqty', $update_qty, false);
								$this->db->where('inorder', $row_stk_in->inorder);
								$this->db->update('stockindetail');							
								
								$stock_item = array();
								$stock_item['comno'] = $row->comno;
								$stock_item['departmentno'] = 4;
								$stock_item['storeno'] = $row->storeno;
								$stock_item['itemno'] = $row->itemno;
								$stock_item['inqty'] = 0;
								$stock_item['outqty'] = $row_stk_in->bal;
								
								$this->store->update_store_item($stock_item);														
								
								if ($this->mrn->update_received_qty($row_stk_in->bal, $row->docno, $row->itemno, $row->refcomno, $row->departmentno, $row->machineno) == 0){
									throw new Exception('Stock Balance Error in MRN '.$row->docref.' Item No: '.$row->itemno);
								}

								$stock_tran = array();
								$stock_tran['comno'] = $row->comno;
								$stock_tran['departmentno'] = 4;
								$stock_tran['storeno'] = $row->storeno;
								$stock_tran['machineno'] = $row->machineno;
								$stock_tran['itemno'] = $row->itemno;
								$stock_tran['inqty'] = 0;
								$stock_tran['outqty'] = $row_stk_in->bal;
								$stock_tran['unitcost'] = $row_stk_in->unitcost;
								$stock_tran['doctype'] = "MIN";
								$stock_tran['docno'] = $id;
								$stock_tran['docref'] = $id_reff;
								
								$stock_tran['docdate'] = mdate(date("Y-m-d h:i:sa"));
								//$stock_tran['docdate'] = $this->LocalTime;
								
								$this->store->insert_stock_transaction($stock_tran);
								
                                $line_total = $row_stk_in->bal * $row_stk_in->unitcost;

				                $dtl_ass[$issue_x] = array ('no' => $id,
						                'mrno' => $row->docno,
						                'itemno' => $row->itemno,
						                'qty' => $row_stk_in->bal,
						                'uom' => $row->uom,
						                'unitcost' => $row_stk_in->unitcost,
						                'total' => $line_total,
						                'machineno' => $row->machineno);

                                $issue_x++;
								
								$balqty -= $row_stk_in->bal;								
							}														
							
						}
					}
				}else{									
					throw new Exception('No Enough Stock!');																	
				}
				
				if ($balqty > 0){					
					throw new Exception('No Enough Stock!');								
				}

			}	
			
			$this->db->insert_batch('issue_dtl', $dtl_ass);

            $this->store->delete_hold_stock_guid($hdr[0]['guid']);
			
			$this->db->trans_complete();
			
			if ($this->db->trans_status() === FALSE)
			{
				log_message('error', $e->getMessage());
				
				$return_Status[0] = "0";
				$return_Status[1] = $e->getMessage();
				$return_Status[2] = "";							
				
				return $return_Status;
			}
			else{
				$return_Status[0] = "1";
				$return_Status[1] = $id_reff;
				$return_Status[2] = $this->mrn->read_mrn_balance_html_table();				
				$return_Status[3] = $id;
				
				$username = $this->session->userdata['logged_in']['username'];
				$useremail = $this->session->userdata['logged_in']['email'];				
				
				$print_url = "<a href=\"".base_url()."index.php/print/4/".$id."\"/>".$id_reff."</a>";
				
				$message = "";
				
				$message .= "<h4>New MIN</h4>";
				$message .= "<p>Please click on the follwing link to view document</p>";
				$message .= $print_url;
				
				$this->generate_mail->mailfrom = $useremail;
				$this->generate_mail->namefrom = $username;
				
				$this->generate_mail->mailsubject = "New MIN - ".$id_reff;								
				
				$this->generate_mail->mailmessage = $message;
																	
				$arrlength = count($dtl_ass);
				
				for($x = 0; $x < $arrlength; $x++) {
					$email_list = $this->user->read_mail_list_by_doc(3, $dtl_ass[$x]['mrno']);
					
					$this->generate_mail->mailto_list = $email_list;
					$this->generate_mail->mail_send();	
				}				
				
				return $return_Status;
			}
			
		}
		catch(Exception $e)
		{
			
			$this->db->trans_rollback();
			
			log_message('error', $e->getMessage());
			
			$return_Status[0] = "0";
			$return_Status[1] = $e->getMessage();
			$return_Status[2] = "";
			
			return $return_Status;
			
		}
	}	
	
	public function read_min_hdr_by_minno($no){
		try
		{
			$this->db->select('no, issueno, companyno, departmentno, date, remarks, isactive, insertuser, insertdatetime, saprefno');
			
			$this->db->from('issue_hdr');
			$this->db->where('no', $no);
			
			return $this->db->query($this->db->get_compiled_select());
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	public function read_min_dtl_by_no($no) {
		try
		{
			
			$this->db->select('mr_hdr.mrno, issue_hdr.date as `Date`, item.itemcode, item.description as `Item`,
								company.description as Company, department.description as Department, machine.machineno as Machine,
								issue_dtl.uom as Uom, issue_dtl.qty as Qty, issue_dtl.unitcost, issue_dtl.total');
			$this->db->from('issue_dtl');
			$this->db->join('issue_hdr', 'issue_hdr.no = issue_dtl.no');
			$this->db->join('item', 'item.no = issue_dtl.itemno');
			$this->db->join('company', 'company.no = issue_hdr.companyno');
			$this->db->join('department', 'department.no = issue_hdr.departmentno');
			$this->db->join('machine', 'machine.no = issue_dtl.machineno');
            $this->db->join('mr_hdr', 'mr_hdr.no = issue_dtl.mrno');
			$this->db->where('issue_hdr.no', $no);
			$this->db->order_by('issue_dtl.dtlno', 'ASC');
			
			return $this->db->query($this->db->get_compiled_select());
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	public function read_min_list_report($DateData, $ComPara, $DeptPara, $ItemPara, $StatusPara, $MachinePara) {
		try
		{
			
			/*select h.issueno, h.date, i.itemcode, i.description Item, d.qty, d.uom, d.unitcost,	 
					d.total, r.mrno, c.description company, de.description dept, m.machineno
				from issue_dtl d 
				inner join issue_hdr h on d.no = h.no
				inner join item i on d.itemno = i.no
				inner join machine m on d.machineno = m.no
				inner join mr_hdr r on d.mrno = r.no
				inner join mr_dtl rd on d.mrno = rd.no and
					d.itemno = rd.itemno
				inner join company c on rd.companyno = c.no
				inner join department de on rd.departmentno = de.no*/
			
			$this->db->select('h.issueno, h.date, i.itemcode, i.description Item, d.qty, d.uom, d.unitcost,	 
								d.total, r.mrno, c.description company, de.description dept, m.machineno');
			$this->db->from('issue_dtl d');
			
			$this->db->join('issue_hdr h', 'd.no = h.no');
			$this->db->join('item i', 'd.itemno = i.no');
			$this->db->join('machine m', 'd.machineno = m.no');
			
			$this->db->join('mr_hdr r', 'd.mrno = r.no');
			$this->db->join('mr_dtl rd', 'd.mrno = rd.no and d.itemno = rd.itemno');
			
			$this->db->join('company c', 'rd.companyno = c.no');
			$this->db->join('department de', 'rd.departmentno = de.no');
			
			$isasat = (empty($DateData[0]['isasat']) ? 0 : $DateData[0]['isasat']);
			
			if ($isasat){
				$date_asat = $DateData[0]['date_asat'];
				if(isset($date_asat) && !empty($date_asat)){
					$this->db->where('h.date', $date_asat);
				}
			}
			
			$isperiod = (empty($DateData[0]['isperiod']) ? 0 : $DateData[0]['isperiod']);
			
			if ($isperiod){
				$date_from = $DateData[0]['date_from'];
				
				if(isset($date_from) && !empty($date_from)){
					$this->db->where('h.date >=', $date_from);
				}
				
				$date_to = $DateData[0]['date_to'];
				
				if(isset($date_to) && !empty($date_to)){
					$this->db->where('h.date <=', $date_to);
				}
			}
			
			if(count($ComPara) > 0){
				
				for ($row = 0; $row < count($ComPara); $row++) {
					$com_list[$row] = $ComPara[$row]['companyno'];
				}
				
				if(count($com_list) > 0){
					$this->db->where_in('rd.companyno', $com_list);
				}
				
			}
			
			if(count($DeptPara) > 0){
				
				for ($row = 0; $row < count($DeptPara); $row++) {
					$dept_list[$row] = $DeptPara[$row]['deptno'];
				}
				
				if(count($dept_list) > 0){
					$this->db->where_in('rd.departmentno', $dept_list);
				}
				
			}
			
			if(count($ItemPara) > 0){
				
				for ($row = 0; $row < count($ItemPara); $row++) {
					$item_list[$row] = $ItemPara[$row]['itemno'];
				}
				
				if(count($item_list) > 0){
					$this->db->where_in('d.itemno', $item_list);
				}
				
			}
			
			if(isset($MachinePara) && count($MachinePara) > 0){
				
				for ($row = 0; $row < count($MachinePara); $row++) {
					$machine_list[$row] = $MachinePara[$row]['machineno'];
				}
				
				if(count($machine_list) > 0){
					$this->db->where_in('d.machineno', $machine_list);
				}
				
			}
			
			$approve = $StatusPara[0]['approve'];
			$accept = $StatusPara[0]['accept'];
			$proceed = (empty($StatusPara[0]['proceed']) ? 0 : $StatusPara[0]['proceed']);			
			
			$this->db->order_by('h.issueno', 'ASC');
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