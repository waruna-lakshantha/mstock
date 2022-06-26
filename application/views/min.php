<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	$this->store->delete_hold_stock('MIN');
?>

<div class="w3-panel w3-card-4 w3-light-grey">

	<br>

	<div class="w3-row-padding">
		<div class="w3-col m3">
	    	<label>Sap Ref No</label>
		    <input id="idsap_refno" class="w3-input" type="text">	
		</div>
		<div class="w3-col m3">
			<label>MIN No</label>
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
			  <button class="w3-bar-item w3-button w3-teal" onclick="Reprint_Doc(4, '<?php echo base_url();?>')"><i class="fa fa-print" aria-hidden="true"></i> Re-Print</button>
			</div>		
		</div>		
	</div>	
	
	<br>
	
	<div class="w3-row-padding">
		<div class="w3-col m4">
			<!--  <label>Company</label>
			<select id="idgrn_company" class="w3-select" onchange="filter_company()"> -->
				<?php 					
					/*if (isset($company_list)) {
						$data['company_list'] = $company_list;
						$this->load->view('company_option_list', $data);						
					}*/						
				?>													
			<!--  </select> -->
			<p></p>
		</div>
		<div class="w3-col m4">
			<!-- <label>Department</label>
			<select id="idgrn_dept" class="w3-select" onchange="filter_department()"> -->
				<?php 					
					/*if (isset($user_dept_list)) {
						$data['dept_list'] = $dept_list;
						$this->load->view('department_option_list', $data);						
					}*/						
				?>													
			<!--  </select>	 -->
			<p></p>
		</div>
		<div class="w3-col m4">
			<label>MRN No</label>
		    <input id="idmin_mrn" class="w3-input" type="text" onkeyup="filter_mrn()">
		</div>					
	</div> 
	
	<br> 
	
	<div class="w3-row-padding">
		<div id="idMRNBalTable" class="w3-col m12 w3-responsive">
			<?php 									
				echo $this->mrn->read_mrn_balance_html_table();
			?>			 
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
			<button id="idupdatebtm" class="w3-btn w3-green w3-right" class="w3-btn" onclick="update_MIN()"><i class="fa fa-floppy-o" aria-hidden="true"></i> Update</button>
		</div>
	</div>	

    <br> 	

</div>

<!-- 
	no				0
	MRN No			1
	Date			2
	itemno			3
	itemcode		4
	Item			5
	companyno		6
	Company			7
	departmentno	8
	Department		9
	machineno		10
	Machine			11
	Uom				12
	Bal				13
	Issue Qty		14
 -->

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
	filter_company();
	filter_department();
	filter_mrn();
	
	function format_table(){
	    var table = document.getElementById("tblMIN");

        for (var i = 0, row; row = table.rows[i]; i++)
        {
        	table.rows[i].cells[0].style.display = "none";
        	table.rows[i].cells[3].style.display = "none";
        	table.rows[i].cells[6].style.display = "none";
        	table.rows[i].cells[8].style.display = "none";
        	table.rows[i].cells[10].style.display = "none";
        	
			if (i > 0){

				var mrnno, itemno, machineno, inputid, divid, pendingqty;

				mrnno = table.rows[i].cells[0].innerHTML;
				itemno = table.rows[i].cells[3].innerHTML;
				machineno = table.rows[i].cells[10].innerHTML;	
				pendingqty = table.rows[i].cells[13].innerHTML;	

				inputid = 'idissueqty_' + mrnno + '_' + itemno + '_' + machineno;

				divid = 'idissuediv_' + mrnno + '_' + itemno + '_' + machineno;
				
				var con = "<div class=\"w3-dropdown-hover w3-right\"><div class=\"w3-panel w3-margin-left\" id=\"" + inputid + "\" value=\"0\" onmouseover=\"Load_Stock(this, '" + divid + "', " + pendingqty + ", " + itemno + ", '" + mrnno + "', " + i + ", '" + inputid + "')\">0</div>" +
	        	    "<div class=\"w3-dropdown-content w3-bar-block w3-card-4\" style=\"right:0\">" +
	        	      "<div id=\"" + divid + "\" class=\"w3-container\">" +	        	      
	        	      "</div>" +
	        	    "</div>" +
				"</div>";

				table.rows[i].cells[14].innerHTML = con;
			}
        	
        	//table.rows[i].cells[13].contentEditable = "true";
        }	    
	}
	
	function Load_Stock(txtbox, divid, penqty, itemcode, mrnno, maintblrowindex, maintblissueid){

		var _ReqHdrData = new Array();

        _ReqHdrData[0] = { "itemno": itemcode, "mrnno": mrnno, "insertuser": "<?php echo $this->session->userdata['logged_in']['username'];?>" };		
		
        var ReqHdrData = JSON.stringify(_ReqHdrData);

        <?php echo "var url = '". base_url()."index.php/read/itemstockbalance';"; ?>

        //alert (ReqHdrData);

        <?php 
            /*stockmaster.comno                      0
            company.description as `Company`        1
            stockmaster.storeno                     2
            storelocation.code as `Store Code`      3
            storelocation.description as `Store`    4
            stockmaster.itemno                      5
            item.itemcode as `Item Code`            6
            item.description as `Item`              7					
            stockmaster.balqty as `Bal Qty`         8
            0 as `Issue Qty`                        9*/
        ?>

		$.ajax({
			type: "POST",
			url: url,
			data: ({pHdrData: ReqHdrData}),
			dataType: 'json',
			cache: false,
			success: function(data){
                //alert (data);

                $('#'+divid).html(data);

				var tblname = "tbl_"+itemcode+"_"+mrnno;
                
                var table = document.getElementById(tblname);
                
                for (var i = 0, row; row = table.rows[i]; i++)
                {
                	table.rows[i].cells[0].style.display = "none";
                	table.rows[i].cells[2].style.display = "none";
                    table.rows[i].cells[5].style.display = "none";

					if (i > 0){
	                    var celcon = "<input class=\"w3-input w3-right-align\" type=\"number\" onchange=\"validate_Issue_Qty(this, " + penqty + ", '" + tblname + "', " + maintblrowindex + ", " + i + ", '" + maintblissueid + "')\" style=\"width:100px;\"\>";                                    
						table.rows[i].cells[9].innerHTML = celcon;  
					}                  
					
                    //table.rows[i].cells[9].contentEditable = "true";
                }               

			}
		});
	}

	function validate_Issue_Qty(txtbox, valqty, tablename, maintblrowindex, loctablerowid, maintblissueid){
		if (!($.isNumeric(txtbox.value)) || (txtbox.value <= 0))
		{
        	$("#idmessage").removeClass("w3-red");
        	$("#idmessage").removeClass("w3-yellow");
        	$("#idmessage").removeClass("w3-green");

        	$("#idmessage").addClass("w3-yellow");
        	$("#msgHed").text("Invalied Qty");
        	$("#msgDes").text("Valied Issue Qty Required! " + txtbox.value + " not Accepted!");
        	$("#idmessage").removeClass("w3-hide");
        	document.getElementById('idmessage').style.display = 'block'; 

            $('html, body').animate({
                scrollTop: $("#idmessage").offset().top
            }, 2000);
			return;
		}

		var loctable = document.getElementById(tablename);
		
		var locstockbal = loctable.rows[loctablerowid].cells[8].innerHTML;
		var company = loctable.rows[loctablerowid].cells[1].innerHTML;
		var store = loctable.rows[loctablerowid].cells[4].innerHTML;

		if (parseFloat(txtbox.value) > parseFloat(locstockbal))
		{
        	$("#idmessage").removeClass("w3-red");
        	$("#idmessage").removeClass("w3-yellow");
        	$("#idmessage").removeClass("w3-green");

        	$("#idmessage").addClass("w3-yellow");
        	$("#msgHed").text("Invalied Qty");
        	$("#msgDes").text("Valied Issue Qty Required! Stock Balance : " + company + "/" + store + " = " + locstockbal + ". Your Value " + txtbox.value + " not Accepted!");
        	$("#idmessage").removeClass("w3-hide");
        	document.getElementById('idmessage').style.display = 'block'; 

            $('html, body').animate({
                scrollTop: $("#idmessage").offset().top
            }, 2000);
			return;
		}

		var totlocissueval = 0;
		
        for (var i = 1, row; row = loctable.rows[i]; i++)
        {
			var issueval = parseFloat($((row.cells[9])).find('input').val());
			if ($.isNumeric(issueval)){
				totlocissueval += parseFloat(issueval);
			}
        }

		if (parseFloat(totlocissueval) > parseFloat(valqty))
		{
        	$("#idmessage").removeClass("w3-red");
        	$("#idmessage").removeClass("w3-yellow");
        	$("#idmessage").removeClass("w3-green");

        	$("#idmessage").addClass("w3-yellow");
        	$("#msgHed").text("Invalied Qty");
        	$("#msgDes").text("Valied Issue Qty Required! Requested Qty : " + valqty + ". You Try to Issue " + totlocissueval);
        	$("#idmessage").removeClass("w3-hide");
        	document.getElementById('idmessage').style.display = 'block'; 

            $('html, body').animate({
                scrollTop: $("#idmessage").offset().top
            }, 2000);
			return;
		}

		$('#'+maintblissueid).text(totlocissueval);
		
		var maintable = document.getElementById('tblMIN');

		var _TableData = new Array();

		var d = new Date()
		
        var _date = d.toJSON();
		
	    $('#' + tablename +'  tr').each(function(row, tr){
	  		    
	    	_TableData[row]={
	    	    "guid" : "<?php echo $guid;?>"
	            ,"comno" : $(tr).find('td:eq(0)').text()
	            , "storeno" : $(tr).find('td:eq(2)').text()
	            , "itemno" : $(tr).find('td:eq(5)').text()
	            , "qty" : $(tr).find('td:eq(9)').find('input').val()            
	            , "datetime" : _date
	            , "user" : "<?php echo $this->session->userdata['logged_in']['username'];?>"
	            , "doctype" : "MIN"
	            , "docno" : maintable.rows[maintblrowindex].cells[0].innerHTML
	            , "docref" : maintable.rows[maintblrowindex].cells[1].innerHTML
	            , "refcomno" : maintable.rows[maintblrowindex].cells[6].innerHTML
	            , "departmentno" : maintable.rows[maintblrowindex].cells[8].innerHTML
	            , "machineno" : maintable.rows[maintblrowindex].cells[10].innerHTML
	        }
	    });		    
	    
	    _TableData.shift();	

	    var TableData = JSON.stringify(_TableData);

	    <?php echo "var url = '". base_url()."index.php/transaction/updateholditem';"; ?>

		$.ajax({
			type: "POST",
			url: url,
			data: ({pTableData: TableData}),
			dataType: 'json',
			cache: false,
			success: function(data){

                if (parseInt(data[0]) == 1){
                	//alert (data[0]);
                }else{

                	$('#'+maintblissueid).text("0");
                    
                	$("#idmessage").removeClass("w3-red");
                	$("#idmessage").removeClass("w3-yellow");
                	$("#idmessage").removeClass("w3-green");

                	$("#idmessage").addClass("w3-red");
                	$("#msgHed").text("Error");
                	$("#msgDes").text(data[1]);
                	$("#idmessage").removeClass("w3-hide");
                	document.getElementById('idmessage').style.display = 'block';
                	
                }
                
			}
		});
		
		/*guid
		comno
		storeno
		itemno
		qty
		datetime
		user
		doctype
		docno
		docref*/		
		
	}

	function update_MIN(){
    	$("#idupdatebtm").hide();
        
		var TableData = storeTableValue();        

		if(TableData.length <= 0){
        	$("#idmessage").removeClass("w3-red");
        	$("#idmessage").removeClass("w3-yellow");
        	$("#idmessage").removeClass("w3-green");

        	$("#idmessage").addClass("w3-red");
        	$("#msgHed").text("Error");
        	$("#msgDes").text("Issue Qty Required!");
        	$("#idmessage").removeClass("w3-hide");
        	document.getElementById('idmessage').style.display = 'block';

            $('html, body').animate({
                scrollTop: $("#idmessage").offset().top
            }, 2000);

        	$("#idupdatebtm").show();
		}
        
        var ReqHdrData = JSON.stringify(storeHdrDet());

        <?php echo "var url = '". base_url()."index.php/transaction/updatemin';"; ?>

		$.ajax({
			type: "POST",
			url: url,
			data: ({pHdrData: ReqHdrData}),
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

                    $('html, body').animate({
                        scrollTop: $("#idmessage").offset().top
                    }, 2000);

                    $("#idMRNBalTable").html(data[2]);

                    format_table();

                	clear_Form();

                	var print_url = '<?php echo base_url();?>index.php/print/4/'+data[3];
                	
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

                    $('html, body').animate({
                        scrollTop: $("#idmessage").offset().top
                    }, 2000);

                	$("#idupdatebtm").show();
                }
                
			}
		});		
	}

    function clear_Form(){
	    $("#idgrn_freetxt").val('');	    
	    $("#idupdatebtm").show();
	    $('#idsap_refno').val('');
    }

    function storeHdrDet() {
        var _ReqHdrData = new Array();

		var d = new Date()		
        var _date = d.toJSON();

		var _company_no = $('#idgrn_company').val();
		var _department_no = $('#idgrn_dept').val();
        var _idgrn_freetxt = $('#idgrn_freetxt').val();
        var _sap_refno = $('#idsap_refno').val();  

        if (_sap_refno == 'undefined' || _sap_refno == null || _sap_refno.length == 0)
        {
        	_sap_refno = '';
        }

        _ReqHdrData[0] = { "guid": "<?php echo $guid;?>", "company_no":_company_no, "department_no":_department_no, "remarks": _idgrn_freetxt, "date": _date, "insertuser": "<?php echo $this->session->userdata['logged_in']['username'];?>", "sap_refno": _sap_refno };

        return _ReqHdrData;
    } 
    
    function storeTableValue() {
        var _TableData = new Array();

        <?php 
            /*no        0
            MRN No      1
            Date        2
            itemno      3
            itemcode    4
            Item        5
            companyno   6
            Company     7
            departmentno    8
            Department  9
            machineno   10
            machineno   11
            Uom         12
            Bal         13
            Issue Qty   14*/ 
        ?>
		
        var mi = 0;

	    $('#tblMIN tr').each(function(row, tr){

	    	inputid = 'idissueqty_' + $(tr).find('td:eq(0)').text() + '_' + $(tr).find('td:eq(3)').text() + '_' + $(tr).find('td:eq(10)').text();	    		    
	    	
            if(isNaN($('#'+inputid).text()) == false && $('#'+inputid).text() > 0)
            {
	    	    _TableData[mi]={
	    	    	    
	                "qty" : $('#'+inputid).text()
	            }
                
                mi++;
            }
	    });

        return _TableData;

    }    
	
	function filter_company() {
		  var filter, table, tr, td, i;
		  filter = $("#idgrn_company option:selected").html();
		  filter = filter.toUpperCase();
		  table = document.getElementById("tblMIN");
		  tr = table.getElementsByTagName("tr");
		  for (i = 0; i < tr.length; i++) {
		    td = tr[i].getElementsByTagName("td")[6];
		    if (td) {
		      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
		        tr[i].style.display = "";
		      } else {
		        tr[i].style.display = "none";
		      }
		    }
		  }
		}

	function filter_department() {
		  var filter, table, tr, td, i;
		  filter = $("#idgrn_dept option:selected").html();
		  filter = filter.toUpperCase();
		  table = document.getElementById("tblMIN");
		  tr = table.getElementsByTagName("tr");
		  for (i = 0; i < tr.length; i++) {
		    td = tr[i].getElementsByTagName("td")[8];
		    if (td) {
		      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
		        tr[i].style.display = "";
		      } else {
		        tr[i].style.display = "none";
		      }
		    }
		  }
		}	

	function filter_mrn() {
		  var filter, table, tr, td, i;
		  filter = $("#idmin_mrn").val();
		  filter = filter.toUpperCase();
		  table = document.getElementById("tblMIN");
		  tr = table.getElementsByTagName("tr");
		  for (i = 0; i < tr.length; i++) {
		    td = tr[i].getElementsByTagName("td")[1];
		    if (td) {
		      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
		        tr[i].style.display = "";
		      } else {
		        tr[i].style.display = "none";
		      }
		    }
		  }
		}				
</script>

<script src="<?php echo base_url();?>assets/js/printdoc.js"></script> 
