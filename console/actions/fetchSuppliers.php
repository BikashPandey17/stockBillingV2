<?php

//product_fetch.php

include('../../script/dbase/dbase_cred.php');

$query = '';

$output = array();
$query .= "SELECT * FROM seller_details ";

if(isset($_POST["search"]["value"]))
{
	$query .= 'WHERE seller_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR seller_gstin LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR seller_state_name LIKE "%'.$_POST["search"]["value"].'%" ';
}

if(isset($_POST['order']))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY seller_id DESC ';
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
	$sub_array[] = $row['seller_name'];
	$sub_array[] = $row['seller_address'];
	$sub_array[] = $row['seller_gstin'];
	$state = explode(",", $row['seller_state_name']);
	$sub_array[] = $state[1];
	$sub_array[] = $row['seller_phone'];
	$sub_array[] = '<button type="button" name="view" id="'.$row["seller_id"].'" class="btn btn-info btn-xs view">View</button>';
	$sub_array[] = '<button type="button" name="update" id="'.$row["seller_id"].'" class="btn btn-warning btn-xs update">Update</button>';
	$sub_array[] = '<button type="button" name="delete" id="'.$row["seller_id"].'" class="btn btn-danger btn-xs delete">Delete</button>';
	$data[] = $sub_array;
}

function get_total_all_records($con)
{
	$statement = mysqli_query($con, 'SELECT * FROM seller_details');
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