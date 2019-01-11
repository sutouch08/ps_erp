<?php

//--- adjust object
$cs = new adjust();

//--- รหัสเล่มเอกสาร
$bookcode = getConfig('BOOKCODE_ADJUST');

//--- วันที่เอกสาร
$date_add = dbDate($_POST['date_add'], TRUE);

//--- อ้างถึงเอกสารอื่นๆ
$refer = $_POST['refer'];

//--- ชื่อผู้ขอปรับยอด
$requester = $_POST['requester'];

//--- หมายเหตุเอกสาร
$remark = $_POST['remark'];

//--- ส่งข้อมูลไป formula หรือไม่
$is_so = isset($_POST['is_so']) ? $_POST['is_so'] : 1;  //--- default คือ ส่ง

//--- เลขที่เอกสาร
$reference = $cs->getNewReference($date_add);

//--- เตรียมข้อมูลเพื่อเพิ่มเอกสาร
$arr = array(
        'bookcode'  => $bookcode,
        'reference' => $reference,
        'refer'     => $refer,
        'id_employee' => getCookie('user_id'),
        'requester' => $requester,
        'remark'    => $remark,
        'date_add'  => $date_add,
        'is_so' => $is_so
      );

//--- เพิ่มเอกสาร
//--- ถ้าสำเร็จจะได้ insert id กลับมา
//--- ถ้าไม่สำเร็จ จะได้ FALSE
$id = $cs->add($arr);

if( $id === FALSE )
{
  $message = 'เพิ่มเอกสารไม่สำเร็จ กรุณาลองใหม่อีกครั้ง';
}

//--- ถ้าเพิ่มสำเร็จส่ง ไอดีกลับ ถ้าไม่สำเร็จส่ง error กลับ
echo $id === FALSE ? $message : $id;


 ?>
