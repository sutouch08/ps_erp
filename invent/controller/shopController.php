<?php
require '../../library/config.php';
require '../../library/functions.php';
require '../function/tools.php';


if(isset($_GET['getShopData']))
{
  $sc = '';
  //--- รหัสร้าน
  $id = $_GET['id_shop'];
  $shop = new shop();
  $rs = $shop->getShopData($id);
  if( !empty($rs))
  {
    $sc = $rs->id_customer.' | '.$rs->customer_name.' | '.$rs->id_zone.' | '.$rs->zone_name;
  }

  echo $sc;
}


if(isset($_GET['getShop']))
{
  $sc = array();
  $cs = new shop();
  $qs = $cs->search($_REQUEST['term']);
  if( dbNumRows($qs) > 0)
  {
    while($rs = dbFetchObject($qs))
    {
      $sc[] = $rs->code.' | '.$rs->name.' | '.$rs->id;
    }
  }
  else
  {
    $sc[] = 'ไม่พบจุดขาย';
  }

  echo json_encode($sc);
}

 ?>
