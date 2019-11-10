<?php

//product_fetch.php

include('../../script/dbase/dbase_cred.php');

$query = '';

$output = array();
$query .= "SELECT * FROM stock_login ";

if(isset($_POST["search"]["value"]))
{
	$query .= 'WHERE user_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR username LIKE "%'.$_POST["search"]["value"].'%" ';
}

if(isset($_POST['order']))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY id ASC ';
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
	$sub_array[] = $row['user_name'];
	$sub_array[] = $row['username'];
	$sub_array[] = $row['desig'];
	$sub_array[] = '<button type="button" name="delete" id="'.$row["id"].'" class="btn btn-danger btn-xs delete">Delete</button>';
	$data[] = $sub_array;
}

function get_total_all_records($con)
{
	$statement = mysqli_query($con, 'SELECT * FROM stock_login');
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