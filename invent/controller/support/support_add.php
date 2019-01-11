<?php
$result = TRUE;
$message = '';

$id = $_POST['id_customer'];
$sp = new support();

//---	ตรวจสอบก่อนว่ามีลูกค้าคนตี้อยู่ในรายชื่อสปอนเซอร์แล้วหรือยัง
//---	ถ้ายังไม่มีเพิ่มใหม่
if( $sp->isExistsCustomer($id) === FALSE )
{
  $arr = array(
          'name'	=> $_POST['name'],
          'id_customer'	=> $id
          );

  //---	เพิ่มรายชื่อสปอนเซอร์ก่อน
  $id_support = $sp->add($arr);

  //---	ถ้าเพิ่มรายชื่อสำเร็จ
  if( $id_support != FALSE )
  {
    $bd = new support_budget();
    $arr = array(
            'reference'		=> $_POST['reference'],
            'id_support'	=> $id_support,
            'id_customer'	=> $id,
            'start'				=> dbDate($_POST['fromDate']),
            'end'					=> dbDate($_POST['toDate']),
            'year'				=> $_POST['year'],
            'budget'			=> $_POST['budget'],
            'remark'			=> $_POST['remark']
          );

    //---	เพิ่มงบประมาณ
    $rs = $bd->add($arr);

    //---	ถ้าเพิ่มงบประมาณสำเร็จ
    if( $rs != FALSE)
    {
      //--- คำนวณงบประมาณคงเหลือใหม่
      $bd->calculate($rs);

      //---	เลือกงบประมาณนี้เป็นงบประมาณที่ใช้งาน
      $sc = $sp->update($id_support, array('id_budget'	=> $rs));
    }
    else
    {
      $result = FALSE;
      $message = 'เพิ่มชื่อผู้รับสปอนเซอร์สำเร็จ แต่เพิ่มงบประมาณไม่สำเร็จ';
    }
  }
  else
  {
    $result = FALSE;
    $message = 'เพิ่มชื่อผู้รับสปอนเซอร์ไม่สำเร็จ';
  }
}
else //---	ถ้ามีอยู่ในรายชื่อแล้ว ไม่ต้องเพิ่มแจ้งกลับไป
{
  $result = FALSE;
  $message = 'ลูกค้านี้อยู่ในรายการผู้รับสปอนเซอร์อยู่แล้ว';
}

echo $result === TRUE ? 'success' : $message;
 ?>
