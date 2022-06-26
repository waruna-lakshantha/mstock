<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<?php
	if (isset($this->session->userdata['logged_in'])) {	
		header("location: ".base_url()."index.php/User_Authentication/user_login_process");
        //echo "Already Logged In";
        //exit();
	}
?>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<?php        
	if (isset($title)) {	
		echo "<title>".$title."</title>";
	}else{
		echo "<title>Undifined</title>";
	}
	?>
	
    <link rel = "stylesheet" type = "text/css" 
       href = "<?php echo base_url(); ?>assets/css/w3.css">
    <link rel = "stylesheet" type = "text/css" 
       href = "<?php echo base_url(); ?>assets/css/4/w3.css">       
    <link rel = "stylesheet" type = "text/css" 
       href = "<?php echo base_url(); ?>assets/css/w3-theme-teal.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">       
    <link rel = "stylesheet" type = "text/css" 
       href = "<?php echo base_url(); ?>assets/font-awesome-4.3.0/css/font-awesome.min.css">
       
</head>