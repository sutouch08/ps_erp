<?php

$order = new order();
$qs = $order->getOverDateOrder();
if(dbNumRows($qs) > 0)
{
  startTransection();
  while($rs = dbFetchObject($qs))
  {
    $sc = TRUE;
    if($order->setOrderExpired($rs->id) !== TRUE)
    {
      $sc = FALSE;
    }

    if($rs->role == 4)
    {
      $details = $order->getDetails($rs->id);
      if(dbNumRows($details) > 0)
      {
        //--- เตรียมข้อมูลงบประมาณ
        $bd = new sponsor_budget();

        while($rd = dbFetchObject($details))
        {
          //--- ดึงยอดเงินของรายการที่บันทึกแล้ว
        	$amount = $order->getDetailAmountSaved($rd->id);

          //--- ถ้ายอดเงินมากกว่า 0 คืนยอดงบประมาณ
          if($amount > 0)
          {
            if($bd->decreaseUsed($rs->id_budget, $amount) !== TRUE)
            {
              $sc = FALSE;
            }
          }
        }//--- end while
      }//--- end if dbNumRows;

    }//--- end if $order->role == 4



    if($rs->role == 3)
    {
      $details = $order->getDetails($rs->id);
      if(dbNumRows($details) > 0)
      {
        //--- เตรียมข้อมูลงบประมาณ
        $bd = new support_budget();

        while($rd = dbFetchObject($details))
        {
          //--- ดึงยอดเงินของรายการที่บันทึกแล้ว
        	$amount = $order->getDetailAmountSaved($rd->id);

          //--- ถ้ายอดเงินมากกว่า 0 คืนยอดงบประมาณ
          if($amount > 0)
          {
            if($bd->decreaseUsed($rs->id_budget, $amount) !== TRUE)
            {
              $sc = FALSE;
            }
          }
        }//--- end while
      }//--- end if dbNumRows;

    }//--- end if $order->role == 4


    if($order->setOrderDetailExpired($rs->id) !== TRUE)
    {
      $sc = FALSE;
    }

    if($sc === TRUE)
    {
      commitTransection();
    }
    else
    {
      dbRollback();
    }

  }//--- End while

  endTransection();

}

echo 'done';

 ?>
