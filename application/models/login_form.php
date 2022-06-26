<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php
	if (isset($this->session->userdata['logged_in'])) {			
		header("location: ".base_url()."index.php/User_Authentication/user_login_process");
        //echo "Already Logged In";
        //exit();
	}
?>

<?php
	$data['title'] = 'Maintenance Stock';
	$this->load->view('header', $data);
?>

<body>

        <div class="w3-container"> 

			<?php
			if (isset($logout_message)) {
			
				echo "<div class=\"w3-panel w3-green w3-display-container w3-round w3-card-4 w3-animate-top\">";
				echo "<span onclick=\"this.parentElement.style.display='none'\"";
				echo "class=\"w3-button w3-red w3-large w3-display-topright\">&times;</span>";
				
				echo "<p>".$logout_message."</p>";
							
				echo "</div>";
			}
			?>
			
			<?php
				if (isset($message_display)) {
				
					echo "<div class=\"w3-panel w3-green w3-display-container w3-round w3-card-4 w3-animate-top\">";
					echo "<span onclick=\"this.parentElement.style.display='none'\"";
					echo "class=\"w3-button w3-red w3-large w3-display-topright\">&times;</span>";
					
					echo "<p>".$message_display."</p>";
					
					echo "</div>";
				}
			?>    

			<?php
				if (isset($error_message)) {
					echo "<div class=\"w3-panel w3-red w3-display-container w3-round w3-card-4 w3-animate-top\">";					
					echo "<span onclick=\"this.parentElement.style.display='none'\"";
					echo "class=\"w3-button w3-red w3-large w3-display-topright\">&times;</span>";
					
						echo "<p>".$error_message."</p>";
				
					echo validation_errors();
					echo "</div>";
				}
			?>  
         
            <div class="w3-card-24 w3-display-middle">
                <div class="w3-container w3-light-blue">
                    <h2>M-Stock System</h2>
                </div>

                <div class="w3-container w3-center">
                    <img src="<?php echo base_url(); ?>assets/image/user.png" alt="User" style="width:30%" class="w3-circle w3-margin-top">
                </div>

                <form class="w3-container" method="POST" action="<?php echo base_url();?>index.php/User_Authentication/user_login_process">                            
                
                    <div class="w3-section">
                        <label><b>Username</b></label>
                        <input class="w3-input w3-margin-bottom" type="text" name="username" id="name" placeholder="Enter Username" required>
                        <label><b>Password</b></label>
                        <input class="w3-input" type="password" name="password" id="password" placeholder="Enter Password" required>
                        <button class="w3-btn-block w3-blue w3-section w3-padding" type="submit" value=" Login " name="submit">Login</button>
                    </div>
                </form>                            
                
                <span class="w3-right w3-padding w3-hide-small"><a href="<?php echo base_url() ?>index.php/User_authentication/User_registration_show">To SignUp Click Here</a></span>

            </div>
        </div>

</body>
</html>
