<?php
$id    = $_GET['id_consign_check'];
$id_pd = $_GET['id_product'];
$pd    = new product();

$sc    = array('pdCode' => $pd->getCode($id_pd));

$cs = new consign_check();
$qs = $cs->getProductCheckedDetail($id, $id_pd);

if(dbNumRows($qs) > 0)
{
  $ds = array();
  while($rs = dbFetchObject($qs))
  {
    $arr = array(
      'box' => 'กล่องที่ '.$rs->box_no,
      'qty' => $rs->qty,
      'id_box' => $rs->id_consign_box,
      'id_pd' => $rs->id_product
    );

    array_push($ds, $arr);
  }

}
else
{
  $ds = array('nodata' => 'nodata');
}

$sc['rows'] = $ds;

echo json_encode($sc);
 ?>
