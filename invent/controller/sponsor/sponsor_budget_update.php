<?php

$id = $_POST['id_budget'];
$bd = new sponsor_budget($id);
$id_sponsor = $_POST['id_sponsor'];
$year = dbYear($_POST['year']);

if( $bd->isExistsYear($id_sponsor, $year, $id) === FALSE )
{
  $arr = array(
          'reference' => trim($_POST['reference']),
          'start' => dbDate($_POST['fromDate']),
          'end'	=> dbDate($_POST['toDate']),
          'year' => $year,
          'budget'	=> $_POST['budget'],
          'remark'	=> $_POST['remark']
        );

  //---	ถ้ามีการแก้ไขเพิ่มยอดงบประมาณต้องได้รับการอนุมัติก่อนใช้งาน
  //---	เป็นสถานะเป็นรออนุมัติ
  if( $bd->budget < $_POST['budget'])
  {
    $arr['active'] = 0;
  }

  $rs = $bd->update($id, $arr);

  if( $rs === TRUE )
  {
    $bd->calculate($id);
  }

  echo $rs === FALSE ? 'แก้ไขงบประมาณไม่สำเร็จ' : 'success';
}
else
{
  echo 'ปีงบประมาณซ้ำ กรุณาเลือกใหม่';
}

 ?>
