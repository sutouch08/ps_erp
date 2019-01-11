<?php
$sc = TRUE;

$allPo  = $_GET['allPo'];
$fromPo = $_GET['fromPo'];
$toPo   = $_GET['toPo'];

$allSup = $_GET['allSup'];
$fromSup = $_GET['fromSup'];
$toSup = $_GET['toSup'];

$fromDate = fromDate($_GET['fromDate']);
$toDate = toDate($_GET['toDate']);

$qr  = "SELECT po.date_add, po.reference, sup.code, sup.name, po.pd_code, po.price, po.qty, po.status ";
$qr .= "FROM tbl_po AS po ";
$qr .= "LEFT JOIN tbl_supplier AS sup ON po.id_supplier = sup.id ";
$qr .= "WHERE po.id_product != '' ";
$qr .= "AND po.date_add >= '".$fromDate."' ";
$qr .= "AND po.date_add <= '".$toDate."' ";

if($allSup == 0)
{
  $qr .= "AND sup.code >= '".$fromSup."' ";
  $qr .= "AND sup.code <= '".$toSup."' ";
}

if($allPo == 0)
{
  $qr .= "AND po.reference >= '".$fromPo."' ";
  $qr .= "AND po.reference <= '".$toPo."' ";
}

$qr .= "ORDER BY po.date_add ASC, po.reference ASC, po.pd_code ASC";

$qs = dbQuery($qr);

$rows = dbNumRows($qs);

if($rows > 2000)
{
  $sc = FALSE;
  $message = 'ผลลัพธ์เกิน 2000 รายการ กรุณาส่งออกแทนการแสดงผลหน้าจอ';
}
else
{
  $no = 1;
  $totalQty = 0;
  $totalAmount = 0;
  $ds = array();
  while($rs = dbFetchObject($qs))
  {
    $arr = array(
      'no' => $no,
      'date' => thaiDate($rs->date_add,'/'),
      'poCode' => $rs->reference,
      'supplier' => $rs->code.' : '.$rs->name,
      'pdCode' => $rs->pd_code,
      'price' => number($rs->price, 2),
      'qty' => number($rs->qty),
      'amount' => number(($rs->qty * $rs->price), 2),
      'status' => ($rs->status == 3 ? 'Closed' : ($rs->status == 2 ? 'Part' : ''))
    );

    array_push($ds, $arr);
    $no++;
    $totalQty += $rs->qty;
    $totalAmount += ($rs->qty * $rs->price);
    unset($arr);
  }

  $arr = array(
    'totalQty' => number($totalQty),
    'totalAmount' => number($totalAmount, 2)
  );

  array_push($ds, $arr);
  unset($arr);
}

echo $sc === TRUE ? json_encode($ds) : $message;





 ?>
