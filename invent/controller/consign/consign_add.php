<?php
$cs = new consign();
$date_add = dbDate($_POST['date_add'], TRUE);
$arr = array(
    'reference' => $cs->getNewReference($date_add),
    'id_customer' => $_POST['id_customer'],
    'id_employee' => getCookie('user_id'),
    'id_zone' => $_POST['id_zone'],
    'id_channels' => $_POST['channels'],
    'remark'  => $_POST['remark'],
    'date_add'  => $date_add,
    'is_so' => $_POST['is_so']
);

$id = $cs->add($arr);

echo $id === FALSE ? 'เพิ่มเอกสารไม่สำเร็จ' : $id;

 ?>
