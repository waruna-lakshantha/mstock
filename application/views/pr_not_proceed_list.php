<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	$prtype = '-';
	
	if (isset($pr_type)) {
		$prtype = $pr_type;
	}
?>

<div class="w3-panel w3-card-4 w3-light-grey">

	<br>

	<div class="w3-row-padding">
		<div class="w3-col m4">
	    	<label>Date</label>
		    <input id="idgrn_date" class="w3-input" type="text" disabled>						
        </div>
		<div class="w3-col m4">
			<p></p>
		</div>	
	</div>
	
	<br>	
	
	<div class="w3-row-padding">
		<div class="w3-col m12">
			<div id="idPrtoApp" class="w3-responsive">
			<?php
				echo $this->pr->read_pr_for_proceed_html($prtype);
			?>
			</div>	
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

	function dropdown_dtl(id) {
	    var x = document.getElementById(id);
	    if (x.className.indexOf("w3-show") == -1) {
	        x.className += " w3-show";
	        x.previousElementSibling.className = 
	        x.previousElementSibling.className.replace("w3-black", "w3-red");
	    } else { 
	        x.className = x.className.replace(" w3-show", "");
	        x.previousElementSibling.className = 
	        x.previousElementSibling.className.replace("w3-red", "w3-black");
	    }
	}

	function validate_po_qty(txtbox, balqty, tblname){
		if(isNaN(txtbox.value)){
			txtbox.value = 0;
			return;
		}

		if(txtbox.length == 0){
			txtbox.value = 0;
			return;
		}

		if(txtbox.value < 0){
			txtbox.value = 0;
			return;
		}

		if (txtbox.value > balqty){
        	$("#idmessage").removeClass("w3-red");
        	$("#idmessage").removeClass("w3-yellow");
        	$("#idmessage").removeClass("w3-green");

        	$("#idmessage").addClass("w3-red");
        	$("#msgHed").text("Validation Error");
        	$("#msgDes").text("Valied Qty Required!");
        	$("#idmessage").removeClass("w3-hide");
        	
        	document.getElementById('idmessage').style.display = 'block';

            $('html, body').animate({
                scrollTop: $("#idmessage").offset().top
            }, 2000);     	

            txtbox.value = 0;
			return;        	
		}

		cal_Total(tblname);
		
	}

	function cal_Total(tableid){

		<?php
			/*Old
			Dtl No			0
			Item Code		1
			Item			2
			Supplier		3
			PO No			4
			PO Qty			5
			Unit Cost		6
			Total			7
			Bal Qty			8
			Uom				9
			Company			10
			Department		11
			Machine			12*/
		
			/*New
			Dtl No			0
			Item Code		1
			Item			2
			Supplier		3
			PO No			4
			PO Qty			5
			Unit Cost		6
			Currency		7
			Curr Rate		8
			U Cost LKR		9
			Total			10
			Bal Qty			11
			Uom				12
			Company			13
			Department		14
			Machine			15
			Type			16*/
		?>
		
        var table = document.getElementById(tableid);
        var tot = 0;
        var linetot = 0;
        for (var i = 1, row; row = table.rows[i]; i++)
        {
            var ptype = table.rows[i].cells[16].innerHTML;
        	var qty = $(table.rows[i]).find('td:eq(5)').find('input').val();
        	var unitcost = $(table.rows[i]).find('td:eq(6)').find('input').val();

        	var currate = 1;
        	
        	if(ptype == 'L'){
				currate = table.rows[i].cells[8].innerHTML;
        	}
        	
        	if(ptype == 'I'){
				currate = $(table.rows[i]).find('td:eq(8)').find('input').val();
        	}
        	
    		if (qty == 'undefined' || qty == null || qty.length == 0){
    			qty = 0;
    		}
        	
    		if (currate == 'undefined' || currate == null || currate.length == 0){
    			currate = 1;
    		}
        	
    		if (unitcost == 'undefined' || unitcost == null || unitcost.length == 0){
    			unitcost = 0;
    		}

    		//alert(currate);

        	if(ptype == 'L'){
        		table.rows[i].cells[9].innerHTML = unitcost;
        		linetot = parseFloat(qty) * parseFloat(unitcost);
        	} 

        	if(ptype == 'I'){
        		table.rows[i].cells[9].innerHTML = parseFloat(unitcost) * parseFloat(currate);
        		linetot = parseFloat(qty) * parseFloat(unitcost) * parseFloat(currate);
        	}
        	        	
        	table.rows[i].cells[10].innerHTML = linetot;
            
        	tot += linetot;            
        }
        //$("#idgrn_totcost").val(tot);
	}	

    function update_Proceed(prno){

    	//alert('Under Construction');
        //return;
		
		var upid = '#idupdaterej_' + prno;
		
    	$(upid).hide();
        
    	var _ReqHdrData = new Array();
    	
    	var _prno = prno;
		var _insertuser = '<?php echo $this->session->userdata['logged_in']['username'];?>';

		_ReqHdrData[0] = { "prno": _prno, "insertuser": _insertuser };

		var ReqHdrData = JSON.stringify(_ReqHdrData);

        var TableData = storeTableValue(prno);

        if (TableData.length == 0)
        {
        	$("#idmessage").removeClass("w3-red");
        	$("#idmessage").removeClass("w3-yellow");
        	$("#idmessage").removeClass("w3-green");

        	$("#idmessage").addClass("w3-yellow");
        	$("#msgHed").text("Required");
        	$("#msgDes").text("Invalied Item Detail! PO Qty Required!");
        	$("#idmessage").removeClass("w3-hide");            

        	document.getElementById('idmessage').style.display = 'block';

            $('html, body').animate({
                scrollTop: $("#idmessage").offset().top
            }, 2000);

            $(upid).show();
            
            return;
        }

		<?php echo "var url = '". base_url()."index.php/transaction/proceedpr';"; ?>	
		
        TableData = JSON.stringify(TableData);

        //alert(TableData);

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
                	$("#msgHed").text(data[1]);
                	$("#msgDes").text("Successfuly Updated!");
                	$("#idmessage").removeClass("w3-hide");

                	$("#idPrtoApp").html(data[2]);
                	
                	document.getElementById('idmessage').style.display = 'block';

                	$(upid).show();
                	
                }else{
                	$("#idmessage").removeClass("w3-red");
                	$("#idmessage").removeClass("w3-yellow");
                	$("#idmessage").removeClass("w3-green");

                	$("#idmessage").addClass("w3-red");
                	$("#msgHed").text("Error");
                	$("#msgDes").text(data[1]);
                	$("#idmessage").removeClass("w3-hide");

                	$("#idPrtoApp").html(data[2]);
                	
                	document.getElementById('idmessage').style.display = 'block';

                	$(upid).show();

                }               
                
			}
		});		
    	
    }

    function storeTableValue(prno) {
        var _TableData = new Array();

		var ai = 0;

		var rowi = 0;

		var tbl = '#id_pr_det_' + prno + ' tr';
		var upid = '#idupdaterej_' + prno;

		//alert(tbl);
		    
		$(tbl).each(function(row, tr){

			if (rowi > 0){
		    	var poqty = $(tr).find('td:eq(5)').find('input').val();
		    	var ptype = $(tr).find('td:eq(16)').text();
					
				//alert ($(tr).find('td:eq(1)').text());			
		    	
		    	if((isNaN(poqty) == false) && (poqty > 0))
		    	{               

		    		//alert ($(tr).find("select").val());
		    		
					if ($(tr).find("select").val() == undefined){
	
						//alert ($(tr).find("select").val());
						
			        	$("#idmessage").removeClass("w3-red");
			        	$("#idmessage").removeClass("w3-yellow");
			        	$("#idmessage").removeClass("w3-green");
	
			        	$("#idmessage").addClass("w3-yellow");
			        	$("#msgHed").text("Required");
			        	$("#msgDes").text("Supplier Required!");
			        	$("#idmessage").removeClass("w3-hide");            
	
			        	document.getElementById('idmessage').style.display = 'block';
	
			            $('html, body').animate({
			                scrollTop: $("#idmessage").offset().top
			            }, 2000);
	
			            $(upid).show();
	
			            _TableData = new Array();
	
			            return;
			            
					}

					var _currate = 0;
					var _curr = 'LKR';

					if (ptype == 'L'){
						_currate = $(tr).find('td:eq(8)').text();
					}

					if (ptype == 'I'){
						_currate = $(tr).find('td:eq(8)').find('input').val();
						_curr = $(tr).find('td:eq(7)').find('select').val();
					}
	             	    
			    	_TableData[ai]={
			            "dtlno" : $(tr).find('td:eq(0)').text()
	                    //, "supplier" : $(tr).find("select").val()
	                    , "supplier" : $(tr).find('td:eq(3)').find('select').val()
			            , "pono" : $(tr).find('td:eq(4)').find('input').val()            		            		            
	                    , "qty" : $(tr).find('td:eq(5)').find('input').val()
                        , "ucost" : $(tr).find('td:eq(6)').find('input').val()
                        , "currency" : _curr
                        , "currate" : _currate
                        , "ucostlkr" : $(tr).find('td:eq(9)').text()
                        , "total" : $(tr).find('td:eq(10)').text()}
	
			    	    ai++;

				        <?php
			                /*Old
			                Dtl No			0
			                Item Code		1
			                Item			2
			                Supplier		3
			                PO No			4
			                PO Qty			5
			                Unit Cost		6
			                Total			7
			                Bal Qty			8
			                Uom				9
			                Company			10
			                Department		11
			                Machine			12*/
					        
					        /*New
					         Dtl No			0
					         Item Code		1
					         Item			2
					         Supplier		3
					         PO No			4
					         PO Qty			5
					         Unit Cost		6
					         Currency		7
					         Curr Rate		8
					         U Cost LKR		9
					         Total			10
					         Bal Qty		11
					         Uom			12
					         Company		13
					         Department		14
					         Machine		15
					         Type			16*/
				        ?>
	
		    	}

			}

	    	rowi++;
	    	
	    });

        //_TableData.shift();

        return _TableData;

    }

</script>