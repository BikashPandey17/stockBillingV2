<?php
	session_start();
	include("../script/dbase/dbase_cred.php");
  
	if(!isSet($_COOKIE['STOCK_LOGGED_IN']))
		header("location:../index.php?err=2");
	
	if(!isset($_SESSION['logged']) || !isset($_SESSION['des']))
		header("location:../index.php?err=2");
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Kalyan Majumdar">

    <title>Stock Management System</title>
    <script src="bower_components/jquery-1.10.2.min.js"></script>
    <!-- Bootstrap Core CSS -->
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />

    <!-- MetisMenu CSS -->
    <link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">Stock Management System</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
				<li style="text-align:center;padding:16px 16px;" data-toggle="tooltip" title=<?php echo ($_SESSION['des']==1?"Administrator":"Employee");?>>
					Welcome, <?php echo "".$_SESSION['user_name'];?>
				</li>
                <li class="dropdown pull-right">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li onclick="alert('Function Not Implemented Yet!');"><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="changePwd.php"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="../script/log/logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div>
                            <!-- /input-group -->
                        </li>
                        <li>
                            <a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>
                        <li>
                            <a href="addItems.php"><i class="fa fa-table fa-fw"></i> Add Item</a>
                        </li>
                        <li>
                            <a href="billItems.php"><i class="fa fa-table fa-fw"></i> Sell Item</a>
                        </li>
                        <li>
                            <a href="purchaseItems.php"><i class="fa fa-table fa-fw"></i> Purchase Item</a>
                        </li>
                        <li>
                            <a href="addSupplier.php"><i class="fa fa-table fa-fw"></i> Add Supplier</a>
                        </li>
						<?php
							if($_SESSION['des'] == 1) {
								echo "
									<li>
										<a href='users.php'><i class='fa fa-user fa-fw'></i> User Control</a>
									</li>
								";
							}
						?>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Account Settings</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
				<div class="col-md-12 col-md-offset-0">
					<span id='alert_action'></span>
					<div class="row">
						<div class="col-lg-6 col-xs-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									<div class="row">
										<div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
											<h3 class="panel-title">Change Password</h3>
										</div>
									</div>
								</div>
								<div class="panel-body">
									<div class="row">
										<form method="POST" id="pwdForm" style="padding:10px;">
											<div class="form-group input-group">
												<span class="input-group-addon">Old Password</span>
												<input class="form-control" placeholder="Old Password" id="old_pwd" name="old_pwd" type="password" autofocus required>
												<span class="input-group-addon" id="sp1"><i class="fa fa-eye-slash" id="eye1"></i></span>
											</div>
											<div class="form-group input-group">
												<span class="input-group-addon">New Password</span>
												<input class="form-control" placeholder="New Password" id="new_pwd" name="new_pwd" type="password" required>
												<span class="input-group-addon" id="sp2"><i class="fa fa-eye-slash" id="eye2"></i></span>
											</div>
											<div class="form-group input-group">
												<span class="input-group-addon">Confirm Password</span>
												<input class="form-control" placeholder="Confirm Password" id="new_pwd2" name="new_pwd2" type="password" required>
												<span class="input-group-addon" id="sp3"><i class="fa fa-eye-slash" id="eye3"></i></span>
											</div>
											<div class="btn-group btn-group-justified">
												<div class="btn-group">
													<button type="submit" name="action" id="action" class="btn btn-success" value="Add">Change Password</button>
												</div>
												<div class="btn-group">
													<button type="reset" class="btn btn-danger">Clear</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <!-- <script src="bower_components/jquery/dist/jquery.min.js"></script> -->
	<script src="../js/jquery.dataTables.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="../js/dataTables.bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>
	<script>
		$(document).ready(function(){
			$(document).on('submit', '#pwdForm', function(event){
				event.preventDefault();
				var pwd1 = $("#new_pwd").val();
				var pwd2 = $("#new_pwd2").val();
				if(pwd1 != pwd2) {
					alert("New Passwords don't match!!");
					return;
				}
				$('#action').attr('disabled', 'disabled');
				var form_data = $(this).serialize();
				$.ajax({
					url:"actions/changePassword.php",
					method:"POST",
					data:form_data,
					success:function(data)
					{
						$('#pwdForm')[0].reset();
						$('#alert_action').fadeIn().html('<div class="alert alert-success alert-dismissible fade in">'+
  '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+data+'</div>');
						$('#action').attr('disabled', false);
					}
				})
			});
		});
		
		
		var c = 1;
		$(document).on('click', '#sp1', function() {
			if ( c == 1 ) {
				$("#old_pwd").attr('type','text');
				c = 0;
				$("#eye1").removeClass("fa-eye-slash");
				$("#eye1").addClass("fa-eye");
			} else {
				$("#old_pwd").attr('type','password');
				c = 1;
				$("#eye1").removeClass("fa-eye");
				$("#eye1").addClass("fa-eye-slash");
			}
		});
		
		var c2 = 1;
		$(document).on('click', '#sp2', function() {
			if ( c2 == 1 ) {
				$("#new_pwd").attr('type','text');
				c2 = 0;
				$("#eye2").removeClass("fa-eye-slash");
				$("#eye2").addClass("fa-eye");
			} else {
				$("#new_pwd").attr('type','password');
				c2 = 1;
				$("#eye2").removeClass("fa-eye");
				$("#eye2").addClass("fa-eye-slash");
			}
		});
		
		var c3 = 1;
		$(document).on('click', '#sp3', function() {
			if ( c3 == 1 ) {
				$("#new_pwd2").attr('type','text');
				c3 = 0;
				$("#eye3").removeClass("fa-eye-slash");
				$("#eye3").addClass("fa-eye");
			} else {
				$("#new_pwd2").attr('type','password');
				c3 = 1;
				$("#eye3").removeClass("fa-eye");
				$("#eye3").addClass("fa-eye-slash");
			}
		});
	</script>

</body>

</html>
