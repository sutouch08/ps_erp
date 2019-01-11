<?php
$sc = TRUE;
$allProduct = $_GET['allProduct'];
$pdFrom = $_GET['pdFrom'];
$pdTo = $_GET['pdTo'];

$Years = array();
$fYear = getConfig('START_YEAR');
$cYear = date('Y');

while($fYear <= $cYear)
{
  $Years[] = $fYear;
  $fYear++;
}

$Years[] = '0000';

$qr  = "SELECT pd.code, pd.name, pd.year, SUM(st.qty) AS qty ";
$qr .= "FROM tbl_stock AS st ";
$qr .= "JOIN tbl_product AS pd ON st.id_product = pd.id ";
$qr .= "LEFT JOIN tbl_product_style AS ps ON pd.id_style = ps.id ";
$qr .= "LEFT JOIN tbl_color AS co ON pd.id_color = co.id ";
$qr .= "LEFT JOIN tbl_size AS si ON pd.id_size = si.id ";
$qr .= "WHERE st.qty != 0 ";
if($allProduct == 0)
{
  $qr .= "AND pd.code >= '".$pdFrom."' ";
  $qr .= "AND pd.code <= '".$pdTo."' ";
}

$qr .= "GROUP BY st.id_product ";
$qr .= "ORDER BY ps.code ASC , co.code ASC, si.position ASC ";

$qs = dbQuery($qr);

if(dbNumRows($qs) < 2001)
{
  $ds = array();
  $no = 1;
  $total = array();
  foreach($Years as $year)
  {
    $total[$year.'_sum'] = 0;
  }

  while($rs = dbFetchObject($qs))
  {
    $arr = array(
      'no' => $no,
      'pdCode' => $rs->code,
      'pdName' => $rs->name
    );

    foreach($Years as $year)
    {
      $arr[$year.'_qty'] = $rs->year == $year ? number($rs->qty) : '-';
      $total[$year.'_sum'] += $rs->year == $year ? $rs->qty : 0;
    }

    $no++;
    array_push($ds, $arr);
    unset($arr);
  }

  array_push($ds, $total);
}
else
{
  $sc = FALSE;
  $message = 'ผลลัพธ์มีมากกว่า 2000 รายการ กรุณาส่งออกข้อมูลแทนการแสดงผลหน้าจอ';
}

echo $sc === TRUE ? json_encode($ds) : $message;



 ?>
