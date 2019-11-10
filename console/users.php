<?php
	session_start();
	include("../script/dbase/dbase_cred.php");
  
	if(!isSet($_COOKIE['STOCK_LOGGED_IN']))
		header("location:../index.php?err=2");
	
	if(!isset($_SESSION['logged']) || !isset($_SESSION['des']))
		header("location:../index.php?err=2");
	
	if($_SESSION['des'] != 1) {
		echo "<script>alert('Permission Not Granted!');</script>";
		header("location:index.php");
	}
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
                    <h1 class="page-header">User Control</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
				<div class="col-md-12 col-md-offset-0">
					<span id='alert_action'></span>
					<div class="row">
						<div class="col-lg-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									<div class="row">
										<div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
											<h3 class="panel-title">User List</h3>
										</div>
									
										<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align='right'>
											<button type="button" name="add" id="add_button" class="btn btn-success btn-xs">Add</button>
										</div>
									</div>
								</div>
								<div class="panel-body">
									<div class="row"><div class="col-sm-12 table-responsive">
										<table id="user_data" class="table table-bordered table-striped">
											<thead><tr>
												<th>Name</th>
												<th>Username</th>
												<th>Designation</th>
												<th></th>
											</tr></thead>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div id="userModal" class="modal fade">
					<div class="modal-dialog">
						<form method="post" id="user_form">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="modal-title"><i class="fa fa-plus"></i> Add User</h4>
								</div>
								<div class="modal-body">
									<div class="form-group input-group">
										<span class="input-group-addon">Name</span>
										<input class="form-control" placeholder="Name" id="user_name" name="user_name" type="text" autofocus required>
									</div>
									<div class="form-group input-group">
										<span class="input-group-addon">Username</span>
										<input class="form-control" placeholder="Username" style="text-transform:lowercase;" name="username" id="username" type="text" value="" required>
									</div>
									<div class="form-group input-group">
										<span class="input-group-addon">Designation</span>
										<select class="form-control" name="user_desig" id="user_desig" required>
											<option value="administrator">Admin</option>
											<option value="employee" selected>Employee</option>
										</select>
									</div>
									<div class="form-group input-group">
										<span class="input-group-addon">Password</span>
										<input class="form-control" name="pwd1" id="pwd1" type="password" value="" required>
										<span class="input-group-addon" id="sp1"><i class="fa fa-eye-slash" id="eye1"></i></span>
									</div>
									<div class="form-group input-group">
										<span class="input-group-addon">Re-enter Password</span>
										<input class="form-control" name="pwd2" id="pwd2" type="password" value="" required>
										<span class="input-group-addon" id="sp2"><i class="fa fa-eye-slash" id="eye2"></i></span>
									</div>
								</div>
								<div class="modal-footer">
									<input type="hidden" name="user_id" id="user_id" />
									<input type="hidden" name="btn_action" id="btn_action" />
									<button type="submit" name="action" id="action" class="btn btn-success" value="Add">Add User</button>
									<!--input type="submit" name="action" id="action" class="btn btn-info" value="Add" /-->
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
							</div>
						</form>
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
	<?php if ($_SESSION['des']!=1) {
		echo "<script>
			var up = document.getElementsByName('update');
			for (i=0;i<up.length;i++){
				up[i].disabled = true;
			}
			</script>";
	}?>
	<script>
		$(document).ready(function(){
			var userdataTable = $('#user_data').DataTable({
				"processing":true,
				"serverSide":true,
				"order":[],
				"ajax":{
					url:"actions/fetchUsers.php",
					type:"POST"
				},
				"columnDefs":[
					{
						"targets":[ 0,1,2, 3],
						"orderable":false,
					},
				],
				"pageLength": 10
			});

			$('#add_button').click(function(){
				$('#userModal').modal('show');
				$('#userform')[0].reset();
				$('.modal-title').html("<i class='fa fa-plus'></i> Add User");
				$('#action').val("Add");
				$('#btn_action').val("Add");
			});

			/**$(document).on('click', '.update', function(){
				var sessionDes = '<?php echo $_SESSION['des'];?>';
				if (sessionDes != 1) {
					alert("Permission not granted!");
					return;
				}
				var item_id = $(this).attr("id");
				var btn_action = 'fetch_single';
				// alert(inventory_order_id,btn_action);
				$.ajax({
					url:"actions/addItem2.php",
					method:"POST",
					data:{item_id:item_id, btn_action:btn_action},
					dataType:"json",
					success:function(data){
						$('#productModal').modal('show');
						$('#item_name').val(data.item_name);
						$('#item_hsn').val(data.item_hsn);
						$('#item_price').val(data.item_price);
						$('#item_quantity').val(data.item_quantity);
						$('#item_gst').val(data.item_gst);
						$('#item_unit').val(data.unit);
						$('#item_unit_variety').val(data.item_variety_unit);
						$('#item_variety').val(data.item_variety);
						$('.modal-title').html("<i class='fa fa-pencil-square-o'></i> Edit Product");
						$('#item_id').val(item_id);
						$('#action').val("Edit");
						$('#btn_action').val("Edit");
					}
				})
			});*/

			$(document).on('submit', '#user_form', function(event){
				event.preventDefault();
				var pwd1 = $("#pwd1").val();
				var pwd2 = $("#pwd2").val();
				if(pwd1 != pwd2) {
					alert("Passwords don't match!!");
					return;
				}
				$('#action').attr('disabled', 'disabled');
				var form_data = $(this).serialize();
				$.ajax({
					url:"actions/addUser.php",
					method:"POST",
					data:form_data,
					success:function(data)
					{
						$('#user_form')[0].reset();
						$('#userModal').modal('hide');
						$('#alert_action').fadeIn().html('<div class="alert alert-success alert-dismissible fade in">'+
  '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+data+'</div>');
						$('#action').attr('disabled', false);
						userdataTable.ajax.reload();
					}
				})
			});
			//int c = 0;
		});
		
		
		var c = 0;
		$(document).on('click', '#sp1', function() {
			if ( c == 1 ) {
				$("#pwd1").attr('type','text');
				c = 0;
				$("#eye1").removeClass("fa-eye-slash");
				$("#eye1").addClass("fa-eye");
			} else {
				$("#pwd1").attr('type','password');
				c = 1;
				$("#eye1").removeClass("fa-eye");
				$("#eye1").addClass("fa-eye-slash");
			}
		});
		
		var c2 = 0;
		$(document).on('click', '#sp2', function() {
			if ( c2 == 1 ) {
				$("#pwd2").attr('type','text');
				c2 = 0;
				$("#eye2").removeClass("fa-eye-slash");
				$("#eye2").addClass("fa-eye");
			} else {
				$("#pwd2").attr('type','password');
				c2 = 1;
				$("#eye2").removeClass("fa-eye");
				$("#eye2").addClass("fa-eye-slash");
			}
		});
	</script>

</body>

</html>
