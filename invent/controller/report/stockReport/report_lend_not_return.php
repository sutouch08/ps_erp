<?php
$sc = TRUE;

$allLender = $_GET['allLender'];
$lender = $_GET['lender'];

$allProduct = $_GET['allProduct'];
$pdFrom = $_GET['pdFrom'];
$pdTo = $_GET['pdTo'];

$from = fromDate($_GET['fromDate']);
$to = toDate($_GET['toDate']);

$em = new employee();

$qr  = "SELECT cus.name, od.reference, pd.code, ld.qty, ld.received, pd.price, em.first_name, em.last_name, ou.id_user ";
$qr .= "FROM tbl_order_lend_detail AS ld ";
$qr .= "JOIN tbl_order_lend AS lo ON ld.id_order = lo.id_order ";
$qr .= "JOIN tbl_order AS od ON ld.id_order = od.id ";
$qr .= "JOIN tbl_product AS pd ON ld.id_product = pd.id ";
$qr .= "JOIN tbl_customer AS cus ON od.id_customer = cus.id ";
$qr .= "JOIN tbl_employee AS em ON od.id_employee = em.id_employee ";
$qr .= "JOIN tbl_order_user AS ou ON od.id = ou.id_order ";
$qr .= "WHERE lo.isClosed = 0 ";
$qr .= "AND ld.received < ld.qty ";
$qr .= "AND od.date_add >= '".$from."' ";
$qr .= "AND od.date_add <= '".$to."' ";

if($allLender == 0)
{
  $qr .= "AND cus.id = '".$lender."' ";
}

if($allProduct == 0)
{
  $qr .= "AND pd.code >= '".$pdFrom."' ";
  $qr .= "AND pd.code <= '".$pdTo."' ";
}

$qr .= "ORDER BY cus.name ASC, pd.code ASC";

$qs = dbQuery($qr);
if(dbNumRows($qs) < 2001)
{
  $ds = array();
  $no = 1;
  $totalQty = 0;
  $totalReceived = 0;
  $totalBalance = 0;
  $totalAmount = 0;

  while($rs = dbFetchObject($qs))
  {
    $balance = $rs->qty - $rs->received;
    $arr = array(
      'no' => $no,
      'cusName' => $rs->name,
      'empName' => $rs->first_name.' '.$rs->last_name,
      'userName' => $em->getName($rs->id_user),
      'reference' => $rs->reference,
      'pdCode' => $rs->code,
      'qty' => number($rs->qty),
      'received' => number($rs->received),
      'balance' => number($balance),
      'price' => number($rs->price, 2),
      'amount' => number($balance * $rs->price, 2)
    );

    $no++;
    $totalQty += $rs->qty;
    $totalReceived += $rs->received;
    $totalBalance += $balance;
    $totalAmount += ($balance * $rs->price);

    array_push($ds, $arr);
    unset($arr);
  }

  $arr = array(
    'totalQty' => number($totalQty),
    'totalReceived' => number($totalReceived),
    'totalBalance' => number($totalBalance),
    'totalAmount' => number($totalAmount, 2)
  );

  array_push($ds, $arr);
  unset($arr);
}
else
{
  $sc = FALSE;
  $message = 'ผลลัพธ์มากกว่า 2000 รายการ กรุณาใช้การส่งออกแทนการแสดงผลหน้าจอ';
}

echo $sc === TRUE ? json_encode($ds) : $message;


 ?>
