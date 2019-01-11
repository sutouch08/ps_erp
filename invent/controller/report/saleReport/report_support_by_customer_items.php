<?php

$sc = TRUE;
$allCustomer = $_GET['allCustomer'];
$role = 3; //--- สปอนเซอร์
$fromCode = $_GET['fromCode']; //--- รหัสลูกค้า
$toCode = $_GET['toCode']; //-- รหัสลูกค้า

$fromDate = fromDate($_GET['fromDate']);
$toDate = toDate($_GET['toDate']);
$ds = array();


$qr  = "SELECT product_code, customer_code, customer_name, SUM(qty) AS qty, SUM(total_amount_inc) AS amount ";
$qr .= "FROM tbl_order_sold ";
$qr .= "WHERE id_role IN(".$role.") ";

if($allCustomer == 0)
{
  $qr .= "AND customer_code >= '".$fromCode."' ";
  $qr .= "AND customer_code <= '".$toCode."' ";
}


$qr .= "AND date_add >= '".$fromDate."' ";
$qr .= "AND date_add <= '".$toDate."' ";

$qr .= "GROUP BY id_product, id_customer ";

$qr .= "ORDER BY customer_code ASC, product_code ASC";


$qs = dbQuery($qr);

$rows = dbNumRows($qs);
if($rows > 0)
{
  if($rows > 2000)
  {
    $sc = FALSE;
    $message = 'ข้อมูลมีปริมาณมากเกินกว่าจะแสดงผลได้ กรุณาส่งออกข้อมูลแทนการแสดงผลหน้าจอ';
  }
  else
  {
    $no = 1;
    $totalQty = 0;
    $totalAmount = 0;
    while($rs = dbFetchObject($qs))
    {
      $arr = array(
        'no' => $no,
        'product' => $rs->product_code,
        'customer' => $rs->customer_code.' : '.$rs->customer_name,
        'qty' => number($rs->qty),
        'amount' => number($rs->amount,2)
      );

      array_push($ds, $arr);
      $no++;
      $totalQty += $rs->qty;
      $totalAmount += $rs->amount;
    }

    $arr = array(
      'totalQty' => number($totalQty),
      'totalAmount' => number($totalAmount,2)
    );

    array_push($ds, $arr);
  }

}
else
{
  $ds = array(
    'nodata' => 'nodata'
  );

}

echo $sc === TRUE ? json_encode($ds) : $message;

 ?>
