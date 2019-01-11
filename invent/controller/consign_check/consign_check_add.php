<?php
$cs = new consign_check();
$stock = new stock();
$date_add = dbDate($_POST['date_add'], TRUE);
$arr = array(
    'reference' => $cs->getNewReference($date_add),
    'id_customer' => $_POST['id_customer'],
    'id_employee' => getCookie('user_id'),
    'id_zone' => $_POST['id_zone'],
    'remark'  => addslashes($_POST['remark']),
    'date_add'  => $date_add
);

$id = $cs->add($arr);

//--- ถ้าเพิ่มสำเร็จจะได้ ไอดีมา ตรวจสอบว่าไอดีเป็นตัวเลขหรือไม่(ป้องกันความผิดพลาด)
if(is_numeric($id) === TRUE && $id > 0)
{
  $qs = $stock->getStockInZone($_POST['id_zone']);
  if(dbNumRows($qs) > 0)
  {
    $sc = TRUE;
    startTransection();
    while($rs = dbFetchObject($qs))
    {
      if($sc == FALSE)
      {
        break;
      }
      
      $arr = array(
        'id_consign_check' => $id,
        'id_product' => $rs->id_product,
        'product_code' => $rs->code,
        'stock_qty' => $rs->qty
      );

      if($cs->addDetail($arr) !== TRUE)
      {
        $sc = FALSE;
        $message = 'บันทึกยอดตั้งต้นไม่สำเร็จ';
      }
    }

    if($sc === TRUE)
    {
      commitTransection();
    }
    else
    {
      dbRollback();
    }

    endTransection();
  }

}

echo $id === FALSE ? 'เพิ่มเอกสารไม่สำเร็จ' : $id;

 ?>
