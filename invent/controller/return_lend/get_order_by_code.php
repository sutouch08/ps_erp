<?php

$code = $_GET['reference'];
$order = new order();
$customer = new customer();
$order->getDataByReference($code);
$sc = TRUE;
$ds = array();

if($order->id != '')
{
  $cs = new lend($order->id);
  $qs = $cs->getDetails($order->id);
  $sd = array(
      'id_customer' => $order->id_customer,
      'customerName' => $customer->getName($order->id_customer),
      'id_order' => $order->id
    );

  if(dbNumRows($qs) > 0)
  {
    $no = 1;
    $totalQty = 0;
    $totalReceived = 0;
    $totalBalance = 0;
    while($rs = dbFetchObject($qs))
    {
      $pd = new product($rs->id_product);
      $product = $pd->code.' : '.$pd->name;
      $arr = array(
        'no' => $no,
        'id' => $rs->id,
        'product' => limitText($product, 80),
        'price' => number($pd->price, 2),
        'qty' => number($rs->qty),
        'received' => number($rs->received),
        'balance' => number($rs->qty - $rs->received)
      );

      array_push($ds, $arr);
      $no++;
      $totalQty += $rs->qty;
      $totalReceived += $rs->received;
      $totalBalance += ($rs->qty - $rs->received);
    }

    $arr = array(
      'totalQty' => number($totalQty),
      'totalReceived' => number($totalReceived),
      'totalBalance' => number($totalBalance)
    );
    array_push($ds, $arr);
  }
  else
  {
    array_push($ds, array('nodata' => 'nodata'));
  }

  $sd['details'] = $ds;

}
else
{
  $sc = FALSE;
  $message = 'ไม่พบเลขที่เอกสาร';
}

echo $sc === TRUE ? json_encode($sd) : $message;
 ?>
