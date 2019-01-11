<?php
$sc = TRUE;

//--   ไอดีรายการที่จะลบ
$id	= $_POST['id_move_detail'];

//--- ไอดีเอกสาร
$id_move = $_POST['id_move'];

//--- move object พร้อมข้อมูล
$cs = new move($id_move);

//--- stock object สำหรับจัดการสต็อก
$stock = new stock();

//--- zone object สำหรับจัดการโซนสินค้า
$zone = new zone();

//--- movement object สำหรับจัดการ movement
$movement = new movement();

//--- cancle zone object
$cn = new cancle_zone();


//--- product object
$pd = new product();

//----- ดึงรายการที่จะลบมาตรวจสอบก่อน
//--- ได้ object กลับมา
$rs = $cs->getDetail($id);

if( $rs !== FALSE )
{
  startTransection();

  //--- ถ้าโอนเข้าโซนปลายทางเรียบร้อยแล้ว
  if( $rs->valid == 1 && $rs->to_zone != 0 )
  {

    //------ ตรวจสอบยอดคงเหลือในโซนก่อนว่าพอที่จะย้ายกลับมั้ย
    $isEnough = $stock->isEnough($rs->to_zone, $rs->id_product, $rs->qty);


    if( $isEnough === FALSE )
    {
      $sc = FALSE;
      $message = 'ยอดคงเหลือในโซนไม่พอให้ย้ายกลับ';
    }
    else
    {
      //----- ถ้าพอย้าย ดำเนินการย้าย
      //--- ตัดยอดออกจากโซนปลายทาง
      if( $stock->updateStockZone($rs->to_zone, $rs->id_product, ($rs->qty * -1)) !== TRUE )
      {
        $sc = FALSE;
        $message = 'ตัดยอดสต็อกไม่สำเร็จ';
      }

      //--- ลบ movement ขาเข้า
      if( $movement->dropMoveIn($cs->reference, $rs->to_zone, $rs->id_product) !== TRUE )
      {
        $sc = FALSE;
        $message = 'ลบ movement ขาเข้า ไม่สำเร็จ';
      }

      //--- คืนยอดโซนต้นทาง
      if( $rs->from_cancle == 1 && $rs->id_order != 0 )
      {
        if( $cn->updateCancle($rs->id_order, $pd->getStyleId($rs->id_product), $rs->id_product, $rs->from_zone, $rs->id_warehouse, $rs->qty) !== TRUE)
        {
          $sc = FALSE;
          $message = 'คืนยอดสินค้าเข้าโซนยกเลิกไม่สำเร็จ';
        }
      }
      else
      {
        if( $stock->updateStockZone($rs->from_zone, $rs->id_product, $rs->qty) !== TRUE )
        {
          $sc = FALSE;
          $message = 'คืนยอดสต็อกไม่สำเร็จ';
        }
      }


      //--- ลบ movement ขาออก
      if( $movement->dropMoveOut($cs->reference, $rs->from_zone, $rs->id_product) !== TRUE )
      {
        $sc = FALSE;
        $message = 'ลบ movement ขาออก ไม่สำเร็จ';
      }

      //--- ลบรายการโอนสินค้า
      if( $cs->deleteDetail($rs->id) !== TRUE )
      {
        $sc = FALSE;
        $message = 'ลบรายการโอนไม่สำเร็จ';
      }
    }
  }
  else /////---- if valid
  {
    //------- move stock in temp to original zone
    //-------  get stock in temp
    $qr = $cs->getTempDetail($id);

    if( dbNumRows($qr) == 1 )
    {
      $res = dbFetchObject($qr);

      //--- ถ้ายอดใน temp น้อยกว่ายอดที่โอนออกมา
      //--- แสดงว่ามีการโอนเข้าปลายทางแล้วบางส่วน
      //--- ต้องทำการย้ายจากโซนปลายทางกลับเข้า temp
      if( $res->qty < $rs->qty)
      {
        //--- ยอดต่างสำหร้บไว้ย้ายกลังเข้า temp
        $diff_qty = $rs->qty - $res->qty;

        //------ ตรวจสอบยอดคงเหลือในโซนก่อนว่าพอที่จะย้ายกลับมั้ย
        $isEnough = $stock->isEnough($rs->to_zone, $rs->id_product, $diff_qty);

        if( $isEnough === FALSE )
        {
          $sc = FALSE;
          $message = 'ยอดคงเหลือในโซนไม่พอให้ย้ายกลับ';
        }
        else
        {
          //--- ถ้าสินค้าพอย้ายกลับ หรือ โซนสามารถติดลบได้
          //--- ลดยอดโซนปลายทาง
          if( $stock->updateStockZone($rs->to_zone, $rs->id_product, $diff_qty * -1) !== TRUE)
          {
            $sc = FALSE;
            $message = 'ลดยอดสต็อกปลายทางไม่สำเร็จ';
          }

          //--- ลบ movement ขาเข้าโซนปลายทาง
          if( $movement->dropMoveIn($cs->reference, $rs->to_zone, $rs->id_product) !== TRUE)
          {
            $sc = FALSE;
            $message = 'ลบ movement ขาเข้าโซนปลายทางไม่สำเร็จ';
          }

          //--- เพิ่มยอดเข้า temp
          if( $cs->updateTransferTemp($res->id, $diff_qty ) !== TRUE)
          {
            $sc = FALSE;
            $message = 'Update temp ไม่สำเร็จ';
          }

        } //--- end if isEnough

      } //--- end if $res->qty < $rs->qty

      //------- คืนยอดสต็อกเข้าโซนต้นทาง
      if( $rs->from_cancle == 1 && $rs->id_order != 0 )
      {
        if( $cn->updateCancle($rs->id_order, $pd->getStyleId($rs->id_product), $rs->id_product, $rs->from_zone, $rs->id_warehouse, $rs->qty) !== TRUE)
        {
          $sc = FALSE;
          $message = 'คืนยอดสินค้าเข้าโซนยกเลิกไม่สำเร็จ';
        }
      }
      else
      {
        if( $stock->updateStockZone($rs->from_zone, $rs->id_product, $rs->qty) !== TRUE )
        {
          $sc = FALSE;
          $message = 'คืนยอดสต็อกไม่สำเร็จ';
        }
      }

      //--- ลบ movement ขาออก
      if( $movement->dropMoveOut($cs->reference, $rs->from_zone, $rs->id_product) !== TRUE )
      {
        $sc = FALSE;
        $message = 'ลบ movement ขาออก ไม่สำเร็จ';
      }

      //--- ลบ temp
      if( $cs->removeTempDetail($id) !== TRUE )
      {
        $sc = FALSE;
        $message = 'ลบ temp ไม่สำเร็จ';
      }

      //--- ลบรายการโอนสินค้า
      if( $cs->deleteDetail($rs->id) !== TRUE )
      {
        $sc = FALSE;
        $message = 'ลบรายการโอนไม่สำเร็จ';
      }

    } //--- end if dbNumrows get temp

  } //--- end if valid


  if( $sc === TRUE )
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
  $message = 'ไม่พบข้อมูลการโอนสินค้า';

}//--- end if $rs !== FALSE

echo $sc === TRUE ? 'success' : $message;
 ?>
