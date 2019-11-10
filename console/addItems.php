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
                    <h1 class="page-header">Add Items</h1>
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
											<h3 class="panel-title">Product List</h3>
										</div>
									
										<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align='right'>
											<button type="button" name="add" id="add_button" class="btn btn-success btn-xs">Add</button>
											<?php if ($_SESSION['des']!=1) {
												echo "<script>document.getElementById('add_button').disabled = true;</script>";
											}?>
										</div>
									</div>
								</div>
								<div class="panel-body">
									<div class="row"><div class="col-sm-12 table-responsive">
										<table id="product_data" class="table table-bordered table-striped">
											<thead><tr>
												<th>Name</th>
												<th>HSN</th>
												<th>Price</th>
												<th>Quantity</th>
												<th>Variety</th>
												<th>GST</th>
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
				
				<div id="productModal" class="modal fade">
					<div class="modal-dialog">
						<form method="post" id="product_form">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="modal-title"><i class="fa fa-plus"></i> Add Product</h4>
								</div>
								<div class="modal-body">
									<div class="form-group input-group">
										<span class="input-group-addon">Name</span>
										<input class="form-control" placeholder="Item Name" id="item_name" name="item_name" type="text" autofocus required>
									</div>
									<div class="form-group input-group">
										<span class="input-group-addon">HSN</span>
										<input class="form-control" placeholder="HSN" name="item_hsn" id="item_hsn" type="text" value="">
									</div>
									<div class="form-group input-group">
										<span class="input-group-addon">Price &nbsp <i class="fa fa-inr"></i></span>
										<input class="form-control" placeholder="Price per unit" step="any" name="item_price" id="item_price" type="number" value="" min=1>
									</div>
									<div class="form-group input-group">
										<span class="input-group-addon">Quantity</span>
										<input class="form-control" placeholder="No. of units" name="item_quantity" id="item_quantity" type="number" value="" min=0>
										<span class="input-group-addon">
											<select name="item_unit" id="item_unit">
												<option value="Bags">Bags</option>
												<option value="Bottles">Bottles</option>
												<option value="Box">Box</option>
												<option value="Cartons">Cartons</option>
												<option value="Dozens">Dozens</option>
												<option value="Pieces">Pieces</option>
												<option value="Grams">Grams</option>
												<option value="Inch">Inch</option>
												<option value="Kg">Kg</option>
												<option value="Liters">Liters</option>
												<option value="Meter">Meter</option>
												<option value="Nos">Nos</option>
												<option value="Packet">Packet</option>
												<option value="Rolls">Rolls</option>
												<option value="Milligrams">Milligrams</option>
												<option value="Milliliters">Milliliters</option>
												<option value="Sets">Sets</option>
												<option value="Gruce">Gruce</option>
											</select>
										</span>
									</div>
									
									<div class="form-group input-group">
										<span class="input-group-addon">Variety</span>
										<input class="form-control" placeholder="No. of units" name="item_variety" id="item_variety" type="text" value="" min=0>
										<span class="input-group-addon">
											<select name="item_unit_variety" id="item_unit_variety">
												<option value="Bags">Bags</option>
												<option value="Bottles">Bottles</option>
												<option value="Box">Box</option>
												<option value="Cartons">Cartons</option>
												<option value="Dozens">Dozens</option>
												<option value="Feet">Feet</option>
												<option value="Pieces">Pieces</option>
												<option value="Gallon">Gallon</option>
												<option value="Grams">Grams</option>
												<option value="Inch">Inch</option>
												<option value="Kg">Kg</option>
												<option value="Liters">Liters</option>
												<option value="Meter">Meter</option>
												<option value="Nos">Nos</option>
												<option value="Packet">Packet</option>
												<option value="Rolls">Rolls</option>
												<option value="Milligrams">Milligrams</option>
												<option value="Milliliters">Milliliters</option>
												<option value="Sets">Sets</option>
												<option value="Gruce">Gruce</option>
											</select>
										</span>
									</div>
									<div class="form-group input-group">
										<span class="input-group-addon">GST</span>
										<select class="form-control" name="item_gst" id="item_gst" required>
											<option>0</option>
											<option>5</option>
											<option>12</option>
											<option>18</option>
											<option>28</option>
										</select>
										<span class="input-group-addon">%</span>
									</div>
								</div>
								<div class="modal-footer">
									<input type="hidden" name="item_id" id="item_id" />
									<input type="hidden" name="btn_action" id="btn_action" />
									<button type="submit" name="action" id="action" class="btn btn-success" value="Add">Add to Inventory</button>
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
			var productdataTable = $('#product_data').DataTable({
				"processing":true,
				"serverSide":true,
				"order":[],
				"ajax":{
					url:"actions/fetchItems.php",
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
				$('#productModal').modal('show');
				$('#product_form')[0].reset();
				$('.modal-title').html("<i class='fa fa-plus'></i> Add Product");
				$('#action').val("Add");
				$('#btn_action').val("Add");
			});

			$(document).on('click', '.update', function(){
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
    });

			$(document).on('submit', '#product_form', function(event){
				event.preventDefault();
				$('#action').attr('disabled', 'disabled');
				var form_data = $(this).serialize();
				$.ajax({
					url:"actions/addItem.php",
					method:"POST",
					data:form_data,
					success:function(data)
					{
						$('#product_form')[0].reset();
						$('#productModal').modal('hide');
						$('#alert_action').fadeIn().html('<div class="alert alert-success alert-dismissible fade in">'+
  '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+data+'</div>');
						$('#action').attr('disabled', false);
						productdataTable.ajax.reload();
					}
				})
			});
			
			
			$(document).on('click', '.delete', function(){
				var item_id = $(this).attr("id");
				var btn_action = "delete";
				if(confirm("Are you sure you want to delete Item?"))
				{
					$.ajax({
						url:"actions/addItem.php",
						method:"POST",
						data:{item_id:item_id, btn_action:btn_action},
						success:function(data)
						{
							$('#alert_action').fadeIn().html('<div class="alert alert-info">'+data+'</div>');
							productdataTable.ajax.reload();
						}
					})
				}
				else
				{
					return false;
				}
			});
		});
	</script>

</body>

</html>
