<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";


if(isset($_GET['addNewStock']))
{
  $sc = TRUE;
  $id_pd = $_POST['id_product'];
  $id_zone = $_POST['id_zone'];
  $qty = $_POST['qty'];

  $qs = dbQuery("SELECT id_stock FROM tbl_stock WHERE id_product = '".$id_pd."' AND id_zone = '".$id_zone."'");
  if(dbNumRows($qs) > 0)
  {
    $sc = FALSE;
    $message = 'มีสินค้านี้ในโซนแล้ว ไม่สามารถเพิ่มใหม่ได้';
  }
  else
  {
    $qr  = "INSERT INTO tbl_stock (id_zone, id_product, qty) ";
    $qr .= "VALUES ('".$id_zone."', '".$id_pd."', ".$qty.")";
    $qs = dbQuery($qr);

    if(! $qs)
    {
      $sc = FALSE;
      $message = 'เพิ่มสต็อกไม่สำเร็จ';
    }
  }

  echo $sc === TRUE ? 'success' : $message;
}



if(isset($_GET['updateStock']))
{
  $sc = TRUE;

  $id = $_POST['id_stock'];
  $qty = $_POST['qty'];

  $qr = "UPDATE tbl_stock SET qty = ".$qty." WHERE id_stock = ".$id;
  if( dbQuery($qr) !== TRUE)
  {
    $sc = FALSE;
    $message = 'update fail';
  }

  echo $sc === TRUE ? 'success' : $message;
}



if(isset($_GET['deleteStock']))
{
  $sc = TRUE;
  $id = $_POST['id_stock'];

  $qr = "DELETE FROM tbl_stock WHERE id_stock = ".$id;

  if( dbQuery($qr) !== TRUE)
  {
    $sc = FALSE;
    $message = 'ลบรายการไม่สำเร็จ';
  }

  echo $sc === TRUE ? 'success' : $message;
}



if(isset($_GET['removeZero']))
{
  dbQuery("DELETE FROM tbl_stock WHERE qty = 0");
  echo 'done';
}



if(isset($_GET['clearFilter']))
{
  deleteCookie('pdCode');
  deleteCookie('zoneCode');
  echo 'done';
}
 ?>
