<?php







	session_start();



	include("../../script/dbase/dbase_cred.php");



	include('../../script/dbase/database_connection.php');



	if(isset($_POST['btn_action']) && isset($_COOKIE["STOCK_LOGGED_IN"]) && isset($_SESSION['logged']))



	{



		if($_POST['btn_action'] == 'Add')



		{



			$user_name = $_SESSION['user_name'];

			

			$purchase_amt = 0;

			

			$discount = $_POST['invoice_disc'];

			

			$invoice_no = $_POST['invoice_no'];



			$purchase_date = $_POST['invoice_date'];

			

			$payment_status = $_POST['payment_status'];

			$cheque_details = "";

			

			if(isset($_POST['cheque_num']) && isset($_POST['cheque_date']))

				$cheque_details = $_POST['cheque_num'].','.$_POST['cheque_date'];

			

			$supplier_id = $_POST['seller_name'];

			

			$sub_query = "SELECT * FROM seller_details WHERE seller_id='$supplier_id' LIMIT 1";

			

			$stmt = mysqli_query($con, $sub_query);

			

			$row = mysqli_fetch_assoc($stmt);



			$supplier_name = $row['seller_name'];//$_POST['invoice_name'];



			$supplier_address = $row['seller_address'];//$_POST['invoice_address'];

			

			$supplier_gstin = $row['seller_gstin'];//$_POST['invoice_gstin'];

			

			$supplier_state_name = $row['seller_state_name'];//$_POST['invoice_state_code'];

			

			$supplier_state_code = $row['seller_state_code'];

			

			$special_disc = intval("0".$_POST['special_disc']);



			



			$q = mysqli_query($con, "INSERT INTO stock_purchase SET user_name='$user_name', invoice_no='$invoice_no', purchase_amt='$purchase_amt', discount='$discount', purchase_date='$purchase_date', seller_id='$supplier_id', supplier_name='$supplier_name', supplier_address='$supplier_address', supplier_gstin='$supplier_gstin',supplier_state_code='$supplier_state_code', supplier_state_name='$supplier_state_name', payment_status='$payment_status', cheque_details='$cheque_details', special_disc='$special_disc';");

			



			



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

						
                        if(is_numeric($product))
						$product_name = fetch_product_name($product, $con);
                        else
                        $product_name=$product;
        

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



				$total_amount = $total_amount - $special_disc;

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


        else if($_POST["btn_action"]=='delete'){

			$purchase_id=$_POST["purchase_id"];
			$q="

			SELECT * FROM inventory_purchase_product



			WHERE inventory_purchase_id = '".$purchase_id."'

			";
			$statement = mysqli_query($con, $q);
			while($sub_row=(mysqli_fetch_assoc($statement)))
			{
				$sub_qty_update="

				UPDATE stock_items

				SET item_quantity = (item_quantity - '".$sub_row["quantity"]."')

				WHERE item_id = '".$sub_row["product_id"]."'

				";
			}
			$delete_query = "



			DELETE FROM inventory_purchase_product 



			WHERE inventory_purchase_id = '".$_POST["purchase_id"]."'

			

			";
			$statement = $connect->prepare($delete_query);
			$statement->execute();
			$delete_query ="
			DELETE FROM stock_purchase
			WHERE purchase_id ='".$_POST["purchase_id"]."'
			";

			$statement = $connect->prepare($delete_query);
			$statement->execute();
			$result = $statement->fetchAll();
			if(isset($result))
			{
				echo 'Order Deleted';
			}
		}
		else if($_POST["btn_action"]=='Edit')

		{

			$inventory_order_id = $_POST["purchase_id"];

			



			$q="

			SELECT * FROM inventory_purchase_product



			WHERE inventory_purchase_id = '".$inventory_order_id."'

			";

			$statement = mysqli_query($con, $q);



			while($sub_row=(mysqli_fetch_assoc($statement)))

			{

				$sub_qty_update="

				UPDATE stock_items

				SET item_quantity = (item_quantity - '".$sub_row["quantity"]."')

				WHERE item_id = '".$sub_row["product_id"]."'

				";

				mysqli_query($con,$sub_qty_update );

			}

			$delete_query = "



			DELETE FROM inventory_purchase_product 



			WHERE inventory_purchase_id = '".$_POST["purchase_id"]."'

			

			";



			$statement = $connect->prepare($delete_query);



			$statement->execute();



			$delete_result = $statement->fetchAll();



			$user_name = $_SESSION['user_name'];

			

				$purchase_amt = 0;

				

				$discount = $_POST['invoice_disc'];

				

				$invoice_no = $_POST['invoice_no'];



				$purchase_date = $_POST['invoice_date'];



				//$supplier_name = $_POST['invoice_name'];



				//$supplier_address = $_POST['invoice_address'];

				

				$payment_status = $_POST['payment_status'];

				$cheque_details = "";

				if(isset($_POST['cheque_num']) && isset($_POST['cheque_date']))

				$cheque_details = $_POST['cheque_num'].','.$_POST['cheque_date'];
				$supplier_id = $_POST['seller_name'];


				$sub_query = "SELECT * FROM seller_details WHERE seller_id='$supplier_id' LIMIT 1";

			

				$stmt = mysqli_query($con, $sub_query);

				

				$row = mysqli_fetch_assoc($stmt);



				$supplier_name = $row['seller_name'];//$_POST['invoice_name'];



				$supplier_address = $row['seller_address'];//$_POST['invoice_address'];

				

				$supplier_gstin = $row['seller_gstin'];//$_POST['invoice_gstin'];

				

				$supplier_state_name = $row['seller_state_name'];//$_POST['invoice_state_code'];

				

				$supplier_state_code = $row['seller_state_code'];

				

				$special_disc = intval("0".$_POST['special_disc']);
				

			if(isset($delete_result))



			{

				$total_amount = 0;



				for($count = 0; $count<count($_POST["product_name"]); $count++)



				{



					$product = $_POST['product_name'][$count];

					

					$product_id = $product;

					

					$product_details = fetch_product_details($product, $_POST['price'][$count], $con);

					

					if ($product_details == null) 

					{

						

						 if(is_numeric($product))
						$product_name = fetch_product_name($product, $con);
                        else
                        $product_name=$product;



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
                         if(is_numeric($product_name))
						$product_name = fetch_product_name($product_name, $con);
                        else
                        $product_name=$product_name;


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

						$total_amount = $total_amount - $special_disc;

						$quantity = $_POST['quantity'][$count];

						

						$sub_query = "



						INSERT INTO inventory_purchase_product SET inventory_purchase_id='$inventory_order_id', product_id='$product_id', quantity='$quantity', price='$price', discount='$discount', tax='$gst'



						";



						

						mysqli_query($con, $sub_query);

					}

				}

					$item_insert_id = mysqli_insert_id($con);

				

				if($item_insert_id) {

					echo "Purchase updated Successfully!! : ".$inventory_order_id;

				}

				

				$update_query = "UPDATE stock_purchase SET user_name='$user_name', invoice_no='$invoice_no', discount='$discount', purchase_date='$purchase_date', seller_id='$supplier_id', supplier_name='$supplier_name', supplier_address='$supplier_address', supplier_gstin='$supplier_gstin',supplier_state_code='$supplier_state_code', supplier_state_name='$supplier_state_name', payment_status='$payment_status',cheque_details='$cheque_details', special_disc='$special_disc', purchase_amt = '".$total_amount."' WHERE purchase_id = '".$inventory_order_id."'

				";



				$statement = mysqli_query($con, $update_query);



				if(mysqli_affected_rows($con) > 0)



				{



					echo '<br/>Order updated...';



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