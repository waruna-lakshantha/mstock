<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
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
			<div id="idPrtoApp">
			<?php
				echo $this->mrn->read_mrn_for_approval_html();
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

    function update_App_Rej(mrnno, status){
    	
    	var up_btn_id = '#idupdateapp_' + mrnno;

    	$(up_btn_id).hide();
        
    	var _ReqHdrData = new Array();

    	var _mrnno = mrnno;
    	var _docstatus = status;
    	var _apprejremarks = $('#id_freetxt').val();
		var _insertuser = '<?php echo $this->session->userdata['logged_in']['username'];?>';

		_ReqHdrData[0] = { "mrnno": _mrnno, "docstatus": _docstatus, "apprejremarks": _apprejremarks, "insertuser": _insertuser };

		var ReqHdrData = JSON.stringify(_ReqHdrData);

		<?php echo "var url = '". base_url()."index.php/transaction/approvemrn';"; ?>

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
                	$("#msgHed").text(data[1]);
                	$("#msgDes").text("Successfuly Updated!");
                	$("#idmessage").removeClass("w3-hide");

                	$("#idPrtoApp").html(data[2]);
                	
                	document.getElementById('idmessage').style.display = 'block';

                	$(up_btn_id).show();
                	
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

                	$(up_btn_id).show();

                }               
                
			}
		});		
    	
    }    

</script>