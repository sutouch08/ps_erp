<?php
require '../../library/config.php';
require '../../library/functions.php';
require '../function/tools.php';


if(isset($_GET['saveReturnLend']))
{
  include 'return_lend/return_lend_save.php';
}




if(isset($_GET['cancleReturnLend']))
{
  include 'return_lend/cancle_return_lend.php';
}


if(isset($_GET['getLendCode']))
{
  $txt = $_REQUEST['term'];
  $id_customer = isset($_GET['id_customer']) ? $_GET['id_customer'] : FALSE;
  $limit = 50; //---- limit result

  $sc = array();

  $qr  = "SELECT od.reference FROM tbl_order AS od ";
  $qr .= "JOIN tbl_order_lend AS ol ON od.id = ol.id_order ";
  $qr .= "WHERE od.role = 6 AND ol.isClosed = 0 ";
  if($txt != '*')
  {
    $qr .= "AND od.reference LIKE '%".$txt."%' ";
  }

  if($id_customer !== FALSE)
  {
    $qr .= "AND id_customer = '".$id_customer."' ";
  }

  $qr .= "ORDER BY od.reference ASC";

  $qs = dbQuery($qr);

  if(dbNumRows($qs) > 0)
  {
    while($rs = dbFetchObject($qs))
    {
      $sc[] = $rs->reference;
    }
  }
  else
  {
    $sc[] = 'ไม่พบข้อมูล';
  }

  echo json_encode($sc);
}



if(isset($_GET['getCustomerId']))
{
  $code = $_GET['code'];
  $cs = new customer();

  $sc = $cs->getId($code);

  echo $sc == 0 ? 'notfount' : $sc;
}


//---  ดึงข้อมูลเอกสารยืมสินค้าจากเลขที่เอกสาร
if(isset($_GET['getLendOrderByCode']))
{
  include 'return_lend/get_order_by_code.php';
}


//--- ตรวจสอบบาร์โค้ดที่ยิงมา
if(isset($_GET['checkBarcode']))
{
  include 'return_lend/check_barcode.php';
}



if(isset($_GET['clearFilter']))
{
  deleteCookie('sCode');
  deleteCookie('sLendCode');
  deleteCookie('sCus');
  deleteCookie('sEmp');
  deleteCookie('fromDate');
  deleteCookie('toDate');
  echo 'done';
}
 ?>
