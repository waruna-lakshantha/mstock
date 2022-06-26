<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="w3-panel w3-card-4 w3-light-grey">

	<br>

	<div class="w3-row-padding">
		<div class="w3-col m4">
			<label>MRN No</label>
			<input id="idgrn_no" class="w3-input" type="text" disabled>	
        </div>
		<div class="w3-col m4">
	    	<label>Date</label>
		    <input id="idgrn_date" class="w3-input" type="text" disabled>	
		</div>
		<div class="w3-col m4">
			<label>Enter Doc. No</label>
			<div class="w3-bar">
			  <input id="id_reprint_no" class="w3-bar-item w3-input w3-border" type="text">
			  <button class="w3-bar-item w3-button w3-teal" onclick="Reprint_Doc(3, '<?php echo base_url();?>')"><i class="fa fa-print" aria-hidden="true"></i> Re-Print</button>
			</div>		
		</div>			
	</div>
	
	<div class="w3-row-padding">
		<div class="w3-col m4">
	    	<label>Sap Ref No</label>
		    <input id="idsap_refno" class="w3-input" type="text">	
		</div>	
		<div class="w3-col m4">
	    	<label>Req Date</label>
		    <input id="idreq_date" class="w3-input" type="date">
		</div>
		<div class="w3-col m4">
	    	<!-- <label>Wherehouse</label>
			<select id="id_wherehouse" class="w3-select"> -->
				<?php 					
					//$this->load->view('wherehouse_option_list');										
				?>	
			<!-- </select> -->
		</div>
	</div>	
	
	<br>
	
</div>	

<div class="w3-panel w3-card-4 w3-light-grey">
	
	<br>
	
	<div class="w3-row-padding">
		<div class="w3-col m4">
			<label>Item Search By (cat/code/des)</label>
			<input id="id_item_search" class="w3-input w3-right-align" type="text" onchange="filter_Item_Option_List('idgrn_item')">	
		</div>		
		<div id="div_item" class="w3-col m8">
			<label>Item</label>
			<select id="idgrn_item" class="w3-select" onchange="read_stk_bal_item('<?php echo base_url()?>',this.value)">
				<?php 					
					if (isset($item_list)) {
						$data['item_list'] = $item_list;
						$this->load->view('item_option_list', $data);						
					}						
				?>	
			</select>		
		</div>
	</div>
	
	<br>
	
	<div class="w3-row-padding">
		<div class="w3-col m4">
			<span id="id_stk_bal" class="w3-badge w3-large w3-padding w3-red">0</span>
		</div>
		<div class="w3-col m4">
			<label>Wherehouse</label>
			<input id="id_w_house" class="w3-input" type="text" disabled>
			<input id="id_w_house_code" class="w3-input" type="hidden">	
		</div>
		<div id="id_sel_img" class="w3-col m4">
			<!-- <img src="fjords.jpg" class="w3-border w3-padding" alt="Norway"> -->
		</div>		
	</div>
	
	<br>
	
	<div class="w3-row-padding">
		<div class="w3-col m4">
			<label>Company</label>
			<select id="idgrn_company" class="w3-select">
				<?php 					
					if (isset($company_list)) {
						$data['company_list'] = $company_list;
						$this->load->view('company_option_list', $data);						
					}						
				?>													
			</select>		
		</div>
		<div class="w3-col m4">
			<label>Department</label>
			<select id="idgrn_dept" class="w3-select">
				<?php 					
					$this->load->view('department_option_list_user');
				?>													
			</select>	
		</div>
		<div class="w3-col m4">
			<label>Machine</label>
			<select id="idgrn_machine" class="w3-select">
				<?php 					
				if (isset($machine_list)) {
						$data['machine_list'] = $machine_list;
						$this->load->view('machine_option_list', $data);						
					}						
				?>													
			</select>	
		</div>
	</div>
	
	<br>	
	
	<div class="w3-row-padding">
		<div class="w3-col m4">
			<label>&nbsp;</label>
			<label class="w3-hide">Type</label>
			<select id="id_pur_type" class="w3-select w3-hide">
				<option value="L">Local Purcahse</option>
				<option value="I">Import</option>
			</select>
		</div>	
		<div class="w3-col m4">
			<label>Qty</label>
			<input id="idgrn_qty" class="w3-input w3-right-align" type="number" onchange="validate_GRN_Qty(this)">	
		</div>
		<div class="w3-col m2">
			<label>UOM</label>
			<select id="idgrn_uom" class="w3-select">
				<?php 					
					if (isset($uom_list)) {
						$data['uom_list'] = $uom_list;
						$this->load->view('uom_option_list', $data);						
					}						
				?>													
			</select>
		</div> 		
		<div class="w3-col m2">
			<br>
			<button class="w3-btn w3-green w3-right" class="w3-btn" onclick="insertRow_GRN()"><i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i> Add</button>
		</div> 							
	</div>	
	
	<br>	
	
	<div class="w3-row-padding">
		<div class="w3-col m12">
			<div class="w3-responsive">
				<table class="w3-table-all" ID="idGrnItemDet">
					<thead>
						<tr class="w3-cyan">
		
							<th style="display: none;">Item No</th>        							<!--0 1-->
							<th>Item Code</th>                       								<!--1 2-->
							<th>Item</th>                        									<!--2 3-->
							<th style="display: none;">Company No</th>								<!--3 4-->
							<th>Company</th>                                						<!--4 5-->
							<th style="display: none;">Dept ID</th>									<!--5 6-->
							<th>Department</th>                                						<!--6 7-->
							<th style="display: none;">Machine ID</th>								<!--7 8-->
							<th>Machine</th>                                						<!--8 9-->
							<th>UOM</th>															<!--9 10-->
							<th style="width:150px;" class="w3-right-align">Qty</th>				<!--10 11-->
							<th></th>																<!--11 12-->
							<th style="display: none;">Type</th>															<!--12 13-->
							<th style="display: none;">WH Code</th>									<!--13 14-->
							<th>W House</th>														<!--14 15-->
							
						</tr>
					</thead>
				</table>
			</div>	
		</div>
	</div>
	
	<br>	
	
	<div class="w3-row-padding">
		<div class="w3-col m12">
			<label>Free Text</label>
			<textarea id="idgrn_freetxt" class="w3-input"></textarea>
		</div>
	</div>		
	
	<br>

</div>

<div class="w3-panel w3-card-4 w3-light-grey">
	
	<div class="w3-row-padding">
		<div class="w3-col m8">
			<p></p>
		</div>
		<div class="w3-col m4">
			<br>
			<button id="idupdatebtm" class="w3-btn w3-green w3-right" class="w3-btn" onclick="update_GRN()"><i class="fa fa-floppy-o" aria-hidden="true"></i> Update</button>
		</div>
	</div>	

    <br>     

</div>

<div id="idmessage" class="w3-panel w3-red w3-display-container w3-round w3-card-4 w3-animate-bottom w3-hide">
  <span onclick="this.parentElement.style.display='none'"
  class="w3-button w3-red w3-large w3-display-topright">&times;</span>
  <h3 id="msgHed"></h3>
  <p id="msgDes"></p>
</div> 

<script type="text/javascript">

	n =  new Date();
	y = n.getFullYear();
	m = n.getMonth() + 1;
	d = n.getDate();
	$("#idgrn_date").val(d + "/" + m + "/" + y);

	function insertRow_GRN() {
	
	    /*Item No				<!--0 1-->
			Item Code			<!--1 2-->
		    Item				<!--2 3-->
			Company No			<!--3 4-->
			Company				<!--4 5-->
			Dept ID				<!--5 6-->
			Department			<!--6 7-->
			Machine ID			<!--7 8-->
			Machine				<!--8 9-->
			UOM					<!--9 10-->
			Qty					<!--10 11-->
			<th></th>			<!--11 12-->
			Type				<!--12 13-->
			WH Code				<!--13 14-->
			W House				<!--14 15-->*/
	
	    var item = $("#idgrn_item").val();
	    var itmdes = $("#idgrn_item option:selected").html();
	    var company = $("#idgrn_company").val();
	    var companydes = $("#idgrn_company option:selected").html();
	    var dept = $("#idgrn_dept").val();
	    var deptdes = $("#idgrn_dept option:selected").html();	 
	    var machine = $("#idgrn_machine").val();
	    var machineno = $("#idgrn_machine option:selected").html();	
	    var uom = $("#idgrn_uom").val();	    	    
	    var grnqty = parseFloat($("#idgrn_qty").val());	
	    var purtype = $("#id_pur_type").val();

	    var whcode = $("#id_w_house_code").val();
	    var whouse = $("#id_w_house").val();
	
		if (!($.isNumeric(grnqty)) || (grnqty <= 0))
		{
        	$("#idmessage").removeClass("w3-red");
        	$("#idmessage").removeClass("w3-yellow");
        	$("#idmessage").removeClass("w3-green");

        	$("#idmessage").addClass("w3-yellow");
        	$("#msgHed").text("Required");
        	$("#msgDes").text("MRN Qty Required!");
        	$("#idmessage").removeClass("w3-hide");
        	document.getElementById('idmessage').style.display = 'block'; 

            $('html, body').animate({
                scrollTop: $("#idmessage").offset().top
            }, 2000);
			return;
		}	
			
	    if(($.trim(item)).length == 0 || ($.trim(itmdes)).length == 0)
	    {
        	$("#idmessage").removeClass("w3-red");
        	$("#idmessage").removeClass("w3-yellow");
        	$("#idmessage").removeClass("w3-green");

        	$("#idmessage").addClass("w3-yellow");
        	$("#msgHed").text("Required");
        	$("#msgDes").text("Item Required!");
        	$("#idmessage").removeClass("w3-hide");
        	document.getElementById('idmessage').style.display = 'block';  			

            $('html, body').animate({
                scrollTop: $("#idmessage").offset().top
            }, 2000);
			return;
	    }

	    if(($.trim(company)).length == 0 || ($.trim(companydes)).length == 0)
	    {
        	$("#idmessage").removeClass("w3-red");
        	$("#idmessage").removeClass("w3-yellow");
        	$("#idmessage").removeClass("w3-green");

        	$("#idmessage").addClass("w3-yellow");
        	$("#msgHed").text("Required");
        	$("#msgDes").text("Company Required!");
        	$("#idmessage").removeClass("w3-hide");
        	document.getElementById('idmessage').style.display = 'block';  			

            $('html, body').animate({
                scrollTop: $("#idmessage").offset().top
            }, 2000);
			return;
	    }
	    	
	    if(($.trim(dept)).length == 0 || ($.trim(deptdes)).length == 0)
	    {
        	$("#idmessage").removeClass("w3-red");
        	$("#idmessage").removeClass("w3-yellow");
        	$("#idmessage").removeClass("w3-green");

        	$("#idmessage").addClass("w3-yellow");
        	$("#msgHed").text("Required");
        	$("#msgDes").text("Department Required!");
        	$("#idmessage").removeClass("w3-hide");
        	document.getElementById('idmessage').style.display = 'block';  			

            $('html, body').animate({
                scrollTop: $("#idmessage").offset().top
            }, 2000);
			return;
	    }

	    if(($.trim(machine)).length == 0 || ($.trim(machineno)).length == 0)
	    {
        	$("#idmessage").removeClass("w3-red");
        	$("#idmessage").removeClass("w3-yellow");
        	$("#idmessage").removeClass("w3-green");

        	$("#idmessage").addClass("w3-yellow");
        	$("#msgHed").text("Required");
        	$("#msgDes").text("Machine Required!");
        	$("#idmessage").removeClass("w3-hide");
        	document.getElementById('idmessage').style.display = 'block';  			

            $('html, body').animate({
                scrollTop: $("#idmessage").offset().top
            }, 2000);
			return;
	    }	

	    if(uom === undefined || uom === null || ($.trim(uom)).length == 0)
	    {
        	$("#idmessage").removeClass("w3-red");
        	$("#idmessage").removeClass("w3-yellow");
        	$("#idmessage").removeClass("w3-green");

        	$("#idmessage").addClass("w3-yellow");
        	$("#msgHed").text("Required");
        	$("#msgDes").text("UOM Required!");
        	$("#idmessage").removeClass("w3-hide");
        	document.getElementById('idmessage').style.display = 'block';  			

            $('html, body').animate({
                scrollTop: $("#idmessage").offset().top
            }, 2000);
			return;
	    }

	    if(($.trim(whcode)).length == 0 || ($.trim(whcode)).length == 0)
	    {
        	$("#idmessage").removeClass("w3-red");
        	$("#idmessage").removeClass("w3-yellow");
        	$("#idmessage").removeClass("w3-green");

        	$("#idmessage").addClass("w3-yellow");
        	$("#msgHed").text("Required");
        	$("#msgDes").text("Item Wherehouse Required!");
        	$("#idmessage").removeClass("w3-hide");
        	document.getElementById('idmessage').style.display = 'block';  			

            $('html, body').animate({
                scrollTop: $("#idmessage").offset().top
            }, 2000);
			return;
	    }	    
	
	    var item_split = item.split("~");
	    var company_split = company.split("~");
	    var dept_split = dept.split("~");
	    var machine_split = machine.split("~");
	
	    var table = document.getElementById("idGrnItemDet");
	
	    for (var i = 1, row; row = table.rows[i]; i++)
	    {
	        var itemid = table.rows[i].cells[0].innerHTML;
	        var companyid = table.rows[i].cells[3].innerHTML;
	        var deptid = table.rows[i].cells[5].innerHTML;
	        var machineid = table.rows[i].cells[7].innerHTML;
	        
	        //if ((itemid == item_split[0]) && (companyid == company_split[0]) && (deptid == dept_split[0]) && (machineid == machine_split[0]))
	        if ((itemid == item_split[0]) && (machineid == machine_split[0]))
	        {
	        	$("#idmessage").removeClass("w3-red");
	        	$("#idmessage").removeClass("w3-yellow");
	        	$("#idmessage").removeClass("w3-green");

	        	$("#idmessage").addClass("w3-yellow");
	        	$("#msgHed").text("Duplicate");
	        	$("#msgDes").text("Already Added!");
	        	$("#idmessage").removeClass("w3-hide");
	        	document.getElementById('idmessage').style.display = 'block';  			

	            $('html, body').animate({
	                scrollTop: $("#idmessage").offset().top
	            }, 2000);		        
	            return;
	        }
	    }  

	    /*Item No			<!--0 1-->
		Item Code			<!--1 2-->
		Item</th>			<!--2 3-->
		Company No			<!--3 4-->
		Company				<!--4 5-->
		Dept ID				<!--5 6-->
		Department			<!--6 7-->
		Machine ID			<!--7 8-->
		Machine				<!--8 9-->
		UOM					<!--9 10-->
		Qty					<!--10 11-->
		<th></th>			<!--11 12-->
		Type				<!--12 13-->
		WH Code				<!--13 14-->
		W House				<!--14 15-->*/
		
	    var row = table.insertRow(-1);
	    var cell1 = row.insertCell(0);
	    var cell2 = row.insertCell(1);
	    var cell3 = row.insertCell(2);
	    var cell4 = row.insertCell(3);
	    var cell5 = row.insertCell(4);
	    var cell6 = row.insertCell(5);
	    var cell7 = row.insertCell(6);
	    var cell8 = row.insertCell(7);
	    var cell9 = row.insertCell(8);
	    var cell10 = row.insertCell(9);
	    var cell11 = row.insertCell(10);
	    var cell12 = row.insertCell(11);
	    var cell13 = row.insertCell(12);
	    var cell14 = row.insertCell(13);
	    var cell15 = row.insertCell(14);	    
	    cell1.innerHTML = item_split[0];
	    cell1.style.display = "none";
	    cell2.innerHTML = item_split[1];
	    cell3.innerHTML = itmdes;
	    cell4.innerHTML = company_split[0];
	    cell4.style.display = "none";
	    cell5.innerHTML = companydes;
	    cell6.innerHTML = dept_split[0];
	    cell6.style.display = "none";
	    cell7.innerHTML = deptdes;
	    cell8.innerHTML = machine_split[0];
	    cell8.style.display = "none";
	    cell9.innerHTML = machineno;	   
	    cell10.innerHTML = uom;	    
	    cell11.innerHTML = grnqty;
	    cell11.style.textAlign = 'right';
	
	    var rowCount = document.getElementById("idGrnItemDet").rows.length;
	    rowCount--;
	
	    cell12.innerHTML = '<button class="w3-btn w3-red" onclick="removeRow(' + rowCount + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>';	

	    cell13.innerHTML = purtype;
	    cell13.style.display = "none";

	    cell14.innerHTML = whcode;
	    cell14.style.display = "none";
	    cell15.innerHTML = whouse;
	    
	    $("#idgrn_qty").val(0);		    
	
	}
	
	function removeRow(indx) {
        var table = document.getElementById("idGrnItemDet");
        table.deleteRow(indx);
        for (var i = 1, row; row = table.rows[i]; i++)
        {
            table.rows[i].cells[11].innerHTML = '<button class="w3-btn w3-red" onclick="removeRow(' + i + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>';
        }	    
	}

    function update_GRN(){

    	$("#idupdatebtm").hide();
    	
        var x = document.getElementById("idGrnItemDet").rows.length;

        if (x == 1)
        {
        	$("#idmessage").removeClass("w3-red");
        	$("#idmessage").removeClass("w3-yellow");
        	$("#idmessage").removeClass("w3-green");

        	$("#idmessage").addClass("w3-yellow");
        	$("#msgHed").text("Required");
        	$("#msgDes").text("Item Detail Required!");
        	$("#idmessage").removeClass("w3-hide");
        	document.getElementById('idmessage').style.display = 'block';

            $('html, body').animate({
                scrollTop: $("#idmessage").offset().top
            }, 2000);

            $("#idupdatebtm").show();
            
            return;
        }

        //var ReqHdrData = $.toJSON(storeHdrDet());
        //var TableData = $.toJSON(storeTableValue());
        
        var ReqHdrData = JSON.stringify(storeHdrDet());
        var TableData = JSON.stringify(storeTableValue());

        <?php echo "var url = '". base_url()."index.php/transaction/updatemrn';"; ?>

        //alert (JSON.stringify(TableData));

		$.ajax({
			type: "POST",
			url: url,
			data: ({pHdrData: ReqHdrData, pTableData: TableData}),
			dataType: 'json',
			cache: false,
			success: function(data){
                //alert ('ok');

                if (parseInt(data[0]) == 1){                	
                	$("#idmessage").removeClass("w3-red");
                	$("#idmessage").removeClass("w3-yellow");
                	$("#idmessage").removeClass("w3-green");

                	$("#idmessage").addClass("w3-green");
                	$("#msgHed").text("Ref No:" + data[1]);
                	$("#msgDes").text("Successfuly Updated!");
                	$("#idmessage").removeClass("w3-hide");
                	document.getElementById('idmessage').style.display = 'block';

                	//alert(data[3]);

                	clear_Form();

                	var print_url = '<?php echo base_url();?>index.php/print/3/'+data[3];
                	
                	var win = window.open(print_url, '_blank');
                	win.focus(); 

                	return;               	
                	
                }else{
                	$("#idmessage").removeClass("w3-red");
                	$("#idmessage").removeClass("w3-yellow");
                	$("#idmessage").removeClass("w3-green");

                	$("#idmessage").addClass("w3-red");
                	$("#msgHed").text("Error");
                	$("#msgDes").text(data[1]);
                	$("#idmessage").removeClass("w3-hide");
                	document.getElementById('idmessage').style.display = 'block';

                	$("#idupdatebtm").show();

                	return;
                }
                
			}
		});

        //alert (TableData);
        
    }

    function clear_Form(){
        var tableItem = document.getElementById("idGrnItemDet");
    
		for (var i = tableItem.rows.length - 1, row; row = tableItem.rows[i-1]; i--)
		{
		    if (i > 0)
			{
		    	tableItem.deleteRow(i);
			}
		}

	    $("#idgrn_qty").val(0);
	    $("#idgrn_freetxt").val('');
	    $('#idsap_refno').val('');
	    $('#id_w_house').val('');
	    
	    $("#idupdatebtm").show();		
    }

    function storeHdrDet() {
        var _ReqHdrData = new Array();

        var _reqdate = get_json_date($('#idreq_date').val()); 
        var _idgrn_freetxt = $('#idgrn_freetxt').val();
        //var _wherehouse = $('#id_wherehouse').val();
        var _wherehouse = '';
        var _sap_refno = $('#idsap_refno').val();  

        if (_sap_refno == 'undefined' || _sap_refno == null || _sap_refno.length == 0)
        {
        	_sap_refno = '';
        }

        _ReqHdrData[0] = { "remarks": _idgrn_freetxt, "insertuser": "<?php echo $this->session->userdata['logged_in']['username'];?>", "reqdate": _reqdate, "wherehouse": _wherehouse, "sap_refno": _sap_refno };

        return _ReqHdrData;
    }

    function storeTableValue() {
        var _TableData = new Array();

	    /*Item No			<!--0 1-->
		Item Code			<!--1 2-->
		Item</th>			<!--2 3-->
		Company No			<!--3 4-->
		Company				<!--4 5-->
		Dept ID				<!--5 6-->
		Department			<!--6 7-->
		Machine ID			<!--7 8-->
		Machine				<!--8 9-->
		UOM					<!--9 10-->
		Qty					<!--10 11-->
		<th></th>			<!--11 12-->
		Pur Type			<!--12 13-->
		WH Code				<!--13 14-->
		W House				<!--14 15-->*/
		
	    $('#idGrnItemDet tr').each(function(row, tr){
	    	_TableData[row]={
	            "itemno" : $(tr).find('td:eq(0)').text()
	            , "companyno" : $(tr).find('td:eq(3)').text()
	            , "departmentno" : $(tr).find('td:eq(5)').text()
	            , "machineno" : $(tr).find('td:eq(7)').text()
	            , "uom" : $(tr).find('td:eq(9)').text()	            
	            , "qty" : $(tr).find('td:eq(10)').text()
	            , "purtype" : $(tr).find('td:eq(12)').text()
	            , "whcode" : $(tr).find('td:eq(13)').text()
	        }
	    });

        _TableData.shift();

        return _TableData;

    }
</script>

<script src="<?php echo base_url();?>assets/js/filter.js"></script> 
<script src="<?php echo base_url();?>assets/js/printdoc.js"></script> 
