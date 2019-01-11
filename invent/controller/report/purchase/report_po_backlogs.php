<?php

//--- ตรวจสอบ ใบสั่งซื้อ
$allPO  = $_GET['allPO'];
$poFrom = $_GET['poFrom'];
$poTo  = $_GET['poTo'];

$isClosed = $_GET['allClosed'];


$allSup = $_GET['allSup'];
$id_supplier = $_GET['id_supplier'];

$showItem = $_GET['showItem'];
$allProduct = $_GET['allProduct'];
$pdFrom = $_GET['pdFrom'];
$pdTo = $_GET['pdTo'];
$styleFrom = $_GET['styleFrom'];
$styleTo = $_GET['styleTo'];

$allDate = $_GET['allDate'];
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];

$qr  = "SELECT ";
$qr .= "po.date_add, ";
$qr .= "pd.code AS pdCode, ";
$qr .= "st.code AS styleCode, ";
$qr .= "po.reference, ";
$qr .= "su.name, ";
$qr .= "po.date_need, ";
$qr .= "SUM(po.qty) AS qty, ";
$qr .= "SUM(po.received) AS received, ";
$qr .= "po.status ";
$qr .= "FROM tbl_po AS po ";
$qr .= "LEFT JOIN tbl_supplier AS su ON po.id_supplier = su.id ";
$qr .= "LEFT JOIN tbl_product AS pd ON po.id_product = pd.id ";
$qr .= "LEFT JOIN tbl_color AS co ON pd.id_color = co.id ";
$qr .= "LEFT JOIN tbl_size AS si ON pd.id_size = si.id ";
$qr .= "LEFT JOIN tbl_product_style AS st ON po.id_style = st.id ";
$qr .= "WHERE po.isCancle = 0 AND qty > received ";


//--- ถ้าระบุใบสั่งซื้อ
if($allPO == 0)
{
  $qr .= "AND po.reference >= '".$poFrom."' ";
  $qr .= "AND po.reference <= '".$poTo."' ";
}

//--- เฉพาะใบสั่งซื้อที่ยังไม่ปิด
if($isClosed == 0)
{
  $qr .= "AND po.status != 3 ";
}

//--- เฉพาะใบสั่งซื้อที่ปิดแล้ว
if($isClosed == 1 )
{
  $qr .= "AND po.status = 3 ";
}

//----   ถ้าระบุ Supplier
if($allSup == 0)
{
  $qr .= "AND po.id_supplier = '".$id_supplier."' ";
}


//--- ถ้าระบุวันที่
if($allDate == 0)
{
  $qr .= "AND po.date_add >= '".fromDate($fromDate)."' ";
  $qr .= "AND po.date_add <= '".toDate($toDate)."' ";
}
else
{
  $qr .= "AND po.date_add >= '".date('Y-01-01 00:00:00')."' ";
  $qr .= "AND po.date_add <= '".date('Y-12-31 23:59:59')."' ";
}


//--- ถ้าแสดงผลเป็นรุ่นสินค้า
if($showItem == 0)
{
  if($allProduct == 0)
  {
    $qr .= "AND st.code >='".$styleFrom."' ";
    $qr .= "AND st.code <='".$styleTo."' ";
  }

  $qr .= "GROUP BY po.reference, po.id_style ";
  $qr .= "ORDER BY po.reference ASC, st.code ASC, co.code ASC , si.position ASC";

}
else
{
  if($allProduct == 0)
  {
    $qr .= "AND pd.code >='".$pdFrom."' ";
    $qr .= "AND pd.code <='".$pdTo."' ";
  }

  $qr .= "GROUP BY po.reference, po.id_product ";
  $qr .= "ORDER BY po.reference ASC, st.code ASC, co.code ASC , si.position ASC";
}

//echo $qr;
$sc = array();

$qs = dbQuery($qr);

if(dbNumRows($qs) > 0)
{
  $no = 1;
  $totalQty = 0;
  $totalReceived = 0;
  $totalBacklogs = 0;

  while($rs = dbFetchObject($qs))
  {
    $arr = array(
      'no' => number($no),
      'poDate' => thaiDate($rs->date_add),
      'itemCode' => $showItem == 0 ? $rs->styleCode : $rs->pdCode,
      'poCode' => $rs->reference,
      'supName' => $rs->name,
      'dueDate' => thaiDate($rs->date_need),
      'qty' => number($rs->qty),
      'received' => number($rs->received),
      'backlogs' => number($rs->qty - $rs->received),
      'remark' => $rs->status == 3 ? 'closed' : ($rs->status == 2 ? 'Part' : '')
    );

    array_push($sc, $arr);
    $totalQty += $rs->qty;
    $totalReceived += $rs->received;
    $totalBacklogs += ($rs->qty - $rs->received);
    $no++;
  }

  $arr = array(
    'totalQty' => number($totalQty),
    'totalReceived' => number($totalReceived),
    'totalBacklogs' => number($totalBacklogs)
  );

  array_push($sc, $arr);
}
else
{
  $sc = array('nodata' => '1');
}

echo json_encode($sc);

 ?>
