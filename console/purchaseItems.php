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
		ORDER BY item_name ASC
		";
		$statement = mysqli_query($con, $query);
		//$result = mysqli_fetch($statement);
		//$totalRows = mysqli_affected_rows($con);
		$output = '';
		 while($row=mysqli_fetch_assoc($statement))
		{
			$output .= '<option value="'.$row["item_id"].'">'.$row["item_name"].'</option>';
		}
		return $output;
	}
	
	function fill_supplier_list($con)
	{
		$query = "
		SELECT * FROM seller_details 
		ORDER BY seller_id ASC
		";
		$statement = mysqli_query($con, $query);
		//$result = mysqli_fetch($statement);
		//$totalRows = mysqli_affected_rows($con);
		$output = '';
		 while($row=mysqli_fetch_assoc($statement))
		{
			$output .= '<option value="'.$row["seller_id"].'">'.$row["seller_name"].'</option>';
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
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"/>
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
                    <h1 class="page-header">Purchase Items</h1>
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
											<h3 class="panel-title">Purchase List</h3>
										</div>
									
										<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align='right'>
											<button type="button" name="add" id="add_button" class="btn btn-success btn-xs">Add</button>
										</div>
									</div>
								</div>
								<div class="panel-body">
									<div class="row"><div class="col-sm-12 table-responsive">
										<table id="purchase_data" class="table table-bordered table-striped">
											<thead><tr>
												<th>Purchase ID</th>
												<th>Invoice No</th>
												<th>Invoice Date</th>
												<th>Supplier Name</th>
												<th>Supplier Address</th>
												<th>Supplier GSTIN</th>
												<th>Supplier State Code</th>
												<th>Total Amount</th>
												<th>Discount (%)</th>
												<th>Special Discount (Rs)</th>
												<th>User Name</th>
												<th>Payment</th>
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
				
				<div id="purchaseModal" class="modal fade">
					<div class="modal-dialog">
						<form method="post" id="purchase_form">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="modal-title"><i class="fa fa-plus"></i> Create Purchase</h4>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Invoice Number</label>
												<input type="text" name="invoice_no" id="invoice_no" class="form-control" required />
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Date</label>
												<input type="date" name="invoice_date" id="invoice_date" class="form-control" required />
											</div>
										</div>
									</div>
									<!--div class="form-group">
										<label>Enter Supplier Name</label>
										<input type="text" name="invoice_name" id="invoice_name" class="form-control" required/>
									</div>
									<div class="form-group">
										<label>Enter Supplier Address</label>
										<textarea name="invoice_address" id="invoice_address" class="form-control" required style="resize:vertical;"></textarea>
									</div-->
									<div class="row">
										<!--div class="col-md-4">
											<div class="form-group">
												<label>Supplier GSTIN</label>
												<input type="text" name="invoice_gstin" id="invoice_gstin" class="form-control" minlength=15 maxlength=15 required />
											</div>
										</div-->
										<div class="col-md-8">
											<div class="form-group">
												<label>Select Supplier</label>
												<select name="seller_name" id="seller_id" class="form-control supplierpicker" required style="width:100%;" onchange="alert(document.getElementById('seller_id').value);">
													<?php echo fill_supplier_list($con); ?>
												</select>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>Diccount (%)</label>
												<div class="input-group">
													<input type="number" step="any" name="invoice_disc" id="invoice_disc" class="form-control" required />
													<span class="input-group-addon">%</span>
												</div>
											</div>
										</div>
										<!--div class="col-md-5">
											<div class="form-group">
												<label>State Code</label>
												// <!--input type="number" name="invoice_state_code" id="invoice_state_code" class="form-control" required /-->
												<!--select name="invoice_state_code" id="invoice_state_code" class="form-control">
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
										</div-->
									</div>
									<div class="form-group">
										<label>Special Discount after GST (Rs.)</label>
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-inr"></i></span>
											<input type="number" step="any" name="special_disc" id="special_disc" class="form-control"/>
										</div>
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
									<!--div class="form-group">
										<label>Select Payment Status</label>
										<select name="payment_status" id="payment_status" class="form-control">
											<option value="cash">Cash</option>
											<option value="credit">Credit</option>
										</select>
									</div-->
								</div>
								<div class="modal-footer">
									<input type="hidden" name="purchase_id" id="purchase_id" />
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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>
	<script type="text/javascript">
		
			$('#add_button').click(function(){
				$('#purchaseModal').modal('show');
				$('#purchase_form')[0].reset();
				$('.modal-title').html("<i class='fa fa-plus'></i> Create Purchase");
				$('#action').val('Add');
				$('#btn_action').val('Add');
				$('#span_product_details').html('');
				$('.supplierpicker').selectpicker();
				add_product_row();
			});
			
			function add_product_row(count = '')
			{
				var html = '';
				html += '<span id="row'+count+'"><div class="row">';
				html += '<div class="col-md-8">';
				html += '<select name="product_name[]" id="product_id'+count+'" class="form-control selectpicker" required style="width:100%;" onchange="alert(document.getElementById(\'product_id'+count+'\').value);">';
				html += '<?php echo fill_product_list($con); ?>';
				html += '</select>';
				html += '<input type="hidden" name="hidden_product_id[]" id="hidden_product_id'+count+'" />';
				html += '</div>';
				
				html += '<div class="col-md-4">';
				html += '<input type="number" name="hsn[]" id="hsn'+count+'" class="form-control" placeholder="HSN" required />';
				html += '</div></div>';
				
				
				html += '<div class="row"><div class="col-md-3">';
				html += '<input type="number" name="quantity[]" class="form-control" title="Quantity" placeholder="Quantity" required /></div><div class="col-md-3">';
				html += '<select name="unit[]" id="unit'+count+'" class="form-control" title="Unit" required>';
				html += '<option>Box</option>';
				html += '<option>Pieces</option>';
				html += '<option>Dozens</option>';
				html += '<option>Cartons</option>';
				html += '<option>Jars</option>';
				html += '<option>Packet</option>';
				html += '<option>Sets</option>';
				html += '<option>Gruce</option>';
				html += '<option>Patta</option>';
				html += '<option value="Bags">Bags</option>';
				html += '<option value="Bottles">Bottles</option>';
				html += '<option value="Feet">Feet</option>';
				html += '<option value="Gallon">Gallon</option>';
				html += '<option value="Grams">Grams</option>';
				html += '<option value="Inch">Inch</option>';
				html += '<option value="Kg">Kg</option>';
				html += '<option value="Liters">Liters</option>';
				html += '<option value="Meter">Meter</option>';
				html += '<option value="Nos">Nos</option>';
				html += '<option value="Rolls">Rolls</option>';
				html += '<option value="Milligrams">Milligrams</option>';
				html += '<option value="Milliliters">Milliliters</option>';
				html += '</select>';
				html += '</div>';
				html += '<div class="col-md-2">';
				html += '<input type="number" name="price[]" class="form-control" title="Price" placeholder="Price" step="any" required />';
				html += '</div>';
				
				html += '<div class="col-md-3">';
				html += '<div class="form-group input-group">';
				html += '<select name="gst[]" id="gst'+count+'" class="form-control" title="GST" required>';
				html += '<option>0</option>';
				html += '<option>5</option>';
				html += '<option>12</option>';
				html += '<option>18</option>';
				html += '<option>28</option>';
				html += '</select>';
				html += '<span class="input-group-addon">%</span>';
				html += '</div>';
				html += '</div>';
				
				html += '<div class="col-md-1" style="margin-left:-10px;">';
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
				
				$(".selectpicker").select2({
				  tags: true,
				  createTag: function (params) {
					return {
					  id: params.term,
					  text: params.term,
					  newOption: true
					}
				  },
				   templateResult: function (data) {
					var $result = $("<span></span>");

					$result.text(data.text);

					if (data.newOption) {
					  $result.append(" <em>(new)</em>");
					}

					return $result;
				  }
				});
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
			var purchasedataTable = $('#purchase_data').DataTable({
				"processing":true,
				"serverSide":true,
				"order":[],
				"ajax":{
					url:"actions/fetchPurchases.php",
					type:"POST"
				},
				"columnDefs":[
					{
						"targets":[3,4, 5, 6, 7,8,9,10,11,12],
						"orderable":false,
					},
				],
				"pageLength": 10
			});
			

			$(document).on('click', '.update', function(){
				// var sessionDes = '<?php echo $_SESSION['des'];?>';
				// if (sessionDes != 1) {
					// alert("Permission not granted!");
					// return;
				// }

				var purchase_id = $(this).attr("id");

				var btn_action = 'fetch_single';

				// alert(inventory_order_id+btn_action);

				$.ajax({

					url:"actions/addPurchase2.php",

					method:"POST",

					data:{purchase_id:purchase_id, btn_action:btn_action},

					dataType:"json",

					success:function(data)

					{



						$('#purchaseModal').modal('show');

						 $('#invoice_no').val(data.invoice_no);

						 $('#invoice_date').val(data.purchase_date);

						//$('#invoice_name').val(data.supplier_name);

						//$('#invoice_address').val(data.supplier_address);

						$('#seller_id').val(data.seller_id);

						$('#invoice_gstin').val(data.supplier_gstin);

						$('#invoice_disc').val(data.discount);

						 $('#span_product_details').html(data.product_details);

						$('#invoice_state_code').val(data.supplier_state_name);

						$('.modal-title').html("<i class='fa fa-pencil-square-o'></i> Edit Purchase");

						$('#purchase_id').val(purchase_id);

						$('#special_disc').val(data.special_disc);

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

			

			$(document).on('submit', '#purchase_form', function(event){
				event.preventDefault();
				$('#action').attr('disabled', 'disabled');
				var form_data = $(this).serialize();
				$.ajax({
					url:"actions/addPurchase.php",
					method:"POST",
					data:form_data,
					success:function(data)
					{
						$('#purchase_form')[0].reset();
						$('#purchaseModal').modal('hide');
						$('#alert_action').fadeIn().html('<div class="alert alert-success alert-dismissible fade in">'+
  '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+data+'</div>');
						$('#action').attr('disabled', false);
						purchasedataTable.ajax.reload();
					}
				});
			});
			$(document).on('click', '.delete', function(){
			var purchase_id = $(this).attr("id");
			var btn_action = "delete";
			if(confirm("Are you sure you want to change status?"))
			{
				$.ajax({
					url:"actions/addPurchase.php",
					method:"POST",
					data:{purchase_id:purchase_id, btn_action:btn_action},
					success:function(data)
					{
						$('#alert_action').fadeIn().html('<div class="alert alert-info">'+data+'</div>');
						purchasedataTable.ajax.reload();
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
