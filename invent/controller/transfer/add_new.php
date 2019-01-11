<?php
  $sc = '';
  $cs = new transfer();

  //--- วันที่เอกสาร
  $date_add = dbDate($_POST['date_add'], TRUE);

  //--- รหัสเล่มเอกสาร
  $bookcode = getConfig('BOOKCODE_TRANSFER');

  if( $_POST['from_warehouse'] != $_POST['to_warehouse'] )
  {

    //--- เตรียมข้อมูลสำหรับเพิ่มเอกสาร
    $arr = array(
      'bookcode'       => $bookcode,
      'reference'      => $cs->getNewReference($date_add),
      'from_warehouse' => $_POST['from_warehouse'],
      'to_warehouse'   => $_POST['to_warehouse'],
      'id_employee'    => getCookie('user_id'),
      'date_add'       => $date_add,
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
  }
  else
  {
    $sc = 'คลังสินค้าไม่ถูกต้อง';
  }

echo $sc;

 ?>
