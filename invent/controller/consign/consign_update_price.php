<?php
$id_consign = $_POST['id_consign'];
$priceList = $_POST['price'];
$cs = new consign();

$sc = TRUE;

if(!empty($priceList))
{
  startTransection();
  foreach($priceList as $id => $price)
  {
    if($sc == FALSE)
    {
      break;
    }
    
    $qs = $cs->getDetail($id);
    if(dbNumRows($qs) == 1)
    {
      $rs = dbFetchObject($qs);
      $disc = explode(' ',trim($rs->discount));

      //--- ส่วนลดเป็น %
      $p_disc = count($disc) > 1 ? $disc[0] : 0;

      //--- ส่วนลดเป็นจำนวนเงิน
      $a_disc = count($disc) > 1 ? 0 : $disc[0];

      //--- ส่วนลดรวมเป็นจำนวนเงิน
      $discAmount = 0 ;

      //--- ถ้ามีส่วนลด(%)
      if($p_disc > 0)
      {
        $discAmount = (($p_disc * 0.01) * $price) * $rs->qty;
      }

      //--- ถ้ามีส่วนลด(จำนวนเงิน)
      if($a_disc > 0)
      {
        $discAmount = $a_disc * $rs->qty;
      }

      $totalAmount = ($rs->qty * $price) - $discAmount;

      //--- เตรียมข้อมูลสำหรับ update
      $arr = array(
        'price' => $price,
        'discount_amount' => $discAmount,
        'total_amount' => $totalAmount
      );

      if($cs->updateDetail($id, $arr) !== TRUE)
      {
        $sc = FALSE;
        $message = 'บันทึกราคา '.$rs->product_code.' ไม่สำเร็จ';
      }
    } //--- end if dbNumrows
  } //--- end foreach

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
else
{
  $sc = FALSE;
  $message = 'ไม่พบรายการที่ต้องปรับปรุง';
}

echo $sc === TRUE ? 'success' : $message;

 ?>
