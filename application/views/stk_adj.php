<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="w3-panel w3-card-4 w3-light-grey">

	<br>

	<div class="w3-row-padding">
		<div class="w3-col m3">
	    	<label>Sap Ref No</label>
		    <input id="idsap_refno" class="w3-input" type="text">	
		</div>		
		<div class="w3-col m3">
			<label>ADJ No</label>
			<input id="idgrn_no" class="w3-input" type="text" disabled>	
        </div>
		<div class="w3-col m3">
	    	<label>Date</label>
		    <!-- <input id="idgrn_date" class="w3-input" type="text" disabled>  -->	
			<input id="idgrn_date" class="w3-input" type="date">
		</div>	
		<div class="w3-col m3">
			<label>Enter Doc. No</label>
			<div class="w3-bar">
			  <input id="id_reprint_no" class="w3-bar-item w3-input w3-border" type="text">
			  <button class="w3-bar-item w3-button w3-teal" onclick="Reprint_Doc(19, '<?php echo base_url();?>')"><i class="fa fa-print" aria-hidden="true"></i> Re-Print</button>
			</div>		
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
			<select id="idgrn_item" class="w3-select" onchange="read_stk_bal_item_com_global('<?php echo base_url()?>',this.value)">
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
			<select id="idgrn_company" class="w3-select" onchange="read_stk_bal_item_com(this.value)">
				<?php 					
					if (isset($company_list)) {
						$data['company_list'] = $company_list;
						$this->load->view('company_option_list', $data);						
					}						
				?>													
			</select>		
		</div>
		<div class="w3-col m2">
			<label>Department</label>
			<select id="idgrn_dept" class="w3-select">
				<?php 					
					$this->load->view('department_option_list_store');											
				?>													
			</select>	
		</div>
		<div class="w3-col m2">
			<label>Qty</label>
			<input id="idgrn_qty" class="w3-input w3-right-align" type="number">
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
			<label>U Cost</label>
			<input id="idgrn_cost" class="w3-input w3-right-align" type="number">
		</div>        		
	</div>
	
	<br>	
	
	<div class="w3-row-padding">
		<div class="w3-col m4">
			<span id="id_stk_bal_com" class="w3-badge w3-large w3-padding w3-red">0</span>
		</div> 
		<div class="w3-col m4">
			<label>Import CSV file: </label><input class="w3-btn" type="file" id="fileInputCSV" />
		</div> 	
		<div class="w3-col m4">
			<button class="w3-btn w3-green w3-right" class="w3-btn" onclick="insertRow_GRN()"><i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i> Add</button>
		</div> 
	</div>
	
        <script type="text/javascript">
        
            // check browser support
            // console.log(SimpleExcel.isSupportedBrowser);
            
            var fileInputCSV = document.getElementById('fileInputCSV'); 
                  
            // when local file loaded
            fileInputCSV.addEventListener('change', function (e) {
                
                // parse as CSV
                var file = e.target.files[0];
                var csvParser = new SimpleExcel.Parser.CSV();
                csvParser.setDelimiter(',');
                csvParser.loadFile(file, function () {
                    
                    // draw HTML table based on sheet data
                    var sheet = csvParser.getSheet();
                    var table = document.getElementById('idGrnItemDet');
                    table.innerHTML = "";
                    sheet.forEach(function (el, i) {                    
                        var row = document.createElement('tr');
                        el.forEach(function (el, i) {
                            var cell = document.createElement('td');
                            cell.innerHTML = el.value;
                            row.appendChild(cell);
                        });
                        table.appendChild(row);
                    });                                  
                    
                    // print to console just for quick testing
                    console.log(csvParser.getSheet(1));
                    console.log(csvParser.getSheet(1).getRow(1));
                    console.log(csvParser.getSheet(1).getColumn(2));
                    console.log(csvParser.getSheet(1).getCell(3, 1));
                    console.log(csvParser.getSheet(1).getCell(2, 3).value); 
                });
            });
            
        </script>
	
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
							<th>UOM</th>															<!--7 8-->
							<th style="width:100px;" class="w3-right-align">Cur Bal</th>			<!--8 9-->
							<th style="width:100px;" class="w3-right-align">Adj Qty</th>			<!--9 10-->
							<th style="width:100px;" class="w3-right-align">New Bal</th>			<!--10 11-->
							<th style="width:100px;" class="w3-right-align">U Cost</th>				<!--11 12-->
                            <th style="width:100px;" class="w3-right-align">Total</th>				<!--12 13-->											<!--7 8-->
							<th style="display: none;">WH Code</th>									<!--13 14-->
							<th>W House</th>														<!--14 15-->
                            <th></th>																<!--15 16-->
							
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
		<div class="w3-col m4">
			<p id="id_process"><i class="fa fa-cog w3-spin" aria-hidden="true"></i></p>
		</div>
		<div class="w3-col m8">
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

	$("#id_process").hide();

	n =  new Date();
	y = n.getFullYear();
	m = n.getMonth() + 1;
	d = n.getDate();
	$("#idgrn_date").val(d + "/" + m + "/" + y);

    function read_stk_bal_item_com(com) {
        var itm = $("#idgrn_item").val();
        var item_split = itm.split("~");
        var company_split = com.split("~");
        read_stk_bal_item('<?php echo base_url()?>', item_split[0], company_split[0]);
    }

	function insertRow_GRN() {
	
	    /*Item No				<!--0 1-->
			Item Code			<!--1 2-->
		    Item				<!--2 3-->
			Company No			<!--3 4-->
			Company				<!--4 5-->
			Dept ID				<!--5 6-->
			Department			<!--6 7-->
			UOM					<!--7 8-->
			Cur Bal				<!--8 9-->
			Adj Qty				<!--9 10-->
			New Bal				<!--10 11-->
            U Cost			    <!--11 12-->	
            Total				<!--12 13-->			
			WH Code				<!--13 14-->
			W House				<!--14 15-->
            <th></th>			<!--15 16-->*/
	
	    var item = $("#idgrn_item").val();
	    var itmdes = $("#idgrn_item option:selected").html();
	    var company = $("#idgrn_company").val();
	    var companydes = $("#idgrn_company option:selected").html();
	    var dept = $("#idgrn_dept").val();
	    var deptdes = $("#idgrn_dept option:selected").html();
	    var uom = $("#idgrn_uom").val();
	    var cur_bal = $("#id_stk_bal_com").text();	    	    
	    var grnqty = parseFloat($("#idgrn_qty").val());
        var ucost = parseFloat($("#idgrn_cost").val());	

	    var whcode = $("#id_w_house_code").val();
	    var whouse = $("#id_w_house").val();
	
		if (!($.isNumeric(grnqty)))
		{
            ucost = 0;
		}

		if (!($.isNumeric(grnqty)) || (grnqty == 0))
		{
        	$("#idmessage").removeClass("w3-red");
        	$("#idmessage").removeClass("w3-yellow");
        	$("#idmessage").removeClass("w3-green");

        	$("#idmessage").addClass("w3-yellow");
        	$("#msgHed").text("Required");
        	$("#msgDes").text("valied Qty Required!");
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
	
        var total = parseFloat(grnqty) * parseFloat(ucost);

        var new_bal = parseFloat(grnqty) + parseFloat(cur_bal);

	    var item_split = item.split("~");
	    var company_split = company.split("~");
	    var dept_split = dept.split("~");
	
	    var table = document.getElementById("idGrnItemDet");
	
	    for (var i = 1, row; row = table.rows[i]; i++)
	    {
	        var itemid = table.rows[i].cells[0].innerHTML;
	        var companyid = table.rows[i].cells[3].innerHTML;
	        var deptid = table.rows[i].cells[5].innerHTML;
	        
	        //if ((itemid == item_split[0]) && (companyid == company_split[0]) && (deptid == dept_split[0]) && (machineid == machine_split[0]))
	        if ((itemid == item_split[0]) && (companyid == company_split[0]))
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
	    Item				<!--2 3-->
		Company No			<!--3 4-->
		Company				<!--4 5-->
		Dept ID				<!--5 6-->
		Department			<!--6 7-->
		UOM					<!--7 8-->
		Cur Bal				<!--8 9-->
		Adj Qty				<!--9 10-->
		New Bal				<!--10 11-->
        U Cost			    <!--11 12-->	
        Total				<!--12 13-->			
		WH Code				<!--13 14-->
		W House				<!--14 15-->
        <th></th>			<!--15 16-->*/
		
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
        var cell16 = row.insertCell(15);
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
	    cell8.innerHTML = uom;	

	    cell9.innerHTML = cur_bal;
	    cell9.style.textAlign = 'right';
	    
	    cell10.innerHTML = grnqty;
	    cell10.style.textAlign = 'right';    

	    cell11.innerHTML = new_bal;
	    cell11.style.textAlign = 'right';  
	    
        cell12.innerHTML = ucost;
        cell12.style.textAlign = 'right';
        cell13.innerHTML = total;
        cell13.style.textAlign = 'right';             
        	
	    cell14.innerHTML = whcode;
	    cell14.style.display = "none";
	    cell15.innerHTML = whouse;

	    var rowCount = document.getElementById("idGrnItemDet").rows.length;
	    rowCount--;
	
	    cell16.innerHTML = '<button class="w3-btn w3-red" onclick="removeRow(' + rowCount + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>';
	    
	    $("#idgrn_qty").val(0);	
        $("#idgrn_cost").val(0);	    
	
	}
	
	function removeRow(indx) {
        var table = document.getElementById("idGrnItemDet");
        table.deleteRow(indx);
        for (var i = 1, row; row = table.rows[i]; i++)
        {
            table.rows[i].cells[15].innerHTML = '<button class="w3-btn w3-red" onclick="removeRow(' + i + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>';
        }	    
	}

    function update_GRN(){

    	$("#idupdatebtm").hide();
    	$("#id_process").show();
    	
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

            $("#id_process").hide();
            return;
        }

        //var ReqHdrData = $.toJSON(storeHdrDet());
        //var TableData = $.toJSON(storeTableValue());           		 
        
        var ReqHdrData = JSON.stringify(storeHdrDet());

        //alert('ok'); 
        
        var TableData = JSON.stringify(storeTableValue());       

        <?php echo "var url = '". base_url()."index.php/transaction/updateadj';"; ?>

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

                	var print_url = '<?php echo base_url();?>index.php/print/19/'+data[3];
                	
                	var win = window.open(print_url, '_blank');
                	win.focus(); 

                	$("#id_process").hide();

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

                	$("#id_process").hide();
                	
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
        $("#idgrn_cost").val(0);
	    $("#idgrn_freetxt").val('');
	    $('#idsap_refno').val('');
	    
	    $("#idupdatebtm").show();		
    }

    function storeHdrDet() {
        var _ReqHdrData = new Array();

        var _idgrn_freetxt = $('#idgrn_freetxt').val();
        var _sap_refno = $('#idsap_refno').val();        

        //alert($('#idreq_date').val()); 
        
        var _adj_date = get_json_date($('#idgrn_date').val());       

        if (_sap_refno == 'undefined' || _sap_refno == null || _sap_refno.length == 0)
        {
        	_sap_refno = '';
        }

        _ReqHdrData[0] = { "remarks": _idgrn_freetxt, "insertuser": "<?php echo $this->session->userdata['logged_in']['username'];?>", "sap_refno": _sap_refno, "adjdate": _adj_date, };

        return _ReqHdrData;
    }

    function storeTableValue() {
        var _TableData = new Array();

	    /*Item No			<!--0 1-->
		Item Code			<!--1 2-->
	    Item				<!--2 3-->
		Company No			<!--3 4-->
		Company				<!--4 5-->
		Dept ID				<!--5 6-->
		Department			<!--6 7-->
		UOM					<!--7 8-->
		Cur Bal				<!--8 9-->
		Adj Qty				<!--9 10-->
		New Bal				<!--10 11-->
        U Cost			    <!--11 12-->	
        Total				<!--12 13-->			
		WH Code				<!--13 14-->
		W House				<!--14 15-->
        <th></th>			<!--15 16-->*/

		var i = 0;
		
	    $('#idGrnItemDet tr').each(function(row, tr){

			var itemcode = $(tr).find('td:eq(1)').text();
	    	
			if (itemcode.length > 0){
			   
		    	_TableData[i]={
		            "itemno" : $(tr).find('td:eq(0)').text()
		            , "itemcode" : $(tr).find('td:eq(1)').text()
		            , "companyno" : $(tr).find('td:eq(3)').text()
		            , "departmentno" : $(tr).find('td:eq(5)').text()
		            , "uom" : $(tr).find('td:eq(7)').text()	
		            , "curbal" : $(tr).find('td:eq(8)').text()            
		            , "qty" : $(tr).find('td:eq(9)').text()
		            , "newbal" : $(tr).find('td:eq(10)').text()
	                , "unitcost" : $(tr).find('td:eq(11)').text()
	                , "total" : $(tr).find('td:eq(12)').text()
		            , "whcode" : $(tr).find('td:eq(13)').text()	            
		        }

				i++;
				
			}
			
	    });

        _TableData.shift();

        return _TableData;

    }
</script>

<script src="<?php echo base_url();?>assets/js/filter.js"></script> 
<script src="<?php echo base_url();?>assets/js/printdoc.js"></script> 
