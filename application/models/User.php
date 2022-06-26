<?php 

Class User extends CI_Model {
	// Read Data from the company table
	public function read_user_permission($user_id, $sys_id) {
		try
		{
			
			$return_Status = array();		
			
			$return_Status[0] = "0";
			$return_Status[1] = "0";
			$return_Status[2] = "0";
			
			$this->db->select('*');
			$this->db->from('user_login');
			$this->db->where('user_name', $user_id);
			$query_user_det = $this->db->get();
			
			$row_user_det = $query_user_det->row();
			
			if (isset($row_user_det)){
				$return_Status[0] = $row_user_det->companyno;
				$return_Status[1] = $row_user_det->departmentno;
				$return_Status[2] = $row_user_det->user_email;														
			}
			
			$html_out = "";				
			
			$this->db->select('*');
			$this->db->from('systemmenu');
			$this->db->where('systemid', $sys_id);
			$this->db->order_by('category', 'DESC');
			$this->db->order_by('displayorder', 'ASC');			
			$query = $this->db->get();
			
			foreach ($query->result() as $row)
			{
				
				$this->db->select('*');
				$this->db->from('usermenu');
				$this->db->where('userid', $user_id);
				$this->db->where('systemid', $row->systemid);
				$this->db->where('menuid', $row->menuid);
				$query_user = $this->db->get();
				
				$row_user = $query_user->row();
				
				if(isset($row_user))
				{
					$html_out .= "<input class=\"w3-check\" type=\"checkbox\" value=\"".$row->menuid."\" checked=\"checked\">";
				}
				else
				{
					$html_out .= "<input class=\"w3-check\" type=\"checkbox\" value=\"".$row->menuid."\">";
				}
				
				$html_out .= "<label class=\"w3-validate\">".$row->menuname."</label></p>";				
				
			}	
			
			$return_Status[3] = $html_out;
			
			return $return_Status;
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return $e->getMessage();
		}
	}
	
	// Read user menu
	public function user_insert($hdr,$dtl) {
		
		$return_Status = array();
		
		try
		{
			
			if (empty($hdr[0]['user_name']) || empty($hdr[0]['password']))
			{
				$return_Status[0] = "0";
				$return_Status[1] = "User Name or Password Required!";
				$return_Status[2] = "";
				
				return $return_Status;
			}
			
			$hdr_ass = array();
			$dtl_ass = array();
			
			$sys_id = $hdr[0]['sys'];;
																			
			$hdr_ass['user_name'] = $hdr[0]['user_name'];
			$hdr_ass['user_email'] = $hdr[0]['email'];
			$hdr_ass['user_password'] = $hdr[0]['password'];
			$hdr_ass['companyno'] =  $hdr[0]['company'];
			$hdr_ass['departmentno'] = $hdr[0]['dept'];			
			
			$this->db->trans_start();
			
			$this->db->where('user_name', $hdr[0]['user_name']);
			$this->db->delete('user_login');
			
			/*$return_Status[0] = "0";
			$return_Status[1] = "Error! ".$hdr[0]['user_name'];
			$return_Status[2] = "";
			
			return $return_Status;*/
							
			$this->db->insert('user_login',$hdr_ass);		
			
			for ($row = 0; $row < count($dtl); $row++) {

				$dtl_ass[$row] = array ('userid' => $hdr[0]['user_name'],
						'systemid' => $sys_id,
						'menuid' => $dtl[$row]['menuid'],
						'canview' => 1,
						'canadd' => 1,
						'canedit' => 1,
						'caninactive' => 1
				);
				
			}
			
			$this->db->where('userid', $hdr[0]['user_name']);
			$this->db->where('systemid', $sys_id);
			$this->db->delete('usermenu');
			
			$this->db->insert_batch('usermenu', $dtl_ass);
			
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
				$return_Status[1] = 0;
				$return_Status[2] = "";
				
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
	
	public function check_access($uid, $sysid, $menuid) {
		try
		{			
			$this->db->select('*');
			$this->db->from('usermenu');
			$this->db->where('userid', $uid);
			$this->db->where('systemid', $sysid);
			$this->db->where('menuid', $menuid);
			$query_user_det = $this->db->get();
			
			$row_user_det = $query_user_det->row();
			
			//return $menuid;
			
			if (isset($row_user_det)){
				return true;
			}else{
				return false;
			}
		}
		catch(Exception $e)
		{			
			return false;
		}
	}
	
	public function read_mail_list_user($userid) {
		try
		{	
			$this->db->select('u.user_name, u.user_email');
			$this->db->from('user_login u');
			$this->db->where('u.user_name', $userid);
			
			$query = $this->db->get();
			
			return $query->result();
		}
		catch(Exception $e)
		{
			return false;
		}
	}
	
	public function read_mail_list($sysid, $menuid) {
		try
		{
			
			$depid = $this->session->userdata['logged_in']['dept'];
			
			$this->db->select('u.user_name, u.user_email');
			$this->db->from('usermenu um');
			$this->db->join('user_login u', 'um.userid = u.user_name');
			$this->db->where('um.menuid', $menuid);
			$this->db->where('um.systemid', $sysid);
			$this->db->where('u.departmentno', $depid);
			$this->db->where('um.mailalert', 1);			
			
			$query = $this->db->get();
			
			return $query->result();			
		}
		catch(Exception $e)
		{
			return false;
		}
	}
	
	public function read_mail_list_all($sysid, $menuid) {
		try
		{			
			$this->db->select('u.user_name, u.user_email');
			$this->db->from('usermenu um');
			$this->db->join('user_login u', 'um.userid = u.user_name');
			$this->db->where('um.menuid', $menuid);
			$this->db->where('um.systemid', $sysid);
			$this->db->where('um.mailalert', 1);
			
			$query = $this->db->get();
			
			return $query->result();
		}
		catch(Exception $e)
		{
			return false;
		}
	}
	
	public function read_mail_list_dept($sysid, $menuid, $depid) {
		try
		{			
			$this->db->select('u.user_name, u.user_email');
			$this->db->from('usermenu um');
			$this->db->join('user_login u', 'um.userid = u.user_name');
			$this->db->where('um.menuid', $menuid);
			$this->db->where('um.systemid', $sysid);
			$this->db->where('u.departmentno', $depid);
			$this->db->where('um.mailalert', 1);
			
			$query = $this->db->get();
			
			return $query->result();
		}
		catch(Exception $e)
		{
			return false;
		}
	}
	
	public function read_mail_list_by_doc($mnu_id, $docno) {
		try
		{
			
			switch ($mnu_id) {
				case 1: //Material Requisition for Purchase (MRP)
					
					$this->db->select('u.user_name, u.user_email');
					$this->db->from('pr_hdr p');
					$this->db->join('user_login u', 'p.insertuser = u.user_name');
					$this->db->where('p.no', $docno);
					
					$query = $this->db->get();
					
					return $query->result();
					
					break;
					
				case 2: //Goods Received Note
					
					$this->db->select('u.user_name, u.user_email');
					$this->db->from('grn_hdr g');
					$this->db->join('user_login u', 'g.insertuser = u.user_name');
					$this->db->where('g.no', $docno);
					
					$query = $this->db->get();
					
					return $query->result();
					
					break;
					
				case 3: //Material Request Note
					
					$this->db->select('u.user_name, u.user_email');
					$this->db->from('mr_hdr m');
					$this->db->join('user_login u', 'm.insertuser = u.user_name');
					$this->db->where('m.no', $docno);
					
					$query = $this->db->get();
					
					return $query->result();
					
					break;
					
				case 4: //Material Issue Note
					
					$this->db->select('u.user_name, u.user_email');
					$this->db->from('issue_hdr i');
					$this->db->join('user_login u', 'i.insertuser = u.user_name');
					$this->db->where('i.no', $docno);	
					
					$query = $this->db->get();
					
					return $query->result();
					
					break;
					
				case 11: //Job Requisition
					
					$this->db->select('u.user_name, u.user_email');
					$this->db->from('job_requisition j');
					$this->db->join('user_login u', 'j.insertuser = u.user_name');
					$this->db->where('j.no', $docno);
					
					$query = $this->db->get();
					
					return $query->result();
					
					break;
					
			}	
			

		}
		catch(Exception $e)
		{
			return false;
		}
	}
}

?>