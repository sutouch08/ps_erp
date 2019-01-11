<?php
$pdFrom  = $_GET['pdFrom'];
$pdTo    = $_GET['pdTo'];
$whFrom  = $_GET['whFrom'];
$whTo    = $_GET['whTo'];

$sc = array();
$products = array();
$header = array();

$qs = dbQuery("SELECT code, name FROM tbl_warehouse WHERE code >= '".$whFrom."' AND code <= '".$whTo."' ORDER BY code ASC");
if(dbNumRows($qs) > 0)
{
  while($rs = dbFetchObject($qs))
  {
    array_push($header,array('whName' => $rs->name));
    $wh[$rs->code] = $rs->code;
  }
}

$qr  = "SELECT pd.code FROM tbl_product AS pd ";
$qr .= "JOIN tbl_product_style AS st ON pd.id_style = st.id ";
$qr .= "JOIN tbl_color AS co ON pd.id_color = co.id ";
$qr .= "JOIN tbl_size AS si ON pd.id_size = si.id ";
$qr .= "WHERE pd.code >= '".$pdFrom."' ";
$qr .= "AND pd.code <= '".$pdTo."' ";
$qr .= "ORDER BY st.code ASC, co.code ASC, si.position ASC";

$qs = dbQuery($qr);

if(dbNumRows($qs) > 0)
{
  while($rs = dbFetchObject($qs))
  {
    $pds[$rs->code] = $rs->code;
  }
}

if( !empty($wh) && !empty($pds))
{
  $qr = "SELECT p.code AS pdCode, w.code AS whCode, SUM(s.qty) AS qty FROM tbl_stock AS s ";
  $qr .= "LEFT JOIN tbl_zone AS z ON s.id_zone = z.id_zone ";
  $qr .= "LEFT JOIN tbl_warehouse AS w ON z.id_warehouse = w.id ";
  $qr .= "LEFT JOIN tbl_product AS p ON s.id_product = p.id ";
  $qr .= "WHERE w.code >= '".$whFrom."' ";
  $qr .= "AND w.code <= '".$whTo."' ";
  $qr .= "AND p.code >= '".$pdFrom."' ";
  $qr .= "AND p.code <= '".$pdTo."' ";
  $qr .= "GROUP BY p.code, w.code ";
  $qr .= "ORDER BY p.code ASC, w.code ASC";

  $qs = dbQuery($qr);

  if(dbNumRows($qs) > 0)
  {
    $no = 1;
    while($rs = dbFetchObject($qs))
    {
      $product[$rs->pdCode][$rs->whCode] = $rs->qty;
    }

    foreach($pds as $pdCode)
    {

      $qtys = array();
      foreach($wh as $whCode)
      {
        $qty = isset($product[$pdCode][$whCode]) ? $product[$pdCode][$whCode] : 0;
        $arr = array('qty' => $qty);
        array_push($qtys, $arr);
      }

      $pd = array(
        'no' => $no,
        'pdCode' => $pdCode,
        'balance' => $qtys
      );

      $no++;
      array_push($products, $pd);

    }
  }
}


$sc = array(
  'header' => $header,
  'products' => $products
);

echo json_encode($sc);
/*
$qs = dbQuery("SELECT code, name FROM tbl_warehouse WHERE code >= '".$whFrom."' AND code <= '".$whTo."' ORDER BY code ASC");
if(dbNumRows($qs) > 0)
{
  $wh = array();
  while($rs = dbFetchObject($qs))
  {
    $wh[$rs->code] = $rs->name;
    array_push($header, array('whName' => $rs->name));
  }

  $qs = dbQuery("SELECT id, code FROM tbl_product WHERE code >= '".$pdFrom."' AND code <='".$pdTo."'");

  if( dbNumRows($qs) > 0)
  {
    $no = 1;
    while($rs = dbFetchObject($qs))
    {
      $balance = array();
      foreach($wh as $code => $name)
      {
        $qr = "SELECT SUM(s.qty) AS qty FROM tbl_stock AS s ";
        $qr .= "LEFT JOIN tbl_zone AS z ON s.id_zone = z.id_zone ";
        $qr .= "LEFT JOIN tbl_warehouse AS w ON z.id_warehouse = w.id ";
        $qr .= "LEFT JOIN tbl_product AS p ON s.id_product = p.id ";
        $qr .= "WHERE w.code = '".$code."' ";
        $qr .= "AND s.id_product = '".$rs->id."' ";

        //echo $qr;
        $qa = dbQuery($qr);
        list($qty) = dbFetchArray($qa);
        $qty = is_null($qty) ? 0 : $qty;
        $arr = array('qty' => number($qty));
        array_push($balance, $arr);
      }

      $arr = array(
        'no'  => $no,
        'pdCode' => $rs->code,
        'balance' => $balance
      );
      $no++;

      array_push($products, $arr);
    }
  }

  $sc = array(
    'header' => $header,
    'products' => $products
  );

  echo json_encode($sc);
}
*/



 ?>
