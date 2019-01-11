<?php
  $sc = '';
  $cs = new move();

  //--- เตรียมข้อมูลสำหรับเพิ่มเอกสาร
  $arr = array(
    'reference'      => $cs->getNewReference(),
    'id_warehouse'   => $_POST['id_warehouse'],
    'id_employee'    => getCookie('user_id'),
    'remark'         => $_POST['remark']
  );

  //--- เพิ่มเอกสาร ถ้าสำเร็จจะได้ ไอดีกลับมา ถ้าไม่สำเร็จจะได้ FALSE;
  $rs = $cs->add($arr);

  //--- ถ้าสำเร็จ
  if( $rs !== FALSE )
  {
    //--- เตรียมส่งไอดีกลับ
    $sc = $rs;
  }
  else
  {
    $sc = 'เพิ่มเอกสารไม่สำเร็จ';
  }

echo $sc;

 ?>
