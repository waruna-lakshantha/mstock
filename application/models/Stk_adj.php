<?php

Class Stk_adj extends CI_Model {
	
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
		$this->load->model('item'); 
	}
	
	// Read user menu
	public function adj_insert($hdr,$dtl) {
		
		$return_Status = array();
		
		try
		{	
			
			$this->db->trans_begin();
								            
			$hdr_ass = array();	
			$dtl_ass = array();

			$hdr_ass['date'] = $hdr[0]['adjdate'];
            $hdr_ass['remarks'] = $hdr[0]['remarks'];
            $hdr_ass['insertuser'] = $hdr[0]['insertuser'];

			//$this->db->trans_start();
			
			$this->db->insert('stock_adj_hdr',$hdr_ass);  
			
			$id = $this->db->insert_id();						
			
			$id_reff = "A".str_pad($id, 9, '0', STR_PAD_LEFT);	
            
			$this->db->set('AdjNo', $id_reff);
			$this->db->set('insertdatetime', $this->LocalTime, false);
			$this->db->set('date', $this->LocalTime, false);
			$this->db->where('no', $id);
			$this->db->update('stock_adj_hdr');

			$result = $this->store->read_default_store();
			$def_store = $result[0]->no;           

			$index = 0;
			
            for ($row = 0; $row < count($dtl); $row++) {            	            
            	
            	$sapitemcode = trim($dtl[$row]['itemcode']);
            	
            	$item_check = $this->item->read_item_by_code($sapitemcode);
				if(isset($item_check)){
					$dtl[$row]['itemno'] = $item_check->no;      
				}else{
					$dtl[$row]['itemno'] = 0;
				}            				
				
            	if(!empty($dtl[$row]['itemno'])){
	            	/*$cur_bal = $this->store->read_stock_bal_item_company($dtl[$row]['itemno'], $dtl[$row]['companyno']);	            	
	            	
	            	if($cur_bal !== $dtl[$row]['curbal']){
	            		throw new Exception('Current stock balance not match! '.$sapitemcode.' - '.$cur_bal);
	            	}*/            		            		
	            	            		
            		$dtl[$row]['curbal'] = $this->store->read_stock_bal_item_company_dept($dtl[$row]['itemno'], $dtl[$row]['companyno'], $dtl[$row]['departmentno'], $def_store);
            		$dtl[$row]['qty'] = $dtl[$row]['newbal'] - $dtl[$row]['curbal'];
            		
            		$dtl_ass[$index] = array ('no' => $id,
							'itemno' => $dtl[$row]['itemno'],
							'companyno' => $dtl[$row]['companyno'],
							'departmentno' => $dtl[$row]['departmentno'],
							'curbal' => $dtl[$row]['curbal'],
							'qty' => $dtl[$row]['qty'],
							'newbal' => $dtl[$row]['newbal'],
							'uom' => $dtl[$row]['uom'],						
							'unitcost' => $dtl[$row]['unitcost'],
							'total' => $dtl[$row]['total'],
							'storeno' => $def_store
					);                            
	
	                if($dtl[$row]['qty'] > 0){
						    $stock_item = array();
						    $stock_item['comno'] = $dtl[$row]['companyno'];
						    $stock_item['departmentno'] = $dtl[$row]['departmentno'];
						    $stock_item['storeno'] = $def_store;
						    $stock_item['itemno'] = $dtl[$row]['itemno'];
						    $stock_item['inqty'] = $dtl[$row]['qty'];
						    $stock_item['outqty'] = 0;
						 
						    $this->store->update_store_item($stock_item);
						
						    $stock_dtl = array();
						    $stock_dtl['comno'] = $dtl[$row]['companyno'];
						    $stock_dtl['departmentno'] = $dtl[$row]['departmentno'];
						    $stock_dtl['storeno'] = $def_store;
						    $stock_dtl['itemno'] = $dtl[$row]['itemno'];
						    $stock_dtl['inqty'] = $dtl[$row]['qty'];
						    $stock_dtl['unitcost'] = $dtl[$row]['unitcost'];
						    $stock_dtl['doctype'] = "ADJ";
						    $stock_dtl['docno'] = $id;
						    $stock_dtl['uom'] = $dtl[$row]['uom'];
						 
						    $this->store->insert_stock_in_details($stock_dtl);   
	                    
						    $stock_tran = array();
						    $stock_tran['comno'] = $dtl[$row]['companyno'];
						    $stock_tran['departmentno'] = $dtl[$row]['departmentno'];
						    $stock_tran['storeno'] = $def_store;
						    $stock_tran['machineno'] = 118; //General
						    $stock_tran['itemno'] = $dtl[$row]['itemno'];
						    $stock_tran['inqty'] = $dtl[$row]['qty'];
						    $stock_tran['outqty'] = 0;
						    $stock_tran['unitcost'] = $dtl[$row]['unitcost'];
						    $stock_tran['doctype'] = "ADJ";
						    $stock_tran['docno'] = $id;
						    $stock_tran['docref'] = $id_reff;					
						 
						    $this->store->insert_stock_transaction($stock_tran);
						
						    $trid = $this->db->insert_id();
						
						    $this->db->set('docdate', $this->LocalTime, false);
						    $this->db->where('order', $trid);
						    $this->db->update('stocktransaction');                                 
	                }
	
	                if($dtl[$row]['qty'] < 0){
					    $balqty = $dtl[$row]['qty'] * -1;							
					
					    $stock_in_result = $this->store->read_stock_in_detail($dtl[$row]['companyno'], $def_store, $dtl[$row]['itemno']);							
					
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
									    $stock_item['comno'] = $dtl[$row]['companyno'];
									    $stock_item['departmentno'] = $dtl[$row]['departmentno'];
									    $stock_item['storeno'] = $def_store;
									    $stock_item['itemno'] = $dtl[$row]['itemno'];
									    $stock_item['inqty'] = 0;
									    $stock_item['outqty'] = $balqty;
	
	                                    //throw new Exception('Stock Balance Error in MRN '.$stock_item['outqty']);                                
									
									    $this->store->update_store_item($stock_item);																																				
									
	                                    $stock_tran = array();
	                                    $stock_tran['comno'] = $dtl[$row]['companyno'];
	                                    $stock_tran['departmentno'] = $dtl[$row]['departmentno'];
	                                    $stock_tran['storeno'] = $def_store;
	                                    $stock_tran['machineno'] = 118; //General
	                                    $stock_tran['itemno'] = $dtl[$row]['itemno'];
	                                    $stock_tran['inqty'] = 0;
	                                    $stock_tran['outqty'] = $balqty;
	                                    $stock_tran['unitcost'] = $dtl[$row]['unitcost'];
	                                    $stock_tran['doctype'] = "ADJ";
	                                    $stock_tran['docno'] = $id;
	                                    $stock_tran['docref'] = $id_reff;
	                                
	                                    $stock_tran['docdate'] = mdate(date("Y-m-d h:i:sa"));
	                                
	                                    //$stock_tran['docdate'] = $this->LocalTime;
	                                
	                                    $this->store->insert_stock_transaction($stock_tran);                                                        								
									
									    $balqty = 0;								
								    }else{
									
									    $update_qty = 'usedqty + '.$row_stk_in->bal;
									
									    $this->db->set('usedqty', $update_qty, false);
									    $this->db->where('inorder', $row_stk_in->inorder);
									    $this->db->update('stockindetail');							
									
									    $stock_item = array();
									    $stock_item['comno'] = $dtl[$row]['companyno'];
									    $stock_item['departmentno'] = $dtl[$row]['departmentno'];
									    $stock_item['storeno'] = $def_store;
									    $stock_item['itemno'] = $dtl[$row]['itemno'];
									    $stock_item['inqty'] = 0;
									    $stock_item['outqty'] = $row_stk_in->bal;
									
									    $this->store->update_store_item($stock_item);																						
	
									    $stock_tran = array();
									    $stock_tran['comno'] = $dtl[$row]['companyno'];
									    $stock_tran['departmentno'] = $dtl[$row]['departmentno'];
									    $stock_tran['storeno'] = $def_store;
									    $stock_tran['machineno'] = 118; //General
									    $stock_tran['itemno'] = $dtl[$row]['itemno'];
									    $stock_tran['inqty'] = 0;
									    $stock_tran['outqty'] = $row_stk_in->bal;
									    $stock_tran['unitcost'] = $dtl[$row]['unitcost'];
									    $stock_tran['doctype'] = "ADJ";
									    $stock_tran['docno'] = $id;
									    $stock_tran['docref'] = $id_reff;
									
									    $stock_tran['docdate'] = mdate(date("Y-m-d h:i:sa"));
									    //$stock_tran['docdate'] = $this->LocalTime;
									
									    $this->store->insert_stock_transaction($stock_tran);							
									
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
	                
	                $index++;
                
            	}
            }
            
            //throw new Exception('Item! '.count($dtl_ass));

            if(count($dtl_ass) > 0){
            	$this->db->insert_batch('stock_adj_dtl', $dtl_ass);	            
            }else{
            	throw new Exception('No Item Found!');
            }

            //$this->db->trans_complete();

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

            	$this->db->trans_commit();
            	
            	$return_Status[0] = "1";
            	$return_Status[1] = $id_reff;
            	$return_Status[2] = "";
                $return_Status[3] = $id;
            	
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
	
	public function read_stk_hdr_adj_no($no){
		try
		{
			$this->db->select('no, AdjNo, date, remarks, insertuser, insertdatetime');
			
			$this->db->from('stock_adj_hdr');
			$this->db->where('no', $no);
			
			return $this->db->query($this->db->get_compiled_select());
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return array();
		}
	}
	
	public function read_stk_adj_dtl_by_no($no) {
		//try
		//{
		
		$this->db->select('stock_adj_dtl.itemno, item.itemcode, item.description as `Item`, 
							stock_adj_dtl.companyno, company.description as Company, stock_adj_dtl.departmentno, 
							stock_adj_dtl.curbal, stock_adj_dtl.qty, stock_adj_dtl.newbal, stock_adj_dtl.uom, 
							stock_adj_dtl.unitcost, stock_adj_dtl.total, stock_adj_dtl.storeno');
		$this->db->from('stock_adj_dtl');
		$this->db->join('stock_adj_hdr', 'stock_adj_hdr.no = stock_adj_dtl.no');
		$this->db->join('item', 'item.no = stock_adj_dtl.itemno');
		$this->db->join('company', 'company.no = stock_adj_dtl.companyno');
		$this->db->join('department', 'department.no = stock_adj_dtl.departmentno');
		$this->db->join('wherehouse', 'wherehouse.no = item.whcode');
		$this->db->where('stock_adj_hdr.no', $no);
		$this->db->order_by('stock_adj_dtl.dtlno', 'ASC');
		
		return $this->db->query($this->db->get_compiled_select());
		
		//}
		//catch(Exception $e)
		//{
		//log_message('error', $e->getMessage());
		//return array();
		//}
	}

}

?>