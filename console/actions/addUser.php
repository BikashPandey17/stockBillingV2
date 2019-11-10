<?php
	session_start();
	include("../../script/dbase/dbase_cred.php");
  	
	if(isset($_COOKIE["STOCK_LOGGED_IN"]) && isset($_SESSION['logged']))
    {
    	if($_POST['btn_action'] == 'Edit')
		{
			// $name = $_POST['item_name'];
			// $hsn = isset($_POST['item_hsn'])?$_POST['item_hsn']:"";
			// $price = $_POST['item_price'];//isset($_POST['item_price'])?$_POST['item_price']:"0";
			// $qty = $_POST['item_quantity'];//isset($_POST['item_quantity'])?$_POST['item_quantity']:"0";
			// $gst = $_POST['item_gst'];
			// $unit = $_POST['item_unit'];
			// $variety = isset($_POST['item_variety'])?($_POST['item_variety'].' '.$_POST['item_unit_variety']):"";
			// $item_id=$_POST['item_id'];
			
			// // echo "<script>alert(".$name."".$hsn."".$price."".$qty."".$gst."".$unit."".$variety.");</script>";
			
			// $q = mysqli_query($con, "UPDATE stock_items SET item_name='$name', item_hsn='$hsn', item_price='$price', item_quantity='$qty', item_gst='$gst', unit='$unit', item_variety='$variety' WHERE item_id = '$item_id'");
			// if(mysqli_affected_rows($con) == 1)
				// echo "Product Details Edited";
			// else
				// echo "Failed";
		}
//ADD
		else if(isset($_POST['user_name']) && isset($_POST['pwd1']))
		{
			//$dr = date("d-M-Y");
			$user_name = $_POST['user_name'];
			$username = $_POST['username'];
			$desig = $_POST['user_desig'];
			$pwd1 = $_POST['pwd1'];
			$pwd = md5($pwd1);
			
			// echo "<script>alert(".$name."".$hsn."".$price."".$qty."".$gst."".$unit."".$variety.");</script>";
			
			
			$q = mysqli_query($con, "INSERT INTO stock_login SET user_name='$user_name', username='$username', desig='$desig', pwd='$pwd'");
			
			// $q = mysqli_query($con, "INSERT INTO stock_items SET item_name='$name', item_hsn='$hsn', item_gst='$gst', unit='$unit', variety='$variety'");
			
			if(mysqli_affected_rows($con) == 1)
				echo "Successfully Added!!";
			else
				echo "Failed";
				//echo " ".$name."".$hsn."".$price."".$qty."".$gst."".$unit."".$variety.")";
		}
		else
			echo "failed";
	}
	else
		echo "FAILED";
?>