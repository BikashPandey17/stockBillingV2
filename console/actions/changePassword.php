<?php
	session_start();
	include("../../script/dbase/dbase_cred.php");
  	
	if(isset($_COOKIE["STOCK_LOGGED_IN"]) && isset($_SESSION['logged']))
    {
		//CHANGE
		if(isset($_POST['old_pwd']))
		{
			//$dr = date("d-M-Y");
			$username = $_SESSION['username'];
			$old_pwd = $_POST['old_pwd'];
			$new_pwd = $_POST['new_pwd'];
			$new_pwd2 = $_POST['new_pwd2'];
			$pwd = md5($old_pwd);
			
			// echo "<script>alert(".$name."".$hsn."".$price."".$qty."".$gst."".$unit."".$variety.");</script>";
			$q = mysqli_query($con, "SELECT * FROM stock_login WHERE username='$username' AND pwd='$pwd'");
			if(mysqli_affected_rows($con) == 1) {
				if($new_pwd == $new_pwd2) {
					$pwd = md5($new_pwd);
					$q = mysqli_query($con, "UPDATE stock_login SET pwd='$pwd' WHERE username='$username'");
					
					if(mysqli_affected_rows($con) == 1)
						echo "Successfully Changed Password!!";
					else
						echo "Error in updating password. Please try again.";
				}
				else{
					echo "New Passwords don't Match!!";
				}
			}
			else {
				echo "Invalid Password Entered!! Enter Correct Password to Change password.";
			}
		}
		else
			echo "failed";
	}
	else
		echo "FAILED";
?>