<?php
require '../../library/config.php';
require '../../library/functions.php';
require '../function/tools.php';
require '../function/zone_helper.php';


if(isset($_GET['addNew']))
{
  include 'consign_check/consign_check_add.php';

}



//----  ยิงบาร์โค้ดตรวจนับสินค้า
if(isset($_GET['checkItem']))
{
  include 'consign_check/consign_check_item.php';
}



//---- เปิดหน้าต่างดูว่าสิค้านี้ตรวจไปแล้วอยู่ในกล่องไหนบ้าง
if(isset($_GET['getProductCheckedDetail']))
{
  include 'consign_check/consign_checked_detail.php';
}


if(isset($_GET['getBox']))
{
  $id_consign_check = $_GET['id_consign_check'];
  $barcode = trim($_GET['barcode']);
  $cs = new consign_check();
  $box = $cs->getConsignBox($id_consign_check, $barcode);

  if($box !== FALSE)
  {
    $arr = array(
      'id_box' => $box->id,
      'box_no' => $box->box_no,
      'qty' => $cs->getQtyInBox($box->id, $id_consign_check)
    );
    $sc = json_encode($arr);
  }
  echo $box === FALSE ? 'เพิ่มกล่องไม่สำเร็จ' : $sc;
}



if(isset($_GET['getBoxList']))
{
  $id = $_GET['id_consign_check'];
  $cs = new consign_check();
  $ds = array();
  $qs = $cs->getBoxList($id);

  if(dbNumRows($qs) > 0)
  {
    while($rs = dbFetchObject($qs))
    {
      $arr = array(
        'id_box' => $rs->id,
        'barcode' => $rs->barcode,
        'box_no' => 'กล่องที่ '.$rs->box_no
      );

      array_push($ds, $arr);
    }
  }
  else
  {
    $arr = array(
      'nodata' => 'nodata'
    );
    array_push($ds, $arr);
  }

  echo json_encode($ds);
}



if(isset($_GET['removeCheckedItem']))
{
  include 'consign_check/consign_check_remove_item.php';
}



if(isset($_GET['deleteAllDetails']))
{
  include 'consign_check/consign_check_delete_all_detail.php';
}



if(isset($_GET['buildDetails']))
{
  include 'consign_check/consign_check_build_details.php';
}




if(isset($_GET['updateHeader']))
{
  $id  = $_POST['id_consign_check'];
  $arr = array(
    'date_add' => dbDate($_POST['date_add']),
    'remark'   => addslashes($_POST['remark']),
    'emp_upd'  => getCookie('user_id')
  );

  $cs = new consign_check();

  $sc = $cs->update($id, $arr);

  echo $sc === TRUE ? 'success' : $cs->error;
}


if(isset($_GET['closeConsignCheck']))
{
  $sc = TRUE;
  $id = $_POST['id_consign_check'];
  $cs = new consign_check($id);

  if($cs->valid == 0 && $cs->status == 0)
  {
    if($cs->close($id) !== TRUE)
    {
      $sc = FALSE;
      $message = 'ปิดการกระทบยอดไม่สำเร็จ';
    }
  }
  else
  {
    $sc = FALSE;
    $message = 'เอกสารถูกดึงไปตัดยอดฝากขายแล้ว';
  }

  echo $sc === TRUE ? 'success' : $message;

}


if(isset($_GET['unCloseConsignCheck']))
{
  $sc = TRUE;
  $id = $_POST['id_consign_check'];
  $cs = new consign_check($id);

  if($cs->valid == 0 && $cs->status == 1)
  {
    if($cs->unClose($id) !== TRUE)
    {
      $sc = FALSE;
      $message = 'เปิดการกระทบยอดไม่สำเร็จ';
    }
  }
  else
  {
    $sc = FALSE;
    $message = 'เอกสารถูกดึงไปตัดยอดฝากขายแล้ว';
  }

  echo $sc === TRUE ? 'success' : $message;
}



//-------  ลบรายการทั้งหมดทั้งในกล่อง และในเอกสาร แล้วยกเลิกเอกสาร(เปลี่ยนสถานะเป็น 2)
if(isset($_GET['cancleConsignCheck']))
{
  include 'consign_check/consign_check_cancle.php';
}



if(isset($_GET['getConsignCheckReference']))
{
  $id_customer = $_GET['id_customer'];
  $id_zone  = $_GET['id_zone'];
  $txt = $_REQUEST['term'];

  $sc  = array();

  $qr  = "SELECT reference, id FROM tbl_consign_check ";
  $qr .= "WHERE id_customer = '".$id_customer."' ";
  $qr .= "AND id_zone = '".$id_zone."' ";
  $qr .= "AND status = 1 AND valid = 0 ";
  if($txt != '*')
  {
    $qr .= "AND reference LIKE '%".$txt."%' ";
  }
  $qr .= "ORDER BY reference ASC";

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



//----- ส่งรายการเอกสารสำหรับนำเข้าตัดยอด
if(isset($_GET['getActiveCheckList']))
{
  include 'consign_check/consign_check_list.php';
}





if(isset($_GET['clearFilter']))
{
  deleteCookie('sCheckCode');
  deleteCookie('sCheckCus');
  deleteCookie('sCheckZone');
  deleteCookie('fromDate');
  deleteCookie('toDate');
  echo 'done';
}
 ?>
