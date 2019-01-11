<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";
include '../../library/class/PHPExcel.php';

function writeErrorLogs($name, $error)
{
  $content  = date('Y / m / d  H : i : s').'  |  '. $name;
  $content .= ' | '.$error;
  $content .= PHP_EOL;

  $path = getConfig('IMPORT_LOG_PATH');
  $fileName = $path.'ErrorLogs-'.date('Ymd').'.LOG';
  file_put_contents($fileName, $content, FILE_APPEND);
}

if( isset( $_GET['importStockZone'] ) )
{
	$sc = TRUE;
  $stockImported = 0;
  $stockImportSuccess = 0;
  $stockImportError = 0;
  $movementImported = 0;
  $movementImportSuccess = 0;
  $movementImportError = 0;
  $noZone = 0;
  $noProduct = 0;
  $import = 0;

	//$skr = array();

	$file = isset( $_FILES['uploadFile'] ) ? $_FILES['uploadFile'] : FALSE;
	$file_path 	= "../../upload/";
  $upload	= new upload($file);
  if($upload->uploaded)
  {
  	$upload->file_new_name_body = 'importItem-'.date('YmdHis');
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

      $reference = 'บันทึกยอดยกมา';
      $date_upd = date('Y-m-d H:i:s');

      $i = 1;
      foreach($collection as $rs)
      {
        if($i > 1)
        {
          $import++;
          $id_zone = $zone->getId($rs['A']);
          $id_wh = $id_zone === FALSE ? FALSE : $zone->getWarehouseId($id_zone);
          $id_pd = $pd->getId($rs['B']);
          $qty = $rs['C'];

          if( $id_zone === FALSE OR $id_wh === FALSE)
          {
            $noZone++;
            writeErrorLogs('Zone Error' ,$rs['A'].' : '.$rs['B'].' : '.$qty);
          }
          else if($id_pd === FALSE)
          {
            $noProduct++;
            writeErrorLogs('Product Error' ,$rs['A'].' : '. $rs['B'].' : '.$qty);
          }
          else
          {

            //--- import stock
            $stockImported++;
            if($st->updateStockZone($id_zone, $id_pd, $qty) !== TRUE)
            {
              $stockImportError++;
              writeErrorLogs('Import Stock Error', $rs['A'].' : '.$rs['B'] .' : '. $qty.' : '.$st->error);
            }
            else
            {
              $stockImportSuccess++;

              //--- import movement
              $movementImported++;
              if($mv->move_in($reference, $id_wh, $id_zone, $id_pd, $qty, $date_upd) !== TRUE)
              {
                $sc = FALSE;
                $movementImportError++;
                writeErrorLogs('Import Movement Error', $rs['A'].' : '.$rs['B'] .' : '. $mv->error);
              }
              else
              {
                $movementImportSuccess++;
              }

            }

          }

        }

        $i++;
      }

    }
  }

  $upload->clean();

  $res  = 'นำเข้าทั้งหมด ' .$import.' รายการ <br/>';
  $res .= 'ไม่พบโซน '.$noZone.' รายการ <br/>';
  $res .= 'ไม่พบสินคา '.$noProduct.' รายการ </br/>';
  $res .= '======================== <br/>';
  $res .= 'นำเข้าสต็อก '.$stockImported.' รายการ <br/>';
  $res .= 'สำเร็จ '.$stockImportSuccess.' รายการ <br/>';
  $res .= 'ผิดพลาด '.$stockImportError.' รายการ <br/>';
  $res .= '======================== <br/>';
  $res .= 'บันทึก movement '.$movementImported.' รายการ <br/>';
  $res .= 'สำเร็จ '.$movementImportSuccess.' รายการ <br/>';
  $res .= 'ไม่สำเร็จ '.$movementImportError.' รายการ <br/>';

  echo $sc === TRUE ? $res : $message;
}



if(isset($_GET['importOrderFromWeb']))
{
  $sc = TRUE;
  $import = 0;
  $message = '';

	$file = isset( $_FILES['uploadFile'] ) ? $_FILES['uploadFile'] : FALSE;
	$file_path 	= "../../upload/";
  $upload	= new upload($file);

  if($upload->uploaded)
  {
  	$upload->file_new_name_body = 'importOrder';
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

      $date_upd = date('Y-m-d H:i:s');

      $i = 1;
      $count = count($collection);
      if( $count <= 501 )
      {
        $order = new order();
        $product = new product();
        $customer = new customer();

        //--- รหัสเล่มเอกสาร [อ้างอิงจาก formula]
        //--- ถ้าเป็นฝากขายแบบโอนคลัง ยืมสินค้า เบิกแปรสภาพ เบิกสินค้า (ไม่เปิดใบกำกับ เปิดใบโอนคลังแทน) นอกนั้น เปิด SO
        $bookcode = $order->getBookCode(1, 1);

        //--- ดึงข้อมูลลูกค้า Omise ไว้ก่อน
        $omiseCustomer =  $customer->getDataByCode(getConfig('OMISE_CUSTOMER_CODE'));

        //--- ดึงข้อมูลลูกค้า COD ไว้ก่อน
        $codCustomer = $customer->getDataByCode(getConfig('COD_CUSTOMER_CODE'));

        //--- ดึง ID payment ทาง Omise
        $omise_payment_id = getConfig('OMISE_PAYMENT_ID');

        //--- COD Id payment ทาง COD
        $cod_payment_id = getConfig('COD_PAYMENT_ID');

        //--- ID สาขา
        $id_branch = getConfig('WEB_SITE_BRANCH_ID');

        //---	เป็นออเดอร์ออนไลน์หรือไม่
        $isOnline = 1;

        //---	เป็นเอกสารประเภทไหน
        $role 		=  1;

        //---	เป็นเอกสารที่ออก SO หรือไม่ (default = 1)
        $is_so 		=  1;

        //---	ช่องทางการขาย
        $id_channels = getConfig('WEB_SITE_CHANNELS_ID');

        //---	พนักงาน
        $id_employee = getCookie('user_id');

        //---	ผูํ้ทำรายการจะถูกบันทึกแยกไว้ที่ tbl_order_user
        $id_user = getCookie('user_id');

        //---	id_budget
        $id_budget = 0;

        //---	กรณีที่เป็นฝากขายอาจมี GP ด้วย
        $gp = 0;

        $remark = '';

        $prefix = getConfig('PREFIX_SHIPPING_NUMBER');

        foreach($collection as $rs)
        {
          //--- ถ้าพบ Error ให้ออกจากลูปทันที
          if($sc === FALSE)
          {
            break;
          }

          if($i == 1)
          {
            $headCol = array(
              'A' => 'Consignee Name',
              'B' => 'Address Line 1',
              'C' => 'Province',
              'D' => 'District',
              'E' => 'Sub District',
              'F' => 'postcode',
              'G' => 'email',
              'H' => 'tel',
              'I' => 'orderNumber',
              'J' => 'CreateDateTime',
              'K' => 'Payment Method',
              'L' => 'itemId',
              'M' => 'amount',
              'N' => 'price',
              'O' => 'shipping fee',
              'P' => 'service fee',
              'Q' => 'force update'
            );

            foreach($headCol as $col => $field)
            {
              if($rs[$col] !== $field)
              {
                $sc = FALSE;
                $message = 'Column '.$col.' Should be '.$field;
                break;
              }
            }

            if($sc === FALSE)
            {
              break;
            }
          }
          else
          {
            //---- order code from web site
            $ref_code = $rs['I'];

            //--- เลือกลูกค้าตามช่องทางการชำระเงิน
            $cusData = $rs['K'] == 'cashondelivery' ? $codCustomer : $omiseCustomer;

            //------ เช็คว่ามีออเดอร์นี้อยู่ในฐานข้อมูลแล้วหรือยัง
            //------ ถ้ามีแล้วจะได้ id_order กลับมา ถ้ายังจะได้ FALSE;
            $id_order = $order->getIdOrderByRefCode($ref_code);

            //-- state ของออเดอร์ จะมีการเปลี่ยนแปลงอีกที
            $state = 3;

            //---- ถ้ายังไม่มีออเดอร์ ให้เพิ่มใหม่ หรือ มีออเดอร์แล้ว แต่ต้องการ update
            //---- โดยการใส่ force update มาเป็น 1
            if($id_order === FALSE OR ($id_order !== FALSE && $rs['Q'] == 1))
            {
            	//---	ถ้าเป็นออเดอร์ขายหรือสปอนเซอร์ จะมี id_customer
            	$id_customer = $cusData->id;

            	//---	ถ้าเป็นออเดอร์ขาย จะมี id_sale
            	$id_sale = $cusData->id_sale;

            	//---	หากเป็นออนไลน์ ลูกค้าออนไลน์ชื่ออะไร
            	$customerName = addslashes(trim($rs['A']));

              //---	ช่องทางการชำระเงิน
              $id_payment = $rs['K'] == 'omise_credit_debit' ? $omise_payment_id : $cod_payment_id;
              
            	//---	วันที่เอกสาร
            	$date_add = dbDate($rs['J'], TRUE);

              //--- ค่าจัดส่ง
              $shipping_fee = $rs['O'] == '' ? 0.00 : $rs['O'];

              //--- ค่าบริการอื่นๆ
              $service_fee = $rs['P'] == '' ? 0.00 : $rs['P'];

            	//--- รันเลขที่เอกสารตามประเภทเอาสาร
            	$reference = $order->getNewReference($role, $date_add, $is_so);

              //---- กรณียังไม่มีออเดอร์
              if($id_order === FALSE)
              {
                //--- เตรียมข้อมูลสำหรับเพิ่มเอกสารใหม่
              	$arr = array(
              					'bookcode'		=> $bookcode,
              					'reference'		=> $reference,
              					'role'				=> $role,
              					'id_customer'	=> $id_customer,
              					'id_sale'			=> $id_sale,
              					'id_employee'	=> $id_employee,
              					'id_payment'	=> $id_payment,
              					'id_channels'	=> $id_channels,
                        'state'       => 3,
                        'isPaid'      => 1,
              					'isOnline'		=> $isOnline,
                        'status'      => 1,
              					'date_add'		=> $date_add,
              					'id_branch'		=> $id_branch,
              					'remark'			=> $remark,
              					'online_code'	=> $customerName,
                        'shipping_code' => $prefix.$ref_code,
                        'shipping_fee'  => $shipping_fee,
                        'service_fee'  => $service_fee,
              					'is_so'				=> $is_so,
              					'id_budget'		=> $id_budget,
              					'gp'					=> $gp,
              					'ref_code' 		=> $ref_code
              					);


                if($order->add($arr) === TRUE)
                {
                  //--- ดึง id_order
                  $id_order = $order->get_id($reference);

              		//---	เพิ่มผู้ทำรายการแยกตางหาก
              		$order->insertUser($id_order, $id_user);

                  //--- เช็คที่อยู่ของลูกค้าว่ามีแล้วหรือยัง
                  $address = new online_address();

                  if($address->isExists($customerName, $rs['B']) === FALSE)
                  {
                    $arr = array(
                					"customer_code"	=> $customerName,
                					"first_name"	=> $customerName,
                					"last_name"	=> '',
                					"address1"	=> $rs['B'],
                					"address2"	=> $rs['E'],
                          "district"  => $rs['D'],
                					"province"	=> $rs['C'],
                					"postcode"	=> $rs['F'],
                					"phone"		=> $rs['H'],
                					"email"			=> $rs['G'],
                					"alias"			=> 'home'
                				);

                    $address->add($arr);
                  }

                  $import++;
                }
                else
                {
                  $sc = FALSE;
                  $message = $ref_code.': เพิ่มออเดอร์ไม่สำเร็จ';
                }
              }
              else
              {
                $state = $order->getState($id_order);
                if($state <= 3)
                {
                  //--- เตรียมข้อมูลสำหรับเพิ่มเอกสารใหม่
                	$arr = array(
                					'id_customer'	=> $id_customer,
                					'id_sale'			=> $id_sale,
                					'id_employee'	=> $id_employee,
                					'id_payment'	=> $id_payment,
                					'id_channels'	=> $id_channels,
                					'date_add'		=> $date_add,
                					'online_code'	=> $customerName,
                          'shipping_code' => $prefix.$ref_code,
                          'shipping_fee'  => $shipping_fee,
                          'service_fee'  => $service_fee
                					);
                  $order->update($id_order, $arr);
                }

                $import++;
              }

            }


            //---- เตรียมข้อมูลสำหรับเพิมรายละเอียดออเดอร์
            $pd = $product->getDataByCode($rs['L']);

            if($pd === FALSE)
            {
              $sc = FALSE;
              $message = 'ไม่พบข้อมูลสินค้าในระบบ : '.$rs['L'];
              break;
            }

            //---- เช็คข้อมูล ว่ามีรายละเอียดนี้อยู่ในออเดอร์แล้วหรือยัง
            //---- ถ้ามีข้อมูลอยู่แล้ว (TRUE)ให้ข้ามการนำเข้ารายการนี้ไป
            if($order->isExistsDetail($id_order, $pd->id) === FALSE)
            {
              //--- ถ้ายังไม่มีรายการอยู่ เพิ่มใหม่
              $arr = array(
                      "id_order"	=> $id_order,
                      "id_style"		=> $pd->id_style,
                      "id_product"	=> $pd->id,
                      "product_code"	=> $pd->code,
                      "product_name"	=> $pd->name,
                      "cost"  => $pd->cost,
                      "price"	=> ($rs['N']/$rs['M']),
                      "qty"		=> $rs['M'],
                      "discount"	=> 0,
                      "discount_amount" => 0,
                      "total_amount"	=> $rs['N'],
                      "id_rule"	=> 0,
                      "isCount" => $pd->count_stock
                    );

              if($order->addDetail($arr) === FALSE)
              {
                $sc = FALSE;
                $message = 'เพิ่มรายละเอียดรายการไม่สำเร็จ : '.$ref_code;
                break;
              }
            }
            else
            {
              //----  ถ้ามี force update และ สถานะออเดอร์ไม่เกิน 3 (รอจัดสินค้า)
              if($rs['Q'] == 1 && $state <= 3)
              {
                $od = $order->getDetail($id_order, $pd->id);

                $arr = array(
                        "id_order"	=> $id_order,
                        "id_style"		=> $pd->id_style,
                        "id_product"	=> $pd->id,
                        "product_code"	=> $pd->code,
                        "product_name"	=> $pd->name,
                        "cost"  => $pd->cost,
                        "price"	=> ($rs['N']/$rs['M']),
                        "qty"		=> $rs['M'],
                        "discount"	=> 0,
                        "discount_amount" => 0,
                        "total_amount"	=> $rs['N'],
                        "id_rule"	=> 0,
                        "isCount" => $pd->count_stock
                      );

                if($order->updateDetail($od->id, $arr) === FALSE)
                {
                  $sc = FALSE;
                  $message = 'เพิ่มรายละเอียดรายการไม่สำเร็จ : '.$ref_code;
                  break;
                }

              }
            }

          }

          $i++;
        } //--- end foreach
      }
      else
      {
        $sc = FALSE;
        $message = 'ไฟล์มีจำนวนรายการเกิน 500 บรรทัด';
      }

    }
  }

  $upload->clean();

  echo $sc === TRUE ? 'success' : $message;
}



?>
