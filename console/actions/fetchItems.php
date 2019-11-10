<?php

//product_fetch.php

include('../../script/dbase/dbase_cred.php');

$query = '';

$output = array();
$query .= "SELECT * FROM stock_items ";

if(isset($_POST["search"]["value"]))
{
	$query .= 'WHERE item_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR item_hsn LIKE "%'.$_POST["search"]["value"].'%" ';
}

if(isset($_POST['order']))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY item_id DESC ';
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
	$row = mysqli_fetch_array($statement);
	$sub_array = array();
	$sub_array[] = $row['item_name'];
	$sub_array[] = $row['item_hsn'];
	$sub_array[] = $row['item_price'];
	$sub_array[] = $row['item_quantity'].' '.$row['unit'];
	$sub_array[] = $row['item_variety'];
	$sub_array[] = $row['item_gst'];
	$sub_array[] = '<button type="button" name="view" id="'.$row["item_id"].'" class="btn btn-info btn-xs view">View</button>';
	$sub_array[] = '<button type="button" name="update" id="'.$row["item_id"].'" class="btn btn-warning btn-xs update">Update</button>';
	$sub_array[] = '<button type="button" name="delete" id="'.$row["item_id"].'" class="btn btn-danger btn-xs delete">Delete</button>';
	$data[] = $sub_array;
}

function get_total_all_records($con)
{
	$statement = mysqli_query($con, 'SELECT * FROM stock_items');
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