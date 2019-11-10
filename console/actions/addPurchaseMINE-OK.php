<?php



	session_start();

	include("../../script/dbase/dbase_cred.php");



	if(isset($_POST['btn_action']) && isset($_COOKIE["STOCK_LOGGED_IN"]) && isset($_SESSION['logged']))

	{

		if($_POST['btn_action'] == 'Add')

		{

			$user_name = $_SESSION['user_name'];
			
			$purchase_amt = 0;
			
			$discount = $_POST['invoice_disc'];
			
			$invoice_no = $_POST['invoice_no'];

			$purchase_date = $_POST['invoice_date'];

			$supplier_name = $_POST['invoice_name'];

			$supplier_address = $_POST['invoice_address'];
			
			$supplier_gstin = $_POST['invoice_gstin'];
			
			$supplier_state_name = $_POST['invoice_state_code'];
			
			$arr = explode(",", $supplier_state_name);
			$supplier_state_code = $arr[0];

			

			$q = mysqli_query($con, "INSERT INTO stock_purchase SET user_name='$user_name', invoice_no='$invoice_no', purchase_amt='$purchase_amt', discount='$discount', purchase_date='$purchase_date', supplier_name='$supplier_name', supplier_address='$supplier_address', supplier_gstin='$supplier_gstin',supplier_state_code='$supplier_state_code', supplier_state_name='$supplier_state_name';");

			

			

			//$statement = mysqli_query("SELECT LAST_INSERT_ID()");

			$inventory_order_id = mysqli_insert_id($con);



			if(isset($inventory_order_id))

			{


				$total_amount = 0;

				for($count = 0; $count<count($_POST["product_name"]); $count++)

				{

					$product = $_POST['product_name'][$count];
					
					$product_id = $product;
					
					$product_details = fetch_product_details($product, $_POST['price'][$count], $con);
					
					if ($product_details == null) {
						
						$product_name = fetch_product_name($product, $con);

						$hsn = $_POST['hsn'][$count];

						$gst = $_POST['gst'][$count];
						
						$quantity = $_POST['quantity'][$count];
						
						$unit = $_POST['unit'][$count];
						
						$price = $_POST['price'][$count];

						//$tax = $product_details['tax'];

						

						$sub_query = "

						INSERT INTO stock_items SET item_name='$product_name', item_hsn='$hsn', item_gst='$gst', item_price='$price', item_quantity='$quantity', unit='$unit'

						";

						
						mysqli_query($con, $sub_query);
						
						$product_id = mysqli_insert_id($con);
						
						$base_price = $price * $quantity;
						
						$disc = $base_price * $discount/100;

						$tax = (($base_price - $disc)/100)*$gst;

						$total_amount = $total_amount + ($base_price + $tax - $disc);
						
						$total_amount = round($total_amount);
						
					
					
						$sub_query = "

						INSERT INTO inventory_purchase_product SET inventory_purchase_id='$inventory_order_id', product_id='$product_id', quantity='$quantity', price='$price', discount='$discount', tax='$gst'

						";

						
						mysqli_query($con, $sub_query);
					}
					else
					{
						echo "Item found";
						
						$product_name = $product_details['product_name'];

						$hsn = $product_details['hsn'];

						$gst = $product_details['tax'];
						
						$quantity = $_POST['quantity'][$count] + $product_details['quantity'];
						
						$unit = $product_details['unit'];
						
						$price = ($_POST['price'][$count]>$product_details['price'])?$_POST['price'][$count]:$product_details['price'];
						
						
						$sub_query = "

						UPDATE stock_items SET item_name='$product_name', item_hsn='$hsn', item_gst='$gst', item_price='$price', item_quantity='$quantity', unit='$unit' WHERE item_id=".$product."

						";
						
						mysqli_query($con, $sub_query);
						
						$base_price = $_POST['price'][$count] * $_POST['quantity'][$count];

						$disc = $base_price * $discount/100;

						$tax = (($base_price - $disc)/100)*$gst;

						$total_amount = $total_amount + ($base_price + $tax - $disc);
						$total_amount = round($total_amount);
						
						$quantity = $_POST['quantity'][$count];
						
						$sub_query = "

						INSERT INTO inventory_purchase_product SET inventory_purchase_id='$inventory_order_id', product_id='$product_id', quantity='$quantity', price='$price', discount='$discount', tax='$gst'

						";

						
						mysqli_query($con, $sub_query);
					}

				}
				
				$item_insert_id = mysqli_insert_id($con);
				
				if($item_insert_id) {
					echo "Purchase Created Successfully!! : ".$inventory_order_id;
				}

				$update_query = "UPDATE stock_purchase SET purchase_amt = '".$total_amount."' WHERE purchase_id = '".$inventory_order_id."'";

				$statement = mysqli_query($con, $update_query);

				if(mysqli_affected_rows($con) > 0)

				{

					echo '<br/>Order Created...';

					echo '<br />';

					echo $total_amount;

					echo '<br />';

				}

			}

		}

	}

	

	function fetch_product_details($product_id, $price, $con)

	{

		$query = "SELECT * FROM stock_items WHERE item_id = '".$product_id."' AND item_price = '".$price."'";

		$statement = mysqli_query($con, $query);

		$totalRow = mysqli_affected_rows($con);
		
		if($totalRow <= 0)
			return null;

		while($row=(mysqli_fetch_assoc($statement)))

		{

			$output['product_name'] = $row["item_name"];

			$output['hsn'] = $row["item_hsn"];


			$output['tax'] = $row["item_gst"];
			
			$output['unit'] = $row["unit"];
			
			$output['quantity'] = $row["item_quantity"];
			
			$output['price'] = $row["item_price"];

		}

		return $output;

	}
	
	function fetch_product_name($product_id, $con)

	{

		$query = "SELECT * FROM stock_items WHERE item_id = '".$product_id."'";

		$statement = mysqli_query($con, $query);

		$totalRow = mysqli_affected_rows($con);
		
		if($totalRow <= 0)
			return null;

		while($row=(mysqli_fetch_assoc($statement)))

		{

			return $row["item_name"];

		}

		return null;

	}



?>