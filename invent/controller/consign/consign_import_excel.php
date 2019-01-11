<?php
$sc = TRUE;
$id_consign = $_POST['id_consign'];
$cs = new consign($id_consign);
if($cs->isSaved == 0 && $cs->isCancle == 0)
{
  $file = isset( $_FILES['excel'] ) ? $_FILES['excel'] : FALSE;
  $file_path 	= "../../upload/consign/";
  $upload	= new upload($file);

  $zone = new zone($cs->id_zone);
  $st = new stock();

  //--- ไว้ตรวจสอบว่ามียอดต่างบ้างหรือไม่
  //--- จำนวนจะถูกเพิ่มเมื่อรายการมียอดต่าง
  $totalImported = 0;


  $upload->file_new_name_body = $cs->reference.'-'.date('YmdHis');
  $upload->file_overwrite     = TRUE;
  $upload->auto_create_dir    = FALSE;

  $upload->process($file_path);

  if( ! $upload->processed)
  {
    $sc = FALSE;
    $message = $upload->error;
  }
  else
  {

    $reader = new PHPExcel_Reader_Excel2007();
    $reader->setReadDataOnly(TRUE);
    $excel = $reader->load($upload->file_dst_pathname);
    $collection	= $excel->getActiveSheet()->toArray(NULL, TRUE, TRUE, TRUE);

    $pd = new product();
    $mv = new movement();
    $st = new stock();
    $zone = new zone();
    $wh = new warehouse();

    startTransection();

      $i = 1;
      foreach($collection as $rs)
      {
        if($sc == FALSE)
        {
          break;
        }

        if($i > 1)
        {
          //--- A => product code
          //--- B => price
          //--- C => qty
          //--- D => discount (%)
          //--- E => discount (฿)

          //--- เตรียมข้อมูลสำหรับนำเข้ายอดต่าง
          $pdCode = $rs['A'];
          $price  = $rs['B'];
          $qty    = $rs['C'];
          $pDisc  = round($rs['D'], 2);
          $aDisc  = round($rs['E'],2);

          //--- เอาเฉพาะที่มียอดต่าง และ ยอดต่างต้องไม่ติดลบ
          if($qty > 0)
          {
            //--- ดึง id product จากรหัสสินค้า
            $id_pd = $pd->getId($pdCode);

            //--- ถ้าไม่พบไอดีสินค้าแสดงว่าไม่มีสินค้าในระบบ
            if($id_pd === FALSE)
            {
              $sc = FALSE;
              $message = 'ไม่พบรหัสสินค้า '.$pdCode.' ในระบบ';
            }
            else
            {
              if($pDisc > 100)
              {
                $sc = FALSE;
                $message = $pdCode .' ส่วนลดเกิน 100% ';
              }

              if($aDisc > $price)
              {
                $sc = FALSE;
                $message = $pdCode.' ส่วนลดเกินราคาขาย ';
              }

              //--- แปลงเป็นส่วนลด (Label)
              $discLabel = $pDisc > 0 ? $pDisc.' %' : ($aDisc > 0 ? $aDisc : 0);

              //---- ดึงรายการที่มีอยู่แล้ว
              $qs = $cs->getExistsDetail($cs->id, $id_pd, $price, $discLabel);

              //--- ถ้ามีรายการสินค้าอยู่ในเอกสารแล้ว
              if(dbNumRows($qs) == 1)
              {
                //--- update exists detail
                $rs = dbFetchObject($qs);
                $max_qty = $qty + $cs->getSumProductQty($cs->id, $id_pd);
                $qty = $rs->qty + $qty;

                //--- สินค้าคงเหลือในโซนพอตัดหรือไม่
                $isEnough = $st->isEnough($cs->id_zone, $id_pd, $max_qty);

                //--- ถ้าสินค้าคงเหลือในโซนพอตัด หรือ คลังนี้สามารถติดลบได้
                if( $isEnough === TRUE OR $zone->allowUnderZero === TRUE)
                {
                  $discountAmount = $pDisc > 0 ? (($price * ($pDisc * 0.01)) * $qty) : ($aDisc * $qty);
                  $totalAmount = ($qty * $price) - $discountAmount;
                  $arr = array(
                    'qty'  => $qty,
                    'discount_amount' => $discountAmount,
                    'total_amount' => $totalAmount
                  );

                  //--- ถ้า update ข้อมูลไม่สำเร็จ
                  if($cs->updateDetail($rs->id, $arr) !== TRUE)
                  {
                    $sc = FALSE;
                    $message = 'ปรับปรุงข้อมูลตัดยอด '.$pdCode.' ไม่สำเร็จ';
                  }
                }
                else
                {
                  $sc = FALSE;
                  $message = $pdCode.' มีสต็อกในโซนไม่เพียงพอ';
                } //--- end if isEnough of update

              }
              else //--- ถ้ายังไม่มีรายการ
              {
                //----- ถ้ายังไม่มีข้อมูล
                //---- ถ้าสินค้าในโซนไม่พอตัด
                if($st->isEnough($cs->id_zone, $id_pd, $qty) === FALSE && $zone->allowUnderZero === FALSE)
                {
                  $sc = FALSE;
                  $message = $pdCode.' มีสต็อกในโซนไม่เพียงพอ';
                }
                else
                {
                  $pd->getData($id_pd);

                  //---- Insert if not exists
                  $discountAmount = $pDisc > 0 ? (($price * ($pDisc * 0.01)) * $qty) : ($aDisc * $qty);
                  $totalAmount = ($qty * $price) - $discountAmount;
                  $arr = array(
                    'id_consign' => $cs->id,
                    'id_style' => $pd->id_style,
                    'id_product' => $pd->id,
                    'product_code' => $pd->code,
                    'product_name' => $pd->name,
                    'cost' => $pd->cost,
                    'price' => $price,
                    'qty' => $qty,
                    'discount' => $discLabel,
                    'discount_amount' => $discountAmount,
                    'total_amount' => $totalAmount,
                    'input_type' => 2 //--- 0 = hand, 1 = load diff, 2 = excel
                  );

                  //---- Insert detail
                  if($cs->addDetail($arr) === FALSE)
                  {
                    $sc = FALSE;
                    $message = 'เพิ่มรายการไม่สำเร็จ';
                  }

                } //--- end if isEnough

              } //--- end if dbNumRows

            } //--- end if id_pd === FALSE

          } //--- end if diff > 0


        } //--- end if( i > 1)

        $i++;
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

    } //--- end if upload->process

    $upload->clean();

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
  }

  echo $sc === TRUE ? 'success' : $message;

 ?>
