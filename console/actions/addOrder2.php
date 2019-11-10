<?php

//order_action.php

include('../../script/dbase/database_connection.php');

//include('../../script/dbase/function.php');
include("../../script/dbase/dbase_cred.php");

function fill_product_list($con)
	{
		$query = "
		SELECT * FROM stock_items 
		ORDER BY item_name ASC
		";
		$statement = mysqli_query($con, $query);
		//$result = mysqli_fetch($statement);
		//$totalRows = mysqli_affected_rows($con);
		$output = '';
		 while($row=mysqli_fetch_assoc($statement))
		{
			$output .= '<option value="'.$row["item_id"].'">'.$row["item_name"].'</option>';
		}
		return $output;
	}
if(isset($_POST['btn_action']))
{
	
	
	if($_POST['btn_action'] == 'fetch_single')
	{
		// echo '<script language="javascript">';
		// echo 'alert("messInside fetch_single")';
		// echo '</script>';
		$query = "
		SELECT * FROM stock_orders WHERE order_id = :order_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':order_id'	=>	$_POST["order_id"]
			)
		);
		$result = $statement->fetchAll();
		$output = array();
		foreach($result as $row)
		{
			$output['user_name'] = $row['user_name'];
			$output['order_amt'] = $row['order_amt'];
			$output['order_date'] = $row['order_date'];
			$output['order_name'] = $row['order_name'];
			$output['order_address']=$row['order_address'];
			$output['payment_status']=$row['payment_status'];
			$output['state_code']=$row['state_code'];
			$output['state_name']=$row['state_name'];
			$output['gstin']=$row['gstin'];
			if($row['cheque_details']!=NULL){
			$arr=explode(",", $row['cheque_details']);

			$output['cheque_number']=$arr[0];
			$output['cheque_date']=$arr[1];
		}
		}
		// echo "SELECT * FROM inventory_order_product 
		// WHERE inventory_order_id = '".$_POST["order_id"]."'";
		$sub_query = "
		SELECT * FROM inventory_order_product 
		WHERE inventory_order_id = '".$_POST["order_id"]."'
		";
		$statement = mysqli_query($con, $sub_query);
		//$statement = $connect->prepare($sub_query);
		//$statement->execute();
		//$sub_result = $statement->fetchAll();
		$product_details = '';
		$count = 0;
		while($sub_row=(mysqli_fetch_assoc($statement)))
		{
			
			$product_details .= '<script>
			$(document).ready(function(){
				$("#product_id'.$count.'").selectpicker("val", '.$sub_row["product_id"].');
				$(".selectpicker").selectpicker();
			});
			</script>
			<span id="row'.$count.'">
				<div class="row">
					<div class="col-md-8">
						<select name="product_id[]" id="product_id'.$count.'" class="form-control selectpicker" data-live-search="true" required>
							'.fill_product_list($con).'
						</select>
						<input type="hidden" name="hidden_product_id[]" id="hidden_product_id'.$count.'" value="'.$sub_row["product_id"].'" />
					</div></div><div class="row">
					<div class="col-md-5">
						<input type="number" name="quantity[]" class="form-control" value="'.$sub_row["quantity"].'" placeholder="Quantity" title="Quantity" required/>
					</div>
					<div class="col-md-5">
					<input type="number" step="any" name="price[]" class="form-control" value="'.$sub_row["price"].'" placeholder="Price" title="Price" required />
					</div>
					<div class="col-md-1">';

			if($count == 0)
			{
				$product_details .= '<button type="button" name="add_more" id="add_more" class="btn btn-success btn-xs">+</button>';
			}
			else
			{
				$product_details .= '<button type="button" name="remove" id="'.$count.'" class="btn btn-danger btn-xs remove">-</button>';
			}
			$product_details .= '
						</div>
					</div>
				</div><br />
			</span>';
			$count = $count + 1;
		}
		$output['product_details'] = $product_details;
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'Edit')
	{
		$delete_query = "
		DELETE FROM inventory_order_product 
		WHERE inventory_order_id = '".$_POST["inventory_order_id"]."'
		";
		$statement = $connect->prepare($delete_query);
		$statement->execute();
		$delete_result = $statement->fetchAll();
		if(isset($delete_result))
		{
			$total_amount = 0;
			for($count = 0; $count < count($_POST["product_id"]); $count++)
			{
				$product_details = fetch_product_details($_POST["product_id"][$count], $connect);
				$sub_query = "
				INSERT INTO inventory_order_product (inventory_order_id, product_id, quantity, price, tax) VALUES (:inventory_order_id, :product_id, :quantity, :price, :tax)
				";
				$statement = $connect->prepare($sub_query);
				$statement->execute(
					array(
						':inventory_order_id'	=>	$_POST["inventory_order_id"],
						':product_id'			=>	$_POST["product_id"][$count],
						':quantity'				=>	$_POST["quantity"][$count],
						':price'				=>	$product_details['price'],
						':tax'					=>	$product_details['tax']
					)
				);
				$base_price = $product_details['price'] * $_POST["quantity"][$count];
				$tax = ($base_price/100)*$product_details['tax'];
				$total_amount = $total_amount + ($base_price + $tax);
			}
			$update_query = "
			UPDATE inventory_order 
			SET inventory_order_name = :inventory_order_name, 
			inventory_order_date = :inventory_order_date, 
			inventory_order_address = :inventory_order_address, 
			inventory_order_total = :inventory_order_total, 
			payment_status = :payment_status
			WHERE inventory_order_id = :inventory_order_id
			";
			$statement = $connect->prepare($update_query);
			$statement->execute(
				array(
					':inventory_order_name'			=>	$_POST["inventory_order_name"],
					':inventory_order_date'			=>	$_POST["inventory_order_date"],
					':inventory_order_address'		=>	$_POST["inventory_order_address"],
					':inventory_order_total'		=>	$total_amount,
					':payment_status'				=>	$_POST["payment_status"],
					':inventory_order_id'			=>	$_POST["inventory_order_id"]
				)
			);
			$result = $statement->fetchAll();
			if(isset($result))
			{
				echo 'Order Edited...';
			}
		}
	}

	if($_POST['btn_action'] == 'delete')
	{
		$status = 'active';
		if($_POST['status'] == 'active')
		{
			$status = 'inactive';
		}
		$query = "
		UPDATE inventory_order 
		SET inventory_order_status = :inventory_order_status 
		WHERE inventory_order_id = :inventory_order_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':inventory_order_status'	=>	$status,
				':inventory_order_id'		=>	$_POST["inventory_order_id"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Order status change to ' . $status;
		}
	}
}

?>