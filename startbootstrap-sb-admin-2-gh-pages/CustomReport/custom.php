<?php session_start();?>
<!--
Strategies: //List Supplier, Amount spent on Supplier

Select from suppler nj orders nj plords nj ordercontains where date (1 month)

-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />

	<title>Editable Invoice</title>

	<link rel='stylesheet' type='text/css' href='css/style.css' />
	<link rel='stylesheet' type='text/css' href='css/print.css' media="print" />
	<script type='text/javascript' src='js/jquery-1.3.2.min.js'></script>
	<script type='text/javascript' src='js/example.js'></script>

</head>

<body>
	<div id="page-wrap">

<img src="images/fake.png" alt="Forest" width="500" height="500">
		<textarea id="header">Monthly Report</textarea>

		<div id="identity">

		<textarea id="address">Archivist Capital
Address: 5555 Bakersfield, CA 93304
Phone: 661-555-5555
Summary:
A report on the expenditures per monthly supplier.
<?php
include 'config2.php';
$sql =<<<EOF
select * from placesorder natural join orders natural join supplier natural join ordercontains where orderid > 150;
EOF;
$sql2 =<<<EOF
select * from placesorder natural join orders natural join supplier natural join ordercontains where orderid > 150;
EOF;


$ret = pg_query($db,$sql);
while($row = pg_fetch_row($ret)) {
    if($row[0] == $_SESSION['test']) {
	$id = $_SESSION['test'];
	echo "Company ID: $id\n";
	$_SESSION['name'] = $row[6];
	echo "Phone: $row[7]\n";
	echo "Address: $row[8], $row[9], $row[10]\n";
	echo "Zip: $row[11]\n";
	break;
    }
}
//Calculate Totals
foreach($_SESSION['SBCScart'] as $SBCSproduct)
{
    $pr = $_SBCSproduct['quantity'];
    $price = $SBCSproduct['unitprice'];
    $quantity = $SBCSproduct['quantity'];
    $prod = $SBCSproduct['item'];
    $total = $price * $quantity;
    $amount = $amount + $total;
    // $_SESSION['amount'] = $amount;
}


//RIGHT HERE CALC ALL TOTALS AND STUFF

$sql3 =<<<EOF
select * from supplier natural join (select distinct supplierid from placesorder natural join orders natural join supplier where dateplaced >= date_trunc('month',CURRENT_DATE) and dateplaced >= date_trunc('year', CURRENT_DATE) and orderid > 150) as uniquesupplier;
EOF;

$gr;
$csupname = $_SESSION['csname']; //Query the ID
$sups = "select * from supplier";// where name = '$csupname'";
$sdate = $_SESSION['csdate'];
$edate = $_SESSION['cedate'];
$intsd = (int)$sdate;
$inted = (int)$edate;
$ret = pg_query($db,$sups);
while($row = pg_fetch_row($ret)) {
foreach($_SESSION['testa'] as $a) {
    if($row[1] == $a) {
    $id = $row[0];
    //Query monthyl most bought item
    $item = "select * from mostbought($id,$intsd,$inted)";
    $itemquery = pg_query($db,$item);
    $itemrow = pg_fetch_row($itemquery);

    $total = "select * from customtotalitemsbought($id,$intsd, $inted)";
    $totalquery = pg_query($db, $total);
    $totalrow = pg_fetch_row($totalquery);
    
    $expend = "select * from customtotal($id,$intsd, $inted)";
    $expendq = pg_query($db, $expend);
    $expendrow = pg_fetch_row($expendq);
   $exp = $expendrow[0]; 
    $num = preg_replace('/[^0-9]/','',$exp);
    $gr = $gr + $num;
   // $sum =  money_format('%i', ($totals/100));
   // $numbers = preg_replace('/[^0-9]/', '', $totalrow[0]);
   // $month = $month + $numbers;
    // }
    }
}
}
$gr =  money_format('%i', ($gr/100));
$_SESSION['gr'] = $gr;

?>
</textarea>

	    <div id="logo">

	      <div id="logoctr">
		<a href="javascript:;" id="change-logo" title="Change logo">Change Logo</a>
		<a href="javascript:;" id="save-logo" title="Save changes">Save</a>
		|
		<a href="javascript:;" id="delete-logo" title="Delete logo">Delete Logo</a>
		<a href="javascript:;" id="cancel-logo" title="Cancel changes">Cancel</a>
	      </div>

	      <div id="logohelp">
		<input id="imageloc" type="text" size="300" value="" /><br />
		(max width: 540px, max height: 500px)
	      </div>
	      <img id="image" src="images/fake.png" alt="logo" />
	    </div>

		</div>

		<div style="clear:both"></div>

		<div id="customer">

		<textarea id="customer-title">Company Name: Archivist</textarea>

	    <table id="meta">
		<tr>
<?php

include 'config2.php';
$sql =<<<EOF
select * from supplier;
EOF;
$ret = pg_query($db,$sql);
$invnum = $_SESSION['test'];
$month = date('F Y');
//while($row = pg_fetch_row($ret)) {
//	if($_SESSION['sid'] == $row[0]) {

echo "<td class=\"meta-head\">Month of</td>";
echo   " <td><p>$month</p></td>";

//	}

//}
?>    
		    </tr>
		<tr>

		    <td class="meta-head">Date Generated</td>
		    <td><p id="date"><?php echo date('m-d-y');?></p></td>
		</tr>
		<tr>
		    <td class="meta-head">Amount Due</td>
		    <td><div class="due">$<?php echo $_SESSION['gr'];?></div></td>
		</tr>

	    </table>

		</div>

		<table cellspacing="0" cellpadding="0">

		  <tr>
		      <th>Supplier</th>
		      <th>-------------Information----------</th>
		      <th>Purchase Amount</th>
		      <th>Most Ordered Item</th>
		      <th>Expendetures</th>
		  </tr>
<!-- ------------------------------------------------------------- -->
<?php
include 'config2.php';
$sql =<<<EOF
select * from supplier;
EOF;
$ret = pg_query($db,$sql);
//while($row = pg_fetch_row($ret)) {
//    if($row[0] == $_SESSION["SID"]) {
//    }
$SBCSproducts = $_SESSION['SBCScart'];
$prods = $SBCSproducts['item'];
$amount;
$sql2 =<<<EOF
select * from placesorder natural join orders natural join supplier where dateplaced >= date_trunc('month',CURRENT_DATE) and dateplaced >= date_trunc('year', CURRENT_DATE) and orderid > 150 order by orderid desc;
EOF;

$sql3 =<<<EOF
select * from supplier natural join (select distinct supplierid from placesorder natural join orders natural join supplier where dateplaced >= date_trunc('month',CURRENT_DATE) and dateplaced >= date_trunc('year', CURRENT_DATE) and orderid > 150) as uniquesupplier;
EOF;
$sql =<<<EOF
select * from ordercontains natural join itemtype;
EOF;

$month;
$csupname = $_SESSION['csname']; //Query the ID
$sups = "select * from supplier";// where name = '$csupname'";
$sdate = $_SESSION['csdate'];
$edate = $_SESSION['cedate'];
$intsd = (int)$sdate;
$inted = (int)$edate;
$ret = pg_query($db,$sups);
while($row = pg_fetch_row($ret)) {
foreach($_SESSION['testa'] as $a) {
    if($row[1] == $a) {
    $id = $row[0];
    //Query monthyl most bought item
    $item = "select * from mostbought($id,$intsd,$inted)";
    $itemquery = pg_query($db,$item);
    $itemrow = pg_fetch_row($itemquery);

    $total = "select * from customtotalitemsbought($id,$intsd, $inted)";
    $totalquery = pg_query($db, $total);
    $totalrow = pg_fetch_row($totalquery);
    
    $expend = "select * from customtotal($id,$intsd, $inted)";
    $expendq = pg_query($db, $expend);
    $expendrow = pg_fetch_row($expendq);
    $num = preg_replace('/[^0-9]/','',$row[6]);
    $totals = $row[2] * $num;
    $sum =  money_format('%i', ($totals/100));
    echo"<tr class=\"item-row\">";
    echo"<td class=\"item-name\"><div class=\"delete-wpr\"><p>$row[1]</p></div></td>";
    echo "<td class=\"description\"><p>$row[2]</p></td>";
    echo"<td><p class=\"cost\">                           $totalrow[0]</p></td>";
    echo    "<td><p class=\"qty\">                        $itemrow[0]</p></td>";
    echo		      "<td><span class=\"price\">$expendrow[0]</span></td>";
    echo "\n";
    echo "</tr>";
    $numbers = preg_replace('/[^0-9]/', '', $totalrow[0]);
    $month = $month + $numbers;
    }
}
    // }
}
//  $month =  money_format('%i', ($month/100));
//   $_SESSION['mamount'] = ($month);
?>
<!--
			<td class="item-name"><div class="delete-wpr"><textarea>Web Updates</textarea><a class="delete" href="javascript:;" title="Remove row">X</a></div></td>
		      <td class="description"><textarea>Monthly web updates for http://widgetcorp.com (Nov. 1 - Nov. 30, 2009)</textarea></td>
		      <td><textarea class="cost">$650.00</textarea></td>
		      <td><textarea class="qty">1</textarea></td>
		      <td><span class="price">$650.00</span></td>
		  </tr> -->
<!-- --------------------------------------------------------------------------------- -->		  <tr>
<!--		  <tr>
		      <td><textarea>Monthly web updates for http://widgetcorp.com (Nov. 1 - Nov. 30, 2009)</textarea></td>
		      <td><textarea>$650.00</textarea></td>
		      <td><textarea>1</textarea></td>
		      <td><span>$650.00</span></td>
		  </tr> -->
<!--	  <tr class="item-row">
		      <td class="item-name"><div class="delete-wpr"><textarea>SSL Renewals</textarea><a class="delete" href="javascript:;" title="Remove row">X</a></div></td>

		      <td class="description"><textarea>Yearly renewals of SSL certificates on main domain and several subdomains</textarea></td>
		      <td><textarea class="cost">$75.00</textarea></td>
		      <td><textarea class="qty">3</textarea></td>
		      <td><span class="price">$225.00</span></td>
		  </tr>

		  <tr id="hiderow">
		    <td colspan="5"><a id="addrow" href="javascript:;" title="Add a row">Add a row</a></td>
		  </tr>

		  <tr>
		      <td colspan="2" class="blank"> </td>
		      <td colspan="2" class="total-line">Subtotal</td>
		      <td class="total-value"><div id="subtotal">$875.00</div></td>
		  </tr>
		  <tr>

		      <td colspan="2" class="blank"> </td>
		      <td colspan="2" class="total-line">Total</td>
		      <td class="total-value"><div id="total">$875.00</div></td>
		  </tr>
		  <tr>
		      <td colspan="2" class="blank"> </td>
		      <td colspan="2" class="total-line">Amount Paid</td>

		      <td class="total-value"><textarea id="paid">$0.00</textarea></td>
		  </tr>
		  <tr>
		      <td colspan="2" class="blank"> </td>
		      <td colspan="2" class="total-line balance">Balance Due</td>
		      <td class="total-value balance"><div class="due">$875.00</div></td>
		  </tr>
-->
		</table>

		<div id="terms">
		  <h5>Terms</h5>
		  <textarea>NET 30 Days. Finance Charge of 1.5% will be made on unpaid balances after 30 days.</textarea>
		</div>

	</div>

</body>
</html><?php// session_destroy();
?>
