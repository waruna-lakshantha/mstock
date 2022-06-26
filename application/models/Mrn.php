<?php

Class Mrn extends CI_Model {
	
	public $LocalTime = "addtime(now(),'09:30:00')";
	public $template = array(
			'table_open'            => '<table class="w3-table-all">',
			
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
		$this->load->model('wherehouse');
	}
	
	// Read user menu
	public function mrn_insert($hdr,$dtl) {
		
		$return_Status = array();
		
		try
		{	
          
			$hdr_ass = array();	
			$dtl_ass = array();
			
			$reqdate;
			
			//$d = explode("_", $hdr[0]['reqdate']);
			//$reqdate= $d[0]."-".$d[1]."-".$d[2];
			
			$hdr_ass['reqdate'] = mdate($hdr[0]['reqdate']);
			$hdr_ass['remarks'] = $hdr[0]['remarks'];	
			$hdr_ass['wherehouseno'] = $hdr[0]['wherehouse'];
			$hdr_ass['insertuser'] = $hdr[0]['insertuser'];
			$hdr_ass['docstatus'] = 'P';
			$hdr_ass['saprefno'] = $hdr[0]['sap_refno'];
									
			$this->db->trans_start();
			
			$this->db->insert('mr_hdr',$hdr_ass);  
			
			$id = $this->db->insert_id();						
			
			$id_reff = "M".str_pad($id, 9, '0', STR_PAD_LEFT);
			
			$this->db->set('mrno', $id_reff);
			$this->db->set('insertdatetime', $this->LocalTime, false);
			$this->db->set('date', $this->LocalTime, false);
			$this->db->where('no', $id);
			$this->db->update('mr_hdr'); // gives UPDATE grn_hdr SET grnno = value WHERE id = 2
								
			//$result = $this->store->read_default_store();
			//$def_store = $result[0]->no;
			
			for ($row = 0; $row < count($dtl); $row++) {		
				
				$dtl_ass[$row] = array ('no' => $id,
						'itemno' => $dtl[$row]['itemno'],
						'companyno' => $dtl[$row]['companyno'],
						'departmentno' => $dtl[$row]['departmentno'],
						'machineno' => $dtl[$row]['machineno'],
						'uom' => $dtl[$row]['uom'],
						'qty' => $dtl[$row]['qty'],
						'purtype' => $dtl[$row]['purtype'],
						'wherehouseno' => $dtl[$row]['whcode']
				);
				
			}

			$this->db->insert_batch('mr_dtl', $dtl_ass);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE)
            {
				log_message('error', $e->getMessage());
				//return 0;
                    
				$return_Status[0] = "0";
				$return_Status[1] = $e->getMessage();
				$return_Status[2] = "";
            }else{
            	$return_Status[0] = "1";
            	$return_Status[1] = $id_reff;
            	$return_Status[2] = "";
            	$return_Status[3] = $id;
            	
            	$username = $this->session->userdata['logged_in']['username'];
            	$useremail = $this->session->userdata['logged_in']['email'];
            	
            	//MRN approval user
            	$email_list = $this->user->read_mail_list("MS", 8);
            	
            	$print_url = "<a href=\"".base_url()."index.php/print/3/".$id."\"/>".$id_reff."</a>";
            	
            	$message = "";
            	
            	$message .= "<h4>New MRN</h4>";
            	$message .= "<p>Please click on the follwing link to view document</p>";
            	$message .= $print_url;
            	
            	$this->generate_mail->mailfrom = $useremail;
            	$this->generate_mail->namefrom = $username;
            	
            	$this->generate_mail->mailsubject = "New MRN - ".$id_reff;
            	
            	$this->generate_mail->mailto_list= $email_list;
            	
            	$this->generate_mail->mailmessage = $message;
            	
            	$this->generate_mail->mail_send();
            	
            }
            
            return $return_Status;
			
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
	
	// Read Data from the mrn detail table for pending issue
	public function read_mrn_balance() {
		try
		{		
			$condition = "mr_dtl.qty > mr_dtl.receivedqty and mr_hdr.docstatus = 'A'";
			$this->db->select('mr_hdr.no, mr_hdr.mrno as `MRN No`, mr_hdr.date as `Date`, mr_dtl.itemno, item.itemcode as `Item Code`, item.description as `Item`, 
								mr_dtl.companyno, company.description as Company, mr_dtl.departmentno, 
								department.description as Department, mr_dtl.machineno, machine.machineno as Machine, 
								mr_dtl.uom as Uom, (mr_dtl.qty - mr_dtl.receivedqty) as Bal, 0 as `Issue Qty`, 
								mr_dtl.purtype as `Pur Type`, mr_hdr.saprefno as `SAP Ref`');
			$this->db->from('mr_dtl');
			$this->db->join('mr_hdr', 'mr_hdr.no = mr_dtl.no');
			$this->db->join('item', 'item.no = mr_dtl.itemno');
			$this->db->join('company', 'company.no = mr_dtl.companyno');
			$this->db->join('department', 'department.no = mr_dtl.departmentno');
			$this->db->join('machine', 'machine.no = mr_dtl.machineno');
			$this->db->where($condition);
			
			return $this->db->query($this->db->get_compiled_select());
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	public function read_mrn_balance_html_table() {
		try
		{
			
			$template = array(
			 'table_open'            => '<table id="tblMIN" class="w3-table-all">',
			 
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
			 
			 $this->table->set_template($template);
			 return $this->table->generate($this->read_mrn_balance());
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return "";
		}
	}
	
	public function read_mrn_for_approval($docstatus) {
		try
		{
			$dept;
			
			if (isset($this->session->userdata['logged_in'])) {
				$dept = ($this->session->userdata['logged_in']['dept']);
			} else {
				header("location: ".base_url()."index.php/User_Authentication/user_login_process");
			}
			
			$this->db->select('mr_hdr.no, mr_hdr.mrno, mr_hdr.date, mr_hdr.reqdate, mr_hdr.remarks, 
								mr_hdr.isactive, mr_hdr.insertuser, mr_hdr.insertdatetime, mr_hdr.docstatus, 
								mr_hdr.apprejby, mr_hdr.apprejdate, mr_hdr.apprejremarks, mr_hdr.saprefno');
			
			$this->db->from('mr_hdr');
			$this->db->join('mr_dtl', 'mr_dtl.no = mr_hdr.no');
			$this->db->where('mr_hdr.docstatus', $docstatus);
			$this->db->where('mr_dtl.departmentno', $dept);
			$this->db->order_by('mr_hdr.no', 'ASC');
			
			return $this->db->query($this->db->get_compiled_select());
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	public function read_mrn_hdr_by_mrnno($no) {
		try
		{
			$this->db->select('no, mrno, date, reqdate, remarks, isactive, insertuser, insertdatetime, docstatus, apprejby, apprejdate, apprejremarks, wherehouseno, saprefno');
			
			$this->db->from('mr_hdr');
			$this->db->where('no', $no);
			
			return $this->db->query($this->db->get_compiled_select());
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	public function read_mrn_dtl_for_approval($no) {
		try
		{
			
			$this->db->select('mr_hdr.mrno as `MRN No`, mr_hdr.date as `Date`, item.itemcode as `Item Code`, item.description as `Item`,
								company.description as Company, department.description as Department, machine.machineno as Machine,
								mr_dtl.uom as Uom, mr_dtl.qty as Qty, mr_dtl.purtype as `Pur Type`, 
								ifnull(vw_stock_balance.balqty,0) as `Stock Bal`, 
								wherehouse.whcode as Wherehouse, mr_hdr.saprefno as `SAP Ref`');
			$this->db->from('mr_dtl');
			$this->db->join('mr_hdr', 'mr_hdr.no = mr_dtl.no');
			$this->db->join('item', 'item.no = mr_dtl.itemno');
			$this->db->join('company', 'company.no = mr_dtl.companyno');
			$this->db->join('department', 'department.no = mr_dtl.departmentno');
			$this->db->join('machine', 'machine.no = mr_dtl.machineno');
			$this->db->join('wherehouse', 'wherehouse.no = mr_dtl.wherehouseno');
			$this->db->join('vw_stock_balance', 'vw_stock_balance.itemno = mr_dtl.itemno', 'left');
			$this->db->where('mr_hdr.no', $no);
			$this->db->order_by('mr_dtl.dtlno', 'ASC');
			
			return $this->db->query($this->db->get_compiled_select());
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	public function read_mrn_dtl_by_no($no) {
		try
		{
			
			$this->db->select('mr_hdr.mrno, mr_hdr.date as `Date`, item.itemcode, item.description as `Item`,
								company.description as Company, department.description as Department, machine.machineno as Machine,
								mr_dtl.uom as Uom, mr_dtl.qty as Qty, mr_dtl.purtype, wherehouse.whcode');
			$this->db->from('mr_dtl');
			$this->db->join('mr_hdr', 'mr_hdr.no = mr_dtl.no');
			$this->db->join('item', 'item.no = mr_dtl.itemno');
			$this->db->join('company', 'company.no = mr_dtl.companyno');
			$this->db->join('department', 'department.no = mr_dtl.departmentno');
			$this->db->join('machine', 'machine.no = mr_dtl.machineno');
			$this->db->join('wherehouse', 'wherehouse.no = mr_dtl.wherehouseno');
			$this->db->where('mr_hdr.no', $no);
			$this->db->order_by('mr_dtl.dtlno', 'ASC');
			
			return $this->db->query($this->db->get_compiled_select());
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	public function read_mrn_for_approval_html() {
		try
		{
			
			$return_html = "No MRN For Approvals..";
			
			$query = $this->read_mrn_for_approval('P');
			
			foreach ($query->result() as $row)
			{
				$dropdownhead = $row->mrno." | ".$row->date." | Req Date : ".$row->reqdate." | ".$row->remarks." | Status : ".$row->docstatus;
				
				if ($return_html === 'No MRN For Approvals..'){
					$return_html = "";
				}
				
				$return_html .= "<button onclick=\"dropdown_dtl('".$row->mrno."')\" class=\"w3-button w3-block w3-black w3-left-align\">".$dropdownhead."</button>";
				
				$return_html .= "<div id=\"".$row->mrno."\" class=\"w3-hide w3-container w3-sand\">";
				
				$return_html .= "<div class=\"w3-responsive\">";
				
				$this->table->set_template($this->template);
				
				$query_dtl = $this->read_mrn_dtl_for_approval($row->no);
				
				$return_html .= $this->table->generate($query_dtl);
				
				$return_html .= "</div>";
				
				$return_html .= "<p></p>";
				
				$return_html .= "<div class=\"w3-row-padding\">";
				$return_html .= "<div class=\"w3-col m12\">";
				$return_html .= "<label>Remarks</label>";
				$return_html .= "<textarea id=\"id_freetxt\" class=\"w3-input\"></textarea>";
				$return_html .= "</div>";
				$return_html .= "</div>";
				
				$return_html .= "<div class=\"w3-row-padding\">";
				$return_html .= "<div class=\"w3-col m4\">";
				$return_html .= "<br>";
				$return_html .= "<a href=\"".base_url()."index.php/print/3/".$row->no."\" class=\"w3-btn w3-ripple w3-blue\" target=\"_blank\"><i class=\"fa fa-print\" aria-hidden=\"true\"></i></a>";
				$return_html .= "</div>";
				$return_html .= "<div class=\"w3-col m4\">";
				$return_html .= "<br>";
				$return_html .= "<button id=\"idupdateapp_".$row->no."\" class=\"w3-btn w3-green w3-right\" class=\"w3-btn\" onclick=\"update_App_Rej(".$row->no.", 'A')\"><i class=\"fa fa-thumbs-o-up\" aria-hidden=\"true\"></i> Approve</button>";
				$return_html .= "</div>";
				$return_html .= "<div class=\"w3-col m4\">";
				$return_html .= "<br>";
				$return_html .= "<button id=\"idupdaterej\" class=\"w3-btn w3-orange w3-right\" class=\"w3-btn\" onclick=\"update_App_Rej(".$row->no.", 'R')\"><i class=\"fa fa-thumbs-o-down\" aria-hidden=\"true\"></i> Reject</button>";
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
	
	public function Update_App_Rej($data) {
		$return_Status = array();
		try
		{
			
			/*docstatus
			 apprejby
			 apprejdate
			 apprejremarks*/
			
			$prno = $data[0]['mrnno'];
			$docstatus = $data[0]['docstatus'];
			$apprejremarks = $data[0]['apprejremarks'];
			$apprejby = $data[0]['insertuser'];
			
			$this->db->set('docstatus', $docstatus);
			$this->db->set('apprejby', $apprejby);
			$this->db->set('apprejdate', 'now()', false);
			$this->db->set('apprejremarks', $apprejremarks);
			$this->db->where('no', $prno);
			$this->db->where('docstatus', "P");
			$this->db->update('mr_hdr');
			
			if($this->db->affected_rows() >= 1){
				$return_Status[0] = "1";
				if ($docstatus == 'A'){
					$return_Status[1] = "Approved";
				}else{
					$return_Status[1] = "Rejected";
				}
				$return_Status[2] = $this->read_mrn_for_approval_html();
				
				//MRN create user
				$email_list = $this->user->read_mail_list_by_doc(1, $prno);
				
				$print_url = "<a href=\"".base_url()."index.php/print/3/".$prno."\"/>".$prno."</a>";
				
				$message = "";
				
				$message .= "<h4>MRN ".$return_Status[1]."</h4>";
				$message .= "<p>Please click on the follwing link to view document</p>";
				$message .= $print_url;
				
				$this->generate_mail->mailfrom = $useremail;
				$this->generate_mail->namefrom = $username;
				
				$this->generate_mail->mailsubject = $return_Status[1]." MRN - ".$prno;
				
				$this->generate_mail->mailto_list= $email_list;
				
				$this->generate_mail->mailmessage = $message;
				
				$this->generate_mail->mail_send();
			}else{
				$return_Status[0] = "0";
				$return_Status[1] = "Already Updated or Something Went Wrong";
				$return_Status[2] = $this->read_mrn_for_approval_html();
			}
			
			return $return_Status;
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			$return_Status[0] = "0";
			$return_Status[1] = "Something Went Wrong";
			$return_Status[2] = "";
			
			return $return_Status;
		}
	}
	
	public function update_received_qty($qty, $docno, $itemno, $refcom, $deptno, $machineno){
		try
		{
			$received_qty = 'receivedqty + '.$qty;
			
			$this->db->set('receivedqty', $received_qty, false);
			$this->db->where('no', $docno);
			$this->db->where('itemno', $itemno);
			$this->db->where('companyno', $refcom);
			$this->db->where('departmentno', $deptno);
			$this->db->where('machineno', $machineno);
			$this->db->where('(qty - ifnull(receivedqty,0)) >=', $qty);
			$this->db->update('mr_dtl');
			
			return $this->db->affected_rows();
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return $e->getMessage();
		}

	}
	
	public function read_mrn_list_report($DateData, $ComPara, $DeptPara, $ItemPara, $StatusPara, $MachinePara) {
		try
		{
			
			/*select h.mrno, i.itemcode, i.description Item, d.qty, d.uom, h.date,
				h.docstatus, c.description company, de.description dept, m.machineno, d.purtype
				from mr_dtl d
				inner join mr_hdr h on d.no = h.no
				inner join item i on d.itemno = i.no
				inner join company c on d.companyno = c.no
				inner join department de on d.departmentno = de.no
				inner join machine m on d.machineno = m.no*/
			
			$this->db->select('h.mrno, i.itemcode, i.description Item, d.qty, d.uom, h.date,
				h.docstatus, c.description company, de.description dept, m.machineno, d.purtype');
			$this->db->from('mr_dtl d');
			
			$this->db->join('mr_hdr h', 'd.no = h.no');
			$this->db->join('item i', 'd.itemno = i.no');
			$this->db->join('company c', 'd.companyno = c.no');
			$this->db->join('department de', 'd.departmentno = de.no');
			$this->db->join('machine m', 'd.machineno = m.no');
			
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
			
			if(isset($approve) && !empty($approve)){
				$this->db->where('h.docstatus', $approve);
			}
			
			$this->db->order_by('h.mrno', 'ASC');
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