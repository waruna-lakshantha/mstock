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
			<label>GRN No</label>
			<input id="idgrn_no" class="w3-input" type="text" disabled>	
        </div>
		<div class="w3-col m3">
	    	<label>Date</label>
		    <input id="idgrn_date" class="w3-input" type="text" disabled>	
		</div>	
		<div class="w3-col m3">
			<label>Enter Doc. No</label>
			<div class="w3-bar">
			  <input id="id_reprint_no" class="w3-bar-item w3-input w3-border" type="text">
			  <button class="w3-bar-item w3-button w3-teal" onclick="Reprint_Doc(2, '<?php echo base_url();?>')"><i class="fa fa-print" aria-hidden="true"></i> Re-Print</button>
			</div>		
		</div>
	</div>
	
	<br>
	
	<div class="w3-row-padding">
		<div class="w3-col m6">
			<label>Store Location</label>
			<select id="id_store_loc" class="w3-select">
				<?php 					
					$this->load->view('store_option_list');										
				?>	
			</select>
        </div>
		<div class="w3-col m6">
	    	<!-- <label>Wherehouse</label>
			<select id="id_wherehouse" class="w3-select"> -->
				<?php 					
					//$this->load->view('wherehouse_option_list');										
				?>	
			<!-- </select> -->
		</div>	
	</div>	
	
	<br>
	
	<div class="w3-row-padding">
		<div class="w3-col m12">
			<div id="idPrBalTable" class="w3-responsive">
			<?php
                echo $this->pr->read_pr_balance_html_table();
			?>
			</div>	
		</div>
	</div>
	
	<br>
	
	<div class="w3-row-padding">
		<div class="w3-col m4">
			<p></p>
		</div>
		<div class="w3-col m4">
			<p></p>
		</div>
		<div class="w3-col m4">
			<!-- <label>Total Cost</label>  -->
			<input id="idgrn_totcost" class="w3-input w3-right-align" type="hidden">	
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

	format_table();
	//filter_company();
	//filter_department();
	//filter_mrn();
	
	function format_table(){

		<?php
			/*pr_hdr.no,							0 
			pr_hdr.prno as `PR No`, 				1
			pr_hdr.date as `Date`, 					2
			pr_dtl_proceed.itemno, 					3
			item.itemcode as `Item Code`,			4
			item.description as `Item`, 			5
			pr_dtl_proceed.companyno, 				6
			company.description as Company, 		7
			pr_dtl_proceed.departmentno,			8
			department.description as Department, 	9
			pr_dtl_proceed.machineno, 				10
			machine.machineno as Machine, 			11
			0 as `Rec Qty`, 						12
			0 as `U Cost`,							13
			0 as `Total Cost`, 						14
			'-' as `Rec Date`, 						15	
			pr_dtl_proceed.uom as Uom_hide, 		16
			(pr_dtl_proceed.qty - pr_dtl_proceed.grnqty) as Bal,	17 
			pr_dtl_proceed.uom as Uom, 				18
			pr_dtl_proceed.dtlno, 					19
			pr_dtl_proceed.supplierno, 				20
			pr_dtl_proceed.pono						21
			wherehouse.whcode as Wherehouse			22
			item.whcode								23*/
		?>
		
	    var table = document.getElementById("tblGRN");

        for (var i = 0, row; row = table.rows[i]; i++)
        {
        	table.rows[i].cells[0].style.display = "none";
        	table.rows[i].cells[3].style.display = "none";
        	table.rows[i].cells[6].style.display = "none";
        	table.rows[i].cells[8].style.display = "none";
        	table.rows[i].cells[10].style.display = "none";
        	table.rows[i].cells[13].style.display = "none";
            table.rows[i].cells[14].style.display = "none";        	
            table.rows[i].cells[16].style.display = "none";
            table.rows[i].cells[23].style.display = "none";

        	if (i > 0){
        		var celcon = "<input style=\"width:100px;\" class=\"w3-input w3-right-align\" type=\"number\" onchange=\"validate_Qty(this, "+ i + ")\">";
        		table.rows[i].cells[12].innerHTML = celcon; 

        		//var celcon = "<input style=\"width:100px;\" class=\"w3-input w3-right-align\" type=\"number\" onchange=\"cal_Total()\">";
        		//table.rows[i].cells[13].innerHTML = celcon; 

        		var celcon = "<input style=\"width:150px;\" class=\"w3-input\" type=\"date\">";
        		table.rows[i].cells[15].innerHTML = celcon;         		
        	}
        	
        }    
	}

	function validate_Qty(valbox, ri){
		var table = document.getElementById("tblGRN");
		var balqty = table.rows[ri].cells[17].innerHTML;
		var row = table.rows[ri];
		var unitcost = table.rows[ri].cells[13].innerHTML;

		if (unitcost.length == 0){
			unitcost = 0;
		}

		if((parseFloat(valbox.value) > parseFloat(balqty)) || (parseFloat(valbox.value) <= 0)){
        	$("#idmessage").removeClass("w3-red");
        	$("#idmessage").removeClass("w3-yellow");
        	$("#idmessage").removeClass("w3-green");

        	$("#idmessage").addClass("w3-yellow");
        	$("#msgHed").text("Required");
        	$("#msgDes").text("Invalied Qty!");
        	$("#idmessage").removeClass("w3-hide");

            $(row).find('td:eq(12)').find('input').val(0);

        	document.getElementById('idmessage').style.display = 'block';
        	
            $('html, body').animate({
                scrollTop: $("#idmessage").offset().top
            }, 2000);
        	
			return;
		}

		var totcost = parseFloat(valbox.value) * parseFloat(unitcost);

		table.rows[ri].cells[14].innerHTML = totcost;
		 
		cal_Total();
	}
	
	function cal_Total(){
        var table = document.getElementById("tblGRN");
        var tot = 0;
        var linetot = 0;
        for (var i = 1, row; row = table.rows[i]; i++)
        {
        	var qty = $(table.rows[i]).find('td:eq(12)').find('input').val();
        	var unitcost = table.rows[i].cells[13].innerHTML;

    		if (qty.length == 0){
    			qty = 0;
    		}
        	
    		if (unitcost.length == 0){
    			unitcost = 0;
    		}
        	
        	linetot = parseFloat(qty) * parseFloat(unitcost);

        	table.rows[i].cells[14].innerHTML = linetot;
            
        	tot += linetot;            
        }
        $("#idgrn_totcost").val(tot);
	}

    function update_GRN(){

    	$("#idupdatebtm").hide();

        //var ReqHdrData = $.toJSON(storeHdrDet());
        //var TableData = $.toJSON(storeTableValue());
        
        var _sap_refno = $('#idsap_refno').val();

        if (_sap_refno == 'undefined' || _sap_refno == null || _sap_refno.length == 0)
        {
        	$("#idmessage").removeClass("w3-red");
        	$("#idmessage").removeClass("w3-yellow");
        	$("#idmessage").removeClass("w3-green");

        	$("#idmessage").addClass("w3-yellow");
        	$("#msgHed").text("Required");
        	$("#msgDes").text("SAP Reference No Required!");
        	$("#idmessage").removeClass("w3-hide");            

        	document.getElementById('idmessage').style.display = 'block';

            $('html, body').animate({
                scrollTop: $("#idmessage").offset().top
            }, 2000);

            $("#idupdatebtm").show();
            
            return;
        }
        
        var ReqHdrData = JSON.stringify(storeHdrDet());
        
        var TableData = storeTableValue();

        //alert(TableData.length);

        //return;

        if (TableData.length == 0)
        {
        	$("#idmessage").removeClass("w3-red");
        	$("#idmessage").removeClass("w3-yellow");
        	$("#idmessage").removeClass("w3-green");

        	$("#idmessage").addClass("w3-yellow");
        	$("#msgHed").text("Required");
        	$("#msgDes").text("Invalied Item Detail! Received Qty and Cost Required!");
        	$("#idmessage").removeClass("w3-hide");            

        	document.getElementById('idmessage').style.display = 'block';

            $('html, body').animate({
                scrollTop: $("#idmessage").offset().top
            }, 2000);

            $("#idupdatebtm").show();
            
            return;
        }

        TableData = JSON.stringify(TableData);

        //alert(TableData);

        <?php echo "var url = '". base_url()."index.php/transaction/updategrn';"; ?>

        //alert (JSON.stringify(TableData));

		$.ajax({
			type: "POST",
			url: url,
			data: ({pHdrData: ReqHdrData, pTableData: TableData}),
			dataType: 'json',
			cache: false,
			success: function(data){
                //alert (data[1]);

                if (parseInt(data[0]) == 1){    
                    	
                	$("#idmessage").removeClass("w3-red");
                	$("#idmessage").removeClass("w3-yellow");
                	$("#idmessage").removeClass("w3-green");

                	$("#idmessage").addClass("w3-green");
                	$("#msgHed").text("Ref No:" + data[1]);
                	$("#msgDes").text("Successfuly Updated!");
                	$("#idmessage").removeClass("w3-hide");
                	document.getElementById('idmessage').style.display = 'block';

                    $("#idPrBalTable").html(data[2]);

                    format_table();

                	clear_Form();

                	var print_url = '<?php echo base_url();?>index.php/print/2/'+data[3];
                	
                	var win = window.open(print_url, '_blank');
                	win.focus();                	
                	
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
                }
                
			}
		});

        //alert (TableData);
        
    }

    function clear_Form(){
        var table = document.getElementById("tblGRN");
    
        for (var i = 1, row; row = table.rows[i]; i++)
        {
            $(row).find('td:eq(12)').find('input').val(0);
            //$(row).find('td:eq(13)').find('input').val(0);
            $(row).find('td:eq(14)').find('input').val(0);
        }

		$("#idgrn_totcost").val(0);
	    $("#idgrn_qty").val(0);	
	    $("#idgrn_ucost").val(0);
	    $("#idgrn_freetxt").val('');
	    $('#idsap_refno').val('');
    
	    $("#idupdatebtm").show();		
    }

    function storeHdrDet() {
        var _ReqHdrData = new Array();

        //var _date = $('#idgrn_date').val();
        //_date = replaceAll(_date, "/", "_");
        var _idgrn_freetxt = $('#idgrn_freetxt').val();
        var _idgrn_totcost = $('#idgrn_totcost').val();
        var _store_loc = $('#id_store_loc').val(); 
        //var _wherehouse = $('#id_wherehouse').val();
        var _wherehouse = ''; 
		var _sap_refno = $('#idsap_refno').val();

        _ReqHdrData[0] = { "remarks": _idgrn_freetxt, "totalvalue": _idgrn_totcost, "insertuser": "<?php echo $this->session->userdata['logged_in']['username'];?>", "store_loc": _store_loc, "wherehouse": _wherehouse, "sap_refno": _sap_refno };

        return _ReqHdrData;
    }

    function storeTableValue() {
        var _TableData = new Array();

		<?php
			/* pr_hdr.no,							0 
			pr_hdr.prno as `PR No`, 				1
			pr_hdr.date as `Date`, 					2
			pr_dtl_proceed.itemno, 					3
			item.itemcode as `Item Code`,			4
			item.description as `Item`, 			5
			pr_dtl_proceed.companyno, 				6
			company.description as Company, 		7
			pr_dtl_proceed.departmentno,			8
			department.description as Department, 	9
			pr_dtl_proceed.machineno, 				10
			machine.machineno as Machine, 			11
			0 as `Rec Qty`, 						12
			0 as `U Cost`,							13
			0 as `Total Cost`, 						14
			'-' as `Rec Date`, 						15	
			pr_dtl_proceed.uom as Uom_hide, 		16
			(pr_dtl_proceed.qty - pr_dtl_proceed.grnqty) as Bal,	17 
			pr_dtl_proceed.uom as Uom, 				18
			pr_dtl_proceed.dtlno, 					19
			pr_dtl_proceed.supplierno, 				20
			pr_dtl_proceed.pono						21
			wherehouse.whcode as Wherehouse			22
			item.whcode								23*/
		?>

		var ai = 0;
		var rowi = 0;
		    
	    $('#tblGRN tr').each(function(row, tr){

	    	if(rowi > 0){
		    	var reqqty = $(tr).find('td:eq(12)').find('input').val();            
		    	var totcost = $(tr).find('td:eq(14)').text(); 

                var recdate = get_json_date($(tr).find('td:eq(15)').find('input').val());
		    	
		    	if((isNaN(reqqty) == false) && (reqqty > 0))
		    	{		                
	
		    	    if((isNaN(totcost) == false) && (totcost > 0))
		    	    {			    	    
			    	                        	    
			    	    _TableData[ai]={
			                "itemno" : $(tr).find('td:eq(3)').text()
			                , "companyno" : $(tr).find('td:eq(6)').text()
			                , "departmentno" : $(tr).find('td:eq(8)').text()
			                , "qty" : $(tr).find('td:eq(12)').find('input').val()
			                , "uom" : $(tr).find('td:eq(16)').text()	            		            
			                , "unitcost" : $(tr).find('td:eq(13)').text()
			                , "total" : $(tr).find('td:eq(14)').text()
			                , "prno" : $(tr).find('td:eq(0)').text()
			                , "machineno" : $(tr).find('td:eq(10)').text()
			                , "dtlno" : $(tr).find('td:eq(19)').text()
			                , "supno" : $(tr).find('td:eq(20)').text()
			                , "pono" : $(tr).find('td:eq(21)').text()
			                , "recdate" : recdate
			                , "whcode" : $(tr).find('td:eq(23)').text()
			            }
	
			    	    ai++;
	
	                }
		    	}
		    }

	    	rowi++;
	    	
	    });

        //_TableData.shift();

        return _TableData;

    }
</script>
<script src="<?php echo base_url();?>assets/js/printdoc.js"></script> 
