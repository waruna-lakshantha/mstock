<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">

<?php
	if (isset($this->session->userdata['logged_in'])) {
		$username = ($this->session->userdata['logged_in']['username']);
		$email = ($this->session->userdata['logged_in']['email']);
	} else {
		header("location: mstock");
	}
	
	$page_header = '';
	$page_id = '';	
	
	if (isset($page)) {
		$page_id = $page;
	}
	
	if (!isset($company_list)) {
		$company_list = array();
	}
	
?>

<head>
	<meta charset="utf-8">
	<title>Maintenance Stock</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
    <link rel = "stylesheet" type = "text/css" 
       href = "<?php echo base_url(); ?>assets/css/w3.css">
    <link rel = "stylesheet" type = "text/css" 
       href = "<?php echo base_url(); ?>assets/css/4/w3.css">       
    <link rel = "stylesheet" type = "text/css" 
       href = "<?php echo base_url(); ?>assets/css/w3-theme-teal.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">       
    <link rel = "stylesheet" type = "text/css" 
       href = "<?php echo base_url(); ?>assets/font-awesome-4.3.0/css/font-awesome.min.css">       
    <script src="<?php echo base_url();?>assets/js/jquery-3.1.1.min.js"></script>
    <script src="<?php echo base_url();?>assets/json/jquery.json.min.js"></script>
	<script src="<?php echo base_url();?>assets/js/common.js"></script> 
	<script src="<?php echo base_url();?>assets/js/simple-excel.js"></script> 
       
	<style>
		body {font-family: "Roboto", sans-serif}
		.w3-bar-block .w3-bar-item{padding:16px;font-weight:bold}
		
		.rpt_para_scroll {
		    height: 300px;
		    overflow: scroll;
		}		
	</style>	
	
</head>

<body>
					
<nav class="w3-sidebar w3-bar-block w3-collapse w3-animate-left w3-card-2" style="z-index:3;width:250px;" id="mySidebar">
  <a class="w3-bar-item w3-button w3-border-bottom w3-large" href="#"><img src="<?php echo base_url(); ?>assets/image/mstock.png" style="width:100%;"></a>
  <a class="w3-bar-item w3-button w3-hide-large w3-large" href="javascript:void(0)" onclick="w3_close()">Close <i class="fa fa-remove"></i></a>
  				
  	<?php
  		if (isset($active_menu)) {
  			
  			$arrlength = count($active_menu);
  			
  			for($x = 0; $x < $arrlength; $x++) {
  				if($active_menu[$x]->category === 'S')
  				{
	  				if ($active_menu[$x]->menuid == $page_id){
	  					echo "<a class=\"w3-bar-item w3-button tablink w3-border-bottom w3-light-grey\" href=\"".base_url()."index.php/form/load/".$active_menu[$x]->menuid."\">".$active_menu[$x]->menuname."</a>";	
	  					$page_header = $active_menu[$x]->menuname;
					}else{
						echo "<a class=\"w3-bar-item w3-button w3-border-bottom tablink\" href=\"".base_url()."index.php/form/load/".$active_menu[$x]->menuid."\">".$active_menu[$x]->menuname."</a>";
					}
  				}

  			}

		}
		
		if (isset($active_menu_rpt)) {
			echo "<button class=\"w3-button w3-block w3-left-align\" onclick=\"showRptMenu()\">";
			echo "Reports <i class=\"fa fa-caret-down\"></i>";
			echo "</button>";
			echo "<div id=\"id_RptMenu\" class=\"w3-hide w3-white w3-card-2\">";
			
			$arrlength_rpt = count($active_menu_rpt);
			
			for($x_r = 0; $x_r < $arrlength_rpt; $x_r++) {
				echo "<a href=\"".base_url()."index.php/form/load/".$active_menu_rpt[$x_r]->menuid."\" class=\"w3-bar-item w3-button w3-pale-green\">".$active_menu_rpt[$x_r]->menuname."</a>";
				if ($active_menu_rpt[$x_r]->menuid == $page_id){
					$page_header = $active_menu_rpt[$x_r]->menuname;
				}
			}
			
			echo "</div>";
		}
		
	?>
	
	<script>
		function showRptMenu() {
		    var x = document.getElementById("id_RptMenu");
		    if (x.className.indexOf("w3-show") == -1) {
		        x.className += " w3-show";
		        x.previousElementSibling.className += " w3-green";
		    } else { 
		        x.className = x.className.replace(" w3-show", "");
		        x.previousElementSibling.className = 
		        x.previousElementSibling.className.replace(" w3-green", "");
		    }
		}	
	</script>
	
	<a class="w3-bar-item w3-button tablink" href="logout"><i class="fa fa-sign-out" aria-hidden="true"></i> <?php echo ucfirst($username);?> Logout</a>
    
  <!-- <a class="w3-bar-item w3-button" href="#">Alerts</a>
  <div>
    <a class="w3-bar-item w3-button" onclick="myAccordion('demo')" href="javascript:void(0)">Accordions <i class="fa fa-caret-down"></i></a>
    <div id="demo" class="w3-hide">
      <a class="w3-bar-item w3-button" href="#">Link 1</a>
      <a class="w3-bar-item w3-button" href="#">Link 2</a>
      <a class="w3-bar-item w3-button" href="#">Link 3</a>
    </div>
  </div>
  <a class="w3-bar-item w3-button" href="#">Tables</a> -->
</nav>

<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" id="myOverlay"></div>

<div class="w3-main" style="margin-left:250px;">

<div id="myTop" class="w3-top w3-theme w3-large">
  <i class="fa fa-bars w3-button w3-teal w3-hide-large w3-xlarge" onclick="w3_open()"></i>
  <span id="myIntro" class="w3-hide"><?php echo ucwords($page_header);?></span>
</div>

<header class="w3-bar w3-theme w3-padding-16 w3-animate-top" style="padding-left:32px">
  <h1 class="w3-xxxlarge w3-padding-16"><?php echo ucwords($page_header);?></h1>
</header>

<div class="w3-container w3-padding-32" style="padding-left:32px">

	<?php 
	
		//echo $this->user->check_access($username, 'MS', $page_id);
	
		if ($this->user->check_access($username, 'MS', $page_id) === false){
			return;
		}
	
		switch ($page_id) {
			case 1:
				$data['company_list'] = $company_list;
				$this->load->view('pr', $data);
				
				break;
			case 2:
				$data['company_list'] = $company_list;
				//$data['item_list'] = $item_list;
				//$data['uom_list'] = $uom_list;
				$this->load->view('grn', $data);
				
				break;
			case 3:
				$data['company_list'] = $company_list;
				//$data['item_list'] = $item_list;
				//$data['uom_list'] = $uom_list;
				$this->load->view('mrn', $data);
				
				break;
			case 4:
				$data['company_list'] = $company_list;
				//$data['item_list'] = $item_list;
				//$data['uom_list'] = $uom_list;
				$this->load->view('min', $data);
				
				break;
			case 5:
				$data['company_list'] = $company_list;
				//$data['item_list'] = $item_list;
				//$data['uom_list'] = $uom_list;
				$this->load->view('dashboard', $data);
				
				break;
			case 6:
				$data['company_list'] = $company_list;
				//$data['item_list'] = $item_list;
				//$data['uom_list'] = $uom_list;
				$this->load->view('pr_not_app_list', $data);
				
				break;				
			case 7:
				$data['company_list'] = $company_list;
				$data['pr_type'] = 'L';
				$this->load->view('pr_not_proceed_list', $data);
				
				break;
			case 14:
				$data['company_list'] = $company_list;
				$data['pr_type'] = 'I';
				$this->load->view('pr_not_proceed_list', $data);
				
				break;
			case 8:
				$data['company_list'] = $company_list;
				//$data['item_list'] = $item_list;
				//$data['uom_list'] = $uom_list;
				$this->load->view('mrn_not_approve_list', $data);
				
				break;
				
			case 9:
				$this->load->view('item_list');
				
				break;

			case 10:
				$data['company_list'] = $company_list;
				$data['pr_type'] = 'L';
				//$data['item_list'] = $item_list;
				//$data['uom_list'] = $uom_list;
				$this->load->view('pr_not_accept_list', $data);
				
				break;

			case 11:
				$this->load->view('job_requisition');
				
				break;
				
			case 12:
				$this->load->view('grn_not_approve_list');
				
				break;
				
			case 13:
				$data['company_list'] = $company_list;
				$data['pr_type'] = 'I';
				//$data['item_list'] = $item_list;
				//$data['uom_list'] = $uom_list;
				$this->load->view('pr_not_accept_list', $data);
				
				break;

			case 15:
				$this->load->view('job_not_approve_list');
				
				break;

			case 16:
				$this->load->view('job_not_accept_list');
				
				break;
				
			case 17:
				$this->load->view('job_not_complete_list');
				
				break;
				
			case 18:
				$this->load->view('job_not_feedback_list');
				
				break;
				
			case 19:
				$this->load->view('stk_adj');
				
				break;
				
			case 20: case 21: case 22: case 23: case 24: case 25: case 26:
				$data['rpt_id'] = $page_id;
				$this->load->view('reports/rpt_parameter', $data);
				
				break;
				
			case 29:
				$this->load->view('user_permission');
				
				break;
				
			case 30:
				$this->load->view('pr_pending_list_store');
				
				break;

		}
	?>

</div>

<footer id="foot" class="w3-container w3-theme w3-padding-16 w3-animate-bottom" style="padding-left:32px">
  <h5>Design &amp; Develop by Waruna Lakshantha (0773718891)</h5>
  <p>&copy; <?php echo date("Y"); ?> Copyright.</p>
</footer>
     
</div>

	<script>
		// Open and close the sidebar on medium and small screens
		function w3_open() {
		    document.getElementById("mySidebar").style.display = "block";
		    document.getElementById("myOverlay").style.display = "block";
		}
		function w3_close() {
		    document.getElementById("mySidebar").style.display = "none";
		    document.getElementById("myOverlay").style.display = "none";
		}
		
		// Change style of top container on scroll
		window.onscroll = function() {myFunction()};
		function myFunction() {
		    if (document.body.scrollTop > 80 || document.documentElement.scrollTop > 80) {
		        document.getElementById("myTop").classList.add("w3-card-4", "w3-animate-opacity");
		        document.getElementById("myIntro").classList.add("w3-show-inline-block");
		    } else {
		        document.getElementById("myIntro").classList.remove("w3-show-inline-block");
		        document.getElementById("myTop").classList.remove("w3-card-4", "w3-animate-opacity");
		    }
		}
		
		// Accordions
		function myAccordion(id) {
		    var x = document.getElementById(id);
		    if (x.className.indexOf("w3-show") == -1) {
		        x.className += " w3-show";
		        x.previousElementSibling.className += " w3-theme";
		    } else { 
		        x.className = x.className.replace("w3-show", "");
		        x.previousElementSibling.className = 
		        x.previousElementSibling.className.replace(" w3-theme", "");
		    }
		}
	</script>

<?php
	//$this->load->view('footer');
?>

</body>
</html>