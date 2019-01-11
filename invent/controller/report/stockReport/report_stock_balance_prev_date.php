<?php
//--- รายงานสินค้าคงเหลือ ณ ปัจจุบัน
$sc = array();
$allProduct = $_GET['allProduct'];
$allWarehouse = $_GET['allWhouse'];
$prevDate   = $_GET['prevDate'];
$selectDate = $_GET['selectDate'];
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
$sc['reportDate'] = thaiDate($selectDate, '/');
$sc['whList']   = $allWarehouse == 1 ? 'ทั้งหมด' : $wh_list;
$sc['productList']   = $allProduct == 1 ? 'ทั้งหมด' : '('.$pdFrom.') - ('.$pdTo.')';


$qr  = "SELECT b.barcode, p.code, p.name, p.cost, (SUM(s.move_in) - SUM(s.move_out)) AS qty ";
$qr .= "FROM tbl_stock_movement AS s ";
$qr .= "JOIN tbl_product AS p ON s.id_product = p.id ";
$qr .= "JOIN tbl_product_style AS ps ON p.id_style = ps.id ";
$qr .= "LEFT JOIN tbl_barcode AS b ON p.id = b.id_product ";
$qr .= "JOIN tbl_warehouse AS wh ON s.id_warehouse = wh.id "; //--- add
$qr .= "JOIN tbl_zone AS zn ON zn.id_zone = s.id_zone "; //--- add
$qr .= "WHERE s.date_upd <= '".toDate($selectDate)."' ";



if($allProduct != 1)
{
  $qr .= "AND ps.code >= '".$pdFrom."' ";
  $qr .= "AND ps.code <= '".$pdTo."' ";
}

if($allWarehouse != 1)
{
  $qr .= "AND zn.id_warehouse IN(".$wh_in.") ";
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
