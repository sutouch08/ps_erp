<?php
$cs = new cancle_zone();
$id_warehouse = $_GET['id_warehouse'];
$qs = $cs->getDetailsByWarehouse($id_warehouse);
$warehouse = new warehouse();
$zone = new zone();
$product = new product();
$order = new order();
$ds = array();
if( dbNumRows($qs) > 0 )
{
  $no = 1;
  while($rs = dbFetchObject($qs))
  {
    $product->getData($rs->id_product);
    $arr = array(
      'no' => $no,
      'id_cancle' => $rs->id,
      'id_product' => $product->id,
      'product' => $product->code,
      'order' => $order->getReference($rs->id_order),
      'id_order' => $rs->id_order,
      'barcode' => $product->barcode,
      'id_zone' => $rs->id_zone,
      'zoneName' => $zone->getName($rs->id_zone),
      'qty' => $rs->qty
    );

    array_push($ds, $arr);
    $no++;
  }
}
else
{
  array_push($ds, array('nodata' => 'nodata'));
}

echo json_encode($ds);


 ?>
