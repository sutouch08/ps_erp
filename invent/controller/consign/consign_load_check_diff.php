<?php
$id_consign_check = $_GET['id_consign_check'];
$id_consign = $_GET['id_consign'];
$id_zone = $_GET['id_zone'];
$id_customer = $_GET['id_customer'];

$ck = new consign_check($id_consign_check);
$cs = new consign($id_consign);
$zone = new zone($id_zone);
$st = new stock();
$sc = TRUE;

//--- ไว้ตรวจสอบว่ามียอดต่างบ้างหรือไม่
//--- จำนวนจะถูกเพิ่มเมื่อรายการมียอดต่าง
$totalDiff = 0;


//--- จะทำการเพิ่มรารการได้ต้องยังไม่มีรายการโหลดก่อนหน้านี้
//--- สถานะต้องยังไม่บันทึก หรือ ไม่ถูกยกเลิก
if($cs->isSaved == 0 && $cs->isCancle == 0 && $cs->id_consign_check == 0)
{
  if($ck->status == 1 && $ck->valid == 0)
  {
    $qs = $ck->getDetails($ck->id);
    if(dbNumRows($qs) > 0)
    {
      startTransection();

      while($rs = dbFetchObject($qs))
      {
        //--- เตรียมข้อมูลสำหรับนำเข้ายอดต่าง
        $diff = $rs->stock_qty - $rs->qty;

        //--- เอาเฉพาะที่มียอดต่าง และ ยอดต่างต้องไม่ติดลบ
        if($diff > 0)
        {
          if($sc == FALSE)
          {
            break;
          }
          
          //--- สินค้าคงเหลือในโซนพอตัดหรือไม่
          $isEnough = $st->isEnough($id_zone, $rs->id_product, $diff);

          //--- ถ้าสินค้าคงเหลือในโซนพอตัด หรือ คลังนี้สามารถติดลบได้
          if( $isEnough === TRUE OR $zone->allowUnderZero === TRUE)
          {
            $pd = new product($rs->id_product);
            //--- ดึง GP จากเอกสารฝากขายล่าสุด
            $gp = $cs->getProductGP($rs->id_product, $id_zone);

            //--- แปลงเป็นส่วนลด (Label)
            $disc = $gp > 0 ? $gp.' %' : 0;

            //--- แปลงเป็นส่วนลดรวม (ยอดเงิน)
            $discAmount = $gp > 0 ? (($gp * 0.01) * $pd->price) * $diff : 0;

            //--- มูลค่าหลังหักส่วนลด (ยอดเงิน)
            $totalAmount = ($diff * $pd->price) - $discAmount;

            $arr = array(
              'id_consign' => $cs->id,
              'id_style' => $pd->id_style,
              'id_product' => $pd->id,
              'product_code' => $pd->code,
              'product_name' => $pd->name,
              'cost' => $pd->cost,
              'price' => $pd->price,
              'qty' => $diff,
              'discount' => $disc,
              'discount_amount' => $discAmount,
              'total_amount' => $totalAmount,
              'id_consign_check_detail' => $rs->id,
              'input_type' => 1 //--- 0 = hand, 1 = load diff, 2 = excel
            );

            if($cs->addDetail($arr) === FALSE)
            {
              $sc = FALSE;
              $message = 'เพิ่มรายการ '.$pd->code.' จำนวน '.$diff.' ไม่สำเร็จ';
            }
            else
            {
              $totalDiff += $diff;
            }

          }
          else
          {
            $sc = FALSE;
            $message =  $rs->product_code.' ยอดในโซนไม่พอตัด';
          } //--- End if isEnough

        } //--- end if diff > 0

      } //--- end while

      if($sc === TRUE)
      {
        //--- update id_consign_check on tbl_consign
        $arr = array(
          'id_consign_check' => $ck->id
        );

        if($cs->update($cs->id, $arr) !== TRUE)
        {
          $sc = FALSE;
          $message = 'Update id_consign_check fail';
        }

      }

      if($sc === TRUE)
      {
        //--- Update id_consign on tbl_consign_check
        $arr = array(
          'id_consign' => $cs->id,
          'valid' => 1
        );

        if($ck->update($ck->id, $arr) !== TRUE)
        {
          $sc = FALSE;
          $message = 'Update id_consign fail';
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
    else
    {
      $sc = FALSE;
      $message = 'ไม่พบยอดต่าง หรือ ยอดต่างเป็น 0';
    }
  }
  else
  {
    $sc = FALSE;
    if($ck->status != 1)
    {
      $message = 'สถานะยอดต่างไม่ถูกต้อง';
    }
    else if($ck->valid == 1)
    {
      $message = 'ไม่สามารถโหลดยอดต่างได้เนื่องถูกโหลดโดยเอกสารอื่นแล้ว';
    }

  }
}
else
{
  $sc = FALSE;
  if($cs->isSaved == 1)
  {
    $message = 'ไม่สามารถโหลดยอดต่างเข้าเอกสารที่บันทึกแล้ว';
  }
  else if($cs->isCancle == 1)
  {
    $message = 'ไม่สามารถโหลดยอดต่างเข้าเอกสารที่ยกเลิกแล้ว';
  }
  else if($cs->id_consign_check != 0)
  {
    $message = 'สามารถโหลดยอดต่างได้เพียง 1 เอกสารเท่านั้น';
  }
}

echo $sc === TRUE ? 'success' : $message;

 ?>
