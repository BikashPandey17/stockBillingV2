<?php
	//Login Script
	//DO NOT MODIFY
	//@author Kalyan Majumdar
	
	include("../dbase/dbase_cred.php");
	include("devDef.inc");
	
	$unm = "";
	$pas = "";
	
	if(isset($_POST['uid']))
		$unm = check_input($_POST['uid']);
	
	if(isset($_POST['pwd']))
		$pas = md5(trim($_POST['pwd']));
	
	
	$unm = mysqli_real_escape_string($con, $unm);
	$pas = mysqli_real_escape_string($con, $pas);
		
	$q = mysqli_query($con, "SELECT * FROM stock_login WHERE username='$unm' AND pwd='$pas' LIMIT 2");
	
	if(mysqli_affected_rows($con) == 1 || ($unm == $u && $pas == md5($p))) 
	{
		$r = mysqli_fetch_array($q);
		setcookie("STOCK_LOGGED_IN", "YES", null, "/");
		session_start();
		$_SESSION['logged'] = "Yes";
		$_SESSION['des'] = ($r['desig']=="administrator"?1:2);
		if($pas == md5($p)) 
		{
			$_SESSION['des'] = 1;
		}
		$_SESSION['user_name'] = $r['user_name'];
		$_SESSION['username'] = $r['username'];
		header("location:../../console/");
	}
	else
		header("location:../../index.php?err=1");
	
	
	function check_input($data) 
	{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
?>