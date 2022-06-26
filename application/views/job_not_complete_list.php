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
			<div id="idPrtoApp" class="w3-responsive">
			<?php
				echo $this->job_requisition->read_job_dtl_list_not_complete_html();
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

    function update_Complete(jobno){

        //alert(jobno);

    	var _ReqHdrData = new Array();

    	var _jobno = jobno;
        var freetxt_id = '#id_freetxt_' + jobno;
        var estcost_id = '#id_estimate_cost_' + jobno;
        var actcost_id = '#id_actual_cost_' + jobno;
    	var _apprejremarks = $(freetxt_id).val();

    	var _estcost = $(estcost_id).val();
    	var _actcost = $(actcost_id).val();

    	if (isNaN(_estcost)){
    		_estcost = 0;
    	}

    	if (isNaN(_actcost)){
    		_actcost = 0;
    	}    	
    	
		var _insertuser = '<?php echo $this->session->userdata['logged_in']['username'];?>';	

		_ReqHdrData[0] = { "jobno": _jobno, "apprejremarks": _apprejremarks, "insertuser": _insertuser, "estcost": _estcost, "actcost": _actcost };

		var ReqHdrData = JSON.stringify(_ReqHdrData);

        //alert(ReqHdrData);

		<?php echo "var url = '". base_url()."index.php/transaction/completejob';"; ?>

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

                }               
                
			}
		});		
    	
    }    

</script>