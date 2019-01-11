<?php
$isAll = $_GET['allDocument'];
$fCode = $_GET['from_code'];
$tCode = $_GET['to_code'];

$fromCode = $fCode > $tCode ? $tCode : $fCode;
$toCode = $fCode > $tCode ? $fCode : $tCode;

$from = fromDate($_GET['fromDate']);
$to = toDate($_GET['toDate']);
$sc = array();

$qr  = "SELECT rp.reference, rp.order_code AS invoice, rp.date_add, SUM(rd.qty) AS qty, SUM(rd.qty * pd.cost) AS amount ";
$qr .= "FROM tbl_receive_transform_detail AS rd ";
$qr .= "LEFT JOIN tbl_receive_transform AS rp ON rd.id_receive_transform = rp.id ";
$qr .= "LEFT JOIN tbl_product AS pd ON rd.id_product = pd.id ";
$qr .= "WHERE rp.date_add >= '".$from."' AND rp.date_add <= '".$to."' ";
$qr .= "AND rp.isCancle = 0 AND rd.is_cancle = 0 ";
if($isAll == 0)
{
  $qr .= "AND rp.reference >= '".$fromCode."' AND rp.reference <= '".$toCode."' ";
}

$qr .= "GROUP BY rp.reference ";
$qr .= "ORDER BY rp.reference ASC";

//echo $qr;

$qs = dbQuery($qr);

if(dbNumRows($qs) > 0)
{
  $no = 1;
  $totalQty = 0;
  $totalAmount = 0;
  while($rs = dbFetchObject($qs))
  {
    $arr = array(
      'no' => $no,
      'date_add' => thaiDate($rs->date_add),
      'reference' => $rs->reference,
      'invoice' => $rs->invoice,
      'qty' => number($rs->qty),
      'amount' => number($rs->amount, 2)
    );

    array_push($sc, $arr);
    $no++;
    $totalQty += $rs->qty;
    $totalAmount += $rs->amount;
  }

  $arr = array(
    'totalQty' => number($totalQty),
    'totalAmount' => number($totalAmount, 2)
  );

  array_push($sc, $arr);
}
else
{
  $arr = array('nodata' => 'nodata');
  array_push($sc, $arr);
}

echo json_encode($sc);

 ?>
