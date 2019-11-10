<?php
	session_start();
	include("../../script/dbase/dbase_cred.php");
	include("../../script/dbase/database_connection.php");
  	
	if(isset($_COOKIE["STOCK_LOGGED_IN"]) && isset($_SESSION['logged']))
    {
    	if($_POST['btn_action'] == 'Edit')
		{
			$name = $_POST['item_name'];
			$hsn = isset($_POST['item_hsn'])?$_POST['item_hsn']:"";
			$price = $_POST['item_price'];//isset($_POST['item_price'])?$_POST['item_price']:"0";
			$qty = $_POST['item_quantity'];//isset($_POST['item_quantity'])?$_POST['item_quantity']:"0";
			$gst = $_POST['item_gst'];
			$unit = $_POST['item_unit'];
			$variety = isset($_POST['item_variety'])?($_POST['item_variety'].' '.$_POST['item_unit_variety']):"";
			$item_id=$_POST['item_id'];
			
			// echo "<script>alert(".$name."".$hsn."".$price."".$qty."".$gst."".$unit."".$variety.");</script>";
			
			$q = mysqli_query($con, "UPDATE stock_items SET item_name='$name', item_hsn='$hsn', item_price='$price', item_quantity='$qty', item_gst='$gst', unit='$unit', item_variety='$variety' WHERE item_id = '$item_id'");
			if(mysqli_affected_rows($con) == 1)
				echo "Product Details Edited";
			else
				echo "Failed";
		}
		else if($_POST["btn_action"] == 'delete'){
			$item_id=$_POST["item_id"];
			$delete_query ="
			DELETE FROM stock_items
			WHERE item_id ='".$item_id."'
			";

			$statement = $connect->prepare($delete_query);
			$statement->execute();
			$result = $statement->fetchAll();
			if(isset($result))
			{
				echo 'Item Deleted';
			}
		}

		else if(isset($_POST['item_name']) && isset($_POST['item_gst']))
		{
			//$dr = date("d-M-Y");
			$name = $_POST['item_name'];
			$hsn = isset($_POST['item_hsn'])?$_POST['item_hsn']:"";
			$price = intval("0".$_POST['item_price']);
			$qty = intval("0".$_POST['item_quantity']);
			$gst = $_POST['item_gst'];
			$unit = $_POST['item_unit'];
			$variety = isset($_POST['item_variety'])?($_POST['item_variety'].' '.$_POST['item_unit_variety']):"";
			
			// echo "<script>alert(".$name."".$hsn."".$price."".$qty."".$gst."".$unit."".$variety.");</script>";
			
			
			$q = mysqli_query($con, "INSERT INTO stock_items SET item_name='$name', item_hsn='$hsn', item_price='$price', item_quantity='$qty', item_gst='$gst', unit='$unit', item_variety='$variety'");
			
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