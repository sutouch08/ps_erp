<?php
$id 	= $_GET['id_transfer'];

$cs	= new transfer();

$bc = new barcode();

$product = new product();

$zone = new zone();

$ds 	= array();

$qs 	= $cs->getTempDetails($id);

if( dbNumRows($qs) > 0 )
{
  $no = 1;
  while($rs = dbFetchObject($qs) )
  {
    $barcode = $bc->getBarcode($rs->id_product);
    $pdCode  = $product->getCode($rs->id_product);

    $arr = array(
          "no"		   => $no,
          "id"			 => $rs->id_transfer_detail,
          "barcode"	 => $barcode,
          "products" => $pdCode,
          'from_zone'	=> $rs->id_zone,
          'fromZone'	=> $zone->getName($rs->id_zone),
          "qty"			=> $rs->qty
          );
    array_push($ds, $arr);
    $no++;
  }
}
else
{
  array_push($ds, array("nodata" => "nodata"));
}
echo json_encode($ds);
 ?>
