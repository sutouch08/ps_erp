<?php
//---
$sc = array();

$id_style = $_GET['id_style'];
$allSup = $_GET['allSupplier'];
$id_supplier = $_GET['id_supplier'] == '' ? 0 : $_GET['id_supplier'];
$from = fromDate($_GET['fromDate']);
$to = toDate($_GET['toDate']);

$qr  = "SELECT pd.id, pd.code, co.code AS color, si.code AS size ";
$qr .= "FROM tbl_product AS pd ";
$qr .= "LEFT JOIN tbl_color AS co ON pd.id_color = co.id ";
$qr .= "LEFT JOIN tbl_size AS si ON pd.id_size = si.id ";
$qr .= "WHERE pd.id_style = '".$id_style."' ";
$qr .= "ORDER BY co.code ASC, si.position ASC ";

$qs = dbQuery($qr);

if(dbNumRows($qs) > 0)
{
  $no = 1;
  $totalQty = 0;
  $totalReceived = 0;
  $totalBalance = 0;

  while($rs = dbFetchObject($qs))
  {
    $sql  = "SELECT SUM(qty) AS qty, SUM(received)AS received, id_supplier FROM tbl_po ";
    $sql .= "WHERE id_product = '".$rs->id."' ";
    $sql .= "AND isCancle = 0 ";
    if($id_supplier != 0)
    {
      $sql .= "AND id_supplier = '".$id_supplier."' ";
    }

    $sql .= "AND date_add >= '".$from."' ";
    $sql .= "AND date_add <= '".$to."' ";

    $qa = dbQuery($sql);
    $rd = dbFetchObject($qa);

    $qty = $rd->qty;
    $received = $rd->received;
    $balance = $qty - $received;
    $balance = $balance < 0 ? 0 : $balance;

    $arr = array(
      'no' => $no,
      'id_product' => $rs->id,
      'pdCode' => $rs->code,
      'color' => $rs->color,
      'size' => $rs->size,
      'Qty' => number($qty),
      'received' => number($received),
      'balance' => number($balance),
      'id_sup' => $id_supplier,
      'content' => $rd->qty > 0 ? 'content' : ''
    );

    array_push($sc, $arr);
    $no++;
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
