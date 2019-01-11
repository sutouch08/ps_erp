<?php
$id_zone = $_GET['id_zone'];
$code    = $_GET['code'];

$bc = new barcode();
$cs = new consign();
$st = new stock();
$pd = new product();
$id_pd = $pd->getId($code);

if( $id_pd !== FALSE )
{
  $pd->getData($id_pd);
  $barcode = $bc->getBarcode($id_pd);
  $gp      = $cs->getProductGP($id_pd, $id_zone);
  $stock   = $pd->count_stock == 1 ? $st->getStockZone($id_zone, $id_pd) : 0;

  $arr = array(
    'id_product' => $id_pd,
    'barcode'    => $barcode,
    'product'    => $pd->code,
    'price'      => $pd->price,
    'p_disc'     => $gp,
    'a_disc'     => 0,
    'stock'      => $stock,
    'count_stock' => $pd->count_stock
  );


  $sc = json_encode($arr);
}
else
{
  $sc = 'สินค้าไม่ถูกต้อง';
}

echo $sc;

 ?>
