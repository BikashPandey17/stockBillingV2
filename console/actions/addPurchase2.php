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



		SELECT * FROM stock_purchase WHERE purchase_id = :purchase_id



		";



		$statement = $connect->prepare($query);



		$statement->execute(



			array(



				':purchase_id'	=>	$_POST["purchase_id"]



			)



		);



		$result = $statement->fetchAll();



		$output = array();



		foreach($result as $row)



		{



			$output['user_name'] = $row['user_name'];



			$output['invoice_no'] = $row['invoice_no'];



			$output['purchase_amt'] = $row['purchase_amt'];



			$output['discount'] = $row['discount'];	



			$output['purchase_date'] = $row['purchase_date'];



			$output['supplier_name'] = $row['supplier_name'];



			$output['supplier_address']=$row['supplier_address'];



			$output['supplier_state_code']=$row['supplier_state_code'];



			$output['supplier_state_name']=$row['supplier_state_name'];



			$output['supplier_gstin']=$row['supplier_gstin'];

			$output['special_disc']=$row['special_disc'];

			$output['seller_id']=$row['seller_id'];

		}



		// echo "SELECT * FROM inventory_order_product 



		// WHERE inventory_order_id = '".$_POST["order_id"]."'";



		$sub_query = "



		SELECT * FROM inventory_purchase_product 



		JOIN stock_items on inventory_purchase_product.product_id=stock_items.item_id



		WHERE inventory_purchase_id = '".$_POST["purchase_id"]."'



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



						<select name="product_name[]" id="product_id'.$count.'" class="form-control selectpicker" data-live-search="true" required style="width:100%;">



							'.fill_product_list($con).'



						</select>



						<input type="hidden" name="hidden_product_id[]" id="hidden_product_id'.$count.'" value="'.$sub_row["product_id"].'" />



					</div>

					<div class="col-md-4">

					<input type= "number" name="hsn[]" id="hsn'.$count.'" classs="form-control" placeholder="HSN" value="'.$sub_row["item_hsn"].'" required />

					</div></div>

					<div class="row">



					<div class="col-md-3">



						<input type="number" name="quantity[]" class="form-control" value="'.$sub_row["quantity"].'" placeholder="Quantity" title="Quantity" required/>



					</div>



					<div class="col-md-3">';
					$array_unit=array('Box','Pieces','Dozens','Cartons','Jars','Packet','Sets','Patta','Bags','Bottles','Feet','Gallon','Grams','Inch','Kg','Liters','Meter','Nos','Rolls','Milligrams','Milliliters');
					for($i=0;$i<21;$i++){
						if($sub_row["unit"] == $array_unit[$i]){$selected[$i]='selected';}
						else{$selected[$i]='';}
					}

					$product_details.='<select value="'.$sub_row["unit"].'" name="unit[]" id="unit'.$count.'" class="form-control" title="Unit" required>';
					for($i=0;$i<21;$i++){
						$product_details.='<option value="'.$array_unit[$i].'" '.$selected[$i].'>'.$array_unit[$i].'</option>';
					}

				// 	<option value="Box">Box</option>

				// <option value="Pieces">Pieces</option>

				// <option value="Dozens">Dozens</option>

				// <option value="Cartons">Cartons</option>

				// <option value="Jars">Jars</option>

				// <option value="Packet">Packet</option>

				// <option value="Sets">Sets</option>

				// <option value="Patta">Patta</option>

				// <option value="Bags">Bags</option>

				// <option value="Bottles">Bottles</option>

				// <option value="Feet">Feet</option>

				// <option value="Gallon">Gallon</option>

				// <option value="Grams">Grams</option>

				// <option value="Inch">Inch</option>

				// <option value="Kg">Kg</option>

				// <option value="Liters">Liters</option>

				// <option value="Meter">Meter</option>

				// <option value="Nos">Nos</option>

				// <option value="Rolls">Rolls</option>

				// <option value="Milligrams">Milligrams</option>

			 //    <option value="Milliliters">Milliliters</option>
			   $product_details.='
				</select>

				</div>

			<div class="col-md-2">

				<input type="number" name="price[]" class="form-control" title="Price" placeholder="Price" value="'.$sub_row["price"].'" step="any" required />

				</div>



				<div class="col-md-3">

				<div class="form-group input-group">';

				if((int)$sub_row["tax"]==0){

					$product_details .= '<select value="'.(int)$sub_row["tax"].'" name="gst[]" id="gst'.$count.'" class="form-control" title="GST" step="any" required>

				<option selected="selected">0</option>

				<option >5</option>

				<option>12</option>

				<option>18</option>

				<option>28</option>

				</select>';

				}

				if((int)$sub_row["tax"]==5){

					$product_details .= '<select value="'.(int)$sub_row["tax"].'" name="gst[]" id="gst'.$count.'" class="form-control" title="GST" step="any" required>

				<option >0</option>

				<option selected="selected">5</option>

				<option>12</option>

				<option>18</option>

				<option>28</option>

				</select>';

				}

				else if((int)$sub_row["tax"]==12){

					$product_details .= '<select value="'.(int)$sub_row["tax"].'" name="gst[]" id="gst'.$count.'" class="form-control" title="GST" step="any" required>

				<option >0</option>

				<option >5</option>

				<option selected="selected">12</option>

				<option>18</option>

				<option>28</option>

				</select>';

				}

				else if((int)$sub_row["tax"]==18){

					$product_details .= '<select value="'.(int)$sub_row["tax"].'" name="gst[]" id="gst'.$count.'" class="form-control" title="GST" step="any" required>

				<option >0</option>

				<option >5</option>

				<option>12</option>

				<option selected="selected">18</option>

				<option>28</option>

				</select>';

				}

				else if((int)$sub_row["tax"]==28){

					$product_details .= '<select value="'.(int)$sub_row["tax"].'" name="gst[]" id="gst'.$count.'" class="form-control" title="GST" step="any" required>

				<option >0</option>

				<option >5</option>

				<option>12</option>

				<option>18</option>

				<option selected="selected">28</option>

				</select>';

				}



				$product_details.='<span class="input-group-addon">%</span>

				</div>

				</div>

				

				<div class="col-md-1" style="margin-left:-10px;">';



					







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