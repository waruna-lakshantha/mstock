<?php

Class Pr extends CI_Model {	
	
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
		$this->load->model('vendor');
		$this->load->model('currency');	
	}
	
	// Read user menu
	public function pr_insert($hdr,$dtl) {
		
		$return_Status = array();
		
		try
		{	
								            
			$hdr_ass = array();	
			$dtl_ass = array();
			
			$reqdate;
			
			//$d = explode("_", $hdr[0]['reqdate']);
			//$reqdate= $d[0]."-".$d[1]."-".$d[2];
			
			//throw new Exception('dd'.$hdr[0]['remarks']);
			
			//throw new Exception($hdr[0]['remarks']);
			
			$hdr_ass['reqdate'] = $hdr[0]['reqdate'];
			$hdr_ass['remarks'] = $hdr[0]['remarks'];
			$hdr_ass['insertuser'] = $hdr[0]['insertuser'];
			$hdr_ass['docstatus'] = 'P';
            $hdr_ass['acceptrejby'] = 'P';
			$hdr_ass['proceed'] = 0;
			$hdr_ass['purtype'] = $hdr[0]['purtype'];
			$hdr_ass['saprefno'] = $hdr[0]['sap_refno'];
														
			$this->db->trans_start();
			
			$this->db->insert('pr_hdr',$hdr_ass);
			
			$id = $this->db->insert_id();						
			
			$id_reff = "R".str_pad($id, 9, '0', STR_PAD_LEFT);
			
			$this->db->set('prno', $id_reff);
			$this->db->set('insertdatetime', $this->LocalTime, false);
			$this->db->set('date', $this->LocalTime, false);
			$this->db->where('no', $id);
			$this->db->update('pr_hdr'); // gives UPDATE grn_hdr SET grnno = value WHERE id = 2
								
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
						'receivedqty' => 0,
						'grnqty' => 0
				);
				
			}

			$this->db->insert_batch('pr_dtl', $dtl_ass);

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
            	$return_Status[2] = "";
            	$return_Status[3] = $id;
            	
            	$username = $this->session->userdata['logged_in']['username'];
            	$useremail = $this->session->userdata['logged_in']['email'];

            	$email_list = $this->user->read_mail_list("MS", 6);            	

            	$print_url = "<a href=\"".base_url()."index.php/print/1/".$id."\"/>".$id_reff."</a>";

            	$message = "";

            	$message .= "<h4>New MRP</h4>";
            	$message .= "<p>Please click on the follwing link to view document</p>";
            	$message .= $print_url;
            	
            	$this->generate_mail->mailfrom = $useremail;
            	$this->generate_mail->namefrom = $username;
            	
            	$this->generate_mail->mailsubject = "New MRP - ".$id_reff;
            	
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

	public function Update_App_Rej($data) {
        $return_Status = array();
		try
		{
						
			/*docstatus
			apprejby
			apprejdate
			apprejremarks*/
			
            $prno = $data[0]['prno'];
            $docstatus = $data[0]['docstatus'];
            $apprejremarks = $data[0]['apprejremarks'];
            $apprejby = $data[0]['insertuser'];           

            $this->db->set('docstatus', $docstatus);
            $this->db->set('apprejby', $apprejby);
            $this->db->set('apprejdate', 'now()', false);
            $this->db->set('apprejremarks', $apprejremarks);
            $this->db->where('no', $prno);
            $this->db->where('docstatus', "P");
            $this->db->where('acceptrej', "P");
            $this->db->where('proceed', 0);
		    $this->db->update('pr_hdr');
		    
		    $pr = $this->read_pr_hdr($prno);
		    
		    if($this->db->affected_rows() >= 1){
		    	$return_Status[0] = "1";
		    	if ($docstatus == 'A'){
		    		$return_Status[1] = "Approved";
		    	}else{
		    		$return_Status[1] = "Rejected";
		    	}		    	
		    	$return_Status[2] = $this->read_pr_for_approval_html();
		    	
		    	$username = $this->session->userdata['logged_in']['username'];
		    	$useremail = $this->session->userdata['logged_in']['email'];
		    	
		    	//MRP generate user
		    	$email_list = $this->user->read_mail_list_by_doc(1, $prno);
		    	
		    	$print_url = "<a href=\"".base_url()."index.php/print/1/".$prno."\"/>".$prno."</a>";
		    	
		    	$message = "";
		    	
		    	$message .= "<h4>MRP - ".$return_Status[1]."</h4>";
		    	$message .= "<p>Please click on the follwing link to view document</p>";
		    	$message .= $print_url;
		    	
		    	$this->generate_mail->mailfrom = $useremail;
		    	$this->generate_mail->namefrom = $username;
		    	
		    	$this->generate_mail->mailsubject = $return_Status[1]." MRP - ".$prno;
		    			    	
		    	$this->generate_mail->mailto_list = $email_list;
		    	
		    	$this->generate_mail->mailmessage = $message;
		    	
		    	$this->generate_mail->mail_send();		    	
		    	
		    	if ($docstatus === 'A'){
			    	//MRP accept user
			    	if($pr->purtype === 'L'){
			    		$email_list = $this->user->read_mail_list_all("MS", 10);
			    	}else{
			    		$email_list = $this->user->read_mail_list_all("MS", 13);
			    	}
			    			    	
			    	$this->generate_mail->mailto_list= $email_list;		    	
			    	$this->generate_mail->mail_send();
			    	
			    	//MRP pending view user
			    	$email_list = $this->user->read_mail_list_all("MS", 30);		    	
			    	$this->generate_mail->mailto_list= $email_list;		    	
			    	$this->generate_mail->mail_send();
		    	}
		    	
		    }else{
		    	$return_Status[0] = "0";
		    	$return_Status[1] = "Already Updated or Something Went Wrong";
		    	$return_Status[2] = $this->read_pr_for_approval_html();
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

	public function Update_Acc_Rej($data) {
        $return_Status = array();
		try
		{
						
			/*docstatus
			apprejby
			apprejdate
			apprejremarks*/			
			
            $prno = $data[0]['prno'];
            $docstatus = $data[0]['docstatus'];
            $apprejremarks = $data[0]['apprejremarks'];
            $apprejby = $data[0]['insertuser'];     
            
            $pr = $this->read_pr_hdr($prno);

            $this->db->set('acceptrej', $docstatus);
            $this->db->set('acceptrejby', $apprejby);
            $this->db->set('acceptrejdate', 'now()', false);
            $this->db->set('acceptrejremarks', $apprejremarks);
            $this->db->where('no', $prno);
            $this->db->where('docstatus', "A");
            $this->db->where('acceptrej', "P");
            $this->db->where('proceed', 0);
		    $this->db->update('pr_hdr');
		    
		    if($this->db->affected_rows() >= 1){
		    	$return_Status[0] = "1";
		    	if ($docstatus == 'A'){
		    		$return_Status[1] = "Approved";
		    	}else{
		    		$return_Status[1] = "Rejected";
		    	}		    	
		    	$return_Status[2] = $this->read_pr_for_accept_html($pr->purtype);
		    	
		    	$username = $this->session->userdata['logged_in']['username'];
		    	$useremail = $this->session->userdata['logged_in']['email'];
		    	
		    	//MRP generate user
		    	$email_list = $this->user->read_mail_list_by_doc(1, $prno);
		    	
		    	$print_url = "<a href=\"".base_url()."index.php/print/1/".$prno."\"/>".$prno."</a>";
		    	
		    	$message = "";
		    	
		    	$message .= "<h4>MRP - ".$return_Status[1]."</h4>";
		    	$message .= "<p>Please click on the follwing link to view document</p>";
		    	$message .= $print_url;
		    	
		    	$this->generate_mail->mailfrom = $useremail;
		    	$this->generate_mail->namefrom = $username;
		    	
		    	$this->generate_mail->mailsubject = $return_Status[1]." MRP - ".$prno;
		    			    	
		    	$this->generate_mail->mailto_list= $email_list;
		    	
		    	$this->generate_mail->mailmessage = $message;
		    	
		    	$this->generate_mail->mail_send();		    	
		    	
		    	//MRP approve user
		    	$email_list = $this->user->read_mail_list_user($pr->apprejby);
		    	$this->generate_mail->mailto_list = $email_list;
		    	$this->generate_mail->mail_send();	
		    	
		    	if ($docstatus == 'A'){
			    	//MRP proceed user
			    	if($pr->purtype === 'L'){
			    		$email_list = $this->user->read_mail_list_all("MS", 7);
			    	}else{
			    		$email_list = $this->user->read_mail_list_all("MS", 14);
			    	}
			    	$this->generate_mail->mailto_list = $email_list;		    	
			    	$this->generate_mail->mail_send();
		    	}
		    	
		    }else{
		    	$return_Status[0] = "0";
		    	$return_Status[1] = "Already Updated or Something Went Wrong";
		    	$return_Status[2] = $this->read_pr_for_accept_html($pr->purtype);
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
	
	public function Update_Proceed($data, $dtl) {
		$return_Status = array();
		try
		{
			
			/*proceed
			proceedby
			proceeddate*/
			
			/*dtlno
			supplier
			pono          		            		            
			qty
			ucost
			currency
			currate
			ucostlkr
			total*/					
			
			$dtl_proceed = array();
			
			$this->db->trans_start();
			
			for ($row = 0; $row < count($dtl); $row++) {
				$dtlno = $dtl[$row]['dtlno'];
				$supplier = $dtl[$row]['supplier'];
				$pono = $dtl[$row]['pono'];
				$qty = $dtl[$row]['qty'];	
                $ucost = $dtl[$row]['ucostlkr']; 
                
                $curcode = $dtl[$row]['currency'];
                $curreate = $dtl[$row]['currate'];
                $curcost = $dtl[$row]['ucost'];                                 
                
                $total = $dtl[$row]['total'];                
				
				$pr_dtl_line = $this->read_pr_dtl_by_dtlno($dtlno);							
				
				if($pr_dtl_line === False){
					$this->db->trans_rollback();
					throw new Exception('PR Proceed Detail Not Found!');
				}
				
				$recqty = 'receivedqty + ' . $qty;
				$this->db->set('receivedqty', $recqty, false);
				$this->db->where('dtlno', $dtlno);
				$this->db->update('pr_dtl');
				
				if($this->db->affected_rows() == 0){
					$this->db->trans_rollback();
					throw new Exception('PR Detail Update Error!');
				}							
				
				$dtl_proceed[$row] = array ('no' => $data[0]['prno'],
						'itemno' => $pr_dtl_line->itemno,
						'companyno' => $pr_dtl_line->companyno,
						'departmentno' => $pr_dtl_line->departmentno,
						'machineno' => $pr_dtl_line->machineno,
						'uom' => $pr_dtl_line->uom,
						'qty' => $qty,
						'grnqty' => 0,
						'supplierno' => $supplier,
						'pono' => $pono,
						'unitcost' => $ucost,
						'totalcost' => $total,
						'curcode' => $curcode,
						'curreate' => $curreate,
						'curcost' => $curcost
				);
				
				//throw new Exception('Err '.$dtl_proceed[$row]['curcode']." - ".$dtl_proceed[$row]['curreate']." - ".$dtl_proceed[$row]['curcost']);
			}			
			
			$pr = $this->read_pr_hdr($data[0]['prno']);	
			
			$this->db->insert_batch('pr_dtl_proceed', $dtl_proceed);
			
			//throw new Exception('Err '.$this->db->affected_rows());			
			
			if($this->db->affected_rows() >= 1){
				$return_Status[0] = "1";
				$return_Status[1] = "Successfully Processed";
				$return_Status[2] = $this->read_pr_for_proceed_html($pr->purtype);
				//$return_Status[2] = "";
				
				$username = $this->session->userdata['logged_in']['username'];
				$useremail = $this->session->userdata['logged_in']['email'];
				
				//MRP generate user
				$email_list = $this->user->read_mail_list_by_doc(1, $data[0]['prno']);
				
				$print_url = "<a href=\"".base_url()."index.php/print/1/".$data[0]['prno']."\"/>".$data[0]['prno']."</a>";
				
				$message = "";
				
				$message .= "<h4>MRP Proceed</h4>";
				$message .= "<p>Please click on the follwing link to view document</p>";
				$message .= $print_url;
				
				$this->generate_mail->mailfrom = $useremail;
				$this->generate_mail->namefrom = $username;
				
				$this->generate_mail->mailsubject = "Proceed MRP - ".$data[0]['prno'];
				
				$this->generate_mail->mailto_list = $email_list;
				
				$this->generate_mail->mailmessage = $message;
				
				$this->generate_mail->mail_send();
				
				//MRP approve user
				$email_list = $this->user->read_mail_list_user($pr->apprejby);
				$this->generate_mail->mailto_list = $email_list;
				$this->generate_mail->mail_send();
				
				//MRP accept user
				$email_list = $this->user->read_mail_list_user($pr->acceptrejby);
				$this->generate_mail->mailto_list = $email_list;
				$this->generate_mail->mail_send();
				
				//GRN user
				$email_list = $this->user->read_mail_list_all("MS", 2);				
				$this->generate_mail->mailto_list = $email_list;				
				$this->generate_mail->mail_send();
				
			}else{
				$return_Status[0] = "0";
				$return_Status[1] = "Not Proceed";
				$return_Status[2] = $this->read_pr_for_proceed_html($pr->purtype);
				//$return_Status[2] = "";
				$this->db->trans_rollback();
			}					
			
			//$this->db->trans_rollback();
			$this->db->trans_complete();					
			
			return $return_Status;
		}
		catch(Exception $e)
		{
			$this->db->trans_rollback();
			log_message('error', $e->getMessage());
			$return_Status[0] = "0";
			$return_Status[1] = "Something Went Wrong ". $e->getMessage();
			$return_Status[2] = "";
			
			return $return_Status;
		}
	}
	
	public function read_pr_dtl_by_dtlno($dtlno){
		try
		{	
			$this->db->select('no, itemno, companyno, departmentno, machineno, uom, qty, receivedqty');
			$this->db->from('pr_dtl');
			$this->db->where('dtlno', $dtlno);
			
			$result = $this->db->query($this->db->get_compiled_select());
			
			return $result->row();
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return false;
		}
	}
	
	public function read_pr_dtl_by_no_item($no, $item){
		//try
		//{
			$this->db->select('no, itemno, companyno, departmentno, machineno, uom, qty, receivedqty');
			$this->db->from('pr_dtl');
			$this->db->where('no', $no);
			$this->db->where('itemno', $item);
			
			$result = $this->db->query($this->db->get_compiled_select());
			
			return $result->row();
		/*}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return false;
		}*/
	}
	
	// Read Data from the pr detail table for pending issue
	public function read_pr_balance() {
		try
		{		
			$condition = "pr_dtl.qty > pr_dtl.receivedqty and pr_hdr.docstatus = 'A' and pr_hdr.proceed = 1";
			$this->db->select('pr_hdr.no, pr_hdr.prno as `PR No`, pr_hdr.date as `Date`, pr_dtl.itemno, item.itemcode as `Item Code`, item.description as `Item`, 
								pr_dtl.companyno, company.description as Company, pr_dtl.departmentno, 
								department.description as Department, pr_dtl.machineno, machine.machineno as Machine, 0 as `Rec Qty`, 0 as `U Cost`, 
								0 as `Total Cost`, pr_dtl.uom as Uom_hide, (pr_dtl.qty - pr_dtl.receivedqty) as Bal, pr_dtl.uom as Uom');
			$this->db->from('pr_dtl');
			$this->db->join('pr_hdr', 'pr_hdr.no = pr_dtl.no');
			$this->db->join('item', 'item.no = pr_dtl.itemno');
			$this->db->join('company', 'company.no = pr_dtl.companyno');
			$this->db->join('department', 'department.no = pr_dtl.departmentno');
			$this->db->join('machine', 'machine.no = pr_dtl.machineno');
			$this->db->where($condition);
			
			return $this->db->query($this->db->get_compiled_select());
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	// Read Data from the pr detail table for pending issue
	public function read_pr_po_qty_balance() {
		try
		{
			$condition = "pr_dtl_proceed.qty > pr_dtl_proceed.grnqty and pr_hdr.docstatus = 'A' and pr_hdr.acceptrej = 'A'";
			$this->db->select('pr_hdr.no, pr_hdr.prno as `PR No`, pr_hdr.date as `Date`, pr_dtl_proceed.itemno, item.itemcode as `Item Code`,
								item.description as `Item`, pr_dtl_proceed.companyno, company.description as Company, pr_dtl_proceed.departmentno,
								department.description as Department, pr_dtl_proceed.machineno, machine.machineno as Machine, 0 as `Rec Qty`, pr_dtl_proceed.unitcost as `U Cost`,
								0 as `Total Cost`, 0 as `Rec Date`, pr_dtl_proceed.uom as Uom_hide, (pr_dtl_proceed.qty - pr_dtl_proceed.grnqty) as Bal, 
								pr_dtl_proceed.uom as Uom, pr_dtl_proceed.dtlno, pr_dtl_proceed.supplierno, pr_dtl_proceed.pono, wherehouse.whcode as Wherehouse, item.whcode');
			$this->db->from('pr_dtl_proceed');
			$this->db->join('pr_hdr', 'pr_hdr.no = pr_dtl_proceed.no');
			$this->db->join('item', 'item.no = pr_dtl_proceed.itemno');
			$this->db->join('wherehouse', 'wherehouse.no = item.whcode');
			$this->db->join('company', 'company.no = pr_dtl_proceed.companyno');
			$this->db->join('department', 'department.no = pr_dtl_proceed.departmentno');
			$this->db->join('machine', 'machine.no = pr_dtl_proceed.machineno');
			$this->db->where($condition);
			
			return $this->db->query($this->db->get_compiled_select());
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	public function read_pr_balance_html_table() {
		try
		{
			$template = array(
					'table_open'            => '<table id="tblGRN" class="w3-table-all">',
					
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
			return $this->table->generate($this->read_pr_po_qty_balance());
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return "";
		}
	}
	
	public function read_pr_for_approval($docstatus, $accept, $prtype) {
		try
		{
			$this->db->select('no, prno, date, reqdate, remarks, insertuser, docstatus, apprejby, 
                                apprejdate, apprejremarks, proceed, proceedby, proceeddate, 
                                purtype, acceptrej, acceptrejby, acceptrejdate, acceptrejremarks, saprefno');
			
			$this->db->from('pr_hdr');
			$this->db->where('docstatus', $docstatus);
            $this->db->where('acceptrej', $accept);
            $this->db->where('purtype', $prtype);
			$this->db->order_by('no', 'ASC');
			
			return $this->db->query($this->db->get_compiled_select());
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	public function read_pr_for_approval_L_I($docstatus, $accept) {
		try
		{
			
			$dept;
			
			if (isset($this->session->userdata['logged_in'])) {
				$dept = ($this->session->userdata['logged_in']['dept']);
			} else {
				header("location: ".base_url()."index.php/User_Authentication/user_login_process");
			}
			
			$this->db->select('pr_hdr.no, pr_hdr.prno, pr_hdr.date, pr_hdr.reqdate, pr_hdr.remarks, 
								pr_hdr.insertuser, pr_hdr.docstatus, pr_hdr.apprejby,
                                pr_hdr.apprejdate, pr_hdr.apprejremarks, pr_hdr.proceed, 
								pr_hdr.proceedby, pr_hdr.proceeddate,
                                pr_hdr.purtype, pr_hdr.acceptrej, pr_hdr.acceptrejby, 
								pr_hdr.acceptrejdate, pr_hdr.acceptrejremarks, pr_hdr.saprefno');
			
			$this->db->from('pr_hdr');
			$this->db->join('pr_dtl', 'pr_dtl.no = pr_hdr.no');
			$this->db->where('pr_hdr.docstatus', $docstatus);
			$this->db->where('pr_hdr.acceptrej', $accept);
			$this->db->where('pr_dtl.departmentno', $dept);
			$this->db->order_by('pr_hdr.no', 'ASC');
			
			return $this->db->query($this->db->get_compiled_select());
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	public function read_pr_for_proceed($docstatus, $accept, $prtype) {
		try
		{
			
			$this->db->select('pr_hdr.no, pr_hdr.prno, pr_hdr.date, pr_hdr.reqdate, pr_hdr.remarks, 
								pr_hdr.insertuser, pr_hdr.docstatus, pr_hdr.apprejby,
                                pr_hdr.apprejdate, pr_hdr.apprejremarks, pr_hdr.proceed, pr_hdr.proceedby, pr_hdr.proceeddate,
                                pr_hdr.purtype, pr_hdr.acceptrej, pr_hdr.acceptrejby, pr_hdr.acceptrejdate, pr_hdr.acceptrejremarks,
								pr_dtl.itemno, pr_dtl.companyno, pr_dtl.departmentno, pr_dtl.machineno, 
								pr_dtl.uom, pr_dtl.qty, pr_dtl.receivedqty, pr_dtl.dtlno, 
								pr_dtl.grnqty, pr_dtl.supplierno, pr_dtl.pono,
								company.description as Company, department.description as Department, machine.machineno as Machine,
								(pr_dtl.qty - pr_dtl.receivedqty) as BalQty, item.description as Item, item.itemcode, pr_hdr.saprefno');
			$this->db->from('pr_dtl');
			$this->db->join('pr_hdr', 'pr_hdr.no = pr_dtl.no');
			$this->db->join('item', 'item.no = pr_dtl.itemno');
			$this->db->join('company', 'company.no = pr_dtl.companyno');
			$this->db->join('department', 'department.no = pr_dtl.departmentno');
			$this->db->join('machine', 'machine.no = pr_dtl.machineno');
			$this->db->where('pr_hdr.docstatus', $docstatus);
			$this->db->where('pr_hdr.acceptrej', $accept);
			$this->db->where('pr_hdr.purtype', $prtype);
			$this->db->where('pr_dtl.qty > ifnull(pr_dtl.receivedqty,0)');
			$this->db->order_by('pr_hdr.no', 'ASC');
			$this->db->order_by('pr_dtl.dtlno', 'ASC');
			
			return $this->db->query($this->db->get_compiled_select());
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	public function read_pr_for_proceed_local_import($docstatus, $accept) {
		try
		{
			
			$this->db->select('pr_hdr.no, pr_hdr.prno, pr_hdr.date, pr_hdr.reqdate, pr_hdr.remarks,
								pr_hdr.insertuser, pr_hdr.docstatus, pr_hdr.apprejby,
                                pr_hdr.apprejdate, pr_hdr.apprejremarks, pr_hdr.proceed, pr_hdr.proceedby, pr_hdr.proceeddate,
                                pr_hdr.purtype, pr_hdr.acceptrej, pr_hdr.acceptrejby, pr_hdr.acceptrejdate, pr_hdr.acceptrejremarks,
								pr_dtl.itemno, pr_dtl.companyno, pr_dtl.departmentno, pr_dtl.machineno,
								pr_dtl.uom, pr_dtl.qty, pr_dtl.receivedqty, pr_dtl.dtlno,
								pr_dtl.grnqty, pr_dtl.supplierno, pr_dtl.pono, vendor.name as venname, 
								company.description as Company, department.description as Department, machine.machineno as Machine,
								(pr_dtl.qty - pr_dtl.receivedqty) as BalQty, item.description as Item, item.itemcode, pr_hdr.saprefno');
			$this->db->from('pr_dtl');
			$this->db->join('pr_hdr', 'pr_hdr.no = pr_dtl.no');
			$this->db->join('item', 'item.no = pr_dtl.itemno');
			$this->db->join('company', 'company.no = pr_dtl.companyno');
			$this->db->join('department', 'department.no = pr_dtl.departmentno');
			$this->db->join('machine', 'machine.no = pr_dtl.machineno');
			$this->db->join('vendor', 'vendor.no = pr_dtl.supplierno', 'left outer');
			$this->db->where('pr_hdr.docstatus', $docstatus);
			$this->db->where('pr_hdr.acceptrej', $accept);
			$this->db->where('pr_dtl.qty > ifnull(pr_dtl.grnqty,0)');
			$this->db->order_by('pr_hdr.no', 'ASC');
			$this->db->order_by('pr_dtl.dtlno', 'ASC');
			
			return $this->db->query($this->db->get_compiled_select());
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	public function read_pr_hdr_by_prno($no) {
		try
		{
			$this->db->select('no, prno, date, reqdate, remarks, insertuser, docstatus, apprejby, apprejdate, apprejremarks, acceptrej, acceptrejby, proceed, proceedby, proceeddate, insertdatetime, purtype, saprefno');
			
			$this->db->from('pr_hdr');
			$this->db->where('no', $no);
			
			return $this->db->query($this->db->get_compiled_select());
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	public function read_pr_dtl_for_approval($no) {
		try
		{
			$this->db->select('pr_hdr.prno as `PR No`, pr_hdr.date as `Date`, item.itemcode as `Item Code`, 
                                item.description as `Item`, pr_dtl.qty as Qty, pr_dtl.uom as Uom, 
                                company.description as Company, department.description as Department, 
								machine.machineno as Machine, ifnull(vw_stock_balance.balqty,0) as `Stock Bal`,
								pr_hdr.saprefno as `SAP Ref`');
			$this->db->from('pr_dtl');
			$this->db->join('pr_hdr', 'pr_hdr.no = pr_dtl.no');
			$this->db->join('item', 'item.no = pr_dtl.itemno');
			$this->db->join('company', 'company.no = pr_dtl.companyno');
			$this->db->join('department', 'department.no = pr_dtl.departmentno');
			$this->db->join('machine', 'machine.no = pr_dtl.machineno');
			$this->db->join('vw_stock_balance', 'vw_stock_balance.itemno = pr_dtl.itemno', 'left');
			$this->db->where('pr_hdr.no', $no);
			$this->db->order_by('pr_dtl.dtlno', 'ASC');
			
			return $this->db->query($this->db->get_compiled_select());
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	public function read_pr_dtl_by_no($no) {
		try
		{
			$this->db->select('pr_hdr.prno, pr_hdr.date as `Date`, item.itemcode, item.description as `Item`,
								company.description as Company, department.description as Department, machine.machineno as Machine,
								pr_dtl.qty as Qty, pr_dtl.uom as Uom, pr_dtl.dtlno');
			$this->db->from('pr_dtl');
			$this->db->join('pr_hdr', 'pr_hdr.no = pr_dtl.no');
			$this->db->join('item', 'item.no = pr_dtl.itemno');
			$this->db->join('company', 'company.no = pr_dtl.companyno');
			$this->db->join('department', 'department.no = pr_dtl.departmentno');
			$this->db->join('machine', 'machine.no = pr_dtl.machineno');
			$this->db->where('pr_hdr.no', $no);
			$this->db->order_by('pr_dtl.dtlno', 'ASC');
			
			return $this->db->query($this->db->get_compiled_select());
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	public function read_pr_for_approval_html() {
		try
		{

			$return_html = "No MRP For Approvals..";
			
			$query = $this->read_pr_for_approval_L_I('P', 'P');
			
			foreach ($query->result() as $row)
			{	
				
				$pur_type = "-";
				
				if($row->purtype === "L"){
					$pur_type = 'Local';
				}else{
					$pur_type = 'Import';
				}
				
				$dropdownhead = $row->prno." | ".$row->date." | Req Date : ".$row->reqdate." | ".$row->remarks." | Status : ".$row->docstatus. " | Pur Type : ".$pur_type;
				
				if ($return_html === 'No MRP For Approvals..'){
					$return_html = "";
				}
				
				$return_html .= "<button onclick=\"dropdown_dtl('".$row->prno."')\" class=\"w3-button w3-block w3-black w3-left-align\">".$dropdownhead."</button>";							
				
				$return_html .= "<div id=\"".$row->prno."\" class=\"w3-hide w3-container w3-sand\">";
				
				$this->table->set_template($this->template);
				
				$query_dtl = $this->read_pr_dtl_for_approval($row->no);
				
				$return_html .= "<div class=\"w3-responsive\">";
				
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
		            $return_html .= "<a href=\"".base_url()."index.php/print/1/".$row->no."\" class=\"w3-btn w3-ripple w3-blue\" target=\"_blank\"><i class=\"fa fa-print\" aria-hidden=\"true\"></i></a>";
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

	public function read_pr_for_accept_html($prtype) {
		try
		{

			$return_html = "No MRP For Accept..";
			
			$query = $this->read_pr_for_approval('A', 'P', $prtype);
			
			foreach ($query->result() as $row)
			{	
				
				$pur_type = "-";
				
				if($row->purtype === "L"){
					$pur_type = 'Local';
				}else{
					$pur_type = 'Import';
				}
				
				$dropdownhead = $row->prno." | ".$row->date." | Req Date : ".$row->reqdate." | ".$row->remarks." | Status : ".$row->docstatus. " | Pur Type : ".$pur_type;
				
				if ($return_html === 'No MRP For Accept..'){
					$return_html = "";
				}
				
				$return_html .= "<button onclick=\"dropdown_dtl('".$row->prno."')\" class=\"w3-button w3-block w3-black w3-left-align\">".$dropdownhead."</button>";							
				
				$return_html .= "<div id=\"".$row->prno."\" class=\"w3-hide w3-container w3-sand\">";
				
				$this->table->set_template($this->template);
				
				$query_dtl = $this->read_pr_dtl_for_approval($row->no);
				
				$return_html .= "<div class=\"w3-responsive\">";
				
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
		            $return_html .= "<a href=\"".base_url()."index.php/print/1/".$row->no."\" class=\"w3-btn w3-ripple w3-blue\" target=\"_blank\"><i class=\"fa fa-print\" aria-hidden=\"true\"></i></a>";
		            $return_html .= "</div>";
		            $return_html .= "<div class=\"w3-col m4\">";
			            $return_html .= "<br>";
			            $return_html .= "<button id=\"idupdateapp_".$row->no."\" class=\"w3-btn w3-green w3-right\" class=\"w3-btn\" onclick=\"update_Acc_Rej(".$row->no.", 'A')\"><i class=\"fa fa-thumbs-o-up\" aria-hidden=\"true\"></i> Accept</button>";
		            $return_html .= "</div>";
		            $return_html .= "<div class=\"w3-col m4\">";
			            $return_html .= "<br>";
			            $return_html .= "<button id=\"idupdaterej\" class=\"w3-btn w3-orange w3-right\" class=\"w3-btn\" onclick=\"update_Acc_Rej(".$row->no.", 'R')\"><i class=\"fa fa-thumbs-o-down\" aria-hidden=\"true\"></i> Reject</button>";
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
	
	public function read_pr_for_pending_store() {
		try
		{
			
			$return_html = "";
			
			$query = $this->read_pr_for_proceed_local_import('A', 'A');
			
			$rcount = $this->db->affected_rows();
			
			$preno = 0;
			
			$preno_btn_id = 0;
			
			$curindx = 1;
			
			foreach ($query->result() as $row)
			{
				
				$pur_type = "-";
				
				if($row->purtype === "L"){
					$pur_type = 'Local';
				}else{
					$pur_type = 'Import';
				}
				
				if ($return_html === 'No MRP For Proceed..'){
					$return_html = "";
				}
				
				$ven;
				$ven_list = '';
				
				$ven = $this->vendor->read_vendor_active_by_type($row->purtype);
				
				$curr;
				$curr_list = '';
				
				$curr = $this->currency->read_currency();
				
				if ($preno !== $row->no){
					
					if($preno !== 0){
						
						$return_html .= "</tbody>";
						$return_html .= "</table>";
						
						$return_html .= "</div>";
						
						/*$return_html .= "<div class=\"w3-row-padding\">";
						$return_html .= "<div class=\"w3-col m12\">";
						$return_html .= "<label>Remarks</label>";
						$return_html .= "<textarea id=\"id_freetxt_".$row->no."\" class=\"w3-input\"></textarea>";
						$return_html .= "</div>";
						$return_html .= "</div>";*/
						
						$return_html .= "<div class=\"w3-row-padding\">";
						$return_html .= "<div class=\"w3-col m12\">";
						$return_html .= "<br>";
						$return_html .= "<a href=\"".base_url()."index.php/print/1/".$row->no."\" class=\"w3-btn w3-ripple w3-blue\" target=\"_blank\"><i class=\"fa fa-print\" aria-hidden=\"true\"></i></a>";
						$return_html .= "</div>";
						/*$return_html .= "<div class=\"w3-col m4\">";
						$return_html .= "<p></p>";
						$return_html .= "</div>";
						$return_html .= "<div class=\"w3-col m4\">";
						$return_html .= "<br>";
						$return_html .= "<button id=\"idupdaterej_".$preno."\" class=\"w3-btn w3-green w3-right\" class=\"w3-btn\" onclick=\"update_Proceed(".$preno.")\"><i class=\"fa fa-shopping-basket\" aria-hidden=\"true\"></i> Proceed</button>";
						$return_html .= "</div>";*/
						$return_html .= "</div>";
						
						$return_html .= "<br>";
						
						$return_html .= "</div>";
						
					}
					
					$dropdownhead = $row->prno." | ".$row->date." | ".$row->remarks." | Status : ".$row->docstatus." | Approved  : ".$row->apprejdate. " | Pur Type : ".$pur_type;
					
					$return_html .= "<button onclick=\"dropdown_dtl('".$row->prno."')\" class=\"w3-button w3-block w3-black w3-left-align\">".$dropdownhead."</button>";
					
					$return_html .= "<div id=\"".$row->prno."\" class=\"w3-hide w3-container w3-sand w3-responsive\">";
					
					$return_html .= "<div class=\"w3-responsive\">";
					
					$return_html .= "<table id=\"id_pr_det_".$row->no."\" class=\"w3-table-all\">";
					$return_html .= "<thead>";
					$return_html .= "<tr class=\"w3-cyan\">";
					$return_html .= "<th style=\"display:none;\">Dtl No</th>";
					$return_html .= "<th>Item Code</th>";
					$return_html .= "<th>Item</th>";
					$return_html .= "<th>Supplier</th>";
					$return_html .= "<th>PO No</th>";
					$return_html .= "<th>PO Qty</th>";
					
					//$return_html .= "<th style=\"display:none;\">Unit Cost</th>";
					
					//$return_html .= "<th style=\"display:none;\">Currency</th>";
					
					//$return_html .= "<th style=\"display:none;\">Curr Rate</th>";
					
					//$return_html .= "<th style=\"display:none;\">U Cost LKR</th>";
					
					//$return_html .= "<th style=\"display:none;\">Total</th>";
					
					//$return_html .= "<th>Bal Qty</th>";
					$return_html .= "<th>Uom</th>";
					$return_html .= "<th>Company</th>";
					$return_html .= "<th>Department</th>";
					$return_html .= "<th>Machine</th>";
					//$return_html .= "<th style=\"display:none;\">Type</th>";
					$return_html .= "<th>SAP Ref</th>";
					$return_html .= "</tr>";
					$return_html .= "</thead>";
					
					$return_html .= "<tbody>";
				}
				
				$ven_list = '';
				
				foreach ($ven as $row_ven)
				{
					$ven_list.= "<option value=\"".$row_ven->no."\">".$row_ven->name."</option>";
				}
				
				$curr_list = '';
				
				foreach ($curr as $row_cur)
				{
					$curr_list .= "<option value=\"".$row_cur->curcode."\">".$row_cur->curcode." - ".$row_cur->description."</option>";
				}
				
				//foreach ($query_dtl->result() as $row_dtl)
				//{
				
					$return_html .= "<tr>";
					$return_html .= "<td style=\"display:none;\">".$row->dtlno."</td>";
					$return_html .= "<td>".$row->itemcode."</td>";
					$return_html .= "<td>".$row->Item."</td>";
					
					$return_html .= "<td>".$row->venname."</td>";
					
					$return_html .= "<td>".$row->pono."</td>";
					
					//$return_html .= "<td><input style=\"width:100px;\" id=\"id_qty_".$row->no."\" class=\"w3-input\" type=\"number\" onchange=\"validate_po_qty(this, ".$row->BalQty.", 'id_pr_det_".$row->no."')\"></td>";
					
					//$return_html .= "<td><input style=\"width:100px;\" class=\"w3-input\" type=\"number\" onchange=\"cal_Total('id_pr_det_".$row->no."')\"></td>";
					
					/*if($row->purtype === "L"){
						$return_html .= "<td>LKR</td>";
						$return_html .= "<td>1</td>";
					}else{
						$return_html .= "<td>";
						$return_html .= "<select id=\"id_currency_".$row->no."\" class=\"w3-select\" onchange=\"cal_Total('id_pr_det_".$row->no."')\">";
						$return_html .= $curr_list;
						$return_html .= "</select>";
						$return_html .= "</td>";
						
						$return_html .= "<td><input style=\"width:100px;\" class=\"w3-input\" type=\"number\" onchange=\"cal_Total('id_pr_det_".$row->no."')\"></td>";
					}*/
					
					//$return_html .= "<td>0</td>";
					
					//$return_html .= "<td>0</td>";
					
					$return_html .= "<td>".$row->BalQty."</td>";
					$return_html .= "<td>".$row->uom."</td>";
					$return_html .= "<td>".$row->Company."</td>";
					$return_html .= "<td>".$row->Department."</td>";
					$return_html .= "<td>".$row->Machine."</td>";
					//$return_html .= "<td>".$row->purtype."</td>";
					$return_html .= "<td>".$row->saprefno."</td>";
					$return_html .= "</tr>";
					
					//}
					
					$preno = $row->no;
					
					$curindx++;
					
			}
			
			$curindx--;
			
			if ($rcount > 0){
				if($curindx === $rcount){
					$return_html .= "</tbody>";
					$return_html .= "</table>";
					
					$return_html .= "</div>";
					
					/*$return_html .= "<div class=\"w3-row-padding\">";
					$return_html .= "<div class=\"w3-col m12\">";
					$return_html .= "<label>Remarks</label>";
					$return_html .= "<textarea id=\"id_freetxt_".$preno."\" class=\"w3-input\"></textarea>";
					$return_html .= "</div>";
					$return_html .= "</div>";*/
					
					$return_html .= "<div class=\"w3-row-padding\">";
					$return_html .= "<div class=\"w3-col m12\">";
					$return_html .= "<br>";
					$return_html .= "<a href=\"".base_url()."index.php/print/1/".$preno."\" class=\"w3-btn w3-ripple w3-blue\" target=\"_blank\"><i class=\"fa fa-print\" aria-hidden=\"true\"></i></a>";
					$return_html .= "</div>";
					/*$return_html .= "<div class=\"w3-col m4\">";
					$return_html .= "<p></p>";
					$return_html .= "</div>";
					$return_html .= "<div class=\"w3-col m4\">";
					$return_html .= "<br>";
					$return_html .= "<button id=\"idupdaterej_".$preno."\" class=\"w3-btn w3-green w3-right\" class=\"w3-btn\" onclick=\"update_Proceed(".$preno.")\"><i class=\"fa fa-shopping-basket\" aria-hidden=\"true\"></i> Proceed</button>";
					$return_html .= "</div>";*/
					$return_html .= "</div>";
					
					$return_html .= "<br>";
					
					$return_html .= "</div>";
					
				}
			}
			
			return $return_html;
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return "";
		}
	}
	
	public function read_pr_for_proceed_html($prtype) {
		try
		{
			
			$return_html = "No MRP For Proceed..";
			
			$query = $this->read_pr_for_proceed('A', 'A', $prtype);
			
			$rcount = $this->db->affected_rows();					
			
			$preno = 0;
			
			$preno_btn_id = 0;
			
			$curindx = 1;					
			
			foreach ($query->result() as $row)
			{							
				
				$pur_type = "-";
				
				if($row->purtype === "L"){
					$pur_type = 'Local';
				}else{
					$pur_type = 'Import';
				}
				
				if ($return_html === 'No MRP For Proceed..'){
					$return_html = "";
				}
				
				$ven;
				$ven_list = '';
				
				$ven = $this->vendor->read_vendor_active_by_type($row->purtype);
				
				$curr;
				$curr_list = '';
				
				$curr = $this->currency->read_currency();
				
				if ($preno !== $row->no){
					
					if($preno !== 0){
						
						$return_html .= "</tbody>";
						$return_html .= "</table>";
						
						$return_html .= "</div>";
						
						$return_html .= "<div class=\"w3-row-padding\">";
						$return_html .= "<div class=\"w3-col m12\">";
						$return_html .= "<label>Remarks</label>";
						$return_html .= "<textarea id=\"id_freetxt_".$row->no."\" class=\"w3-input\"></textarea>";
						$return_html .= "</div>";
						$return_html .= "</div>";
						
						$return_html .= "<div class=\"w3-row-padding\">";
						$return_html .= "<div class=\"w3-col m4\">";
						$return_html .= "<br>";
						$return_html .= "<a href=\"".base_url()."index.php/print/1/".$row->no."\" class=\"w3-btn w3-ripple w3-blue\" target=\"_blank\"><i class=\"fa fa-print\" aria-hidden=\"true\"></i></a>";
						$return_html .= "</div>";
						$return_html .= "<div class=\"w3-col m4\">";
						$return_html .= "<p></p>";
						$return_html .= "</div>";
						$return_html .= "<div class=\"w3-col m4\">";
						$return_html .= "<br>";
						$return_html .= "<button id=\"idupdaterej_".$preno."\" class=\"w3-btn w3-green w3-right\" class=\"w3-btn\" onclick=\"update_Proceed(".$preno.")\"><i class=\"fa fa-shopping-basket\" aria-hidden=\"true\"></i> Proceed</button>";
						$return_html .= "</div>";
						$return_html .= "</div>";											
						
						$return_html .= "<br>";
						
						$return_html .= "</div>";											
																						
					}
					
					$dropdownhead = $row->prno." | ".$row->date." | ".$row->remarks." | Status : ".$row->docstatus." | Approved  : ".$row->apprejdate. " | Pur Type : ".$pur_type;
					
					$return_html .= "<button onclick=\"dropdown_dtl('".$row->prno."')\" class=\"w3-button w3-block w3-black w3-left-align\">".$dropdownhead."</button>";
					
					$return_html .= "<div id=\"".$row->prno."\" class=\"w3-hide w3-container w3-sand w3-responsive\">";
					
					$return_html .= "<div class=\"w3-responsive\">";
					
					$return_html .= "<table id=\"id_pr_det_".$row->no."\" class=\"w3-table-all\">";
					$return_html .= "<thead>";
					$return_html .= "<tr class=\"w3-cyan\">";
					$return_html .= "<th style=\"display:none;\">Dtl No</th>";
					$return_html .= "<th>Item Code</th>";
					$return_html .= "<th>Item</th>";
					$return_html .= "<th>Supplier</th>";
					$return_html .= "<th>PO No</th>";
					$return_html .= "<th>PO Qty</th>";																
					
					$return_html .= "<th>Unit Cost</th>";
					
					$return_html .= "<th>Currency</th>";
					
					$return_html .= "<th>Curr Rate</th>";
					
					$return_html .= "<th>U Cost LKR</th>";
					
					$return_html .= "<th>Total</th>";
					
					$return_html .= "<th>Bal Qty</th>";
					$return_html .= "<th>Uom</th>";
					$return_html .= "<th>Company</th>";
					$return_html .= "<th>Department</th>";
					$return_html .= "<th>Machine</th>";
					$return_html .= "<th>Type</th>";
					$return_html .= "<th>SAP Ref</th>";
					$return_html .= "</tr>";
					$return_html .= "</thead>";

					$return_html .= "<tbody>";
				}
				
				$ven_list = '';
				
				foreach ($ven as $row_ven)
				{
					$ven_list.= "<option value=\"".$row_ven->no."\">".$row_ven->name."</option>";
				}
				
				$curr_list = '';
				
				foreach ($curr as $row_cur)
				{
					$curr_list .= "<option value=\"".$row_cur->curcode."\">".$row_cur->curcode." - ".$row_cur->description."</option>";
				}
				
			    //foreach ($query_dtl->result() as $row_dtl)
			    //{	
                    
                        $return_html .= "<tr>";
                        $return_html .= "<td style=\"display:none;\">".$row->dtlno."</td>";
                        $return_html .= "<td>".$row->itemcode."</td>";
                        $return_html .= "<td>".$row->Item."</td>";

                            $return_html .= "<td>";
                            $return_html .= "<select id=\"id_supplier_".$row->no."\" class=\"w3-select\">";
                            $return_html .= $ven_list;
                            $return_html .= "</select>";
                            $return_html .= "</td>";

                            $return_html .= "<td><input style=\"width:100px;\" id=\"id_pono_".$row->no."\" class=\"w3-input\" type=\"text\"></td>";
                            $return_html .= "<td><input style=\"width:100px;\" id=\"id_qty_".$row->no."\" class=\"w3-input\" type=\"number\" onchange=\"validate_po_qty(this, ".$row->BalQty.", 'id_pr_det_".$row->no."')\"></td>";
                            
                            $return_html .= "<td><input style=\"width:100px;\" class=\"w3-input\" type=\"number\" onchange=\"cal_Total('id_pr_det_".$row->no."')\"></td>";
                            
                            if($row->purtype === "L"){
                            	$return_html .= "<td>LKR</td>";
                            	$return_html .= "<td>1</td>";
                            }else{
                            	$return_html .= "<td>";
                            	$return_html .= "<select id=\"id_currency_".$row->no."\" class=\"w3-select\" onchange=\"cal_Total('id_pr_det_".$row->no."')\">";
                            	$return_html .= $curr_list;
                            	$return_html .= "</select>";
                            	$return_html .= "</td>";
                            	
                            	$return_html .= "<td><input style=\"width:100px;\" class=\"w3-input\" type=\"number\" onchange=\"cal_Total('id_pr_det_".$row->no."')\"></td>";
                            }                            

                            $return_html .= "<td>0</td>";
                            
                            $return_html .= "<td>0</td>";
                            
                            $return_html .= "<td>".$row->BalQty."</td>";
                            $return_html .= "<td>".$row->uom."</td>";
                            $return_html .= "<td>".$row->Company."</td>";
                            $return_html .= "<td>".$row->Department."</td>";
                            $return_html .= "<td>".$row->Machine."</td>";
                            $return_html .= "<td>".$row->purtype."</td>";
                            $return_html .= "<td>".$row->saprefno."</td>";
                        $return_html .= "</tr>";
                    
                //}                			   
				
				$preno = $row->no;
				
				$curindx++;
				
			}
			
			$curindx--;
			
			if ($rcount > 0){
				if($curindx === $rcount){
					$return_html .= "</tbody>";
					$return_html .= "</table>";
					
					$return_html .= "</div>";
					
					$return_html .= "<div class=\"w3-row-padding\">";
					$return_html .= "<div class=\"w3-col m12\">";
					$return_html .= "<label>Remarks</label>";
					$return_html .= "<textarea id=\"id_freetxt_".$preno."\" class=\"w3-input\"></textarea>";
					$return_html .= "</div>";
					$return_html .= "</div>";
					
					$return_html .= "<div class=\"w3-row-padding\">";
					$return_html .= "<div class=\"w3-col m4\">";
					$return_html .= "<br>";
					$return_html .= "<a href=\"".base_url()."index.php/print/1/".$preno."\" class=\"w3-btn w3-ripple w3-blue\" target=\"_blank\"><i class=\"fa fa-print\" aria-hidden=\"true\"></i></a>";
					$return_html .= "</div>";
					$return_html .= "<div class=\"w3-col m4\">";
					$return_html .= "<p></p>";
					$return_html .= "</div>";
					$return_html .= "<div class=\"w3-col m4\">";
					$return_html .= "<br>";
					$return_html .= "<button id=\"idupdaterej_".$preno."\" class=\"w3-btn w3-green w3-right\" class=\"w3-btn\" onclick=\"update_Proceed(".$preno.")\"><i class=\"fa fa-shopping-basket\" aria-hidden=\"true\"></i> Proceed</button>";
					$return_html .= "</div>";
					$return_html .= "</div>";
					
					$return_html .= "<br>";
					
					$return_html .= "</div>";
								
				}		
			}
			
			return $return_html;
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return "";
		}
	}	
	
	//Update GRN Qty
	public function update_grn_qty($prno, $itemid, $recqty, $dtlno, $ucost)
	{
		//update pr_dtl set receivedqty = receivedqty + 1 where no = 1 and itemno = 5 and 1 <= (qty - receivedqty)
		//try
		//{
			
			if(empty($ucost)){
				$ucost = 0;
			}
			
			$totcost = $ucost * $recqty;
			
            $whereqty = $recqty . " <= (receivedqty - grnqty)";
            $grnqty = 'grnqty + ' . $recqty;
            $this->db->set('grnqty', $grnqty, false);
		    $this->db->where('no', $prno);
		    $this->db->where('itemno', $itemid);
		    $this->db->where($whereqty);
		    $this->db->update('pr_dtl');
            
            if ($this->db->affected_rows() == 0){
            	throw new Exception('Error with PR Balance PO Qty! PR No : '.$prno." Item No : ".$itemid);
            }
            
            $whereqty = $recqty . " <= (qty - grnqty)";
            $grnqty = 'grnqty + ' . $recqty;
            $this->db->set('grnqty', $grnqty, false);
            $this->db->set('unitcost', $ucost);
            $this->db->set('totalcost', $totcost);
            $this->db->where('dtlno', $dtlno);
            $this->db->where($whereqty);
            $this->db->update('pr_dtl_proceed');
            
            if ($this->db->affected_rows() == 0){
            	throw new Exception('Error with PR Proceed Balance PO Qty!');
            }
		//}
		//catch(Exception $e)
		//{
			//log_message('error', $e->getMessage());
			//return 0;
		//}
	}
	
	public function read_pr_hdr($prno) {
		//try
		//{					
			$this->db->select('*');
			$this->db->from('pr_hdr');
			$this->db->where('no', $prno);
			$this->db->limit(1);
			$query = $this->db->get();
			
			return $query->row();
		/*}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}*/
	}
	
	public function read_pr_list_report($DateData, $ComPara, $DeptPara, $ItemPara, $StatusPara, $MachinePara) {
		try
		{
			
			/*select h.prno, i.itemcode, i.description Item, d.qty, d.uom, h.date, h.reqdate,
			h.purtype, h.docstatus, h.apprejby, h.apprejdate, h.acceptrej, h.acceptrejby, 
			h.acceptrejdate, c.description company, de.description dept, m.machineno
			from pr_dtl d 
			inner join pr_hdr h on d.no = h.no
			inner join item i on d.itemno = i.no
			inner join company c on d.companyno = c.no
			inner join department de on d.departmentno = de.no
			inner join machine m on d.machineno = m.no*/
			
			$this->db->select('h.prno, i.itemcode, i.description Item, d.qty, d.uom, h.date, h.reqdate,
								h.purtype, h.docstatus, h.apprejby, h.apprejdate, h.acceptrej, h.acceptrejby, 
								h.acceptrejdate, c.description company, de.description dept, m.machineno');
			$this->db->from('pr_dtl d');
			
			$this->db->join('pr_hdr h', 'd.no = h.no');
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
			
			if(isset($accept) && !empty($accept)){
				$this->db->where('h.acceptrej', $accept);
			}			
			
			$this->db->order_by('h.prno', 'ASC');
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