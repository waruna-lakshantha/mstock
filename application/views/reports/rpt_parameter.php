<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	$report_id = 0;
	
	if (isset($rpt_id)) {
		$report_id = $rpt_id;
	}
?>

<div class="w3-panel w3-card-4 w3-light-grey">

	<br>

	<div class="w3-bar w3-black">
		<button class="w3-bar-item w3-button tablink w3-red pmtab" onclick="openParaTab(event,'DatePeriod')">Date</button>
		<button class="w3-bar-item w3-button tablink pmtab" onclick="openParaTab(event,'Company')">Company</button>
		<button class="w3-bar-item w3-button tablink pmtab" onclick="openParaTab(event,'Department')">Department</button>
		<button class="w3-bar-item w3-button tablink pmtab" onclick="openParaTab(event,'Item')">Item</button>
		<?php 
			//if($report_id === '20'){
				echo "<button class=\"w3-bar-item w3-button tablink pmtab\" onclick=\"openParaTab(event,'Machine')\">Machine</button>";
			//}
		?>		
		<button class="w3-bar-item w3-button tablink pmtab" onclick="openParaTab(event,'Status')">Status</button>
	</div>

	<div id="DatePeriod" class="w3-container w3-border param">
	
		<div class="w3-row-padding">
			<?php 
			if($report_id !== '24' && $report_id !== '25'){		
					echo "<div class=\"w3-third\">";
						echo "<p>";
						echo "<input id=\"id_chk_date_asat\" class=\"w3-check\" type=\"checkbox\">";
						echo "<label>As At</label></p>";
						echo "<p>";
					echo "</div>";
					echo "<div class=\"w3-third\">";
						echo "<label>Date</label>";
						echo "<input id=\"id_date_asat\" id=\"idreq_date\" class=\"w3-input\" type=\"date\">";
					echo "</div>";
					echo "<div class=\"w3-third\">";
						
					echo "</div>";
				}
			?>
		</div>	
		
		<div class="w3-row-padding">
			<?php 
				if($report_id !== '24' && $report_id !== '25'){
					echo "<div class=\"w3-third\">";
						echo "<p>";
						echo "<input id=\"id_chk_date_period\" class=\"w3-check\" type=\"checkbox\">";
						echo "<label>Date Period</label></p>";
						echo "<p>";
					echo "</div>";
					echo "<div class=\"w3-third\">";
						echo "<label>From</label>";
						echo "<input id=\"id_date_from\" class=\"w3-input\" type=\"date\">";
					echo "</div>";
					echo "<div class=\"w3-third\">";
						echo "<label>To</label>";
						echo "<input id=\"id_date_to\" class=\"w3-input\" type=\"date\">";
					echo "</div>";
				}
			?>
		</div>

		<br>
	</div>

	<div id="Company" class="w3-container w3-border param" style="display:none">
		<label>Company</label>
		<div class="rpt_para_scroll">
			<?php
				//if ($report_id !== '25'){
					$this->load->view('company_table');
				//}
			?>
		</div>
		<br>
	</div>

	<div id="Department" class="w3-container w3-border param" style="display:none">
		<label>Department</label>
		<div class="rpt_para_scroll">
			<?php 
				if($report_id !== '24' && $report_id !== '25'){
					$this->load->view('department_table'); 
				}
			?>
		</div>
		<br>
	</div>
		
	<div id="Item" class="w3-container w3-border param" style="display:none">
		<label>Item</label>

		<div class="w3-bar w3-light-grey w3-border">
			<input type="text" id="id_search_itm_des" class="w3-bar-item w3-input w3-white w3-mobile w3-right" placeholder="Search Description.." onkeyup="filterItemDes()">
			<label class="w3-bar-item w3-mobile w3-right">Item Description</label>
			<input type="text" id="id_search_itm_code" class="w3-bar-item w3-input w3-white w3-mobile w3-right" placeholder="Search Item Code.." onkeyup="filterItemCode()">
			<label class="w3-bar-item w3-mobile w3-right">Item Code</label>
		</div>
		
		<div class="rpt_para_scroll">
			<?php $this->load->view('item_table'); ?>
		</div>		
		
		<br>
	</div>
	
	<div id="Machine" class="w3-container w3-border param" style="display:none">
		<label>Machine</label>
		<div class="rpt_para_scroll">
			<?php
				if($report_id === '20' || $report_id === '22' || $report_id === '23'){
					$this->load->view('machine_table'); 
				}
			?>
		</div>	
		<br>
	</div>
	
	<div id="Status" class="w3-container w3-border param" style="display:none">
	
		<div class="w3-row-padding">
		
			<?php
				if($report_id !== '24' && $report_id !== '25'){
		
					echo "<div class=\"w3-col m4\">";				
						echo "<label>Approve Status</label>";
						echo "<select id=\"id_approve\" class=\"w3-select w3-border\">";
						echo "<option value=\"P\">Pending</option>";
						    echo "<option value=\"A\">Approve</option>";
						    echo "<option value=\"R\">Reject</option>";
						echo "</select>";
					echo "</div>";
					echo "<div class=\"w3-col m4\">";
						echo "<label>Accept</label>";
						echo "<select id=\"id_accept\" class=\"w3-select w3-border\">";
							echo "<option value=\"P\">Pending</option>";
						    echo "<option value=\"A\">Approve</option>";
						    echo "<option value=\"R\">Reject</option>";
						echo "</select>";
					echo "</div>";
					echo "<div class=\"w3-col m4\">";
						echo "<p>";
						echo "<input id=\"id_chk_proceed\" class=\"w3-check\" type=\"checkbox\">";
						echo "<label>Proceed</label></p>";
						echo "<p>";				
					echo "</div>";
					
				}
			?>
		</div>

		<br>
	</div>	

	<br>

	<div class="w3-row-padding">
		<div class="w3-col m8">
			<p></p>
		</div>
		<div class="w3-col m4">
			<button id="idreportbtm" class="w3-btn w3-green w3-right" class="w3-btn" onclick="view_Report(<?php echo $report_id;?>)"><i class="fa fa-file-text" aria-hidden="true"></i> View</button>			
		</div>		
	</div>

	<br>	
	
	<script>
		function view_Report(rpt_id){
			var _ParaData = new Array();
			
			var _DateData = new Array();
			var _ComPara = new Array();
			var _DeptPara = new Array();
			var _ItemPara = new Array();
			var _StatusPara = new Array();
			var _MachinePara = new Array();

			/*_DateData = storeDatePara();
			_ComPara = storeComPara();
			_DeptPara = storeDeptPara();
			_ItemPara = storeItemPara();
			_StatusPara = storeStatusPara();
			_MachinePara = storeMachinePara()*/			
			
			_ParaData[0] = {"menu_id" : rpt_id};
			
			if(rpt_id == 20 || rpt_id == 22 || rpt_id == 23){
				_DateData = storeDatePara();
				_ComPara = storeComPara();
				_DeptPara = storeDeptPara();
				_ItemPara = storeItemPara();
				_StatusPara = storeStatusPara();
				_MachinePara = storeMachinePara()
				
				_ParaData[1] = {"date_para" : _DateData};
				_ParaData[2] = {"com_para" : _ComPara};
				_ParaData[3] = {"dept_para" : _DeptPara};
				_ParaData[4] = {"item_para" : _ItemPara};
				_ParaData[5] = {"status_para" : _StatusPara};
				_ParaData[6] = {"machine_para" : _MachinePara};
			}

			if(rpt_id == 21){
				_DateData = storeDatePara();
				_ComPara = storeComPara();
				_DeptPara = storeDeptPara();
				_ItemPara = storeItemPara();
				_StatusPara = storeStatusPara();
				
				_ParaData[1] = {"date_para" : _DateData};
				_ParaData[2] = {"com_para" : _ComPara};
				_ParaData[3] = {"dept_para" : _DeptPara};
				_ParaData[4] = {"item_para" : _ItemPara};
				_ParaData[5] = {"status_para" : _StatusPara};
			}

			if(rpt_id == 24){
				_ComPara = storeComPara();
				_ItemPara = storeItemPara();
				
				_ParaData[1] = {"com_para" : _ComPara};
				_ParaData[2] = {"item_para" : _ItemPara};
			}	

			if(rpt_id == 25){
				_ComPara = storeComPara();
				_ItemPara = storeItemPara();
				
				_ParaData[1] = {"com_para" : _ComPara};
				_ParaData[2] = {"item_para" : _ItemPara};
			}
			
			var _ParaData_JSON = JSON.stringify(_ParaData);

			//alert(JSON.stringify(storeComPara()));

			var url = '<?php echo base_url(); ?>index.php/view';

			$.ajax({
				type: "POST",
				url: url,
				data: ({pTableData: _ParaData_JSON}),
				dataType: 'json',
				cache: false,
				success: function(data){	                
	                if (parseInt(data[0]) == 1){
	                	
						//alert(data[1]);
						
						var x=window.open();
						x.document.open();
						x.document.write(data[1]);
						x.document.close();
						
	                	return;               	
	                	
	                }else{

	                	return;
	                }	                
				}
			});			
		}
	
		function openParaTab(evt, pTabName) {
			var i, x, tablinks;
			x = document.getElementsByClassName("param");
			for (i = 0; i < x.length; i++) {
				x[i].style.display = "none";
			}
			tablinks = document.getElementsByClassName("pmtab");
			for (i = 0; i < x.length; i++) {
				tablinks[i].className = tablinks[i].className.replace(" w3-red", "");
			}
			document.getElementById(pTabName).style.display = "block";
			evt.currentTarget.className += " w3-red";
		}
	
	    function storeDatePara() {
	        var _ReqHdrData = new Array();

	        var ctrl_isasat = document.getElementById("id_chk_date_asat");
	        var ctrl_isperiod = document.getElementById("id_chk_date_period");
	        
	        var _isasat = ctrl_isasat.checked;
	        var _isperiod = ctrl_isperiod.checked;

	        var _date_asat = ''; 
	        var _date_from = ''; 
	        var _date_to = '';         

	        if (_isasat){
		        if ($('#id_date_asat').val() != undefined || $('#id_date_asat').val() != null){
					_date_asat = get_json_date($('#id_date_asat').val());
		        }else{
	            	$("#idmessage").removeClass("w3-red");
	            	$("#idmessage").removeClass("w3-yellow");
	            	$("#idmessage").removeClass("w3-green");

	            	$("#idmessage").addClass("w3-yellow");
	            	$("#msgHed").text("Required");
	            	$("#msgDes").text("As at date required!");
	            	$("#idmessage").removeClass("w3-hide");            

	            	document.getElementById('idmessage').style.display = 'block';

	                $('html, body').animate({
	                    scrollTop: $("#idmessage").offset().top
	                }, 2000);
	                
	                return new Array();
		        }
	        }

			if(_isperiod){
				if ($('#id_date_from').val() != undefined || $('#id_date_from').val() != null){
					_date_from = get_json_date($('#id_date_from').val()); 
				}else{
	            	$("#idmessage").removeClass("w3-red");
	            	$("#idmessage").removeClass("w3-yellow");
	            	$("#idmessage").removeClass("w3-green");

	            	$("#idmessage").addClass("w3-yellow");
	            	$("#msgHed").text("Required");
	            	$("#msgDes").text("From date required!");
	            	$("#idmessage").removeClass("w3-hide");            

	            	document.getElementById('idmessage').style.display = 'block';

	                $('html, body').animate({
	                    scrollTop: $("#idmessage").offset().top
	                }, 2000);
	                
	                return new Array();
				}

				if ($('#id_date_to').val() != undefined || $('#id_date_to').val() != null){
					_date_to = get_json_date($('#id_date_to').val()); 
				}else{
	            	$("#idmessage").removeClass("w3-red");
	            	$("#idmessage").removeClass("w3-yellow");
	            	$("#idmessage").removeClass("w3-green");

	            	$("#idmessage").addClass("w3-yellow");
	            	$("#msgHed").text("Required");
	            	$("#msgDes").text("To date required!");
	            	$("#idmessage").removeClass("w3-hide");            

	            	document.getElementById('idmessage').style.display = 'block';

	                $('html, body').animate({
	                    scrollTop: $("#idmessage").offset().top
	                }, 2000);
	                
	                return new Array();
				}
			}

	        _ReqHdrData[0] = { "isasat": _isasat, "isperiod":_isperiod, "date_asat":_date_asat, "date_from":_date_from, "date_to":_date_to, "insertuser": "<?php echo $this->session->userdata['logged_in']['username'];?>"};

	        //return JSON.stringify(_ReqHdrData);

	        return _ReqHdrData;
	    }

	    function storeComPara() {
	        var _TableData = new Array();

			var ai = 0;
			var rowi = 0;
			    
		    $('#id_para_comp tr').each(function(row, tr){

		    	if(rowi > 0){

		    		//alert(rowi);
			    	
			    	var issel = $(tr).find('td:eq(0)').find('input').is(':checked');          
			    	var code = $(tr).find('td:eq(1)').text();
			    	
			    	if(issel){
				    	if(code != undefined && code != null && code.length != 0)
				    	{						    	                        	   
				    	    _TableData[ai]={
				                "companyno" : code
				            };
				    	    ai++;
				    	}
			    	}
			    }

		    	rowi++;
		    	
		    });

	        //return JSON.stringify(_TableData);

	        return _TableData;

	    }

	    function storeDeptPara() {
	        var _TableData = new Array();

			var ai = 0;
			var rowi = 0;
			    
		    $('#id_para_dept tr').each(function(row, tr){

		    	if(rowi > 0){
		    		var issel = $(tr).find('td:eq(0)').find('input').is(':checked');            
			    	var code = $(tr).find('td:eq(1)').text();
			    	
			    	if(issel){
				    	if(code != undefined && code != null && code.length != 0)
				    	{						    	                        	   
				    	    _TableData[ai]={
				                "deptno" : code
				            };
				    	    ai++;
				    	}
			    	}
			    }

		    	rowi++;
		    	
		    });

	        //return JSON.stringify(_TableData);

		    return _TableData;

	    }

	    function storeItemPara() {
	        var _TableData = new Array();

			var ai = 0;
			var rowi = 0;

		    $('#id_para_item tr').each(function(row, tr){

		    	if(rowi > 0){
		    		var issel = $(tr).find('td:eq(0)').find('input').is(':checked');           
			    	var code = $(tr).find('td:eq(1)').text();

			    	if(issel){
				    	if(code != undefined && code != null && code.length != 0)
				    	{
				    	    _TableData[ai]={
				                "itemno" : code
				            };
				    	    ai++;
				    	}
			    	}
			    }

		    	rowi++;
		    	
		    });

	        //return JSON.stringify(_TableData);

	        return _TableData;
	    }

	    function storeStatusPara() {
	        var _ReqHdrData = new Array();

	        var ctrl_chk_proceed = document.getElementById("id_chk_proceed");

	        var _approve = $('#id_approve').val();
	        var _accept = $('#id_accept').val();
	        var _proceed = ctrl_chk_proceed.checked;

	        _ReqHdrData[0] = { "approve":_approve, "accept":_accept, "proceed":_proceed, "insertuser": "<?php echo $this->session->userdata['logged_in']['username'];?>"};

	        //return JSON.stringify(_ReqHdrData);

	        return _ReqHdrData;
	    }	    
	</script>		

</div>

<div id="idmessage" class="w3-panel w3-red w3-display-container w3-round w3-card-4 w3-animate-bottom w3-hide">
  <span onclick="this.parentElement.style.display='none'"
  class="w3-button w3-red w3-large w3-display-topright">&times;</span>
  <h3 id="msgHed"></h3>
  <p id="msgDes"></p>
</div> 