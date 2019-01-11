<?php
//--- รายงานสินค้าคงเหลือ ณ ปัจจุบัน
$sc = array();
$allProduct = $_GET['allProduct'];
$allWarehouse = $_GET['allWhouse'];
$pdFrom     = $_GET['pdFrom'];
$pdTo       = $_GET['pdTo'];
$whList     = $allWarehouse == 1 ? FALSE : $_GET['warehouse'];
$wh = new warehouse();
$wh_in      = "";
$wh_list    = "";

if($allWarehouse != 1)
{
  $i = 1;
  foreach($whList as $id_wh)
  {
    $wh_in .= $i == 1 ? "'".$id_wh."'" : ", '".$id_wh."'";
    $wh_list .= $i == 1 ? $wh->getCode($id_wh) : ", ".$wh->getCode($id_wh);
    $i++;
  }
}


//---  Report title
$sc['reportDate'] = thaiDate(date('Y-m-d'), '/');
$sc['whList']   = $allWarehouse == 1 ? 'ทั้งหมด' : $wh_list;
$sc['productList']   = $allProduct == 1 ? 'ทั้งหมด' : '('.$pdFrom.') - ('.$pdTo.')';


$qr  = "SELECT b.barcode, p.code, p.name, p.cost, SUM(s.qty) AS qty ";
$qr .= "FROM tbl_stock AS s ";
$qr .= "LEFT JOIN tbl_zone AS z ON s.id_zone = z.id_zone ";
$qr .= "LEFT JOIN tbl_product AS p ON s.id_product = p.id ";
$qr .= "LEFT JOIN tbl_product_style AS ps ON p.id_style = ps.id ";
$qr .= "LEFT JOIN tbl_barcode AS b ON p.id = b.id_product ";
$qr .= "WHERE s.id_zone != '' ";

if($allProduct != 1)
{
  $qr .= "AND ps.code >= '".$pdFrom."' ";
  $qr .= "AND ps.code <= '".$pdTo."' ";
}

if($allWarehouse != 1)
{
  $qr .= "AND z.id_warehouse IN(".$wh_in.") ";
}

$qr .= "GROUP BY p.id ";

$qr .= "ORDER BY ps.code ASC";

//echo $qr;

$qs = dbQuery($qr);

$bs = array();

if(dbNumRows($qs) > 0)
{
  $no = 1;
  $totalQty = 0;
  $totalAmount = 0;
  while($rs = dbFetchObject($qs))
  {
    $arr = array(
      'no' => number($no),
      'barcode' => $rs->barcode,
      'pdCode' => $rs->code,
      'pdName' => $rs->name,
      'cost' => number($rs->cost, 2),
      'qty' => number($rs->qty),
      'amount' => number($rs->cost * $rs->qty, 2)
    );

    array_push($bs, $arr);
    $no++;

    $totalQty += $rs->qty;
    $totalAmount += ($rs->qty * $rs->cost);
  }

  $arr = array(
    'totalQty' => number($totalQty),
    'totalAmount' => number($totalAmount, 2)
  );

  array_push($bs, $arr);
}
else
{
  $arr = array('nodata' => 'nodata');
  array_push($bs, $arr);
}

$sc['bs'] = $bs;

echo json_encode($sc);

 ?>
