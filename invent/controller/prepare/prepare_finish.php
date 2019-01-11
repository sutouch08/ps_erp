<?php
  $id_order = $_POST['id_order'];
  $order = new order($id_order);
  $prepare = new prepare();
  $sc = TRUE;
  $message = "";

  //---	ถ้าสถานะเป็นกำลังจัด (บางทีอาจมีการเปลี่ยนสถานะตอนเรากำลังจัดสินค้าอยู่)
  if( $order->state == 4)
  {
    startTransection();
    //---	Set valid = 1 for all order details
    $ra = $order->validDetails($id_order);

    //---	เปลียน state ของออเดอร์ เป็น รอแพ็คสินค้า
    $rb = $order->stateChange($id_order, 5);

    if( $ra === TRUE && $rb === TRUE )
    {
      commitTransection();
    }
    else
    {
        $sc = FALSE;
        $message = "ปิดออเดอร์ไม่สำเร็จ กรุณาลองใหม่อีกครั้ง";
        dbRollback();
    }

  }
  else
  {
    $sc = FALSE;
    $message = "สถานะออเดอร์ถูกเปลี่ยน ไม่สามารถปิดออเดอร์ได้";
  }

  echo $sc === TRUE ? 'success' : $message;

 ?>
