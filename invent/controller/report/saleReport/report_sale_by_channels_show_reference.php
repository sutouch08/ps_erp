<?php
ini_set('memory_limit', '1024M');
set_time_limit(600);

$sc = TRUE;
$pd = new product();
$allChannels = $_GET['allChannels'];
$channels = isset($_GET['channels']) ? $_GET['channels'] : FALSE;
$allProduct = $_GET['allProduct'];
$pdFrom  = $pd->getMinCode($_GET['pdFrom']);
$pdTo = $pd->getMaxCode($_GET['pdTo']);
$allDate = $_GET['allDate'];
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];
$ds = array();


$where = "WHERE id_role IN(1,8) ";

if($allChannels == 0)
{
  $ch_in = 5000;
  foreach($channels as $id_channels)
  {
    $ch_in .= ', '.$id_channels;
  }

  $where .= "AND id_channels IN(".$ch_in.") ";
}
else
{
  $where .= "AND id_channels != 0 ";
}



if($allProduct == 0)
{
  $where .= "AND product_code >= '".$pdFrom."' ";
  $where .= "AND product_code <= '".$pdTo."' ";
}



if($allDate == 0)
{
  $where .= "AND date_add >= '".fromDate($fromDate)."' ";
  $where .= "AND date_add <= '".toDate($toDate)."' ";
}
else
{
  $where .= "AND date_add >= '".date('Y-01-01 00:00:00')."' ";
  $where .= "AND date_add <= '".date('Y-12-31 23:59:59')."' ";
}

$where .= "ORDER BY reference ASC, product_code ASC";

$qs = dbQuery("SELECT * FROM tbl_order_sold ".$where);

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
    $order = new order();
    while($rs = dbFetchObject($qs))
    {
      $arr = array(
        'no' => $no,
        'date_add' => thaiDate($rs->date_add),
        'reference' => $rs->reference,
        'ref_code' => $order->getRefCode($rs->id_order),
        'channels' => $rs->channels,
        'itemCode' => $rs->product_code,
        'price' => number($rs->price_inc,2),
        'qty' => number($rs->qty),
        'discount' => $rs->discount_label,
        'amount' => number($rs->total_amount_inc,2)
      );

      array_push($ds, $arr);
      $no++;
      $totalQty += $rs->qty;
      $totalAmount += $rs->total_amount_inc;
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
