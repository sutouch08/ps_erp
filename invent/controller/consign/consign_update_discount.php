<?php
$id_consign = $_POST['id_consign'];
$p_disc = $_POST['p_disc'];
$a_disc = $_POST['a_disc'];
$cs = new consign();

$sc = TRUE;

if(!empty($p_disc))
{
  startTransection();
  foreach($p_disc as $id => $pDisc)
  {
    if($sc == FALSE)
    {
      break;
    }
    
    $qs = $cs->getDetail($id);
    if(dbNumRows($qs) == 1)
    {
      $rs = dbFetchObject($qs);

      //--- ส่วนลดเป็นจำนวนเงิน
      $aDisc = $a_disc[$id];

      //--- ส่วนลดรวมเป็นจำนวนเงิน
      $discAmount = 0;

      $discLabel = $pDisc > 0 ? $pDisc.' %' : $aDisc;


      if($pDisc > 0)
      {
        //--- ถ้ามีส่วนลด(%)
        $discAmount = (($pDisc * 0.01) * $rs->price) * $rs->qty;
      }
      else if($aDisc > 0)
      {
        //--- ถ้ามีส่วนลด(จำนวนเงิน)
        $discAmount = $aDisc * $rs->qty;
      }


      $totalAmount = ($rs->qty * $rs->price) - $discAmount;

      //--- เตรียมข้อมูลสำหรับ update
      $arr = array(
        'discount' => $discLabel,
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
