<?php
//---
$sc = array();

$id_product = $_GET['id_product'];
$id_supplier = $_GET['id_supplier'];
$from = fromDate($_GET['fromDate']);
$to = toDate($_GET['toDate']);
$sup = new supplier();

$qr  = "SELECT reference, id_supplier, date_add, SUM(qty) AS qty, SUM(received) AS received ";
$qr .= "FROM tbl_po ";
$qr .= "WHERE id_product = '".$id_product."' ";
$qr .= "AND isCancle = 0 ";
if($id_supplier != 0)
{
  $qr .= "AND id_supplier = '".$id_supplier."' ";
}

$qr .= "GROUP BY reference ";
$qr .= "ORDER BY date_add ASC";

$qs = dbQuery($qr);

if(dbNumRows($qs) > 0)
{
  $totalQty = 0;
  $totalReceived = 0;
  $totalBalance = 0;

  while($rs = dbFetchObject($qs))
  {
    $qty = $rs->qty;
    $received = $rs->received;
    $balance = $qty - $received;
    $balance = $balance < 0 ? 0 : $balance;

    $arr = array(
      'reference' => $rs->reference,
      'date_add' => thaiDate($rs->date_add),
      'sup_name' => $sup->getName($rs->id_supplier),
      'qty' => number($qty),
      'received' => number($received),
      'balance' => number($balance)
    );

    array_push($sc, $arr);
    $totalQty += $qty;
    $totalReceived += $received;
    $totalBalance += $balance;

  } //--- end while

  $arr = array(
    'totalQty' => number($totalQty),
    'totalReceived' => number($totalReceived),
    'totalBalance' => number($totalBalance)
  );

  array_push($sc, $arr);
} //--- end if

echo json_encode($sc);

 ?>
