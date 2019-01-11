<?php
	function exportConsignSold($id_consign)
	{
		$sc 		= FALSE;
		$cs 		= new consign($id_consign);
		$order 	= new order();
		$cust 	= new customer($cs->id_customer);
		$pd			= new product();
		$wh			= new warehouse();
		$emp		= new employee();


		$path			= getConfig('EXPORT_SO_PATH');
		$ERRMSG		= "";
		$REFTYPE	= 'SO';

		//-- รหัสบริษัท
		$QCCORP		= getConfig('COMPANY_CODE');

		//-- รหัสสาขา
		$QCBRANCH	= getConfig('BRANCH_CODE');

		//-- รหัสแผนก
		$QCSECT		= tis($emp->getDivisionCode($cs->id_employee));

		//--  รหัสโครงการ (ถ้ามี)
		$QCJOB		= "";

		//-- 	สถานะของเอกสาร ปกติว่างไว้,  C = CANCEL
		$STAT			= "";

		//--	รหัสเล่มเอกสาร
		$QCBOOK 	= $order->getBookCode(8);

		//--	เลขที่เอกสาร ถ้าว่างไว้ระบบจะ gen ให้เอง
		$CODE			= "";

		//--	เลขที่อ้างอิง (เลขที่เอกสารใน Smart Invent)
		$REFNO	 	= $cs->reference;

		//--	วันที่ของเอกสาร
		$DATE			= thaiDate($cs->date_add, '/');

		//--	วันที่ยื่นภาษี
		$RECEDATE	= thaiDate($cs->date_add, '/');

		//--	วันที่ครบกำหนด
		$DUEDATE	= date('d/m/Y', strtotime('+'.$cust->term.' day '.$cs->date_add));

		//--	รหัสลูกค้า
		$QCCOOR		= addslashes($cust->getCode($cs->id_customer));

		//---	รหัสพนักงานขาย
		$QCEMPL	= "";

		//--	เครดิตเทอม (จำนวนวัน)
		$CREDTERM	= $cust->getTerm($cs->id_customer);

		//--	เป็นภาษีขอคืนได้ [ Y = ขอคืนได้,  N = ขอคืนไม่ได้ ]
		$HASRET	= "Y";

		//--	รายละเอียดที่พิมพ์ในรายงานภาษีซื้อ - ขาย
		$DETAIL	= "";

		//--	การคิดภาษี  Y = ภาษีแยกนอก,  N = ภาษีรวมใน
		$VATISOUT	= 'N';

		//--	ประเภทภาษี  1 = 7%, 2 = 1.5%, 3 = 0%, 4 = ไม่มี VAT,  5 = 10%
		$VATTYPE	= getConfig('ORDER_VAT_TYPE');

		//--	ข้อความ ส่วนลดท้ายบิลเป็น % หรือยอดเงิน เช่น 10%+5% , 200+3%
		$HDISCSTR	= "";

		//--	มูลค่าส่วนลดท้ายบิล เป็นจำนวนเงินบาท
		$DISCAMT1	= 0.00;

		//--	มูลค่าสินค้าไม่รวม VAT (เป็นยอดรวมทั้งบิล) FALSE = ไม่รวม VAT decmal 15,2
		$AMT = toFixed( $order->getTotalConsignSoldAmount($cs->reference, FALSE), 2);

		//--	มูลค่าภาษี (เป็นยอดรวมทั้งบิล) TRUE = VAT นอก FALSE = VAT ใน decmal 14,2
		$VATAMT	= getVatAmount( $order->getTotalConsignSoldAmount($cs->reference,TRUE ), getVatRate($VATTYPE), FALSE);

		//--	รหัสหน่วยเงิน
		$QCCURRENCY	= "";

		//--	อัตราแลกเปลี่ยนหน่วยเงิน
		$XRATE		= 1;

		//--	มูลค่าส่วนลดท้ายบิล เป็นจำนวนเงิน ตามหน่วยเงินที่คีย์
		$DISCAMTK	= "";

		//--	มูลค่าสินค้าไม่รวม VAT (เป็นยอดรวมทั้งบิล) ตามหน่วยเงินที่คีย์
		$AMTKE		= $AMT;

		//-- 	มูลค่าภาษี (เป็นยอดรวมทั้งบิล) ตามหน่วยเงินที่คีย์
		$VATAMTKE	= $VATAMT;

		//--- LOT
		$LOT			= "";

		//--	ราคาสินค้า ตามหน่วยเงินที่คีย์  กรณีมี Option Currency
		$PRICEKE	= "";

		//--	ราคาสินค้า ตามหน่วยเงินที่คีย์
		$DISCAMTIK	= "";

		//--	รหัสแผนก ที่รายการสินค้า (กรณีมี Feature แผนกที่ Item)
		$QCSECTI	= "";

		//--	รหัส JOB  ที่รายการสินค้า (กรณีมี Feature Job ที่ Item)
		$QCJOBI		= "";

		//---	หมายเหตุที่เอกสาร
		$REMARKH1	= $cs->remark;
		$REMARKH2	= "";
		$REMARKH3	= "";
		$REMARKH4	= "";
		$REMARKH5	= "";
		$REMARKH6	= "";
		$REMARKH7	= "";
		$REMARKH8	= "";
		$REMARKH9	= "";
		$REMARKH10	= "";
		$REMARKI1	= "";
		$REMARKI2	= "";
		$REMARKI3	= "";
		$REMARKI4	= "";
		$REMARKI5	= "";
		$REMARKI6	= "";
		$REMARKI7	= "";
		$REMARKI8	= "";
		$REMARKI9	= "";
		$REMARKI10	= "";


		$file_name = $path.$cs->reference.'-'.date('YmdHis').".xls";
		$workbook	= new Spreadsheet_Excel_Writer($file_name);
		$excel =& $workbook->addWorksheet('SO');

		//------- SET Header Row
		$row = 0;
		$excel->writeString($row, 0,'ERRMSG');
		$excel->writeString($row, 1,'REFTYPE');
		$excel->writeString($row, 2,'QCCORP');
		$excel->writeString($row, 3,'QCBRANCH');
		$excel->writeString($row, 4,'QCSECT');
		$excel->writeString($row, 5,'QCJOB');
		$excel->writeString($row, 6,'STAT');
		$excel->writeString($row, 7,'QCBOOK');
		$excel->writeString($row, 8,'CODE');
		$excel->writeString($row, 9,'REFNO');
		$excel->writeString($row, 10,'DATE');
		$excel->writeString($row, 11,'RECEDATE');
		$excel->writeString($row, 12,'DUEDATE');
		$excel->writeString($row, 13,'QCCOOR');
		$excel->writeString($row, 14,'QCEMPL');
		$excel->writeString($row, 15,'CREDTERM');
		$excel->writeString($row, 16,'VATISOUT');
		$excel->writeString($row, 17,'VATTYPE');
		$excel->writeString($row, 18,'HDISCSTR');
		$excel->writeString($row, 19,'DISCAMT1');
		$excel->writeString($row, 20,'AMT');
		$excel->writeString($row, 21,'VATAMT');
		$excel->writeString($row, 22,'QCCURRENCY');
		$excel->writeString($row, 23,'XRATE');
		$excel->writeString($row, 24,'DISCAMTK');
		$excel->writeString($row, 25,'AMTKE');
		$excel->writeString($row, 26,'VATAMTKE');
		$excel->writeString($row, 27,'QCPROD');
		$excel->writeString($row, 28,'QCWHOUSE');
		$excel->writeString($row, 29,'LOT');
		$excel->writeString($row, 30,'QTY');
		$excel->writeString($row, 31,'QCUM');
		$excel->writeString($row, 32,'UMQTY');
		$excel->writeString($row, 33,'PRICE');
		$excel->writeString($row, 34,'DISCSTR');
		$excel->writeString($row, 35,'PRICEKE');
		$excel->writeString($row, 36,'DISCAMTIK');
		$excel->writeString($row, 37,'QCSECTI');
		$excel->writeString($row, 38,'QCJOBI');
		$excel->writeString($row, 39,'REMARKH1');
		$excel->writeString($row, 40,'REMARKH2');
		$excel->writeString($row, 41,'REMARKH3');
		$excel->writeString($row, 42,'REMARKH4');
		$excel->writeString($row, 43,'REMARKH5');
		$excel->writeString($row, 44,'REMARKH6');
		$excel->writeString($row, 45,'REMARKH7');
		$excel->writeString($row, 46,'REMARKH8');
		$excel->writeString($row, 47,'REMARKH9');
		$excel->writeString($row, 48,'REMARKH10');
		$excel->writeString($row, 49,'REMARKI1');
		$excel->writeString($row, 50,'REMARKI2');
		$excel->writeString($row, 51,'REMARKI3');
		$excel->writeString($row, 52,'REMARKI4');
		$excel->writeString($row, 53,'REMARKI5');
		$excel->writeString($row, 54,'REMARKI6');
		$excel->writeString($row, 55,'REMARKI7');
		$excel->writeString($row, 56,'REMARKI8');
		$excel->writeString($row, 57,'REMARKI9');
		$excel->writeString($row, 58,'REMARKI10');

		//------ End Header Row

		$date_format =& $workbook->addFormat();
		$date_format->setNumFormat('DD-MMM-YYYY');

		//---- Start
		$row = 1;  //--- start on row 2
		$qs = $order->getConsignSoldDetails($cs->reference);
		if( dbNumRows($qs) > 0 )
		{
			while( $rs = dbFetchObject($qs) )
			{
				$excel->writeString($row, 0, $ERRMSG);
				$excel->writeString($row, 1, tis($REFTYPE));
				$excel->writeString($row, 2, tis($QCCORP));
				$excel->writeString($row, 3, tis($QCBRANCH));
				$excel->writeString($row, 4, tis($QCSECT));
				$excel->writeString($row, 5, tis($QCJOB));
				$excel->writeString($row, 6, $STAT);
				$excel->writeString($row, 7, tis($QCBOOK));
				$excel->writeString($row, 8, $CODE);
				$excel->writeString($row, 9, tis($REFNO));
				$excel->write($row, 10, $DATE, $date_format);
				$excel->write($row, 11, $RECEDATE, $date_format);
				$excel->write($row, 12, $DUEDATE, $date_format);
				$excel->writeString($row, 13, tis($QCCOOR));
				$excel->writeString($row, 14, tis($QCEMPL));
				$excel->write($row, 15, $CREDTERM);
				$excel->writeString($row, 16, $VATISOUT);
				$excel->writeString($row, 17, $VATTYPE);
				$excel->writeString($row, 18, tis($HDISCSTR));
				$excel->write($row, 19, tis($DISCAMT1));
				$excel->write($row, 20, $AMT);
				$excel->write($row, 21, $VATAMT);
				$excel->write($row, 22, $QCCURRENCY);
				$excel->write($row, 23, 1);
				$excel->write($row, 24, tis($DISCAMTK));
				$excel->write($row, 25, $AMTKE);
				$excel->write($row, 26, $VATAMTKE);
				$excel->writeString($row, 27, tis($rs->product_code));
				$excel->writeString($row, 28, tis($wh->getCode($rs->id_warehouse)));
				$excel->writeString($row, 29, $LOT);
				$excel->write($row, 30, $rs->qty);
				$excel->writeString($row, 31, tis($pd->getUnitCode($rs->id_product)));
				$excel->write($row, 32, 1);
				$excel->write($row, 33, $rs->price_inc );
				$excel->writeString($row, 34, tis($rs->discount_label));
				$excel->write($row, 35, $rs->price_inc);
				$excel->writeString($row, 36, tis($rs->discount_amount));
				$excel->writeString($row, 37, tis($QCSECTI));
				$excel->writeString($row, 38, tis($QCJOBI));
				$excel->writeString($row, 39, tis($REMARKH1));
				$excel->writeString($row, 40, $REMARKH2);
				$excel->writeString($row, 41, $REMARKH3);
				$excel->writeString($row, 42, $REMARKH4);
				$excel->writeString($row, 43, $REMARKH5);
				$excel->writeString($row, 44, $REMARKH6);
				$excel->writeString($row, 45, $REMARKH7);
				$excel->writeString($row, 46, $REMARKH8);
				$excel->writeString($row, 47, $REMARKH9);
				$excel->writeString($row, 48, $REMARKH10);
				$excel->writeString($row, 49, $REMARKI1);
				$excel->writeString($row, 50, $REMARKI2);
				$excel->writeString($row, 51, $REMARKI3);
				$excel->writeString($row, 52, $REMARKI4);
				$excel->writeString($row, 53, $REMARKI5);
				$excel->writeString($row, 54, $REMARKI6);
				$excel->writeString($row, 55, $REMARKI7);
				$excel->writeString($row, 56, $REMARKI8);
				$excel->writeString($row, 57, $REMARKI9);
				$excel->writeString($row, 58, $REMARKI10);

				$row++;

			}
		}

		try
		{
			$workbook->close();
			$sc = TRUE;
			$cs->setExport($cs->id,1);
		}
		catch (Exception $e)
		{
			$sc =  "ERROR : ".$e->getMessage();
		}

		return $sc;
	}


?>
