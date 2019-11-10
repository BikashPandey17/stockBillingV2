<?php
	session_start();
	include("../script/dbase/dbase_cred.php");
  	
	if(!isSet($_COOKIE['STOCK_LOGGED_IN']))
		header("location:../index.php?err=2");
	
	if(!isset($_SESSION['logged']) || !isset($_SESSION['des']))
		header("location:../index.php?err=2");
	
	function fill_product_list($con)
	{
		$query = "
		SELECT * FROM stock_items 
		ORDER BY item_id ASC
		";
		$statement = mysqli_query($con, $query);
		//$result = mysqli_fetch($statement);
		//$totalRows = mysqli_affected_rows($con);
		$output = '';
		 while($row=mysqli_fetch_assoc($statement))
		{
			$output .= '<option value="'.$row["item_id"].'">'.$row["item_name"].'|  Rs. '.$row["item_price"].'| Q - '.$row["item_quantity"].'|  U. '.$row["unit"].'|  G '.$row["item_gst"].'</option>';
		}
		return $output;
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
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
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
                    <h1 class="page-header">Sell Items</h1>
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
											<h3 class="panel-title">Order List</h3>
										</div>
									
										<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align='right'>
											<button type="button" name="add" id="add_button" class="btn btn-success btn-xs">Add</button>
										</div>
									</div>
								</div>
								<div class="panel-body">
									<div class="row"><div class="col-sm-12 table-responsive">
										<table id="order_data" class="table table-bordered table-striped">
											<thead><tr>
												<th>Order ID</th>
												<th>Customer Name</th>
												<th>Total Amount</th>
												<th>Created By</th>
												<th>Payment Status</th>
												
												<th>Order Date</th>
												
												<th>Customer GSTIN</th>
												
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
				
				<div id="orderModal" class="modal fade">
					<div class="modal-dialog">
						<form method="post" id="order_form">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="modal-title"><i class="fa fa-plus"></i> Create Order</h4>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Enter Receiver Name</label>
												<input type="text" name="inventory_order_name" id="inventory_order_name" class="form-control" />
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Date</label>
												<input type="date" name="inventory_order_date" id="inventory_order_date" class="form-control" required />
											</div>
										</div>
									</div>
									
									<div class="form-group">
										<label>Enter Receiver Address</label>
										<textarea name="inventory_order_address" id="inventory_order_address" class="form-control"></textarea>
									</div>
									<div class="form-group">
										<label>Enter State Code</label>
										<!--input type="number" name="state" id="state" class="form-control" min=1 max=37 / -->
										<select name="state" id="state" class="form-control">
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
									
									<div class="form-group">
										<label>Enter Customer GSTIN</label>
										<input type="text" name="gstin" class="form-control" placeholder="GSTIN" id="gstin" title="GSTIN" minlength=15 maxlength=15 />
									</div>
									<div class="form-group">
										<label>Enter Product Details</label>
										<hr />
										<span id="span_product_details"></span>
										<hr />
									</div>
									<div class="form-group">
										<label>Select Payment Status</label>
										<select name="payment_status" id="payment_status" class="form-control" onchange="paymentChanged();">
											<option value="cash">Cash</option>
											<option value="cheque">Cheque</option>
										</select>
										<div class="row">
											<div class="col-md-6">
												<label>Cheque Number</label>
												<input type="text" name="cheque_num" id="cheque_num" class="form-control" disabled />
											</div>
											<div class="col-md-6">
												<label>Cheque Date</label>
												<input type="date" name="cheque_date" id="cheque_date" class="form-control" disabled />
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<input type="hidden" name="order_id" id="order_id" />
									<input type="hidden" name="btn_action" id="btn_action" />
									<input type="submit" name="action" id="action" class="btn btn-info" value="Add" />
								</div>
							</div>
						</form>
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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>
	<script type="text/javascript">
		
			$('#add_button').click(function(){
				$('#orderModal').modal('show');
				$('#order_form')[0].reset();
				$('.modal-title').html("<i class='fa fa-plus'></i> Create Order");
				$('#action').val('Add');
				$('#btn_action').val('Add');
				$('#span_product_details').html('');
				add_product_row();
			});
			function add_product_row(count = '')
			{
				var html = '';
				html += '<span id="row'+count+'"><div class="row">';
				html += '<div class="col-md-12">';
				html += '<select name="product_id[]" id="product_id'+count+'" class="form-control selectpicker" data-live-search="true" required>';
				html += '<?php echo fill_product_list($con); ?>';
				html += '</select><input type="hidden" name="hidden_product_id[]" id="hidden_product_id'+count+'" />';
				html += '</div></div><div class="row">';
				html += '<div class="col-md-5">';
				html += '<input type="number" name="quantity[]" class="form-control" placeholder="Quantity" title="Quantity" required />';
				html += '</div>';
				html += '<div class="col-md-5">';
				html += '<input type="number" step="any" name="price[]" class="form-control" placeholder="Price" title="Price" required />';
				html += '</div>';
				html += '<div class="col-md-1">';
				if(count == '')
				{
					html += '<button type="button" name="add_more" id="add_more" class="btn btn-success btn-xs">+</button>';
				}
				else
				{
					html += '<button type="button" name="remove" id="'+count+'" class="btn btn-danger btn-xs remove">-</button>';
				}
				html += '</div>';
				html += '</div></div><br /></span>';
				$('#span_product_details').append(html);

				$('.selectpicker').selectpicker();
			}

			var count = 0;
			$(document).on('click', '#add_more', function(){
				count = count + 1;
				add_product_row(count);
			});
			$(document).on('click', '.remove', function(){
				var row_no = $(this).attr("id");
				$('#row'+row_no).remove();
			});
			
			function paymentChanged(){
				var paymentMethod = document.getElementById('payment_status').value;
				
				if(paymentMethod == "cash") {
					document.getElementById('cheque_num').disabled = true;
					document.getElementById('cheque_date').disabled = true;
				}
				else {
					document.getElementById('cheque_num').disabled = false;
					document.getElementById('cheque_date').disabled = false;
				}
			}
	</script>
	<script type="text/javascript">
		$(document).ready(function(){
			var orderdataTable = $('#order_data').DataTable({
				"processing":true,
				"serverSide":true,
				"order":[],
				"ajax":{
					url:"actions/fetchOrders.php",
					type:"POST"
				},
				"columnDefs":[
					{
						"targets":[4, 5, 6, 7, 8, 9],
						"orderable":false,
					},
				],
				"pageLength": 10
			});
			

			

			

			$(document).on('submit', '#order_form', function(event){
				event.preventDefault();
				$('#action').attr('disabled', 'disabled');
				var form_data = $(this).serialize();
				$.ajax({
					url:"actions/addOrder.php",
					method:"POST",
					data:form_data,
					success:function(data)
					{
						$('#order_form')[0].reset();
						$('#orderModal').modal('hide');
						$('#alert_action').fadeIn().html('<div class="alert alert-success alert-dismissible fade in">'+
  '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+data+'</div>');
						$('#action').attr('disabled', false);
						orderdataTable.ajax.reload();
					}
				});
			});
			
			
			

			
			
			$(document).on('click', '.update', function(){
				//var sessionDes = '<?php echo $_SESSION['des'];?>';
				//if (sessionDes != 1) {
				//	alert("Permission not granted!");
				//	return;
				//}
				var order_id = $(this).attr("id");
				var btn_action = 'fetch_single';
				// alert(inventory_order_id+btn_action);
				$.ajax({
					url:"actions/addOrder2.php",
					method:"POST",
					data:{order_id:order_id, btn_action:btn_action},
					dataType:"json",
					success:function(data)
					{

						$('#orderModal').modal('show');
						$('#inventory_order_name').val(data.order_name);
						$('#inventory_order_date').val(data.order_date);
						$('#inventory_order_amt').val(data.order_amt);
						$('#inventory_order_address').val(data.order_address);
						$('#state').val(data.state_name);
						$('#gstin').val(data.gstin);
						$('#span_product_details').html(data.product_details);
						$('#payment_status').val(data.payment_status);
						$('#cheque_num').val(data.cheque_number);
						
						$('#cheque_date').val(data.cheque_date);
						$('.modal-title').html("<i class='fa fa-pencil-square-o'></i> Edit Order");
						$('#order_id').val(order_id);
						$('#action').val('Edit');
						$('#btn_action').val('Edit');
					},
					
					error: function (jqXHR, exception) {
						var msg = '';
						if (jqXHR.status === 0) {
							msg = 'Not connect.\n Verify Network.';
						} else if (jqXHR.status == 404) {
							msg = 'Requested page not found. [404]';
						} else if (jqXHR.status == 500) {
							msg = 'Internal Server Error [500].';
						} else if (exception === 'parsererror') {
							msg = 'Requested JSON parse failed.';
						} else if (exception === 'timeout') {
							msg = 'Time out error.';
						} else if (exception === 'abort') {
							msg = 'Ajax request aborted.';
						} else {
							msg = 'Uncaught Error.\n' + jqXHR.responseText;
						}
						alert(msg);
					}
					
					
					
				})

			});
		});
	</script>

</body>

</html>
