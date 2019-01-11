<?php
ini_set('memory_limit', '1024M');
set_time_limit(600);

$sc = TRUE;
$pd = new product();
$allChannels = $_GET['allChannels'];
$channels = isset($_GET['channels']) ? $_GET['channels'] : FALSE;

$refCodeFrom = $_GET['refCodeFrom'] != '' ? trim($_GET['refCodeFrom']) : FALSE;
$refCodeTo = $_GET['refCodeTo'] != '' ? trim($_GET['refCodeTo']) : FALSE;
$allRefCode = $refCodeFrom === FALSE ? TRUE : FALSE;

$pdFrom  = $_GET['pdFrom'] != '' ? $pd->getMinCode($_GET['pdFrom']) : FALSE;
$pdTo = $_GET['pdTo'] != '' ? $pd->getMaxCode($_GET['pdTo']) : FALSE;
$allProduct = $pdFrom === FALSE ? TRUE : FALSE;

$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];

$ds = array();

$qr = "SELECT
          o.date_add,
          o.reference,
          o.ref_code,
          c.name AS channels,
          pm.name AS payment,
          od.product_code,
          od.price,
          od.qty,
          od.discount_amount,
          od.total_amount
        FROM
          tbl_order_detail AS od
        LEFT JOIN
          tbl_order AS o ON od.id_order = o.id
        LEFT JOIN
          tbl_channels AS c ON o.id_channels = c.id
        LEFT JOIN
          tbl_payment_method AS pm ON o.id_payment = pm.id
        WHERE
          o.role = 1
          AND o.isOnline = 1
          AND o.isExpire = 0 ";


if($allChannels == 0)
{
  if(!empty($channels))
  {
    $ch_in = '';
    $i = 1;
    foreach($channels as $id_channels)
    {
      $ch_in .= $i == 1 ? $id_channels :', '.$id_channels;
      $i++;
    }

    $qr .= "AND o.id_channels IN(".$ch_in.") ";
  }

}


if($allRefCode === FALSE)
{
  $qr .= "AND ref_code >= '".$refCodeFrom."' ";
  $qr .= "AND ref_code <= '".$refCodeTo."' ";
}

if($allProduct === FALSE)
{
  $qr .= "AND product_code >= '".$pdFrom."' ";
  $qr .= "AND product_code <= '".$pdTo."' ";
}

$qr .= "AND o.date_add >= '".fromDate($fromDate)."' ";
$qr .= "AND o.date_add <= '".toDate($toDate)."' ";


$qr .= "ORDER BY o.reference ASC, od.product_code ASC";

//echo $qr;
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
        'date_add' => thaiDate($rs->date_add),
        'reference' => $rs->reference,
        'ref_code' => $rs->ref_code,
        'channels' => $rs->channels,
        'payment' => $rs->payment,
        'itemCode' => $rs->product_code,
        'price' => number($rs->price,2),
        'qty' => number($rs->qty),
        'discount' => $rs->discount_amount,
        'amount' => number($rs->total_amount,2)
      );

      array_push($ds, $arr);
      $no++;
      $totalQty += $rs->qty;
      $totalAmount += $rs->total_amount;
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
  $arr = array(
    'nodata' => 'nodata'
  );
  array_push($ds, $arr);
}

echo $sc === TRUE ? json_encode($ds) : $message;

 ?>
