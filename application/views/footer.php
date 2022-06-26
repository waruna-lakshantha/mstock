<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php
	if (isset($this->session->userdata['logged_in'])) {	
		header("location: ".base_url()."index.php/User_Authentication/user_login_process");
	}
?>

<footer id="foot" class="w3-container w3-theme w3-padding-32 w3-animate-bottom" style="padding-left:32px">
  <h5>Design &amp; Develop by Waruna Lakshantha (0773718891)</h5>
  <p>&copy; <?php echo date("Y"); ?> Copyright.</p>
</footer>

</body>
</html>