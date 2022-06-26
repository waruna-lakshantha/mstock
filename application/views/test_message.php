<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php
	if (!isset($this->session->userdata['logged_in'])) {	
		header("location: ".base_url()."index.php/User_Authentication/user_login_process");
	}
?>

<?php
	echo $message;
?>