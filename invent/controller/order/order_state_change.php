<?php

    /*
    การเปลียนสถานะมี 2 กรณี
    1. เปลี่ยนตามสถานะของออเดอร์ในแต่ละสถานีซึ่เป็นการเปลี่ยนโดยระบบ
    สถานะจะเปลี่ยนเพิ่มขึ้นเรื่อยๆ ทีละ 1 step

    2. เปลียนโดยคน เป็นการย้อนสถานะกลับไปแก้ไขข้อมูลต่าง หรือ ยกเลิกออเดอร์

    สถานะต่างๆมีดังนี้
    ID  สถานะ
    1   รอชำระเงิน
    2   ชำระเงินแล้ว
    3   รอจัดสินค้า
    4   กำลังจัดสินค้า     ** มีสินค้าเพิ่มใน buffer
    5   รอตรวจสินค้า     ** มีสินค้าใน buffer
    6   กำลังตรวจสินค้า   ** มีสินค้าใน buffer มีสินค้าใน qc
    7   รอเปิดบิล        ** มีสินค้าใน buffer มีสินค้าใน qc
    8   เปิดบิลแล้ว       ** สินค้าถูกเคลียร์ออกจาก buffer ส่วนที่เกินออเดอร์ถูกเพิ่มลงใน cancle มีการตัดสต็อก และบันทึกขาย บันทึก movement
    9   กำลังจัดส่ง
    10  จัดส่งแล้ว
    11  ยกเลิก

    *** กรณีที่มีการย้อนสถานะจากสถานะ 8-10
      1. ลบ movement
      2. ลบ รายการบันทึกขาย
      3. เพิ่มรายการกลับเข้า buffer
      4. เปลี่ยนสถานะ
    */

    $id = $_POST['id_order'];
    $order = new order($id);
    if( $order->state != 11)
    {
      $state = $_POST['state'];
      include '../function/bill_helper.php';
      //--- ขาย อภินันท์ สปอนเซอร์
      if( $order->role == 1)
      {
        include 'order/order_state.php';
      }

      //--- ฝากขาย  ยืม
      if( $order->role == 2)
      {
        include 'order/consign_state.php';
      }

      //--- เบิกอภินันท์
      if( $order->role == 3)
      {
        include 'order/support_state.php';
      }

      //--- เบิกสปอนเซอร์สโมสร
      if( $order->role == 4)
      {
        include 'order/sponsor_state.php';
      }

      //--- เบิกแปรสภาพ
      if( $order->role == 5 )
      {
        include 'order/transform_state.php';
      }

      //--- ยิมสินค้า
      if( $order->role == 6)
      {
        include 'order/lend_state.php';
      }
      
    }
    else
    {
      echo 'ออเดอร์ถูกยกเลิกแล้ว';
    }




?>
