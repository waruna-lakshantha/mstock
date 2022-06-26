<?php 

Class Job_requisition extends CI_Model {
	
	public $LocalTime = "addtime(now(),'09:30:00')";
	
	public $template = array(
			'table_open'            => '<table id="id_job_det" class="w3-table-all">',
			
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
	}
	
	public function job_insert($hdr) {
		
		$return_Status = array();
		
		try
		{	

			$this->db->trans_start();
			
			$hdr_ass = array();
			
			/*$d = explode("_", $hdr[0]['datetobecompleted']);
			$datecompplete = $d[0]."-".$d[1]."-".$d[2];*/
			
			$hdr_ass['jobtypeno'] = $hdr[0]['jobtypeno'];
			$hdr_ass['companyno'] = $hdr[0]['companyno'];
			$hdr_ass['departmentno'] = $hdr[0]['departmentno'];
			$hdr_ass['datetobecompleted'] = mdate($hdr[0]['datetobecompleted']);
			$hdr_ass['jobdescription'] = $hdr[0]['jobdescription'];
            $hdr_ass['machineno'] = $hdr[0]['machineno'];
			$hdr_ass['isactive'] = 1;
			$hdr_ass['insertuser'] = $hdr[0]['insertuser'];
			$hdr_ass['docstatus'] = 'P';
			$hdr_ass['acceptrej'] = 'P';
			$hdr_ass['acknowledgement'] = 'P';
			$hdr_ass['complete'] = 'N';
			
			$this->db->insert('job_requisition',$hdr_ass);
			
			$id = $this->db->insert_id();	
			
			$id_reff = "J".str_pad($id, 9, '0', STR_PAD_LEFT);		
			
			$this->db->set('jobno', $id_reff);
			$this->db->set('insertdatetime', $this->LocalTime, false);
			$this->db->set('date', $this->LocalTime, false);
			$this->db->where('no', $id);
			$this->db->update('job_requisition');					
			
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
				$return_Status[1] = $id_reff;
				$return_Status[2] = "";
				$return_Status[3] = $id;
				
				$username = $this->session->userdata['logged_in']['username'];
				$useremail = $this->session->userdata['logged_in']['email'];
				
				$email_list = $this->user->read_mail_list("MS", 15);
				
				$print_url = "<a href=\"".base_url()."index.php/print/11/".$id."\"/>".$id_reff."</a>";
				
				$message = "";
				
				$message .= "<h4>New Job</h4>";
				$message .= "<p>Please click on the follwing link to view document</p>";
				$message .= $print_url;
				
				$this->generate_mail->mailfrom = $useremail;
				$this->generate_mail->namefrom = $username;
				
				$this->generate_mail->mailsubject = "New Job - ".$id_reff;
				
				$this->generate_mail->mailto_list= $email_list;
				
				$this->generate_mail->mailmessage = $message;
				
				$this->generate_mail->mail_send();
				
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
	
	public function read_job_hdr_by_jobno($no) {
		try
		{
			$this->db->select('*');
			
			$this->db->from('job_requisition');
			$this->db->where('no', $no);
			
			return $this->db->query($this->db->get_compiled_select());
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	public function read_job_engineer($jobno) {
		$this->db->select('e.engineer');
		$this->db->from('job_engineer je');
		$this->db->join('engineer e', 'e.no = je.engno');
		$this->db->where('je.no', $jobno);
		$query = $this->db->get();
		
		$row = $query->row();
		
		return $row;
	}
	
	public function read_job_status_log($jobno) {
		$this->db->select('*');
		$this->db->from('job_requisition_status_log');
		$this->db->where('no', $jobno);
		$this->db->order_by('dtlno', 'ASC');
		$query = $this->db->get();
		
		return $query->result();		
	}
	
	public function read_job_status_log_html($jobno) {
		$job_status_log_html = "<br><div class=\"w3-row-padding\">";
		
		$job_st_log = $this->read_job_status_log($jobno);
		
		$job_status_log_html .= "<table class=\"w3-table-all\">";
		
		$job_status_log_html .= "<tr>";
			$job_status_log_html .= "<th>Date</th>";
			$job_status_log_html .= "<th>Comment</th>";
			$job_status_log_html .= "<th>User</th>";
			$job_status_log_html .= "<th>Approve</th>";
			$job_status_log_html .= "<th>Accept</th>";
			$job_status_log_html .= "<th>Complete</th>";
			$job_status_log_html .= "<th>Feedback</th>";
		$job_status_log_html .= "</tr>";
		
		foreach ($job_st_log as $row)
		{
			$job_status_log_html .= "<tr>";
			
				$job_status_log_html .= "<td>".$row->datetime."</td>";
				$job_status_log_html .= "<td>".$row->remark."</td>";
				$job_status_log_html .= "<td>".$row->user."</td>";
				
				if($row->docstatus === 'A'){
					$job_status_log_html .= "<td>Approve</td>";
				}else{
					$job_status_log_html .= "<td><p></p></td>";
				}
				
				if($row->acceptrej === 'A'){
					$job_status_log_html .= "<td>Accept</td>";
				}else{
					$job_status_log_html .= "<td><p></p></td>";
				}
				
				if($row->complete === 'Y'){
					$job_status_log_html .= "<td>Complete</td>";
				}else{
					$job_status_log_html .= "<td><p></p></td>";
				}
				
				if($row->acknowledgement === 'A'){
					$job_status_log_html .= "<td>Accept</td>";
				}else{
					$job_status_log_html .= "<td>Reopen</td>";
				}
				
			$job_status_log_html .= "</tr>";
		}
		
		$job_status_log_html .= "</table>";
		
		$job_status_log_html .= "</div>";
		
		return $job_status_log_html;
	}
	
	public function read_job_dtl_list($status) {
		try
		{
			
			$dept;
			
			if (isset($this->session->userdata['logged_in'])) {
				$dept = ($this->session->userdata['logged_in']['dept']);
			} else {
				header("location: ".base_url()."index.php/User_Authentication/user_login_process");
			}
			
			$this->db->select('j.no, j.jobno, j.jobtypeno, job_type.type as jobtype, j.date, j.companyno, 
                                company.description as company, j.departmentno, department.description as department,
								j.machineno, machine.machineno, j.datetobecompleted, j.jobdescription, 
								j.insertuser, j.docstatus, j.apprejby, j.apprejremarks, j.appdate, j.acceptrej, j.accrejby, 
                                j.accrejremarks, j.accdate, j.estimatedatecomplete, j.acknowledgement, j.acknby, j.ackndate,
								j.complete, j.completeby, j.completedate');
			$this->db->from('job_requisition j');
			$this->db->join('company', 'company.no = j.companyno');
			$this->db->join('department', 'department.no = j.departmentno');
			$this->db->join('machine', 'machine.no = j.machineno');
			$this->db->join('job_type', 'job_type.no = j.jobtypeno');
			
			if($status === 'app'){
				$this->db->where('j.docstatus', 'P');
				$this->db->or_where('j.docstatus', 'O');
				$this->db->where('j.departmentno', $dept);
			}elseif($status === 'acc'){
				$this->db->where('j.docstatus', 'A');
				$this->db->where('j.acceptrej', 'P');
			}elseif($status === 'com'){
				$this->db->where('j.acceptrej', 'A');
				$this->db->where('j.complete', 'N');
			}elseif($status === 'fbk'){
				$this->db->where('j.complete', 'Y');
				$this->db->where('j.acknowledgement', 'P');
				$this->db->where('j.departmentno', $dept);
			}
			
			$this->db->order_by('j.no', 'DESC');	
			
			return $this->db->query($this->db->get_compiled_select());
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	public function read_job_dtl_by_no($jobno) {
		try
		{
			$this->db->select('j.no, j.jobno, j.jobtypeno, job_type.type as jobtype, j.date, j.companyno,
                                company.description as company, j.departmentno, department.description as department,
								j.machineno, machine.machineno, j.datetobecompleted, j.jobdescription,
								j.insertuser, j.docstatus, j.apprejby, j.apprejremarks, j.appdate, j.acceptrej, j.accrejby,
                                j.accrejremarks, j.accdate, j.estimatedatecomplete, j.acknowledgement, j.acknby, j.ackndate,
								j.complete, j.completeby, j.completedate, j.estimatedcost, j.actualcost');
			$this->db->from('job_requisition j');
			$this->db->join('company', 'company.no = j.companyno');
			$this->db->join('department', 'department.no = j.departmentno');
			$this->db->join('machine', 'machine.no = j.machineno');
			$this->db->join('job_type', 'job_type.no = j.jobtypeno');
			$this->db->where('j.no', $jobno);		
			
			$result =  $this->db->query($this->db->get_compiled_select());
			
			return $result->row();
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	public function read_job_dtl_list_html() {
		try
		{						
				
			$return_html = "No Jobs For Approvals..";
			
			$query = $this->read_job_dtl_list('app');
			
			foreach ($query->result() as $row)
			{
				
                if ($row->docstatus == 'O'){
                    $dropdownhead = $row->jobno." | ".$row->jobtype." | Job Date : ".$row->date." | Company : ".$row->company." | Dept : ".$row->department. " | User : ".$row->insertuser." <span class=\"w3-tag w3-blue\">Reopen</span>";
                }else{
				    $dropdownhead = $row->jobno." | ".$row->jobtype." | Job Date : ".$row->date." | Company : ".$row->company." | Dept : ".$row->department. " | User : ".$row->insertuser;
				}

				if ($return_html === 'No Jobs For Approvals..'){
					$return_html = "";
				}
				
				$return_html .= "<button onclick=\"dropdown_dtl('".$row->jobno."')\" class=\"w3-button w3-block w3-black w3-left-align\">".$dropdownhead."</button>";
				
				$return_html .= "<div id=\"".$row->jobno."\" class=\"w3-hide w3-container w3-sand\">";				
				
				$return_html .= "<br>";

				$return_html .= "<div class=\"w3-row-padding\">";
				$return_html .= "<div class=\"w3-col m12\">";
				$return_html .= "<label>Machine</label>";
                $return_html .= "<input id=\"idgrn_date\" class=\"w3-input\" type=\"text\" value=\"".$row->machineno."\" disabled>";
				$return_html .= "</div>";
				$return_html .= "<div class=\"w3-col m12\">";
				$return_html .= "<label>Job Description</label>";
				$return_html .= "<textarea id=\"id_job_req_".$row->no."\" class=\"w3-input\" disabled>".$row->jobdescription."</textarea>";
				$return_html .= "</div>";
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
				$return_html .= "<a href=\"".base_url()."index.php/print/11/".$row->no."\" class=\"w3-btn w3-ripple w3-blue\" target=\"_blank\"><i class=\"fa fa-print\" aria-hidden=\"true\"></i></a>";
				$return_html .= "</div>";
				$return_html .= "<div class=\"w3-col m4\">";
				$return_html .= "<br>";
				$return_html .= "<button id=\"idupdateapp\" class=\"w3-btn w3-green w3-right\" class=\"w3-btn\" onclick=\"update_App_Rej(".$row->no.", 'A')\"><i class=\"fa fa-thumbs-o-up\" aria-hidden=\"true\"></i> Approve</button>";
				$return_html .= "</div>";
				$return_html .= "<div class=\"w3-col m4\">";
				$return_html .= "<br>";
				$return_html .= "<button id=\"idupdaterej\" class=\"w3-btn w3-orange w3-right\" class=\"w3-btn\" onclick=\"update_App_Rej(".$row->no.", 'R')\"><i class=\"fa fa-thumbs-o-down\" aria-hidden=\"true\"></i> Reject</button>";
				$return_html .= "</div>";
				$return_html .= "</div>";
				
				$return_html .= $this->read_job_status_log_html($row->no);
				
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

	public function approve_rej_job($data) {
        $return_Status = array();
		try
		{
			
			/*$return_Status[0] = "0";
			$return_Status[1] = "Something Went Wrong";
			$return_Status[2] = "";
			
			return $return_Status;*/           
            			
			/*jobno
            docstatus
            apprejremarks
            insertuser*/
			
            $jobno = $data[0]['jobno'];
            $docstatus = $data[0]['docstatus'];
            $apprejremarks = $data[0]['apprejremarks'];
            $apprejby = $data[0]['insertuser'];

            $this->db->trans_start();
            
            $this->db->set('docstatus', $docstatus);
            $this->db->set('apprejby', $apprejby);
            $this->db->set('appdate', $this->LocalTime, false);
            $this->db->set('apprejremarks', $apprejremarks);
            $this->db->where('no', $jobno);
		    $this->db->update('job_requisition');
		    
		    $log = array();		    
		    
		    $this->db->set('no', $jobno);
		    $this->db->set('user', $apprejby);
		    $this->db->set('remark', $apprejremarks);
		    $this->db->set('datetime', $this->LocalTime, false);
		    $this->db->set('docstatus', $docstatus);
		    $this->db->insert('job_requisition_status_log');
		    
		    $this->db->trans_complete();
		    
		    if($this->db->trans_status() === TRUE){
		    	$return_Status[0] = "1";
		    	if ($docstatus == 'A'){
		    		$return_Status[1] = "Approved";
		    	}else{
		    		$return_Status[1] = "Rejected";
		    	}
		    	$return_Status[2] = $this->read_job_dtl_list_html();
		    	
		    	$username = $this->session->userdata['logged_in']['username'];
		    	$useremail = $this->session->userdata['logged_in']['email'];
		    	
		    	//Job request user
		    	$email_list = $this->user->read_mail_list_by_doc(11, $jobno);
		    	
		    	$print_url = "<a href=\"".base_url()."index.php/print/11/".$jobno."\"/>".$jobno."</a>";
		    	
		    	$message = "";
		    	
		    	$message .= "<h4>".$return_Status[1]." Job</h4>";
		    	$message .= "<p>Please click on the follwing link to view document</p>";
		    	$message .= $print_url;
		    	
		    	$this->generate_mail->mailfrom = $useremail;
		    	$this->generate_mail->namefrom = $username;
		    	
		    	$this->generate_mail->mailsubject = $return_Status[1]." Job - ".$jobno;
		    	
		    	$this->generate_mail->mailto_list= $email_list;
		    	
		    	$this->generate_mail->mailmessage = $message;
		    	
		    	$this->generate_mail->mail_send();
		    			    	
		    	//Job accept user
		    	if ($docstatus === 'A'){
			    	$email_list = $this->user->read_mail_list_all("MS", 16);
			    	$this->generate_mail->mailto_list = $email_list;
			    	$this->generate_mail->mail_send();
		    	}
		    	
		    }else{
		    	$return_Status[0] = "0";
		    	$return_Status[1] = "Already Updated or Something Went Wrong";
		    	$return_Status[2] = $this->read_job_dtl_list_html();
		    }
		    
		    return $return_Status;
		    
		}
		catch(Exception $e)
		{
			$this->db->trans_rollback();
			
			log_message('error', $e->getMessage());
			$return_Status[0] = "0";
			$return_Status[1] = "Something Went Wrong";
			$return_Status[2] = $this->read_job_dtl_list_html();
			
			return $return_Status;
		}					
	}

	public function read_job_dtl_list_not_accept_html() {
		try
		{						
				
			$return_html = "No Jobs For Accept..";
			
			$query = $this->read_job_dtl_list('acc');
			
			foreach ($query->result() as $row)
			{
				
                if ($row->docstatus == 'O'){
                    $dropdownhead = $row->jobno." | ".$row->jobtype." | Job Date : ".$row->date." | Company : ".$row->company." | Dept : ".$row->department. " | User : ".$row->insertuser." <span class=\"w3-tag w3-blue\">Reopen</span>";
                }else{
				    $dropdownhead = $row->jobno." | ".$row->jobtype." | Job Date : ".$row->date." | Company : ".$row->company." | Dept : ".$row->department. " | User : ".$row->insertuser;
				}

				if ($return_html === 'No Jobs For Accept..'){
					$return_html = "";
				}
				
				$return_html .= "<button onclick=\"dropdown_dtl('".$row->jobno."')\" class=\"w3-button w3-block w3-black w3-left-align\">".$dropdownhead."</button>";
				
				$return_html .= "<div id=\"".$row->jobno."\" class=\"w3-hide w3-container w3-sand\">";				
				
				$return_html .= "<br>";

				$return_html .= "<div class=\"w3-row-padding\">";
				$return_html .= "<div class=\"w3-col m4\">";
				$return_html .= "<label>Machine</label>";
                $return_html .= "<input id=\"idgrn_date\" class=\"w3-input\" type=\"text\" value=\"".$row->machineno."\" disabled>";
				$return_html .= "</div>";
				$return_html .= "<div class=\"w3-col m4\">";
				$return_html .= "<label>Approved By</label>";
                $return_html .= "<input id=\"idgrn_date\" class=\"w3-input\" type=\"text\" value=\"".$row->apprejby."\" disabled>";
				$return_html .= "</div>";
				$return_html .= "<div class=\"w3-col m4\">";
				$return_html .= "<label>Approved Date</label>";
                $return_html .= "<input id=\"idgrn_date\" class=\"w3-input\" type=\"text\" value=\"".$row->appdate."\" disabled>";
				$return_html .= "</div>";
                $return_html .= "</div>";

                $return_html .= "<div class=\"w3-row-padding\">";
				$return_html .= "<div class=\"w3-col m12\">";
				$return_html .= "<label>Job Description</label>";
				$return_html .= "<textarea id=\"id_job_req_".$row->no."\" class=\"w3-input\" disabled>".$row->jobdescription."</textarea>";
				$return_html .= "</div>";
				$return_html .= "</div>";			

				$return_html .= "<div class=\"w3-row-padding\">";
				$return_html .= "<div class=\"w3-col m6\">";
				$return_html .= "<label>Engineer</label>";
			    $return_html .= "<select id=\"id_engineer_".$row->no."\" class=\"w3-select\">";
	            $result = $this->engineer->read_engineer();	
	            foreach ($result as $row_eng)
	            {
	            	$return_html .= "<option value=\"".$row_eng->no."\">".$row_eng->engineer."</option>";
	            }
			    $return_html .= "</select>";
				$return_html .= "</div>";				
				$return_html .= "<div class=\"w3-col m6\">";
				$return_html .= "<label>Estimate Date to be Complete</label>";
				$return_html .= "<input id=\"idestimate_date_".$row->no."\" class=\"w3-input\" type=\"date\">";
				$return_html .= "</div>";
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
				$return_html .= "<a href=\"".base_url()."index.php/print/11/".$row->no."\" class=\"w3-btn w3-ripple w3-blue\" target=\"_blank\"><i class=\"fa fa-print\" aria-hidden=\"true\"></i></a>";
				$return_html .= "</div>";
				$return_html .= "<div class=\"w3-col m4\">";
				$return_html .= "<br>";
				$return_html .= "<button id=\"idupdateapp\" class=\"w3-btn w3-green w3-right\" class=\"w3-btn\" onclick=\"update_Acc_Rej(".$row->no.", 'A')\"><i class=\"fa fa-thumbs-o-up\" aria-hidden=\"true\"></i> Accept</button>";
				$return_html .= "</div>";
				$return_html .= "<div class=\"w3-col m4\">";
				$return_html .= "<br>";
				$return_html .= "<button id=\"idupdaterej\" class=\"w3-btn w3-orange w3-right\" class=\"w3-btn\" onclick=\"update_Acc_Rej(".$row->no.", 'R')\"><i class=\"fa fa-thumbs-o-down\" aria-hidden=\"true\"></i> Reject</button>";
				$return_html .= "</div>";
				$return_html .= "</div>";
				
				$return_html .= $this->read_job_status_log_html($row->no);
				
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
	
	public function accept_rej_job($data) {
		$return_Status = array();
		try
		{			
			
			/*jobno
			 docstatus
			 apprejremarks
			 insertuser*/
			
			/*$return_Status[0] = "0";
			$return_Status[1] = $data[0]['jobno'];
			$return_Status[2] = "";
			 
			return $return_Status;*/
			
			$jobno = $data[0]['jobno'];
			$docstatus = $data[0]['docstatus'];
			$apprejremarks = $data[0]['apprejremarks'];
			$apprejby = $data[0]['insertuser'];
			
			$engineer = $data[0]['engineer'];
			
			//$d = explode("_", $data[0]['estimatedatecomplete']);
			//$estimatedatecomplete = $d[0]."-".$d[1]."-".$d[2];			
			
			$this->db->trans_start();
			
			$job_file = $this->read_job_hdr_by_jobno($jobno);
			
			$this->db->set('acceptrej', $docstatus);
			$this->db->set('accrejby', $apprejby);
			$this->db->set('accdate', $this->LocalTime, false);
			$this->db->set('accrejremarks', $apprejremarks);
			$this->db->set('estimatedatecomplete', mdate($data[0]['estimatedatecomplete']));
			$this->db->where('no', $jobno);
			$this->db->update('job_requisition');			
			
			if($docstatus === 'A'){
				$eng = array();
				
				$eng['no'] = $jobno;
				$eng['engno'] = $engineer;
				
				$this->db->insert('job_engineer',$eng);
			}
			
			$log = array();			
			
			$this->db->set('no', $jobno);
			$this->db->set('user', $apprejby);			
			$this->db->set('remark', $apprejremarks);
			$this->db->set('datetime', $this->LocalTime, false);
			$this->db->set('acceptrej', $docstatus);
			$this->db->insert('job_requisition_status_log');				
			
			$this->db->trans_complete();
			
			/*$return_Status[0] = "0";
			$return_Status[1] = $this->db->trans_status();
			$return_Status[2] = "";
			
			return $return_Status;*/	
			
			if($this->db->trans_status() === TRUE){
				$return_Status[0] = "1";
				if ($docstatus == 'A'){
					$return_Status[1] = "Accepted";
				}else{
					$return_Status[1] = "Rejected";
				}
				$return_Status[2] = $this->read_job_dtl_list_not_accept_html();
				
				$username = $this->session->userdata['logged_in']['username'];
				$useremail = $this->session->userdata['logged_in']['email'];
				
				//Job request user
				$email_list = $this->user->read_mail_list_by_doc(11, $jobno);
				
				$print_url = "<a href=\"".base_url()."index.php/print/11/".$jobno."\"/>".$jobno."</a>";
				
				$message = "";
				
				$message .= "<h4>".$return_Status[1]." Job</h4>";
				$message .= "<p>Please click on the follwing link to view document</p>";
				$message .= $print_url;
				
				$this->generate_mail->mailfrom = $useremail;
				$this->generate_mail->namefrom = $username;
				
				$this->generate_mail->mailsubject = $return_Status[1]." Job - ".$jobno;
				
				$this->generate_mail->mailto_list = $email_list;
				
				$this->generate_mail->mailmessage = $message;
				
				$this->generate_mail->mail_send();
				
				//Job approve user
				$email_list = $this->user->read_mail_list_user($job_file->apprejby);
				$this->generate_mail->mailto_list = $email_list;
				$this->generate_mail->mail_send();
				
			}else{
				$return_Status[0] = "0";
				$return_Status[1] = "Already Updated or Something Went Wrong";
				$return_Status[2] = $this->read_job_dtl_list_not_accept_html();
			}								
			
			return $return_Status;
		}
		catch(Exception $e)
		{
			$this->db->trans_rollback();
			
			log_message('error', $e->getMessage());
			$return_Status[0] = "0";
			$return_Status[1] = "Something Went Wrong";
			$return_Status[2] = $this->read_job_dtl_list_not_accept_html();
			
			return $return_Status;
		}
	}
	
	public function read_job_dtl_list_not_complete_html() {
		try
		{
			
			$return_html = "No Jobs For Complete..";
			
			$query = $this->read_job_dtl_list('com');
			
			foreach ($query->result() as $row)
			{
				
				if ($row->docstatus == 'O'){
					$dropdownhead = $row->jobno." | ".$row->jobtype." | Job Date : ".$row->date." | Company : ".$row->company." | Dept : ".$row->department. " | User : ".$row->insertuser." <span class=\"w3-tag w3-blue\">Reopen</span>";
				}else{
					$dropdownhead = $row->jobno." | ".$row->jobtype." | Job Date : ".$row->date." | Company : ".$row->company." | Dept : ".$row->department. " | User : ".$row->insertuser;
				}
				
				if ($return_html === 'No Jobs For Complete..'){
					$return_html = "";
				}
				
				$return_html .= "<button onclick=\"dropdown_dtl('".$row->jobno."')\" class=\"w3-button w3-block w3-black w3-left-align\">".$dropdownhead."</button>";
				
				$return_html .= "<div id=\"".$row->jobno."\" class=\"w3-hide w3-container w3-sand\">";
				
				$return_html .= "<br>";
				
				$return_html .= "<div class=\"w3-row-padding\">";
				$return_html .= "<div class=\"w3-col m4\">";
				$return_html .= "<label>Machine</label>";
				$return_html .= "<input id=\"idgrn_date\" class=\"w3-input\" type=\"text\" value=\"".$row->machineno."\" disabled>";
				$return_html .= "</div>";
				$return_html .= "<div class=\"w3-col m4\">";
				$return_html .= "<label>Accepted By</label>";
				$return_html .= "<input id=\"idgrn_date\" class=\"w3-input\" type=\"text\" value=\"".$row->accrejby."\" disabled>";
				$return_html .= "</div>";
				$return_html .= "<div class=\"w3-col m4\">";
				$return_html .= "<label>Accepted Date</label>";
				$return_html .= "<input id=\"idgrn_date\" class=\"w3-input\" type=\"text\" value=\"".$row->accdate."\" disabled>";
				$return_html .= "</div>";
				$return_html .= "</div>";
				
				$return_html .= "<div class=\"w3-row-padding\">";
				$return_html .= "<div class=\"w3-col m12\">";
				$return_html .= "<label>Job Description</label>";
				$return_html .= "<textarea id=\"id_job_req_".$row->no."\" class=\"w3-input\" disabled>".$row->jobdescription."</textarea>";
				$return_html .= "</div>";
				$return_html .= "</div>";
				
				$return_html .= "<div class=\"w3-row-padding\">";
				$return_html .= "<div class=\"w3-col m6\">";
				$return_html .= "<label>Engineer</label>";
				
				$result_eng = $this->read_job_engineer($row->no);
				
				$return_html .= "<input class=\"w3-input\" type=\"text\" value=\"".$result_eng->engineer."\" disabled>";
				$return_html .= "</div>";
				$return_html .= "<div class=\"w3-col m6\">";
				$return_html .= "<label>Estimate Date to be Complete</label>";
				$return_html .= "<input class=\"w3-input\" type=\"text\" value=\"".$row->estimatedatecomplete."\" disabled>";
				$return_html .= "</div>";
				$return_html .= "</div>";			
				
				$return_html .= "<div class=\"w3-row-padding\">";
				$return_html .= "<div class=\"w3-col m6\">";
				$return_html .= "<label>Estimated Cost</label>";				
				$return_html .= "<input id=\"id_estimate_cost_".$row->no."\" type=\"number\" class=\"w3-input\">";
				$return_html .= "</div>";
				$return_html .= "<div class=\"w3-col m6\">";
				$return_html .= "<label>Actual Cost</label>";
				$return_html .= "<input id=\"id_actual_cost_".$row->no."\" type=\"number\" class=\"w3-input\">";
				$return_html .= "</div>";
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
				$return_html .= "<a href=\"".base_url()."index.php/print/11/".$row->no."\" class=\"w3-btn w3-ripple w3-blue\" target=\"_blank\"><i class=\"fa fa-print\" aria-hidden=\"true\"></i></a>";
				$return_html .= "</div>";
				$return_html .= "<div class=\"w3-col m4\">";
				$return_html .= "</div>";
				$return_html .= "<div class=\"w3-col m4\">";
				$return_html .= "<br>";
				$return_html .= "<button id=\"idupdaterej\" class=\"w3-btn w3-orange w3-right\" class=\"w3-btn\" onclick=\"update_Complete(".$row->no.")\"><i class=\"fa fa-thumbs-o-up\" aria-hidden=\"true\"></i> Complete</button>";
				$return_html .= "</div>";
				$return_html .= "</div>";
				
				$return_html .= $this->read_job_status_log_html($row->no);
				
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
	
	public function complete_job($data) {
		$return_Status = array();
		try
		{
			
			/*jobno
			 docstatus
			 apprejremarks
			 insertuser*/
			
			/*$return_Status[0] = "0";
			 $return_Status[1] = $data[0]['jobno'];
			 $return_Status[2] = "";
			 
			 return $return_Status;*/
			
			$jobno = $data[0]['jobno'];
			$apprejremarks = $data[0]['apprejremarks'];
			$apprejby = $data[0]['insertuser'];
			
			$estimatedcost = $data[0]['estcost'];
			$actualcost = $data[0]['actcost'];
			
			$this->db->trans_start();
			
			$job_file = $this->read_job_hdr_by_jobno($jobno);
			
			$this->db->set('complete', 'Y');			
			$this->db->set('estimatedcost', $estimatedcost);
			$this->db->set('actualcost', $actualcost);			
			$this->db->set('completeby', $apprejby);
			$this->db->set('completedate', $this->LocalTime, false);
			$this->db->where('no', $jobno);
			$this->db->update('job_requisition');			
			
			$log = array();
			
			$this->db->set('no', $jobno);
			$this->db->set('user', $apprejby);
			$this->db->set('remark', $apprejremarks);
			$this->db->set('datetime', $this->LocalTime, false);
			$this->db->set('complete', 'Y');
			$this->db->insert('job_requisition_status_log');
			
			$this->db->trans_complete();
			
			/*$return_Status[0] = "0";
			 $return_Status[1] = $this->db->trans_status();
			 $return_Status[2] = "";
			 
			 return $return_Status;*/
			
			if($this->db->trans_status() === TRUE){
				$return_Status[0] = "1";
				$return_Status[1] = "Completed";
				$return_Status[2] = $this->read_job_dtl_list_not_complete_html();
				
				$username = $this->session->userdata['logged_in']['username'];
				$useremail = $this->session->userdata['logged_in']['email'];
				
				//Job request user
				$email_list = $this->user->read_mail_list_by_doc(11, $jobno);
				
				$print_url = "<a href=\"".base_url()."index.php/print/11/".$jobno."\"/>".$jobno."</a>";
				
				$message = "";
				
				$message .= "<h4>Job Completed</h4>";
				$message .= "<p>Please click on the follwing link to view document</p>";
				$message .= $print_url;
				
				$this->generate_mail->mailfrom = $useremail;
				$this->generate_mail->namefrom = $username;
				
				$this->generate_mail->mailsubject = "Completed Job - ".$jobno;
				
				$this->generate_mail->mailto_list= $email_list;
				
				$this->generate_mail->mailmessage = $message;
				
				$this->generate_mail->mail_send();
				
				//Job approve user
				$email_list = $this->user->read_mail_list_user($job_file->apprejby);
				$this->generate_mail->mailto_list = $email_list;
				$this->generate_mail->mail_send();
				
				//Job accept user
				$email_list = $this->user->read_mail_list_user($job_file->accrejby);
				$this->generate_mail->mailto_list = $email_list;
				$this->generate_mail->mail_send();
				
			}else{
				$return_Status[0] = "0";
				$return_Status[1] = "Already Updated or Something Went Wrong";
				$return_Status[2] = $this->read_job_dtl_list_not_complete_html();
			}
			
			return $return_Status;
		}
		catch(Exception $e)
		{
			$this->db->trans_rollback();
			
			log_message('error', $e->getMessage());
			$return_Status[0] = "0";
			$return_Status[1] = "Something Went Wrong";
			$return_Status[2] = $this->read_job_dtl_list_not_complete_html();
			
			return $return_Status;
		}
	}
	
	public function read_job_dtl_list_not_feedback_html() {
		try
		{
			
			$return_html = "No Jobs For Acknowledgement..";
			
			$query = $this->read_job_dtl_list('fbk');
			
			foreach ($query->result() as $row)
			{
				
				if ($row->docstatus == 'O'){
					$dropdownhead = $row->jobno." | ".$row->jobtype." | Job Date : ".$row->date." | Company : ".$row->company." | Dept : ".$row->department. " | User : ".$row->insertuser." <span class=\"w3-tag w3-blue\">Reopen</span>";
				}else{
					$dropdownhead = $row->jobno." | ".$row->jobtype." | Job Date : ".$row->date." | Company : ".$row->company." | Dept : ".$row->department. " | User : ".$row->insertuser;
				}
				
				if ($return_html === 'No Jobs For Acknowledgement..'){
					$return_html = "";
				}
				
				$return_html .= "<button onclick=\"dropdown_dtl('".$row->jobno."')\" class=\"w3-button w3-block w3-black w3-left-align\">".$dropdownhead."</button>";
				
				$return_html .= "<div id=\"".$row->jobno."\" class=\"w3-hide w3-container w3-sand\">";
				
				$return_html .= "<br>";
				
				$return_html .= "<div class=\"w3-row-padding\">";
				$return_html .= "<div class=\"w3-col m4\">";
				$return_html .= "<label>Machine</label>";
				$return_html .= "<input id=\"idgrn_date\" class=\"w3-input\" type=\"text\" value=\"".$row->machineno."\" disabled>";
				$return_html .= "</div>";
				$return_html .= "<div class=\"w3-col m4\">";
				$return_html .= "<label>Completed By</label>";
				$return_html .= "<input id=\"idgrn_date\" class=\"w3-input\" type=\"text\" value=\"".$row->completeby."\" disabled>";
				$return_html .= "</div>";
				$return_html .= "<div class=\"w3-col m4\">";
				$return_html .= "<label>Completed Date</label>";
				$return_html .= "<input id=\"idgrn_date\" class=\"w3-input\" type=\"text\" value=\"".$row->completedate."\" disabled>";
				$return_html .= "</div>";
				$return_html .= "</div>";
				
				$return_html .= "<div class=\"w3-row-padding\">";
				$return_html .= "<div class=\"w3-col m12\">";
				$return_html .= "<label>Job Description</label>";
				$return_html .= "<textarea id=\"id_job_req_".$row->no."\" class=\"w3-input\" disabled>".$row->jobdescription."</textarea>";
				$return_html .= "</div>";
				$return_html .= "</div>";
				
				$return_html .= "<div class=\"w3-row-padding\">";
				$return_html .= "<div class=\"w3-col m6\">";
				$return_html .= "<label>Engineer</label>";
				
				$result_eng = $this->read_job_engineer($row->no);
				
				$return_html .= "<input class=\"w3-input\" type=\"text\" value=\"".$result_eng->engineer."\" disabled>";
				$return_html .= "</div>";
				$return_html .= "<div class=\"w3-col m6\">";
				$return_html .= "<label>Estimate Date to be Complete</label>";
				$return_html .= "<input class=\"w3-input\" type=\"text\" value=\"".$row->estimatedatecomplete."\" disabled>";
				$return_html .= "</div>";
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
				$return_html .= "<a href=\"".base_url()."index.php/print/11/".$row->no."\" class=\"w3-btn w3-ripple w3-blue\" target=\"_blank\"><i class=\"fa fa-print\" aria-hidden=\"true\"></i></a>";
				$return_html .= "</div>";
				$return_html .= "<div class=\"w3-col m4\">";
				$return_html .= "<br>";
				$return_html .= "<button id=\"idupdateapp\" class=\"w3-btn w3-green w3-right\" class=\"w3-btn\" onclick=\"feedback_ok_no(".$row->no.", 'A')\"><i class=\"fa fa-thumbs-o-up\" aria-hidden=\"true\"></i> Accept</button>";
				$return_html .= "</div>";
				$return_html .= "<div class=\"w3-col m4\">";
				$return_html .= "<br>";
				$return_html .= "<button id=\"idupdaterej\" class=\"w3-btn w3-orange w3-right\" class=\"w3-btn\" onclick=\"feedback_ok_no(".$row->no.", 'R')\"><i class=\"fa fa-thumbs-o-down\" aria-hidden=\"true\"></i> Reopen</button>";
				$return_html .= "</div>";
				$return_html .= "</div>";
				
				$return_html .= $this->read_job_status_log_html($row->no);
				
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
	
	public function update_feedback($data) {
		$return_Status = array();
		try
		{
			
			/*jobno
			 docstatus
			 apprejremarks
			 insertuser*/
			
			/*$return_Status[0] = "0";
			 $return_Status[1] = $data[0]['jobno'];
			 $return_Status[2] = "";
			 
			 return $return_Status;*/
			
			$jobno = $data[0]['jobno'];
			$docstatus = $data[0]['docstatus'];
			$apprejremarks = $data[0]['apprejremarks'];
			$apprejby = $data[0]['insertuser'];		
			
			$this->db->trans_start();
			
			$this->db->set('acknowledgement', $docstatus);
			$this->db->set('acknby', $apprejby);
			$this->db->set('ackndate', $this->LocalTime, false);
			
			if ($docstatus === 'R'){
				$this->db->set('docstatus', 'O');
				$this->db->set('apprejby', 'P');
				$this->db->set('acceptrej', 'P');
				$this->db->set('complete', 'N');				
			}
			
			$this->db->where('no', $jobno);
			$this->db->update('job_requisition');		
			
			$log = array();
			
			$this->db->set('no', $jobno);
			$this->db->set('user', $apprejby);
			$this->db->set('remark', $apprejremarks);
			$this->db->set('datetime', $this->LocalTime, false);
			$this->db->set('acknowledgement', $docstatus);
			$this->db->insert('job_requisition_status_log');
			
			$this->db->trans_complete();
			
			/*$return_Status[0] = "0";
			 $return_Status[1] = $this->db->trans_status();
			 $return_Status[2] = "";
			 
			 return $return_Status;*/
			
			$job_file = $this->read_job_hdr_by_jobno($jobno);
			
			if($this->db->trans_status() === TRUE){
				$return_Status[0] = "1";
				if ($docstatus == 'A'){
					$return_Status[1] = "Accepted";
				}else{
					$return_Status[1] = "Reopened";
				}
				$return_Status[2] = $this->read_job_dtl_list_not_feedback_html();
				
				$username = $this->session->userdata['logged_in']['username'];
				$useremail = $this->session->userdata['logged_in']['email'];
								
				$print_url = "<a href=\"".base_url()."index.php/print/11/".$jobno."\"/>".$jobno."</a>";
				
				$message = "";
				
				$message .= "<h4>Job feedback - ".$return_Status[1]."</h4>";
				$message .= "<p>Please click on the follwing link to view document</p>";
				$message .= $print_url;
				
				$this->generate_mail->mailfrom = $useremail;
				$this->generate_mail->namefrom = $username;
				
				$this->generate_mail->mailsubject = "Job feedback ".$return_Status[1]."- ".$jobno;
				
				$this->generate_mail->mailto_list= $email_list;
				
				$this->generate_mail->mailmessage = $message;
				
				//Job accepted user
				/*$email_list = $this->user->read_mail_list_user($job_file->accrejby);
				$this->generate_mail->mailto_list = $email_list;
				$this->generate_mail->mail_send();*/
				
				//Job completed user
				$email_list = $this->user->read_mail_list_user($job_file->completeby);
				$this->generate_mail->mailto_list = $email_list;
				$this->generate_mail->mail_send();

			}else{
				$return_Status[0] = "0";
				$return_Status[1] = "Already Updated or Something Went Wrong";
				$return_Status[2] = $this->read_job_dtl_list_not_feedback_html();
			}
			
			return $return_Status;
		}
		catch(Exception $e)
		{
			$this->db->trans_rollback();
			
			log_message('error', $e->getMessage());
			$return_Status[0] = "0";
			$return_Status[1] = "Something Went Wrong";
			$return_Status[2] = $this->read_job_dtl_list_not_feedback_html();
			
			return $return_Status;
		}
	}
	
}

?>