<?php

	$sc = TRUE;
	$import = 0;
	$update = 0;
	$error  = 0;

	//---	Path ของไฟล์ที่จะ import
	$path		= getConfig('IMPORT_SM_PATH');

	//---	Path ของไฟล์ที่จะย้ายไปเก็บเมื่อ import เสร็จแล้ว
	$move	= getConfig('MOVE_SM_PATH');

	//---	เปิด path เพื่อดูไฟล์ทั้งหมดใน directory
	$dr	= opendir($path);

	//---	ถ้าใน directory มีไฟล์อยู่
	if( $dr !== FALSE )
	{
		while( $file = readdir($dr) )
		{
			if( $file == '.' OR $file == '..' )
			{
				continue;
			}

			$fileName	= $path . $file;
			$moveName	= $move . $file;
			$es 			= new PHPExcel();
			$reader		= new PHPExcel_Reader_Excel5();

			$excel		= $reader->load($fileName);
			$collection	= $excel->getActiveSheet()->toArray(NULL, TRUE, TRUE, TRUE);

			$cs   = new return_order();
			$cus = new customer();
			$sale	= new sale();
			$wh	= new warehouse();
			$pd	= new product();
			$i 	= 1;
			foreach ( $collection as $rs )
			{
				if( $i != 1 )
				{
					$bookcode 	= $rs['G'];

					//---	รหัสเอกสาร
					$reference	= $rs['I'];

					//---	คืนสินค้าหรือไม่
					$isReturn   = addslashes($rs['AO']) == 'Y' ? 1 : 0;

					//---	อ้างอิงใบส่งสินค้า/ใบกำกับภาษี
					$invoice    = $rs['AQ'].'-'.$rs['AR'];

					//---	รหัสสินค้า
					$product		= $rs['AC'];

					//---	ไอดีสินค้า
					$id_pd		= $pd->getId($product);

					//---	ไอดีรุ่นสินค้า
					$id_style		= $pd->getStyleId($id_pd);

					//---	ยกเลิกหรือไม่
					$isCancle	= $rs['F'] == "C" ? 1 : 0;

					//---	valid ถ้าไม่มีการคืนสินค้าให้ valid = 1
					$valid = $isReturn == 1 ? 0 : 1;

					$id = $cs->getId($bookcode, $reference, $product, $invoice);

					if( $id === FALSE )
					{
						//------ คำนวณส่วนลดใหม่
						$disc 	= explode('%', $rs['AJ']);
						$disc[0]	= trim( $disc[0] ); //--- ตัดช่องว่างออก
						$discount = count( $disc ) == 1 ? $disc[0] : $rs['AI'] * ($disc[0] * 0.01 ); //--- ส่วนลดต่อตั
						$discount_amount = $rs['AF'] * $discount; //---- ส่วนลดรวม
						$arr = array(
											'bookcode'	    => $bookcode,
											'code'			    => $rs['H'],
											'reference'	    => $reference,
											'invoice'		    => $invoice,
											'id_customer'   => $cus->getId($rs['M']),
											'id_sale'		    => $sale->getId($rs['N']),
											'id_warehouse'	=> $wh->getId($rs['AD']),
											'id_style'	    => $id_style,
											'id_product'	  => $id_pd,
											'product_code'	=> $product,
											'price'			    => addslashes($rs['AI']),
											'qty'		        => addslashes($rs['AF']),
											'unit_code'	    => addslashes($rs['AG']),
											'umqty'	        => addslashes($rs['AH']),
											'discount'			=> addslashes($rs['AJ']),
											'discount_amount' => $discount_amount,
											'bill_discount'	=> addslashes($rs['U']),
											'amount_ex'	    => addslashes($rs['V']),
											'vat_amount'	  => addslashes($rs['W']),
											'date_add'			=> fmDate($rs['J']),
											'isCancle'			=> $isCancle,
											'valid'					=> $valid,
											'isReturn'			=> $isReturn,
											'bDiscLabel'		=> addslashes($rs['T']),
											'credit_term'		=> $rs['O']
										);

						$import++;
						if($cs->add($arr) === FALSE)
						{
							$sc = FALSE;
							$message = 'เพิ่มข้อมูลไม่สำเร็จ';
							$error++;
							writeErrorLogs('SM', $cs->error);
						}
					}

				}//--- end if first row
				$i++;
			}//------ end foreach
			rename($fileName, $moveName); //---- move each file to another folder
		}//----- end while
	}
	else
	{
		$sc = FALSE;
		$message = "Can not open folder please check connection";
	}

	writeImportLogs('ใบลดหนี้ขาย', $import, $update, $error, 'last');

	echo $sc === TRUE ? 'success' : $message;

?>
