<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<html>
<?php
	if (isset($this->session->userdata['logged_in'])) {
		header("location: ".base_url()."index.php/User_Authentication/user_login_process");
	}
?>
<!-- <head>
	<meta charset="utf-8">
	<title>Maintenance Stock</title>

    <link rel = "stylesheet" type = "text/css" 
       href = "<?php echo base_url(); ?>assets/css/w3.css">
    <link rel = "stylesheet" type = "text/css" 
       href = "<?php echo base_url(); ?>assets/css/w3-theme-teal.css">
    <link rel = "stylesheet" type = "text/css" 
       href = "<?php echo base_url(); ?>assets/font-awesome-4.3.0/css/font-awesome.min.css">
</head> -->

<?php
	$data['title'] = 'Maintenance Stock';
	$this->load->view('header', $data);
?>

<body>
<div id="main">
<div id="login">
<h2>Registration Form</h2>
<hr/>
<?php
echo "<div class='error_msg'>";
echo validation_errors();
echo "</div>";
echo form_open('user_authentication/new_user_registration');

echo form_label('Create Username : ');
echo"<br/>";
echo form_input('username');
echo "<div class='error_msg'>";
if (isset($message_display)) {
echo $message_display;
}
echo "</div>";
echo"<br/>";
echo form_label('Email : ');
echo"<br/>";
$data = array(
'type' => 'email',
'name' => 'email_value'
);
echo form_input($data);
echo"<br/>";
echo"<br/>";
echo form_label('Password : ');
echo"<br/>";
echo form_password('password');
echo"<br/>";
echo"<br/>";
echo form_submit('submit', 'Sign Up');
echo form_close();
?>
<a href="<?php echo base_url() ?> ">For Login Click Here</a>
</div>
</div>
</body>
</html>