<?php
$cs 			= new transform();
$qs 			= $cs->getReceiveTransfromProductDetails($_GET['id_order']);
if( dbNumRows($qs) > 0 )
{
  $ds = array();
  $no = 1;
  $totalQty	= 0;
  $totalBacklog = 0;
  $pd = new product();
  $bc = new barcode();
  $limit = getConfig('RECEIVE_OVER_PO');

  while( $rs = dbFetchObject($qs) )
  {
    if( $rs->valid == 1 && $rs->is_closed == 0 )
    {
      $backlog = $rs->qty - $rs->received < 0 ? 0 : $rs->qty - $rs->received;
      $arr = array(
                "no"	    => $no,
                "barcode"	=> $bc->getBarcode($rs->id_product),
                "id_pd"		=> $rs->id_product,
                "pdCode"	=> $pd->getCode($rs->id_product),
                "pdName"	=> $pd->getName($rs->id_product),
                "qty"			=> number_format($rs->qty),
                "limit"		=> ($rs->qty + ($rs->qty* ( $limit * 0.01 ) ) ) - $rs->received,
                "backlog"	=> number_format($backlog)
              );
      array_push($ds, $arr);
      $totalQty += $rs->qty;
      $totalBacklog += $backlog;
      $no++;
    }

  }
  $arr = array(
            "qty"			=> number_format($totalQty),
            "backlog"		=> number_format($totalBacklog)
          );
  array_push($ds, $arr);
  $sc = json_encode($ds);
}
else
{
  $sc = 'ใบเบิกสินค้าไม่ถูกต้อง อาจถูกปิด หรือ ถูกยกเลิกไปแล้ว';
}
echo $sc;

 ?>
