<?php
$allProduct = $_GET['allProduct'];
$pdFrom     = $_GET['pdFrom'];
$pdTo       = $_GET['pdTo'];
$styleFrom  = $_GET['styleFrom'];
$styleTo    = $_GET['styleTo'];
$showItem   = $_GET['showItem']; //--- ค้นมาเป็น รายการหรือเป็นรุ่น


$allWarehouse = $_GET['allWhouse'];
$warehouse    = isset($_GET['warehouse']) ? $_GET['warehouse'] : FALSE;

$allZone    = $_GET['allZone'];
$id_zone    = $_GET['id_zone'];

$prevDate   = $_GET['prevDate'];
$selectDate = $_GET['selectDate'];

$wh = new warehouse();
$wh_in      = "";
$wh_list    = "";

if($allWarehouse != 1)
{
  $i = 1;
  foreach($warehouse as $id_wh)
  {
    $wh_in .= $i == 1 ? "'".$id_wh."'" : ", '".$id_wh."'";
    $wh_list .= $i == 1 ? $wh->getCode($id_wh) : ", ".$wh->getCode($id_wh);
    $i++;
  }
}


$qr  = "SELECT z.zone_name, p.code, p.name, (SUM(s.move_in) - SUM(s.move_out)) AS qty ";
$qr .= "FROM tbl_stock_movement AS s ";
$qr .= "LEFT JOIN tbl_zone AS z ON s.id_zone = z.id_zone ";
$qr .= "LEFT JOIN tbl_product AS p ON s.id_product = p.id ";
$qr .= "LEFT JOIN tbl_product_style AS ps ON p.id_style = ps.id ";
$qr .= "LEFT JOIN tbl_color AS c ON p.id_color = c.id ";
$qr .= "LEFT JOIN tbl_size AS si ON p.id_size = si.id ";
$qr .= "WHERE s.date_upd <= '".toDate($selectDate)."' ";


if($allProduct != 1)
{

  if($showItem == 0)
  {
    //--- กรณีเป็นรุ่น
    $qr .= "AND ps.code >= '".$styleFrom."' ";
    $qr .= "AND ps.code <= '".$styleTo."' ";
  }
  else
  {
    //--- กรณีเป็นรายการ
    $qr .= "AND p.code >= '".$pdFrom."' ";
    $qr .= "AND p.code <= '".$pdTo."' ";
  }

}

if($allZone != 1 && $id_zone != '')
{
  $qr .= "AND s.id_zone = '".$id_zone."' ";
}


if($allZone == 1)
{
  if($allWarehouse == 0)
  {
    $qr .= "AND z.id_warehouse IN(".$wh_in.") ";
  }

}

$qr .= "GROUP BY p.id, z.id_warehouse, z.id_zone ";

$qr .= "ORDER BY ps.code ASC, c.code ASC, si.position ASC, z.zone_name ASC";

//echo $qr;
$qs = dbQuery($qr);

$sc = array();

if(dbNumRows($qs) > 0)
{
  $no = 1;
  $totalQty = 0;
  while($rs = dbFetchObject($qs))
  {
    $arr = array(
      'no' => number($no),
      'zone' => $rs->zone_name,
      'reference' => $rs->code,
      'productName' => $rs->name,
      'qty' => number($rs->qty)
    );

    array_push($sc, $arr);
    $no++;
    $totalQty += $rs->qty;
  }

  $arr = array('totalQty' => number($totalQty));
  array_push($sc, $arr);
}
else
{
  $arr = array('nodata' => 'nodata');
  array_push($sc, $arr);
}

echo json_encode($sc);


 ?>
