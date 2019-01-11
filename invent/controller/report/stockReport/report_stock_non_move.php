<?php
ini_set('memory_limit', '1024M');
set_time_limit(600);
$sc = TRUE;
$from = fromDate($_GET['fromDate']);
$to = toDate($_GET['toDate']);

$qr  = "SELECT st.id_product, SUM(st.qty) AS qty, pd.code AS pdCode, pd.name AS pdName, pd.cost ";
$qr .= "FROM tbl_stock AS st ";
$qr .= "LEFT JOIN tbl_product AS pd ON st.id_product = pd.id ";
$qr .= "LEFT JOIN tbl_color AS co ON pd.id_color = co.id ";
$qr .= "LEFT JOIN tbl_size AS si ON pd.id_size = si.id ";
$qr .= "WHERE st.qty != 0 ";
$qr .= "GROUP BY st.id_product ";
$qr .= "ORDER BY pd.code ASC";

$qs = dbQuery($qr);

$rows = dbNumRows($qs);

if($rows < 10000 && $rows > 0)
{
  $totalQty = 0;
  $totalAmount = 0;
  $ds = array();
  $no = 1;
  while($rs = dbFetchObject($qs))
  {
    $qm  = "SELECT id FROM tbl_order_sold WHERE id_product = '".$rs->id_product."' ";
    $qm .= "AND id_role IN(1,3,4,8) ";
    $qm .= "AND date_add >= '".$from."' AND date_add <= '".$to."' ";
    $qm .= "LIMIT 1";

    $qc = dbQuery($qm);
    $row = dbNumRows($qc);
    if($row == 0)
    {
      $qu  = "SELECT MAX(date_add) FROM tbl_order_sold WHERE id_product = '".$rs->id_product."' ";
      $qu .= "AND date_add < '".$from."' ";
      $qu .= "AND id_role IN(1,3,4,8) ";

      $qy = dbQuery($qu);

      list($lastMove) = dbFetchArray($qy);
      $lastMove = is_null($lastMove) ? '' : thaiDate($lastMove);

      $arr = array(
        'no' => $no,
        'pdCode' => $rs->pdCode,
        'pdName' => $rs->pdName,
        'pdCost' => number($rs->cost, 2),
        'qty' => number($rs->qty),
        'amount' => number($rs->qty * $rs->cost, 2),
        'lastMove' => $lastMove
      );

      array_push($ds, $arr);
      $totalQty += $rs->qty;
      $totalAmount += $rs->qty * $rs->cost;
      $no++;
    }
  }

  $arr = array(
    'totalQty' => number($totalQty),
    'totalAmount' => number($totalAmount, 2)
  );

  array_push($ds, $arr);
  $result = count($ds);
  if($result > 2000)
  {
    $sc = FALSE;
    $message = 'ผลลัพธ์มีมากถึง '.$result.' รายการ ไม่เหมาะสมต่อการแสดงผลบนหน้าจอ กรุณาส่งออกเป็นไฟล์ Excel แทน';
  }
}
else
{
  $sc = FALSE;
  $message = 'ผลลัพธ์อาจมีมากถึง '.$rows.' รายการ ไม่เหมาะสมต่อการแสดงผลบนหน้าจอ กรุณาส่งออกเป็นไฟล์ Excel แทน';
}

echo $sc === TRUE ? json_encode($ds) : $message;


 ?>
