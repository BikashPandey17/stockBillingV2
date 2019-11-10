<?php 

include('../../script/dbase/dbase_cred.php');



$query = '';



$output = array();



$query .= "SELECT * FROM stock_orders ";



if(isset($_POST["search"]["value"]))

{

	$query .= 'WHERE (order_id LIKE "%'.$_POST["search"]["value"].'%" ';

	$query .= 'OR order_name LIKE "%'.$_POST["search"]["value"].'%" ';

	$query .= 'OR order_amt LIKE "%'.$_POST["search"]["value"].'%" ';

	$query .= 'OR order_date LIKE "%'.$_POST["search"]["value"].'%") ';

}





if(isset($_POST["order"]))

{

	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';

}

else

{

	$query .= 'ORDER BY order_id DESC ';

}


if($_POST['length'] != -1)
{
	$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}


$statement = mysqli_query($con, $query);

$data = array();

$filtered_rows = mysqli_affected_rows($con);

for($k = 0; $k < $filtered_rows; $k++)

{

	$payment_status = '';

	

	$row = mysqli_fetch_array($statement);



	if($row['payment_status'] == 'cash')

	{

		$payment_status = '<span class="label label-primary">Cash</span>';

	}

	else if($row['payment_status'] == 'cheque')

	{
		$cheque_details = $row['cheque_details'];
		$arr = explode(",", $cheque_details);
		$check_num = $arr[0];
		$check_date = $arr[1];
		$payment_status = '<span class="label label-warning">Cheque</span><br/>'.$check_num.'<br/>'.$check_date;

	}

	

	$sub_array = array();

	$sub_array[] = $row['order_id'];

	$sub_array[] = $row['order_name'];

	$sub_array[] = $row['order_amt'];

	$sub_array[]= $row['user_name'];

	$sub_array[] = $payment_status;

	$sub_array[] = $row['order_date'];
	
	$sub_array[] = $row['gstin'];

	

	$sub_array[] = '<a href="view_order.php?pdf=1&order_id='.$row["order_id"].'" class="btn btn-info btn-xs" target="_blank">View PDF</a>';

	$sub_array[] = '<button type="button" name="update" id="'.$row["order_id"].'" class="btn btn-warning btn-xs update">Update</button>';

	$sub_array[] = '<button type="button" name="delete" id="'.$row["order_id"].'" class="btn btn-danger btn-xs delete">Delete</button>';

	$data[] = $sub_array;

}



function get_total_all_records($con)

{

	$statement = mysqli_query($con, 'SELECT * FROM stock_orders');

	return mysqli_affected_rows($con);

}



$output = array(

	"draw"    			=> 	intval($_POST["draw"]),

	"recordsTotal"  	=>  $filtered_rows,

	"recordsFiltered" 	=> 	get_total_all_records($con),

	"data"    			=> 	$data

);	



echo json_encode($output);



?>