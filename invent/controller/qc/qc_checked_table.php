<?php
$sc = TRUE;
$id_order = $_GET['id_order'];
$id_pd = $_GET['id_product'];

if($id_order == '')
{
  $sc = FALSE;
  $message = 'ไม่พบไอดีออเดอร์';
}


if($id_pd == '')
{
  $sc = FALSE;
  $message = 'ไม่พบไอดีสินค้า';
}

$ds  = array();

if($sc === TRUE)
{
  $qr  = "SELECT qc.id, box.barcode, box.box_no, qc.qty ";
  $qr .= "FROM tbl_qc AS qc ";
  $qr .= "LEFT JOIN tbl_box AS box ON qc.id_box = box.id_box AND qc.id_order = box.id_order ";
  $qr .= "WHERE qc.id_order = '".$id_order."' ";
  $qr .= "AND qc.id_product = '".$id_pd."' ";

  $qs = dbQuery($qr);

  if(dbNumRows($qs) > 0)
  {
    while($rs = dbFetchObject($qs))
    {
      $arr = array(
        'id_qc' => $rs->id,
        'barcode' => $rs->barcode,
        'box_no' => $rs->box_no,
        'qty' => $rs->qty
      );

      array_push($ds, $arr);
    }
  }
  else
  {
    $sc = FALSE;
    $message = 'ไม่พบรายการตรวจสินค้า';
  }
}


echo $sc === TRUE ? json_encode($ds) : $message;
 ?>
