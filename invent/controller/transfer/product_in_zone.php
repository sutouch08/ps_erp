<?php
$sc 		 = array();

//----	โซน
$id_zone = $_GET['id_zone'];

//---	โซนนี้ติดลบได้หรือไม่
$isAllowUnderZero = $_GET['isAllowUnderZero'] == 0 ? '' : 1;

$stock   = new stock();
$barcode = new barcode();

$qs 		 = $stock->getStockInZone($id_zone);

if( dbNumRows($qs) > 0 )
{
  $no = 1;
  while( $rs = dbFetchObject($qs) )
  {
    $arr = array(
          "no"				 => $no,
          "id_stock" 	 => $rs->id_stock,
          "id_product" => $rs->id_product,
          "barcode" 	 => $barcode->getBarcode($rs->id_product),
          "products" 	 => $rs->code,
          "qty" 			 => $rs->qty,
          "isAllowUnderZero" => $isAllowUnderZero
          );

    array_push($sc, $arr);

    $no++;
  }
}
else
{
  array_push($sc, array("nodata" => "nodata"));
}
echo json_encode($sc);

 ?>
