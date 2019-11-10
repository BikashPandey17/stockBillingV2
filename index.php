<?php
	ob_start();
	session_start();
	
	include("script/dbase/dbase_cred.php");	

	if(isset($_COOKIE['STOCK_LOGGED_IN']) && isset($_COOKIE['STOCK_LOGGED_IN']))
	{
		if(isset($_SESSION['logged']) && isset($_SESSION['des'])) {
			header("location:console/");
		}
	}

	$msg = "";
	if(isSet($_GET['err']))
	{
		if($_GET['err'] == "1")
			$msg = "Wrong Username and Password Combination";
		if($_GET['err'] == "2")
			$msg = "Session timed out. Please Sign in to Continue.";
		
		echo "<script type='text/javascript'>alert('$msg');</script>";
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=1">
		<meta name="description" content="Stock Manangement System">
		<meta name="author" content="Kalyan Majumdar">
		<meta name="keywords" content="Business, Stock Management, Billing">

		<title>Stock Management</title>
		
		<!--link rel="shortcut icon" href="img/logo.png"--/>

		<!-- Bootstrap Core CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet">

		<!-- Custom CSS -->
		<link href="css/style.css" rel="stylesheet">
		
		<!-- Custom Fonts -->
		<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Jura:300,400" rel="stylesheet" type="text/css">
		
		<style>
			
		</style>

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>

	<body id="page-top" class="index" onload="myFunction();" style="overflow-x:hidden;">
		<!-- Navigation -->
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header page-scroll">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<div class="navbar-brand">Stock Management System</div>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav navbar-right">
						<li class="hidden">
							<a href="#page-top"></a>
						</li>
						<li>
							<a class="page-scroll" href="#">Login to access Stock &amp Billing</a>
						</li>
					</ul>
				</div>
				<!-- /.navbar-collapse -->
			</div>
			<!-- /.container-fluid -->
		</nav>
		
		<header>
			<form class="col-md-4 col-md-offset-4 col-xs-12" action="script/log/login.php" method="POST">
				<div class="row">
					<div class="col-md-12 form-heading">
						Login to your account
					</div>
				</div>
				
				<div class="row form-group">
					<div class="col-md-4">
						<label for="uid" class="pull-left">User ID:</label>
					</div>
					<div class="col-md-12">
						<input type="text" class="form-control" id="uid" name="uid">
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<label for="pwd" class="pull-left">Password:</label>
					</div>
				</div>
				<div class="input-group mb-3">
					<span class="input-group-addon"><input id="showP" type="checkbox" aria-label="Show Password"><i class="fa fa-eye"></i></span>
					<input type="password" class="form-control" id="pwd" name="pwd">
				</div>
				
				<div class="row form-group">
					<div class="col-md-offset-1 col-md-10">
						<button type="submit" class="btn btn-success btn-block"><span class="fa fa-sign-in">&nbsp&nbsp</span>Login</button>
					</div>
					<div class="col-md-offset-2 col-md-8">
						<button type="reset" class="btn btn-danger btn-block" style="margin-top:10px;"><span>&times&nbsp&nbsp</span>Cancel</button>
					</div>
				</div>
			</form>
		</header>
		
		<footer>
			<div class="container">
				<div class="row">
					<div class="col-md-6">
						<span>Copyright &copy; Pratik Gupta</span>
					</div>
					<div class="col-md-6">
						<ul class="list-inline">
							<li>
								<span>
									Designed and Developed by<br/>Kalyan Majumdar, Bikash Pandey
								</span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</footer>
		
		
		
		<!-- jQuery -->
		<script src="js/jquery.js"></script>

		<!-- Bootstrap Core JavaScript -->
		<script src="js/bootstrap.min.js"></script>
		
		<script>
			$(function() {
				$("#showP").change(function() {
					if ( $("#showP").prop('checked') ) {
						$("#pwd").attr('type','text');
					} else {
						$("#pwd").attr('type','password');
					}
				});
			});
		</script>
	</body>
</html>
