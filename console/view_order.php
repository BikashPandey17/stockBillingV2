<?php



//view_order.php

session_start();

  include("../script/dbase/dbase_cred.php");

    

  if(!isSet($_COOKIE['STOCK_LOGGED_IN']))

    header("location:../index.php?err=2");

  

  if(!isset($_SESSION['logged']) || !isset($_SESSION['des']))

    header("location:../index.php?err=2");



if(isset($_GET["pdf"]) && isset($_GET['order_id']))

{

  require_once 'pdf.php';

  include('../script/dbase/database_connection.php');

  

  $output = '';

   $statement = $connect->prepare("

    SELECT * FROM stock_orders 

    WHERE order_id = :order_id

    LIMIT 1

   ");

   $statement->execute(

    array(

      ':order_id'       =>  $_GET["order_id"]

    )

   );

   $result = $statement->fetchAll();

   foreach($result as $row)

   {

       $order_id=$row["order_id"];

    if($row['state_code']==19)

    {

		$odate = explode("-", $row["order_date"]);
        $currentYear = $odate[0] - 2018;
		$currentMonth = $odate[1];
		
		if($currentMonth >= 4) {
			$query = "SELECT * FROM stock_orders WHERE DATE_FORMAT(order_date, '%Y')<".($currentYear+2018)." OR (DATE_FORMAT(order_date, '%Y')=".($currentYear+2018)." AND DATE_FORMAT(order_date, '%m')<4)";
		}
		else {
			$query = "SELECT * FROM stock_orders WHERE DATE_FORMAT(order_date, '%Y')<".($currentYear+2017)." OR (DATE_FORMAT(order_date, '%Y')=".($currentYear+2017)." AND DATE_FORMAT(order_date, '%m')<4)";
		}
	
	
	$statement = mysqli_query($con, $query);
    $countInYear = mysqli_affected_rows($con);
	
	$order_id_new = $order_id-$countInYear;

    $output .= '

<style type="text/css">

.tg  {border-collapse:collapse;border-spacing:0;}

.tg td{font-family:Arial, sans-serif;font-size:14px;padding:4px 1px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}

.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:4px 6px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}

.tg .tg-baqh{text-align:center;vertical-align:top}

.tg .tg-cayh{text-align:center;vertical-align:bottom}

.tg .tg-yw4l{vertical-align:top;}

.tg-yw42{vertical-align:top;text-align:center}

.tg .page_break { page-break-before: always; }

</style>

<table class="tg" style="undefined;table-layout: fixed; width: 810px;height:1000px;">



  <tr>

    <td class="tg-yw4l" colspan="20"  style="font-size:12px"><pre><p align="center">                                               <u>TAX INVOICE</u></p></pre></br><div class="tg-yw42" ><h1 style="text-align:center;">MAA BHAWANI BHANDER</h1><br><p  align="center"><i>All Types of Cosmetics Brush, Playing Cards</i></p><br><h3 align="center">20 & 21, RAM MOHAN MULLICK LANE, KOLKATA - 700 007</h3><br><h3 align="center">PHONE : (S) 2268 1596, 4603 6767, MOB. : 9831240264</h3><br/><p>GSTIN:19AANFM9893J1Z6<p></div></td>

  </tr>

  <tr>

    <td class="tg-yw4l" colspan="10">Reverse Charge :</td>

    <td class="tg-yw4l" colspan="10">Transportation Mode:</td>

  </tr>

  <tr>

    <td class="tg-yw4l" colspan="10">Invoice No. : '.$order_id_new.' </td>

    <td class="tg-yw4l" colspan="10">Vehicle no. :</td>

  </tr>

  <tr>

    <td class="tg-yw4l" colspan="10">Invoice Date : '.$row["order_date"].'</td>

    <td class="tg-yw4l" colspan="10">Date of Supply</td>

  </tr>

  <tr>

    <td class="tg-yw4l" colspan="10">State : West Bengal  State code : 19 </td>

    <td class="tg-yw4l" colspan="10">Place of Supply</td>

  </tr>

  <tr>

    <td class="tg-baqh" colspan="10">Details of reciever (Billed to )</td>

    <td class="tg-baqh" colspan="10">Details of Consignee (Shipped to)</td>

  </tr>

  <tr>

    <td class="tg-yw4l" colspan="10">Name :  '.$row["order_name"].'</td>

    <td class="tg-yw4l" colspan="10">Name:</td>

  </tr>

  <tr>

    <td class="tg-yw4l" colspan="10">Address :'.$row["order_address"].'</td>

    <td class="tg-yw4l" colspan="10">Address :</td>

  </tr>

  <tr>

    <td class="tg-yw4l" colspan="10">State: West Bengal  &nbsp; &nbsp; &nbsp;   State code: '.$row['state_code'].'</td>

    <td class="tg-yw4l" colspan="10">State:  State code:</td>

  </tr>

  <tr>

    <td class="tg-yw4l" colspan="10">GSTIN:'.$row['gstin'].'</td>

    <td class="tg-yw4l" colspan="10">GSTIN:</td>

  </tr>';

  $statement = $connect->prepare("

     SELECT * FROM inventory_order_product 

     WHERE inventory_order_id = :inventory_order_id

    ");

    $statement->execute(

     array(

       ':inventory_order_id'       =>  $_GET["order_id"]

     )

    );

    $product_result = $statement->fetchAll();

    $count = 0;

    $total = 0;

    $total_actual_amount = 0;

    $total_tax_amount = 0;

    $total_cgst=0;

    $total_sgst=0;

	$total_quantity = 0;

      $output .= '<tr>

    <th class="tg-yw4l" rowspan="2">Sr.<br>no.</th>

    <th class="tg-baqh" colspan="4" rowspan="2">Name of Product</th>

    <th class="tg-baqh" rowspan="2">HSN</th>

    <th class="tg-baqh" rowspan="2">Qty.</th>

    

    <th class="tg-yw4l" rowspan="2">Unit</th>

    <th class="tg-yw4l" rowspan="2" colspan="2">Rate</th>

    <th class="tg-yw4l" rowspan="2" colspan="2">Taxable Amount</th>

    <th class="tg-baqh" colspan="2">CGST</th>

    <th class="tg-baqh" colspan="2">SGST</th>

    <th class="tg-baqh" colspan="2">IGST</th>

    <th class="tg-yw4l" colspan="2" rowspan="2">TOTAL </th>

  </tr>

  <tr>

    <th class="tg-yw4l">Rate %</th>

    <th class="tg-yw4l">Amt.</th>

    <th class="tg-yw4l">Rate %</th>

    <th class="tg-yw4l">Amt.</th>

    <th class="tg-yw4l">Rate %</th>

    <th class="tg-yw4l">Amt.</th>

  </tr>

  ';

    foreach($product_result as $sub_row)

     {

     $count = $count + 1;

      //$product_data = fetch_product_details($sub_row['product_id'], $con);

     $query = "SELECT * FROM stock_items WHERE item_id = '".$sub_row['product_id']."'";



     $statement = mysqli_query($con, $query);



    //$totalRow = mysqli_affected_rows($connect);



      while($row=(mysqli_fetch_assoc($statement)))



    {



      $product_data['product_name'] = $row["item_name"];



      $product_data['hsn'] = $row["item_hsn"];



      $product_data['tax'] = $row["item_gst"];

	  

	  $product_data['unit'] = $row["unit"];



    }

      $cgst=$sub_row["tax"]/2;

      $sgst=$sub_row["tax"]/2;

      $actual_amount = $sub_row["quantity"] * $sub_row["price"];

      $cgst_amount = (($actual_amount * $sub_row["tax"])/100)/2;

      $total_cgst=$total_cgst+$cgst_amount;

      $sgst_amount = (($actual_amount * $sub_row["tax"])/100)/2;

       $total_sgst=$total_sgst+$sgst_amount;

      $tax_amount = $cgst_amount+$sgst_amount;

      $total_product_amount = $actual_amount + $tax_amount;

      $total_actual_amount = $total_actual_amount + $actual_amount;

      $total_tax_amount = $total_tax_amount + $tax_amount;

      $total = $total + $total_product_amount;

	 $total_quantity = $total_quantity + $sub_row["quantity"];

	 $numInWords = getIndianCurrency($total);

      

     $output.='

     <tr>

    <td class="tg-yw4l">'.$count.'</td>

    <td class="tg-yw4l" colspan="4" style="font-size:12px">'.$product_data['product_name'].'</td>

    <td class="tg-yw4l" >'.$product_data['hsn'].'</td>

    <td class="tg-yw4l">'.$sub_row["quantity"].'</td>

    

    <td class="tg-yw4l" >'.$product_data['unit'].'</td>

	

    <td class="tg-yw4l" colspan="2">'.$sub_row["price"].'</td>

    <td class="tg-yw4l" colspan="2">'.number_format($actual_amount, 2).'</td>

    <td class="tg-yw4l" style="font-size:13px;">'.$cgst.'</td>

    <td class="tg-yw4l" style="font-size:13px;">'.$cgst_amount.'</td>

    <td class="tg-yw4l" style="font-size:13px;">'.$sgst.'</td>

    <td class="tg-yw4l" style="font-size:13px;">'.$sgst_amount.'</td>

     <td class="tg-yw4l" ></td>

    <td class="tg-yw4l" ></td>

   

    <td class="tg-yw4l" colspan="2">'.number_format($total_product_amount, 2).'</td>

  ';

  };
  for($i=$count;$i<6;$i++){
    $output.='<tr ><td class="tg-yw4l" style="height:27px;"></td>

    <td class="tg-yw4l" colspan="4" style="font-size:12px"></td>

    <td class="tg-yw4l" ></td>

    <td class="tg-yw4l"></td>

    

    <td class="tg-yw4l" ></td>

  

    <td class="tg-yw4l" colspan="2"></td>

    <td class="tg-yw4l" colspan="2"></td>

     <td class="tg-yw4l" ></td>

    <td class="tg-yw4l" ></td>

      <td class="tg-yw4l" ></td>

    <td class="tg-yw4l" ></td>

    <td class="tg-yw4l" style="font-size:13px;" ></td>



    <td class="tg-yw4l" style="font-size:13px;" ></td>



    <td class="tg-yw4l" colspan="2"></td></tr>';
}

  $output.='
  </tr>
  <tr>

    <td class="tg-baqh" colspan="6">TOTAL</td>

    <td class="tg-yw4l">'.number_format($total_quantity, 0).'</td>

    

    <td class="tg-yw4l" ></td>

    <td class="tg-yw4l" colspan="2"></td>

    <td class="tg-yw4l" colspan="2">'.number_format($total_actual_amount, 2).'</td>

    <td class="tg-yw4l" colspan="2"></td>

    <td class="tg-yw4l" colspan="2"></td>

    <td class="tg-yw4l" colspan="2"></td>';

    if($count > 6 ){

    $output.='<td class="tg-yw4l page_break" colspan="2" >'.number_format($total, 2).'<div class="page_break"></div></td>';

  }else{

    $output.='<td class="tg-yw4l" colspan="2" >'.number_format($total, 2).'</td>';

  }

    $output.='</tr>

    <tr>

<td class="tg-yw4l" colspan="9" rowspan="4">Total Invoice Amount in Words : '.$numInWords.'</td>

    <td class="tg-yw4l" colspan="9">Total Amount Befofre Tax:</td>

    <td class="tg-yw4l" colspan="2"><b>'.number_format($total_actual_amount, 2).'</b></td>

  </tr>

  <tr>

    <td class="tg-yw4l" colspan="9">Add : CGST</td>

    <td class="tg-yw4l" colspan="2"><b>'.$total_cgst.'</b></td>

  </tr>

  <tr>

    <td class="tg-yw4l" colspan="9">Add : SGST</td>

    <td class="tg-yw4l" colspan="2"><b>'.$total_sgst.'</b></td>

  </tr>

   <tr>

    <td class="tg-yw4l" colspan="9">Add : IGST</td>

    <td class="tg-yw4l" colspan="2"></td>

  </tr>

  <tr>

    <td class="tg-baqh" colspan="9" rowspan="4">Bank Details :<br/><br/><p>INDIAN OVERSEAS BANK<br/>ACC NO : 049702000003084<br/>IFSC CODE : IOBA0000497</p></td>

   <td class="tg-yw4l" colspan="9">Tax Amount : GST</td>

    <td class="tg-yw4l" colspan="2"><b>'.number_format($total_tax_amount, 2).'</b></td>

  </tr>

 

  <tr>

    <td class="tg-yw4l" colspan="9">Total amount after tax</td>

    <td class="tg-yw4l" colspan="2"><b>'.number_format($total, 2).'</b></td>

  </tr>

  <tr>

    <td class="tg-yw4l" colspan="9">GST Payable on Reverse Charge</td>

    <td class="tg-yw4l" colspan="2"></td>

  </tr>

  <tr>

  <td class="tg-baqh" colspan="11">Certified that the particulars given above are true and correct</td>

  </tr>

  <tr>

    <td class="tg-baqh" colspan="9" ><p>Terms And Condition : <br/><br/>Goods once sold will not be returned.</p></td>

  

    <td class="tg-cayh" colspan="5" >Common Seal</td>

    <td class="tg-baqh" colspan="6" >For <p>MAA BHAWANI BHANDER<br/><br/><br/><br/><br/>Partner/Authorised Signatory</p></td>

  </tr>

  

</table>';

}else{

	$arr = explode(",", $row['state_name']);

	$state_name = $arr[1];
	
	
    $currentYear = substr($row["order_date"], 0, 4) - 2018;
	$query = "SELECT * FROM stock_orders WHERE DATE_FORMAT(order_date, '%Y')<".($currentYear+2018)." OR (DATE_FORMAT(order_date, '%Y')=".($currentYear+2018)." AND DATE_FORMAT(order_date, '%m')<4)";
	
	$statement = mysqli_query($con, $query);
    $countInYear = mysqli_affected_rows($con);
	
	$order_id_new = $order_id-$countInYear+1;

  $output .= '

<style type="text/css">

.tg  {border-collapse:collapse;border-spacing:0;}

.tg td{font-family:Arial, sans-serif;font-size:14px;padding:4px 1px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}

.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:4px 6px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}

.tg .tg-baqh{text-align:center;vertical-align:top}

.tg .tg-cayh{text-align:center;vertical-align:bottom}

.tg .tg-yw4l{vertical-align:top;}

.tg-yw42{vertical-align:top;text-align:center}

.tg .page_break { page-break-before: always; }

</style>

<table class="tg" style="undefined;table-layout: fixed; width: 810px;">



  <tr>

    <td class="tg-yw4l" colspan="20"  style="font-size:12px"><pre><p align="center">                                               <u>TAX INVOICE</u></p></pre></br><div class="tg-yw42" ><h1 style="text-align:center;">MAA BHAWANI BHANDER</h1><br><p  align="center"><i>All Types of Cosmetics Brush, Playing Cards</i></p><br><h3 align="center">20 & 21, RAM MOHAN MULLICK LANE, KOLKATA - 700 007</h3><br><h3 align="center">PHONE : (S) 2268 1596, 4603 6767, MOB. : 9831240264</h3><br/><p>GSTIN:19AANFM9893J1Z6<p></div></td>

  </tr>

  <tr>

    <td class="tg-yw4l" colspan="10">Reverse Charge :</td>

    <td class="tg-yw4l" colspan="10">Transportation Mode:</td>

  </tr>

  <tr>

    <td class="tg-yw4l" colspan="10">Invoice No. : '.$order_id_new.' </td>

    <td class="tg-yw4l" colspan="10">Vehicle no. :</td>

  </tr>

  <tr>

    <td class="tg-yw4l" colspan="10">Invoice Date : '.$row["order_date"].'</td>

    <td class="tg-yw4l" colspan="10">Date of Supply</td>

  </tr>

  <tr>

    <td class="tg-yw4l" colspan="10">State : West Bengal  State code : 19 </td>

    <td class="tg-yw4l" colspan="10">Place of Supply</td>

  </tr>

  <tr>

    <td class="tg-baqh" colspan="10">Details of reciever (Billed to )</td>

    <td class="tg-baqh" colspan="10">Details of Consignee (Shipped to)</td>

  </tr>

  <tr>

    <td class="tg-yw4l" colspan="10">Name :  '.$row["order_name"].'</td>

    <td class="tg-yw4l" colspan="10">Name:</td>

  </tr>

  <tr>

    <td class="tg-yw4l" colspan="10">Address :'.$row["order_address"].'</td>

    <td class="tg-yw4l" colspan="10">Address :</td>

  </tr>

  <tr>

    <td class="tg-yw4l" colspan="10">State:'.$state_name.' &nbsp; &nbsp; &nbsp;  State code:'.$row['state_code'].'</td>

    <td class="tg-yw4l" colspan="10">State:  State code:</td>

  </tr>

  <tr>

    <td class="tg-yw4l" colspan="10">GSTIN:'.$row['gstin'].'</td>

    <td class="tg-yw4l" colspan="10">GSTIN:</td>

  </tr>';

  $statement = $connect->prepare("

     SELECT * FROM inventory_order_product 

     WHERE inventory_order_id = :inventory_order_id

    ");

    $statement->execute(

     array(

       ':inventory_order_id'       =>  $_GET["order_id"]

     )

    );

    $product_result = $statement->fetchAll();

    $count = 0;

    $total = 0;

    $total_actual_amount = 0;

    $total_tax_amount = 0;

	$total_quantity = 0;

      $output .= '<tr>

    <th class="tg-yw4l" rowspan="2">Sr.<br>no.</th>

    <th class="tg-baqh" colspan="4" rowspan="2">Name of Product</th>

    <th class="tg-baqh" rowspan="2">HSN</th>

    <th class="tg-baqh" rowspan="2">Qty.</th>

    

    <th class="tg-yw4l" rowspan="2">Unit</th>

    <th class="tg-yw4l" rowspan="2" colspan="2">Rate</th>

    <th class="tg-yw4l" rowspan="2" colspan="2">Taxable Value</th>

    <th class="tg-baqh" colspan="2">CGST</th>

    <th class="tg-baqh" colspan="2">SGST</th>

    <th class="tg-baqh" colspan="2">IGST</th>

    <th class="tg-yw4l" colspan="2" rowspan="2">TOTAL </th>

  </tr>

  <tr>

    <th class="tg-yw4l" >Rate %</th>

    <th class="tg-yw4l" >Amt.</th>

    <th class="tg-yw4l" >Rate %</th>

    <th class="tg-yw4l" >Amt.</th>

    <th class="tg-yw4l" >Rate %</th>

    <th class="tg-yw4l" >Amt.</th>

  </tr>

  ';

   foreach($product_result as $sub_row)

     {

     $count = $count + 1;

      //$product_data = fetch_product_details($sub_row['product_id'], $con);

     $query = "SELECT * FROM stock_items WHERE item_id = '".$sub_row['product_id']."'";



     $statement = mysqli_query($con, $query);



    //$totalRow = mysqli_affected_rows($connect);



      while($row=(mysqli_fetch_assoc($statement)))



    {



      $product_data['product_name'] = $row["item_name"];



      $product_data['hsn'] = $row["item_hsn"];



      $product_data['tax'] = $row["item_gst"];

	  

	  $product_data['unit'] = $row["unit"];



    }

     $actual_amount = $sub_row["quantity"] * $sub_row["price"];

     $igst=$sub_row["tax"];

     $igst_amount=($actual_amount * $igst)/100;

     $tax_amount = $igst_amount;

     $total_product_amount = $actual_amount + $tax_amount;

     $total_actual_amount = $total_actual_amount + $actual_amount;

     $total_tax_amount = $total_tax_amount + $tax_amount;

     $total = $total + $total_product_amount;

	 $total_quantity = $total_quantity + $sub_row["quantity"];

	 $numInWords = getIndianCurrency($total);//getNumberToWords($total);

     $output.='

     <tr>

    <td class="tg-yw4l">'.$count.'</td>

    <td class="tg-yw4l" colspan="4" style="font-size:12px">'.$product_data['product_name'].'</td>

    <td class="tg-yw4l" >'.$product_data['hsn'].'</td>

    <td class="tg-yw4l">'.$sub_row["quantity"].'</td>

    

    <td class="tg-yw4l" >'.$product_data['unit'].'</td>

	

    <td class="tg-yw4l" colspan="2">'.$sub_row["price"].'</td>

    <td class="tg-yw4l" colspan="2">'.number_format($actual_amount, 2).'</td>

     <td class="tg-yw4l" ></td>

    <td class="tg-yw4l" ></td>

      <td class="tg-yw4l" ></td>

    <td class="tg-yw4l" ></td>

    <td class="tg-yw4l" style="font-size:13px;" >'.$sub_row["tax"].'</td>



    <td class="tg-yw4l" style="font-size:13px;" >'.number_format($tax_amount, 2).'</td>



    <td class="tg-yw4l" colspan="2">'.number_format($total_product_amount, 2).'</td>
    </tr>
  ';

  };
  for($i=$count;$i<=6;$i++){
    $output.='<tr><td class="tg-yw4l" style="height:25px;"></td>

    <td class="tg-yw4l" colspan="4" style="font-size:12px"></td>

    <td class="tg-yw4l" ></td>

    <td class="tg-yw4l"></td>

    

    <td class="tg-yw4l" ></td>

  

    <td class="tg-yw4l" colspan="2"></td>

    <td class="tg-yw4l" colspan="2"></td>

     <td class="tg-yw4l" ></td>

    <td class="tg-yw4l" ></td>

      <td class="tg-yw4l" ></td>

    <td class="tg-yw4l" ></td>

    <td class="tg-yw4l" style="font-size:13px;" ></td>



    <td class="tg-yw4l" style="font-size:13px;" ></td>



    <td class="tg-yw4l" colspan="2"></td></tr>';
}
  $output.='
  
  <tr>

    <td class="tg-baqh" colspan="6">TOTAL</td>

    <td class="tg-yw4l">'.number_format($total_quantity, 0).'</td>

    <td class="tg-yw4l" ></td>

    <td class="tg-yw4l" colspan="2"></td>

    

    

    <td class="tg-yw4l" colspan="2">'.number_format($total_actual_amount, 2).'</td>

   <td class="tg-yw4l" colspan="2"></td>

    <td class="tg-yw4l" colspan="2"></td>

    <td class="tg-yw4l" colspan="2"></td>';

    if($count > 6 ){

    $output.='<td class="tg-yw4l" colspan="2" >'.number_format($total, 2).'<div class="page_break"></div></td>';

  }else{

    $output.='<td class="tg-yw4l" colspan="2" >'.number_format($total, 2).'</td>';

  }

    $output.='</tr>

    <tr>

<td class="tg-yw4l" colspan="9" rowspan="4">Total Invoice Amount in Words : '.$numInWords.'</td>

    <td class="tg-yw4l" colspan="9">Total Amount Befofre Tax:</td>

    <td class="tg-yw4l" colspan="2"><b>'.number_format($total_actual_amount, 2).'</b></td>

  </tr>

  <tr>

   <td class="tg-yw4l" colspan="9">Add : CGST</td>

    <td class="tg-yw4l" colspan="2"></td>

  </tr>

  <tr>

   <td class="tg-yw4l" colspan="9">Add : SGST</td>

    <td class="tg-yw4l" colspan="2"></td>

  </tr>

  <tr>

   <td class="tg-yw4l" colspan="9">Add : IGST</td>

    <td class="tg-yw4l" colspan="2"><b>'.number_format($total_tax_amount, 2).'</b></td>

  </tr>

  <tr>

    <td class="tg-baqh" colspan="9" rowspan="4">Bank Details :<br/><br/><p>INDIAN OVERSEAS BANK<br/>ACC NO : 049702000003084<br/>IFSC CODE : IOBA0000497</p></td>

    <td class="tg-yw4l" colspan="9">Tax Amount : GST</td>

    <td class="tg-yw4l" colspan="2"><b>'.number_format($total_tax_amount, 2).'</b></td>

  </tr>

  <tr>

    <td class="tg-yw4l" colspan="9">Total amount after tax</td>

    <td class="tg-yw4l" colspan="2"><b>'.number_format($total, 2).'</b></td>

  </tr>

  

  <tr>

   <td class="tg-yw4l" colspan="9">GST Payable on Reverse Charge</td>

    <td class="tg-yw4l" colspan="2"></td>

  </tr>

  <tr>

    <td class="tg-baqh" colspan="11">Certified that the particulars given above are true and correct</td>

  </tr>

  

  <tr>

    <td class="tg-baqh" colspan="9" ><p>Terms And Condition : <br/><br/>Goods once sold will not be returned.</p></td>

   <td class="tg-cayh" colspan="5" rowspan="1">Common Seal</td>

    <td class="tg-baqh" colspan="6" rowspan="1">For <p>MAA BHAWANI BHANDER<br/><br/><br/><br/><br/>Partner/Authorised Signatory</p></td>

  </tr>

  

</table>';

}

}

  $pdf = new Pdf();

  $file_name = 'Order-'.$order_id.'.pdf';

 // $file_name='order.pdf';

  $pdf->loadHtml($output);

  $pdf->render();

  $pdf->stream($file_name, array("Attachment" => false));

}





function getNumberToWords($num) {

	$num = str_replace(array(',', ' '), '' , trim($num));

    if(! $num) {

        return false;

    }

    $num = (int) $num;

    $words = array();

    $list1 = array('', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven',

        'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'

    );

    $list2 = array('', 'Ten', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety', 'Hundred');

    $list3 = array('', 'Thousand', 'Million', 'Billion', 'Trillion', 'Quadrillion', 'Quintillion', 'Sextillion', 'Septillion',

        'Octillion', 'Nonillion', 'Decillion', 'Undecillion', 'Duodecillion', 'Tredecillion', 'Quattuordecillion',

        'Quindecillion', 'Sexdecillion', 'Septendecillion', 'Octodecillion', 'Novemdecillion', 'Vigintillion'

    );

    $num_length = strlen($num);

    $levels = (int) (($num_length + 2) / 3);

    $max_length = $levels * 3;

    $num = substr('00' . $num, -$max_length);

    $num_levels = str_split($num, 3);

    for ($i = 0; $i < count($num_levels); $i++) {

        $levels--;

        $hundreds = (int) ($num_levels[$i] / 100);

        $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ' ' : '');

        $tens = (int) ($num_levels[$i] % 100);

        $singles = '';

        if ( $tens < 20 ) {

            $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );

        } else {

            $tens = (int)($tens / 10);

            $tens = ' ' . $list2[$tens] . ' ';

            $singles = (int) ($num_levels[$i] % 10);

            $singles = ' ' . $list1[$singles] . ' ';

        }

		

		//if($i == count($num_levels)-1)

		//	$words[] = $hundreds . " and " . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );

		//else

			$words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );

    } //end for loop

    $commas = count($words);

    if ($commas > 1) {

        $commas = $commas - 1;

    }

    return implode(' ', $words);

}



function getIndianCurrency($number) {

	$number = number_format($number, 2);

	if (strpos( $number, "." ) == false)

		$number .= ".00";

	

	$arr = explode(".", $number);

	

	$rupee = $arr[0];

	$paisa = "".$arr[1];

	

	//$paisa = ltrim($paisa, '0');

	

	$words = "Rupees ".getNumberToWords($rupee);

	

	if(intval($paisa) > 0)

		$words = $words." and ".getNumberToWords($paisa)." Paise";

	

	$words = $words." Only";

	

	return $words;

}