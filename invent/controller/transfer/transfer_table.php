<?php

$id			  = $_GET['id_transfer'];
$canAdd	  = $_GET['canAdd'];
$canEdit	= $_GET['canEdit'];

$ds = array();

$cs   = new transfer();
$pd   = new product();
$zone = new zone();
$bc   = new barcode();

$qs = $cs->getDetails($id);

if( dbNumRows($qs) > 0 )
{
  $no = 1;
  while( $rs = dbFetchObject($qs) )
  {
    //--- รหัสสินค้า
    $pdCode     = $pd->getCode($rs->id_product);
    $toZone	    = $rs->to_zone == 0 ? '<button type="button" class="btn btn-xs btn-primary" onclick="move_in('.$rs->id.', '.$rs->from_zone.')">ย้ายเข้าโซน</button>' : $zone->getName($rs->to_zone);
    $btn_delete = ($canAdd == 1 OR $canEdit == 1 ) ? '<button type="button" class="btn btn-xs btn-danger" onclick="deleteMoveItem(' . $rs->id .' , \'' . $pdCode.'\')"><i class="fa fa-trash"></i></button>' : '';
    $barcode    = $bc->getBarcode($rs->id_product);
    $temp_qty   = $rs->qty - $cs->getTempQty($rs->id);
    $arr = array(
          'no'			  => $no,
          'id'				=> $rs->id,
          'barcode'	  => $barcode,
          'products'	=> $pdCode,
          'from_zone'	=> $rs->from_zone,
          'fromZone'	=> $zone->getName($rs->from_zone),
          'toZone'		=> $toZone,
          'qty'			  => number_format($rs->qty),
          'temp'      => number_format($temp_qty),
          'btn_delete'=> $btn_delete,
          'valid'			=> ($rs->valid == 0 ? '<input type="hidden" id="qty-'.$barcode.'" value="'.$rs->qty.'" />' : '')
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
