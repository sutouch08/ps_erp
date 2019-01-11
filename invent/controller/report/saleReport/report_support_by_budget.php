<?php

$allCustomer = $_GET['allCustomer'] == 1 ? TRUE : FALSE;
$fromCode = $_GET['fromCode']; //--- รหัสลูกค้า
$toCode = $_GET['toCode']; //-- รหัสลูกค้า
$orderBy = $_GET['orderBy']; //--- เรียงตาม
$budgetYear = $_GET['budgetYear'];

$ds = array();

$qr  = "SELECT cs.code, cs.name, bd.year, bd.budget, bd.used, bd.balance ";
$qr .= "FROM tbl_support_budget AS bd ";
$qr .= "LEFT JOIN tbl_support AS sp ON bd.id_support = sp.id ";
$qr .= "JOIN tbl_customer AS cs ON sp.id_customer = cs.id ";
$qr .= "WHERE bd.id_support != 0 ";

if($allCustomer === FALSE)
{
  if($fromCode != '' && $toCode != '')
  {
    $qr .= "AND cs.code >= '".$fromCode."' ";
    $qr .= "AND cs.code <= '".$toCode."' ";
  }
}

if($budgetYear != 0)
{
  $qr .= "AND bd.year = '".$budgetYear."' ";
}

$qr .= $orderBy == 'name' ? "ORDER BY cs.name ASC " : "ORDER BY cs.code ASC ";


$qs = dbQuery($qr);

if(dbNumRows($qs) > 0)
{
  $no = 1;
  $totalBudget = 0;
  $totalUsed = 0;
  $totalBalance = 0;

  while($rs = dbFetchObject($qs))
  {
    $arr = array(
      'no' => $no,
      'code' => $rs->code,
      'name' => $rs->name,
      'year' => $rs->year,
      'budget' => number($rs->budget, 2),
      'used' => number($rs->used, 2),
      'balance' => number($rs->balance, 2)
    );

    array_push($ds, $arr);
    $no++;
    $totalBudget += $rs->budget;
    $totalUsed += $rs->used;
    $totalBalance += $rs->balance;
  }

  $arr = array(
    'totalBudget' => number($totalBudget, 2),
    'totalUsed' => number($totalUsed, 2),
    'totalBalance' => number($totalBalance, 2)
  );

  array_push($ds, $arr);
}
else
{
  $ds = array('nodata' => 'nodata');
}



echo json_encode($ds);

 ?>
