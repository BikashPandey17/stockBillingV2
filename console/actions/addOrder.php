<?php







	session_start();



	include("../../script/dbase/dbase_cred.php");



	include('../../script/dbase/database_connection.php');



	if(isset($_POST['btn_action']) && isset($_COOKIE["STOCK_LOGGED_IN"]) && isset($_SESSION['logged']))



	{



		if($_POST['btn_action'] == 'Add')



		{
			
			$check=1;
			for($count = 0; $count<count($_POST["product_id"]); $count++){
				$product_id = $_POST['product_id'][$count];

				

					$quantity = $_POST['quantity'][$count];
					
			$quantity_query = "SELECT * FROM stock_items WHERE item_id='$product_id'";

										

					$stmt = mysqli_query($con, $quantity_query);

					//echo $stmt;

					

					$row = mysqli_fetch_assoc($stmt);

					if($row['item_quantity']<$quantity)
						$check=0;

					

		}
			if($check!=0){
			$user_name = $_SESSION['user_name'];



			$order_amt = 0;



			$order_date = $_POST['inventory_order_date'];



			$order_name = isset($_POST['inventory_order_name'])?$_POST['inventory_order_name']:"";



			$order_address = isset($_POST['inventory_order_address'])?$_POST['inventory_order_address']:"";



			$payment_status = $_POST['payment_status'];





			$arr = explode(",", $_POST['state']);

			$state_code = $arr[0];

			

			$state_name = $_POST['state'];



			$gstin = "";

			

			$cheque_details = "";

			

			if(isset($_POST['cheque_num']) && isset($_POST['cheque_date']))

				$cheque_details = $_POST['cheque_num'].','.$_POST['cheque_date'];

			

			if(isset($_POST['gstin']) && $_POST['gstin'] != "") {

				$gstin = $_POST['gstin'];

			}



			$q = mysqli_query($con, "INSERT INTO stock_orders SET user_name='$user_name', order_amt='$order_amt', order_date='$order_date', order_name='$order_name', order_address='$order_address', payment_status='$payment_status',state_code='$state_code', state_name='$state_name', gstin='$gstin', cheque_details='$cheque_details'");



			



			



			//$statement = mysqli_query("SELECT LAST_INSERT_ID()");



			$inventory_order_id = mysqli_insert_id($con);







			if(isset($inventory_order_id))



			{





				$total_amount = 0;



				for($count = 0; $count<count($_POST["product_id"]); $count++)



				{





					$product_details = fetch_product_details($_POST["product_id"][$count], $con);



					



					$product_id = $_POST['product_id'][$count];



					$quantity = $_POST['quantity'][$count];



					$price = $_POST['price'][$count];



					$tax = $product_details['tax'];



					$sub_query = "



					INSERT INTO inventory_order_product SET inventory_order_id='$inventory_order_id', product_id='$product_id', quantity='$quantity', price='$price', tax='$tax'



					";



					

					mysqli_query($con, $sub_query);

					$base_price = $price * $quantity;



					$tax = ($base_price/100)*$tax;



					$total_amount = $total_amount + ($base_price + $tax);

					

					$quantity_query = "SELECT * FROM stock_items WHERE item_id='$product_id'";

										

					$stmt = mysqli_query($con, $quantity_query);

					//echo $stmt;

					

					$row = mysqli_fetch_assoc($stmt);

					

					$new_quantity = $row['item_quantity'] - $quantity;

					

					

					$sub_query = "UPDATE stock_items SET item_quantity = '$new_quantity' WHERE item_id='$product_id' ";

					

					mysqli_query($con, $sub_query);



				}



				$update_query = "



				UPDATE stock_orders 



				SET order_amt = '".$total_amount."' 



				WHERE order_id = '".$inventory_order_id."'



				";



				$statement = mysqli_query($con, $update_query);



				if(mysqli_affected_rows($con) > 0)



				{



					echo 'Order Created...';



					echo '<br />';



					echo $total_amount;



					echo '<br />';



					echo $inventory_order_id;



				}



			}
		}
		else{
			echo 'Short on stock...';
			echo '</br>';
			echo 'Order not Created...';
		}


		}

		if($_POST['btn_action'] == 'Edit')

	{

		$delete_query = "

		DELETE FROM inventory_order_product 

		WHERE inventory_order_id = '".$_POST["order_id"]."'

		";

		$statement = $connect->prepare($delete_query);

		$statement->execute();

		$delete_result = $statement->fetchAll();

		if(isset($delete_result))

		{

			$total_amount = 0;

			for($count = 0; $count < count($_POST["product_id"]); $count++)

			{

				$product_details = fetch_product_details($_POST["product_id"][$count], $con);



					



					$product_id = $_POST['product_id'][$count];



					$quantity = $_POST['quantity'][$count];



					$price = $_POST['price'][$count];



					$tax = $product_details['tax'];

					$inventory_order_id=$_POST["order_id"];

				$sub_query = "



					INSERT INTO inventory_order_product SET inventory_order_id='$inventory_order_id', product_id='$product_id', quantity='$quantity', price='$price', tax='$tax'



					";

				mysqli_query($con, $sub_query);

				$base_price = $price * $_POST["quantity"][$count];

				$tax = ($base_price/100)*$product_details['tax'];

				$total_amount = $total_amount + ($base_price + $tax);

			}

			$user_name = $_SESSION['user_name'];



			$order_amt = 0;



			$order_date = $_POST['inventory_order_date'];



			$order_name = isset($_POST['inventory_order_name'])?$_POST['inventory_order_name']:"";



			$order_address = isset($_POST['inventory_order_address'])?$_POST['inventory_order_address']:"";



			$payment_status = $_POST['payment_status'];



			$arr = explode(",", $_POST['state']);

			$state_code = $arr[0];

			

			$state_name = $_POST['state'];

			



			$gstin = "";

			

			$cheque_details = "";

			

			if(isset($_POST['cheque_num']) && isset($_POST['cheque_date']))

				$cheque_details = $_POST['cheque_num'].','.$_POST['cheque_date'];

			

			if(isset($_POST['gstin']) && $_POST['gstin'] != "") {

				$gstin = $_POST['gstin'];

			}
			
			echo "UPDATE stock_orders 

			SET  user_name=$user_name, order_amt=$total_amount, order_date=$order_date, order_name=$order_name, order_address=$order_address, payment_status=$payment_status,state_code=$state_code, state_name=$state_name, gstin=$gstin, cheque_details=$cheque_details

			WHERE order_id=$inventory_order_id ";

			$q = mysqli_query($con, "

			UPDATE stock_orders 

			SET  user_name='$user_name', order_amt='$total_amount', order_date='$order_date', order_name='$order_name', order_address='$order_address', payment_status='$payment_status',state_code='$state_code', state_name='$state_name', gstin='$gstin', cheque_details='$cheque_details'

			WHERE order_id='$inventory_order_id'");

			

			

			

			if(mysqli_affected_rows($con) > 0)

			{

				echo 'Order Edited...';

			}

		}

	}



	}



	



	function fetch_product_details($product_id, $con)



	{



		$query = "SELECT * FROM stock_items WHERE item_id = '".$product_id."'";



		$statement = mysqli_query($con, $query);



		//$totalRow = mysqli_affected_rows($connect);



		while($row=(mysqli_fetch_assoc($statement)))



		{



			$output['product_name'] = $row["item_name"];



			$output['hsn'] = $row["item_hsn"];



			



			$output['tax'] = $row["item_gst"];



		}



		return $output;



	}







?>