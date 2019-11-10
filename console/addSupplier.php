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
                    <h1 class="page-header">Add Supplier</h1>
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
											<h3 class="panel-title">Supplier List</h3>
										</div>
									
										<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align='right'>
											<button type="button" name="add" id="add_button" class="btn btn-success btn-xs">Add</button>
										</div>
									</div>
								</div>
								<div class="panel-body">
									<div class="row"><div class="col-sm-12 table-responsive">
										<table id="supplier_data" class="table table-bordered table-striped">
											<thead><tr>
												<th>Name</th>
												<th>Address</th>
												<th>GSTIN</th>
												<th>State</th>
												<th>Phone</th>
												<th></th>
												<th></th>
												<th></th>
											</tr></thead>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div id="supplierModal" class="modal fade">
					<div class="modal-dialog">
						<form method="post" id="supplier_form">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="modal-title"><i class="fa fa-plus"></i> Add Supplier</h4>
								</div>
								<div class="modal-body">
									<div class="form-group input-group">
										<span class="input-group-addon">Name</span>
										<input class="form-control" placeholder="Supplier Name" id="sup_name" name="sup_name" type="text" autofocus required>
									</div>
									<div class="form-group input-group">
										<span class="input-group-addon">Address</span>
										<textarea class="form-control" placeholder="Supplier Address" name="sup_address" id="sup_address" style="resize:vertical;" required></textarea>
									</div>
									<div class="form-group input-group">
										<span class="input-group-addon">GSTIN</span>
										<input class="form-control" required placeholder="GSTIN" name="sup_gstin" id="sup_gstin" type="text" minlength=15 maxlength=15 />
									</div>
									<div class="form-group input-group">
										<span class="input-group-addon">State</span>
										<select name="sup_state" id="sup_state" class="form-control">
											<option value="19,West Bengal">West Bengal</option>
											<option value="35,Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
											<option value="28,Andhra Pradesh">Andhra Pradesh</option>
											<option value="37,Andhra Pradesh(New)">Andhra Pradesh(New)</option>
											<option value="12,Arunachal Pradesh">Arunachal Pradesh</option>
											<option value="18,Assam">Assam</option>
											<option value="10,Bihar">Bihar</option>
											<option value="04,Chandigarh">Chandigarh</option>
											<option value="22,Chattisgarh">Chattisgarh</option>
											<option value="26,Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
											<option value="25,Daman and Diu">Daman and Diu</option>
											<option value="07,Delhi">Delhi</option>
											<option value="30,Goa">Goa</option>
											<option value="24,Gujarat">Gujarat</option>
											<option value="06,Haryana">Haryana</option>
											<option value="02,Himachal Pradesh ">Himachal Pradesh </option>
											<option value="01,Jammu and Kashmir">Jammu and Kashmir</option>
											<option value="20,Jharkhand">Jharkhand</option>
											<option value="29,Karnataka">Karnataka</option>
											<option value="32,Kerala">Kerala</option>
											<option value="31,Lakshadweep Islands">Lakshadweep Islands</option>
											<option value="23,Madhya Pradesh ">Madhya Pradesh </option>
											<option value="27,Maharashtra">Maharashtra</option>
											<option value="14,Manipur">Manipur</option>
											<option value="17,Meghalaya">Meghalaya</option>
											<option value="15,Mizoram">Mizoram</option>
											<option value="13,Nagaland">Nagaland</option>
											<option value="21,Odisha">Odisha</option>
											<option value="03,Punjab">Punjab</option>
											<option value="08,Rajasthan">Rajasthan</option>
											<option value="11,Sikkim">Sikkim</option>
											<option value="33,Tamil Nadu">Tamil Nadu</option>
											<option value="36,Telangana">Telangana</option>
											<option value="16,Tripura">Tripura</option>
											<option value="09,Uttar Pradesh">Uttar Pradesh</option>
											<option value="05,Uttarakhand">Uttarakhand</option>
										</select>
									</div>
									
									<div class="form-group input-group">
										<span class="input-group-addon">Phone</span>
										<input class="form-control" placeholder="Phone" name="sup_phone" id="sup_phone" type="text" minlength=10 />
									</div>
								</div>
								<div class="modal-footer">
									<input type="hidden" name="sup_id" id="sup_id" />
									<input type="hidden" name="btn_action" id="btn_action" />
									<button type="submit" name="action" id="action" class="btn btn-success" value="Add">Add to Database</button>
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
	
	<script>
		$(document).ready(function(){
			var supplierdataTable = $('#supplier_data').DataTable({
				"processing":true,
				"serverSide":true,
				"order":[],
				"ajax":{
					url:"actions/fetchSuppliers.php",
					type:"POST"
				},
				"columnDefs":[
					{
						"targets":[5, 6, 7],
						"orderable":false,
					},
				],
				"pageLength": 10
			});

			$('#add_button').click(function(){
				$('#supplierModal').modal('show');
				$('#supplier_form')[0].reset();
				$('.modal-title').html("<i class='fa fa-plus'></i> Add Supplier");
				$('#action').val("Add");
				$('#btn_action').val("Add");
			});

			// $(document).on('click', '.update', function(){
        // var item_id = $(this).attr("id");
        // var btn_action = 'fetch_single';
        // // alert(inventory_order_id,btn_action);
        // $.ajax({
            // url:"actions/addItem2.php",
            // method:"POST",
            // data:{item_id:item_id, btn_action:btn_action},
            // dataType:"json",
            // success:function(data){
                // $('#productModal').modal('show');
                // $('#item_name').val(data.item_name);
                // $('#item_hsn').val(data.item_hsn);
                // $('#item_price').val(data.item_price);
                // $('#item_quantity').val(data.item_quantity);
                // $('#item_gst').val(data.item_gst);
                // $('#item_unit').val(data.unit);
                // $('#item_unit_variety').val(data.item_variety_unit);
                // $('#item_variety').val(data.item_variety);
                // $('.modal-title').html("<i class='fa fa-pencil-square-o'></i> Edit Product");
                // $('#item_id').val(item_id);
                // $('#action').val("Edit");
                // $('#btn_action').val("Edit");
            // }
        // })
    // });

			$(document).on('submit', '#supplier_form', function(event){
				event.preventDefault();
				$('#action').attr('disabled', 'disabled');
				var form_data = $(this).serialize();
				$.ajax({
					url:"actions/addSupplier.php",
					method:"POST",
					data:form_data,
					success:function(data)
					{
						$('#supplier_form')[0].reset();
						$('#supplierModal').modal('hide');
						$('#alert_action').fadeIn().html('<div class="alert alert-success alert-dismissible fade in">'+
  '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+data+'</div>');
						$('#action').attr('disabled', false);
						supplierdataTable.ajax.reload();
					}
				})
			});
		});
	</script>

</body>

</html>
