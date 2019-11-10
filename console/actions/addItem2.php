<?php

//product_action.php

include('../../script/dbase/database_connection.php');

include('../../script/dbase/function.php');


if(isset($_POST['btn_action']))
{

	
	if($_POST['btn_action'] == 'product_details')
	{
		$query = "
		SELECT * FROM product 
		INNER JOIN category ON category.category_id = product.category_id 
		INNER JOIN brand ON brand.brand_id = product.brand_id 
		INNER JOIN user_details ON user_details.user_id = product.product_enter_by 
		WHERE product.product_id = '".$_POST["product_id"]."'
		";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$output = '
		<div class="table-responsive">
			<table class="table table-boredered">
		';
		foreach($result as $row)
		{
			$status = '';
			if($row['product_status'] == 'active')
			{
				$status = '<span class="label label-success">Active</span>';
			}
			else
			{
				$status = '<span class="label label-danger">Inactive</span>';
			}
			$output .= '
			<tr>
				<td>Product Name</td>
				<td>'.$row["product_name"].'</td>
			</tr>
			<tr>
				<td>Product Description</td>
				<td>'.$row["product_description"].'</td>
			</tr>
			<tr>
				<td>Category</td>
				<td>'.$row["category_name"].'</td>
			</tr>
			
			<tr>
				<td>Available Quantity</td>
				<td>'.$row["product_quantity"].' '.$row["product_unit"].'</td>
			</tr>
			<tr>
				<td>Base Price</td>
				<td>'.$row["product_base_price"].'</td>
			</tr>
			<tr>
				<td>Tax (%)</td>
				<td>'.$row["product_tax"].'</td>
			</tr>
			<tr>
				<td>Enter By</td>
				<td>'.$row["user_name"].'</td>
			</tr>
			<tr>
				<td>Status</td>
				<td>'.$status.'</td>
			</tr>
			';
		}
		$output .= '
			</table>
		</div>
		';
		echo $output;
	}
	if($_POST['btn_action'] == 'fetch_single')
	{
		$query = "
		SELECT * FROM stock_items WHERE item_id = :item_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':item_id'	=>	$_POST["item_id"]
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			
			$output['item_name'] = $row['item_name'];
			$output['item_hsn'] = $row['item_hsn'];
			$output['item_price'] = $row['item_price'];
			$output['item_quantity'] = $row['item_quantity'];

			$output['item_gst'] = $row['item_gst'];
			$output['unit'] = $row['unit'];
			$item_variety = $row['item_variety'];
			$arr=explode(" ", $item_variety);
			$output['item_variety']=$arr[0];
			//if($arr[1]!=null)
			$output['item_variety_unit']=$arr[1];
		}
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'Edit')
	{
		$query = "
		UPDATE product 
		set category_id = :category_id, 
		product_name = :product_name,
		product_description = :product_description, 
		product_quantity = :product_quantity, 
		product_unit = :product_unit, 
		product_base_price = :product_base_price, 
		product_tax = :product_tax 
		WHERE product_id = :product_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':category_id'			=>	$_POST['category_id'],
				':product_name'			=>	$_POST['product_name'],
				':product_description'	=>	$_POST['product_description'],
				':product_quantity'		=>	$_POST['product_quantity'],
				':product_unit'			=>	$_POST['product_unit'],
				':product_base_price'	=>	$_POST['product_base_price'],
				':product_tax'			=>	$_POST['product_tax'],
				':product_id'			=>	$_POST['product_id']
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Product Details Edited';
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
		UPDATE product 
		SET product_status = :product_status 
		WHERE product_id = :product_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':product_status'	=>	$status,
				':product_id'		=>	$_POST["product_id"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Product status change to ' . $status;
		}
	}
}


?>