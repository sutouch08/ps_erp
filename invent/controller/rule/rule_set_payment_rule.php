<?php
$sc = TRUE;

$id_rule = $_POST['id_rule'];

//--- all payment ?
$all = $_POST['all_payment'] == 'Y' ? TRUE : FALSE;

if($all === TRUE)
{
  include 'rule/set_all_payment.php';
  exit;
}

if($all === FALSE)
{
  startTransection();
  //--- เปลี่ยนเงื่อนไข set all_payment = 0
  if(setAllPayment($id_rule, 0) !== TRUE)
  {
    exit;
  }

  //--- ลบเงื่อนไขเก่าออก
  if(dropPaymentRule($id_rule) !== TRUE)
  {
    $sc = FALSE;
    $message = 'ยกเลิกเงื่อนไขเก่าไม่สำเร็จ';
  }
  else
  {
    //--- เพิ่มเงื่อนไขใหม่
    $payment = isset($_POST['payment']) ? $_POST['payment'] : FALSE;
    if(!empty($payment))
    {
      foreach($payment as $id_payment)
      {
        $qr  = "INSERT INTO tbl_discount_rule_payment (id_rule, id_payment) ";
        $qr .= "VALUES ";
        $qr .= "(".$id_rule.", ".$id_payment.")";

        if(dbQuery($qr) !== TRUE)
        {
          $sc = FALSE;
          $message = 'เพิ่มเงื่อนไขช่องทางการชำระเงินไม่สำเร็จ';
        }
      } //--- end foreach
    } //--- end if !empty
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

} //--- end if $all === false

echo $sc === TRUE ? 'success' : $message;



 ?>
