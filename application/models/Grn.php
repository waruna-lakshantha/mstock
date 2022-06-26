<?php

Class Grn extends CI_Model {
	
	public $LocalTime = "addtime(now(),'09:30:00')";
	
	public $template = array(
			'table_open'            => '<table id="id_pr_det" class="w3-table-all">',
			
			'thead_open'            => '<thead>',
			'thead_close'           => '</thead>',
			
			'heading_row_start'     => '<tr class="w3-cyan">',
			'heading_row_end'       => '</tr>',
			'heading_cell_start'    => '<th>',
			'heading_cell_end'      => '</th>',
			
			'tbody_open'            => '<tbody>',
			'tbody_close'           => '</tbody>',
			
			'row_start'             => '<tr>',
			'row_end'               => '</tr>',
			'cell_start'            => '<td>',
			'cell_end'              => '</td>',
			
			'row_alt_start'         => '<tr>',
			'row_alt_end'           => '</tr>',
			'cell_alt_start'        => '<td>',
			'cell_alt_end'          => '</td>',
			
			'table_close'           => '</table>'
	);
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('store');
		$this->load->model('wherehouse');
        $this->load->model('pr');        
	}
	
	// Read user menu
	public function grn_insert($hdr,$dtl) {
		
		$return_Status = array();
		
		try
		{	
								            
			$hdr_ass = array();	
			$dtl_ass = array();
			
			$hdr_ass['remarks'] = $hdr[0]['remarks'];	
			$hdr_ass['totalvalue'] = $hdr[0]['totalvalue'];
			$hdr_ass['insertuser'] = $hdr[0]['insertuser'];
			$hdr_ass['storelocationno'] = $hdr[0]['store_loc'];
			$hdr_ass['wherehouseno'] = $hdr[0]['wherehouse'];
			$hdr_ass['docstatus'] = 'P';
			$hdr_ass['saprefno'] = $hdr[0]['sap_refno'];
									
			$this->db->trans_start();
			
			$this->db->insert('grn_hdr',$hdr_ass);  
			
			$id = $this->db->insert_id();						
			
			$id_reff = "G".str_pad($id, 9, '0', STR_PAD_LEFT);					
			
			$this->db->set('grnno', $id_reff);
			$this->db->set('insertdatetime', $this->LocalTime, false);
			$this->db->set('date', $this->LocalTime, false);
			$this->db->where('no', $id);
			$this->db->update('grn_hdr');
								
			$result = $this->store->read_default_store();
			$def_store = $result[0]->no;
			
			/*no
			itemno
			companyno
			departmentno
			qty
			qtyused
			uom
			unitcost
			total
			storeno
			prno*/
			
			for ($row = 0; $row < count($dtl); $row++) {
				
				$dtl_ass[$row] = array ('no' => $id,
						'itemno' => $dtl[$row]['itemno'],
						'companyno' => $dtl[$row]['companyno'],
						'departmentno' => 4,
						'qty' => $dtl[$row]['qty'],
						'uom' => $dtl[$row]['uom'],						
						'unitcost' => $dtl[$row]['unitcost'],
						'total' => $dtl[$row]['total'],
						'storeno' => $def_store,
						'prno' => $dtl[$row]['prno'],
						'supplierno' => $dtl[$row]['supno'],
						'pono' => $dtl[$row]['pono'],
						'receiveddate' => mdate($dtl[$row]['recdate']),
						'wherehouseno' => $dtl[$row]['whcode']
				);				
				
				/*$stock_item = array();
				$stock_item['comno'] = $dtl[$row]['companyno'];	
				$stock_item['departmentno'] = $dtl[$row]['departmentno'];	
				$stock_item['storeno'] = $def_store;
				$stock_item['itemno'] = $dtl[$row]['itemno'];
				$stock_item['inqty'] = $dtl[$row]['qty'];
				$stock_item['outqty'] = 0;
				
				$this->store->update_store_item($stock_item);*/
				
				/*$stock_dtl = array();
				$stock_dtl['comno'] = $dtl[$row]['companyno'];
				$stock_dtl['departmentno'] = $dtl[$row]['departmentno'];
				$stock_dtl['storeno'] = $def_store;
				$stock_dtl['itemno'] = $dtl[$row]['itemno'];
				$stock_dtl['inqty'] = $dtl[$row]['qty'];
				$stock_dtl['unitcost'] = $dtl[$row]['unitcost'];
				$stock_dtl['doctype'] = "GRN";
				$stock_dtl['docno'] = $id;
				$stock_dtl['uom'] = $dtl[$row]['uom'];
				
				$this->store->insert_stock_in_details($stock_dtl);*/
				
				/*$stock_tran = array();
				$stock_tran['comno'] = $dtl[$row]['companyno'];
				$stock_tran['departmentno'] = $dtl[$row]['departmentno'];
				$stock_tran['storeno'] = $def_store;
				$stock_tran['machineno'] = $dtl[$row]['machineno'];
				$stock_tran['itemno'] = $dtl[$row]['itemno'];
				$stock_tran['inqty'] = $dtl[$row]['qty'];
				$stock_tran['outqty'] = 0;
				$stock_tran['unitcost'] = $dtl[$row]['unitcost'];
				$stock_tran['doctype'] = "GRN";
				$stock_tran['docno'] = $id;
				$stock_tran['docref'] = $id_reff;							
			
				$stock_tran['docdate'] = $this->LocalTime;
				
				$this->store->insert_stock_transaction($stock_tran);*/

				$this->pr->update_grn_qty($dtl[$row]['prno'], $dtl[$row]['itemno'], $dtl[$row]['qty'], $dtl[$row]['dtlno'], $dtl[$row]['unitcost']);				
				
			}

			$this->db->insert_batch('grn_dtl', $dtl_ass);					

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE)
            {
                    log_message('error', $e->getMessage());
                    //return 0;

                    $this->db->trans_rollback();
                    
                    $return_Status[0] = "0";
                    $return_Status[1] = $e->getMessage();
                    $return_Status[2] = "";
                    
                    return $return_Status;
            }
            else{

            	$return_Status[0] = "1";
            	$return_Status[1] = $id_reff;
            	$return_Status[2] = $this->pr->read_pr_balance_html_table();;
                $return_Status[3] = $id;
                
                $username = $this->session->userdata['logged_in']['username'];
                $useremail = $this->session->userdata['logged_in']['email'];
                
                //GRN Approver
                $email_list = $this->user->read_mail_list("MS", 12);
                
                $print_url = "<a href=\"".base_url()."index.php/print/2/".$id."\"/>".$id_reff."</a>";
                
                $message = "";
                
                $message .= "<h4>New GRN</h4>";
                $message .= "<p>Please click on the follwing link to view document</p>";
                $message .= $print_url;
                
                $this->generate_mail->mailfrom = $useremail;
                $this->generate_mail->namefrom = $username;
                
                $this->generate_mail->mailsubject = "New GRN - ".$id_reff;
                
                $this->generate_mail->mailto_list= $email_list;
                
                $this->generate_mail->mailmessage = $message;
                
                $this->generate_mail->mail_send();
            	
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
	
	public function approve_grn($data){
		
		$return_Status = array();
		
		try
		{
			
			$grnno = $data[0]['no'];
			$docstatus = $data[0]['docstatus'];
			$apprejremarks = $data[0]['apprejremarks'];
			$apprejby = $data[0]['insertuser'];
			
			$result = $this->store->read_default_store();
			$def_store = $result[0]->no;
			
			$this->db->trans_start();		
			
			$this->db->set('docstatus', $docstatus);
			$this->db->set('apprejby', $apprejby);
			$this->db->set('apprejdate', $this->LocalTime, false);
			$this->db->set('apprejremarks', $apprejremarks);
			$this->db->where('no', $grnno);
			$this->db->where('docstatus', "P");
			$this->db->update('grn_hdr');					
			
			/*no
			 itemno
			 companyno
			 departmentno
			 qty
			 qtyused
			 uom
			 unitcost
			 total
			 storeno
			 prno*/
			
			$dtl = $this->read_grn_dtl_by_no_approve($grnno);
			
			foreach ($dtl->result() as $row)
			{	
				
				/*no
				itemno
				companyno
				departmentno
				qty
				qtyused
				uom
				unitcost
				total
				dtlno
				storeno
				prno
				supplierno
				pono
				receiveddate*/
				
				if($docstatus === 'A'){
					$stock_item = array();
					$stock_item['comno'] = $row->companyno;
					$stock_item['departmentno'] = $row->departmentno;
					$stock_item['storeno'] = $def_store;
					$stock_item['itemno'] = $row->itemno;
					$stock_item['inqty'] = $row->qty;
					$stock_item['outqty'] = 0;
					 
					$this->store->update_store_item($stock_item);
					
					$stock_dtl = array();
					$stock_dtl['comno'] = $row->companyno;
					$stock_dtl['departmentno'] = $row->departmentno;
					$stock_dtl['storeno'] = $def_store;
					$stock_dtl['itemno'] = $row->itemno;
					$stock_dtl['inqty'] = $row->qty;
					$stock_dtl['unitcost'] = $row->unitcost;
					$stock_dtl['doctype'] = "GRN";
					$stock_dtl['docno'] = $row->no;
					$stock_dtl['uom'] = $row->uom;
					 
					$this->store->insert_stock_in_details($stock_dtl);
					
					$pr_dtl = $this->pr->read_pr_dtl_by_no_item($row->prno, $row->itemno);
					
					$stock_tran = array();
					$stock_tran['comno'] = $row->companyno;
					$stock_tran['departmentno'] = $row->departmentno;
					$stock_tran['storeno'] = $def_store;
					$stock_tran['machineno'] = $pr_dtl->machineno;
					$stock_tran['itemno'] = $row->itemno;
					$stock_tran['inqty'] = $row->qty;
					$stock_tran['outqty'] = 0;
					$stock_tran['unitcost'] = $row->unitcost;
					$stock_tran['doctype'] = "GRN";
					$stock_tran['docno'] = $row->no;
					$stock_tran['docref'] = $row->grnno;					
					 
					$this->store->insert_stock_transaction($stock_tran);
					
					$trid = $this->db->insert_id();
					
					$this->db->set('docdate', $this->LocalTime, false);
					$this->db->where('order', $trid);
					$this->db->update('stocktransaction');
									
				}
				
			}			
			
			$this->db->trans_complete();
			
			if ($this->db->trans_status() === FALSE)
			{
				log_message('error', $e->getMessage());
				//return 0;
				
				$this->db->trans_rollback();
				
				$return_Status[0] = "0";
				$return_Status[1] = $e->getMessage();
				$return_Status[2] = "";
				
				return $return_Status;
			}
			else{
				
				$return_Status[0] = "1";
				$return_Status[1] = $grnno;
				$return_Status[2] = $this->read_grn_for_approval_html();
				$return_Status[3] = $grnno;
				
				//GRN create user				
				
				$email_list = $this->user->read_mail_list_by_doc(1, $grnno);
				
				$print_url = "<a href=\"".base_url()."index.php/print/2/".$id."\"/>".$grnno."</a>";
				
				$stat = "";
				
				if($docstatus === 'A'){
					$stat = "Approved";
				}else{
					$stat = "Rejected";
				}
				
				$message = "";
				
				$message .= "<h4>GRN ".$stat."</h4>";
				$message .= "<p>Please click on the follwing link to view document</p>";
				$message .= $print_url;
				
				$this->generate_mail->mailfrom = $useremail;
				$this->generate_mail->namefrom = $username;
				
				$this->generate_mail->mailsubject = $stat." GRN - ".$grnno;
				
				$this->generate_mail->mailto_list= $email_list;
				
				$this->generate_mail->mailmessage = $message;
				
				$this->generate_mail->mail_send();
				
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

	public function read_grn_hdr_by_minno($no){
		try
		{
			$this->db->select('no, grnno, date, totalvalue, remarks, isactive, insertuser, insertdatetime, storelocationno, wherehouseno, docstatus, apprejby, saprefno');
			
			$this->db->from('grn_hdr');
			$this->db->where('no', $no);
			
			return $this->db->query($this->db->get_compiled_select());
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	public function read_grn_dtl_by_no($no) {
		//try
		//{
			
			$this->db->select('pr_hdr.prno, grn_hdr.date as `Date`, pr_hdr.date as reqdate, item.itemcode, item.description as `Item`,
								company.description as Company, department.description as Department,
								grn_dtl.uom as Uom, grn_dtl.qty as Qty, grn_dtl.unitcost, grn_dtl.total, grn_hdr.totalvalue,
								grn_dtl.pono, vendor.name, grn_hdr.grnno, grn_dtl.receiveddate, wherehouse.whcode');
			$this->db->from('grn_dtl');
			$this->db->join('grn_hdr', 'grn_hdr.no = grn_dtl.no');
			$this->db->join('item', 'item.no = grn_dtl.itemno');
			$this->db->join('company', 'company.no = grn_dtl.companyno');
			$this->db->join('department', 'department.no = grn_dtl.departmentno');
            $this->db->join('pr_hdr', 'pr_hdr.no = grn_dtl.prno');
            $this->db->join('vendor', 'vendor.no = grn_dtl.supplierno');
            $this->db->join('wherehouse', 'wherehouse.no = grn_dtl.wherehouseno');
			$this->db->where('grn_hdr.no', $no);
			$this->db->order_by('grn_dtl.dtlno', 'ASC');
			
			return $this->db->query($this->db->get_compiled_select());
			
		//}
		//catch(Exception $e)
		//{
			//log_message('error', $e->getMessage());
			//return array();
		//}
	}
	
	public function read_grn_dtl_by_no_approve($no) {
		//try
		//{
		
		$this->db->select('grn_dtl.no, grn_dtl.itemno, grn_dtl.companyno, grn_dtl.departmentno, grn_dtl.qty, 
							grn_dtl.qtyused, grn_dtl.uom, grn_dtl.unitcost, grn_dtl.total, grn_dtl.dtlno, 
							grn_dtl.storeno, grn_dtl.prno, grn_dtl.supplierno, grn_dtl.pono, grn_dtl.receiveddate, grn_hdr.grnno');
		$this->db->from('grn_dtl');
		$this->db->join('grn_hdr', 'grn_hdr.no = grn_dtl.no');
		$this->db->where('grn_dtl.no', $no);
		$this->db->order_by('grn_dtl.dtlno', 'ASC');
		
		return $this->db->query($this->db->get_compiled_select());
		
		//}
		//catch(Exception $e)
		//{
		//log_message('error', $e->getMessage());
		//return array();
		//}
	}
	
	public function read_grn_for_approval($status){
		try
		{
			$this->db->select('grn_hdr.no, grn_hdr.grnno, grn_hdr.date, grn_hdr.totalvalue, 
								grn_hdr.remarks, grn_hdr.isactive, grn_hdr.insertuser, 
								grn_hdr.insertdatetime, grn_hdr.storelocationno,
								storelocation.description as store,
								grn_hdr.docstatus, grn_hdr.apprejby, grn_hdr.apprejdate, 
								grn_hdr.apprejremarks, grn_hdr.saprefno');			
			$this->db->from('grn_hdr');
			$this->db->join('storelocation', 'storelocation.no = grn_hdr.storelocationno');
			$this->db->where('grn_hdr.docstatus', $status);
			
			return $this->db->query($this->db->get_compiled_select());
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	public function read_grn_dtl_for_approval($no) {
		try
		{
			$this->db->select('grn_hdr.grnno as `GRN No`, grn_hdr.date as `Date`, item.itemcode as `Item Code`,
                                item.description as `Item`, grn_dtl.qty as Qty, grn_dtl.uom as Uom,
								grn_dtl.unitcost as `Unit Cost`, grn_dtl.total as Total, pr_hdr.prno as `PR No`,
                                company.description as Company, department.description as Department, 
								grn_dtl.receiveddate as `Rec Date`, grn_hdr.saprefno as `SAP Ref`, wherehouse.whcode as `Wherehouse`');
			$this->db->from('grn_dtl');
			$this->db->join('grn_hdr', 'grn_hdr.no = grn_dtl.no');
			$this->db->join('item', 'item.no = grn_dtl.itemno');
			$this->db->join('company', 'company.no = grn_dtl.companyno');
			$this->db->join('department', 'department.no = grn_dtl.departmentno');
			$this->db->join('pr_hdr', 'pr_hdr.no = grn_dtl.prno');
			$this->db->join('wherehouse', 'wherehouse.no = grn_dtl.wherehouseno');
			$this->db->where('grn_hdr.no', $no);
			$this->db->order_by('grn_dtl.dtlno', 'ASC');
			
			return $this->db->query($this->db->get_compiled_select());
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	public function read_grn_for_approval_html() {
		try
		{
			
			$return_html = "No GRN For Approvals..";
			
			$query = $this->read_grn_for_approval('P');
			
			foreach ($query->result() as $row)
			{
				
				$pur_type = "-";			
				
				$dropdownhead = $row->grnno." | ".$row->date." | ".$row->remarks." | Status : ".$row->docstatus;
				
				if ($return_html === 'No GRN For Approvals..'){
					$return_html = "";
				}
				
				$return_html .= "<button onclick=\"dropdown_dtl('".$row->grnno."')\" class=\"w3-button w3-block w3-black w3-left-align\">".$dropdownhead."</button>";
				
				$return_html .= "<div id=\"".$row->grnno."\" class=\"w3-hide w3-container w3-sand w3-responsive\">";
				
				$return_html .= "<div class=\"w3-responsive\">";
				
				$this->table->set_template($this->template);
				
				$query_dtl = $this->read_grn_dtl_for_approval($row->no);
				
				$return_html .= $this->table->generate($query_dtl);
				
				$return_html .= "</div>";
				
				$return_html .= "<p></p>";
				
				$return_html .= "<div class=\"w3-row-padding\">";
				$return_html .= "<div class=\"w3-col m12\">";
				$return_html .= "<label>Remarks</label>";
				$return_html .= "<textarea id=\"id_freetxt_".$row->no."\" class=\"w3-input\"></textarea>";
				$return_html .= "</div>";
				$return_html .= "</div>";
				
				$return_html .= "<div class=\"w3-row-padding\">";
				$return_html .= "<div class=\"w3-col m4\">";
				$return_html .= "<br>";
				$return_html .= "<a href=\"".base_url()."index.php/print/2/".$row->no."\" class=\"w3-btn w3-ripple w3-blue\" target=\"_blank\"><i class=\"fa fa-print\" aria-hidden=\"true\"></i></a>";
				$return_html .= "</div>";
				$return_html .= "<div class=\"w3-col m4\">";
				$return_html .= "<br>";
				$return_html .= "<button id=\"idupdateapp_".$row->no."\" class=\"w3-btn w3-green w3-right\" class=\"w3-btn\" onclick=\"update_App_Rej(".$row->no.", 'A')\"><i class=\"fa fa-thumbs-o-up\" aria-hidden=\"true\"></i> Approve</button>";
				$return_html .= "</div>";
				$return_html .= "<div class=\"w3-col m4\">";
				//$return_html .= "<br>";
				//$return_html .= "<button id=\"idupdaterej\" class=\"w3-btn w3-orange w3-right\" class=\"w3-btn\" onclick=\"update_App_Rej(".$row->no.", 'R')\"><i class=\"fa fa-thumbs-o-down\" aria-hidden=\"true\"></i> Reject</button>";
				$return_html .= "</div>";
				$return_html .= "</div>";
				
				$return_html .= "<br>";
				
				$return_html .= "</div>";
				
			}
			
			return $return_html;
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return "";
		}
	}
	
	public function read_grn_list_report($DateData, $ComPara, $DeptPara, $ItemPara, $StatusPara) {
		try
		{
			
			/*select h.grnno, i.itemcode, i.description Item, d.qty, d.uom, h.date,
					h.docstatus, c.description company, de.description dept, v.name, d.pono
				from grn_dtl d
				inner join grn_hdr h on d.no = h.no
				inner join item i on d.itemno = i.no
				inner join company c on d.companyno = c.no
				inner join department de on d.departmentno = de.no
				inner join vendor v on d.supplierno = v.no*/
			
			$this->db->select('h.grnno, i.itemcode, i.description Item, d.qty, d.uom, h.date,
								h.docstatus, c.description company, de.description dept, v.name, d.pono');
			$this->db->from('grn_dtl d');
			
			$this->db->join('grn_hdr h', 'd.no = h.no');
			$this->db->join('item i', 'd.itemno = i.no');
			$this->db->join('company c', 'd.companyno = c.no');
			$this->db->join('department de', 'd.departmentno = de.no');
			$this->db->join('vendor v', 'd.supplierno = v.no');
			
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
					$this->db->where_in('d.companyno', $com_list);
				}
				
			}
			
			if(count($DeptPara) > 0){
				
				for ($row = 0; $row < count($DeptPara); $row++) {
					$dept_list[$row] = $DeptPara[$row]['deptno'];
				}
				
				if(count($dept_list) > 0){
					$this->db->where_in('d.departmentno', $dept_list);
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
			
			$approve = $StatusPara[0]['approve'];
			$accept = $StatusPara[0]['accept'];
			$proceed = (empty($StatusPara[0]['proceed']) ? 0 : $StatusPara[0]['proceed']);
			
			if(isset($approve) && !empty($approve)){
				$this->db->where('h.docstatus', $approve);
			}
			
			$this->db->order_by('h.grnno', 'ASC');
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