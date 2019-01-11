
<?php
	function exportConsignSO($reference)
	{
		$cs = new return_order($reference);
		$sc 	= FALSE;
		$id_employee = getCookie('user_id');
		$cust 	= new customer($cs->id_customer);
		$sale 	= new sale($cs->id_sale);
		$pd			= new product();
		$wh			= new warehouse();
		$emp		= new employee();
		$order  = new order();

		$ERRMSG		= "";
		$REFTYPE	= 'SO';
		$QCCORP		= getConfig('COMPANY_CODE'); 	//-- รหัสบริษัท
		$QCBRANCH	= getConfig('BRANCH_CODE'); 		//-- รหัสสาขา
		$QCSECT		= $emp->getDivisionCode($id_employee); 	//-- รหัสแผนก
		$QCJOB		= ""; 	//--  รหัสโครงการ (ถ้ามี)
		$STAT			= "";	//-- 	สถานะของเอกสาร ปกติว่างไว้,  C = CANCEL
		$QCBOOK 	= $order->getBookCode(1, 1);		//--	รหัสเล่มเอกสาร
		$CODE			= "";	//--	เลขที่เอกสาร ถ้าว่างไว้ระบบจะ gen ให้เอง
		$REFNO	 	= $reference;		//--	เลขที่อ้างอิง (เลขที่เอกสารใน Smart Invent)
		$DATE			= thaiDate($cs->date_add, '/');	//--	วันที่ของเอกสาร
		$RECEDATE	= thaiDate($cs->date_add, '/');;	//--	วันที่ยื่นภาษี
		$DUEDATE	= date('d/m/Y', strtotime('+'.$cs->credit_term.' day '.$cs->date_add));	//--	วันที่ครบกำหนด
		$QCCOOR		= $cust->getCode($cs->id_customer);	//--	รหัสลูกค้า
		$QCEMPL		= "";
		$CREDTERM	= $cs->credit_term;	//--	เครดิตเทอม (จำนวนวัน)
		$HASRET		= "Y"; 	//--	เป็นภาษีขอคืนได้ [ Y = ขอคืนได้,  N = ขอคืนไม่ได้ ]
		$DETAIL		= "";		//--	รายละเอียดที่พิมพ์ในรายงานภาษีซื้อ - ขาย
		$VATISOUT	= 'N';	//--	การคิดภาษี  Y = ภาษีแยกนอก,  N = ภาษีรวมใน
		$VATTYPE	= getConfig('ORDER_VAT_TYPE');		//--	ประเภทภาษี  1 = 7%, 2 = 1.5%, 3 = 0%, 4 = ไม่มี VAT,  5 = 10%
		$HDISCSTR	= $cs->bDiscLabel;	//--	ข้อความ ส่วนลดท้ายบิลเป็น % หรือยอดเงิน เช่น 10%+5% , 200+3%
		$DISCAMT1	= $cs->bill_discount;		//--	มูลค่าส่วนลดท้ายบิล เป็นจำนวนเงินบาท
		$AMT			= toFixed( $cs->amount_ex, 2);	//--	มูลค่าสินค้าไม่รวม VAT (เป็นยอดรวมทั้งบิล) FALSE = ไม่รวม VAT decmal 15,2
		$VATAMT		= $cs->vat_amount; 	//--	มูลค่าภาษี (เป็นยอดรวมทั้งบิล) TRUE = VAT นอก FALSE = VAT ใน decmal 14,2
		$QCCURRENCY	= "";		//--	รหัสหน่วยเงิน
		$XRATE		= 1;			//--	อัตราแลกเปลี่ยนหน่วยเงิน
		$DISCAMTK	= $DISCAMT1;			//--	มูลค่าส่วนลดท้ายบิล เป็นจำนวนเงิน ตามหน่วยเงินที่คีย์
		$AMTKE		= $AMT;			//--	มูลค่าสินค้าไม่รวม VAT (เป็นยอดรวมทั้งบิล) ตามหน่วยเงินที่คีย์
		$VATAMTKE	= $VATAMT;			//-- 	มูลค่าภาษี (เป็นยอดรวมทั้งบิล) ตามหน่วยเงินที่คีย์
		$LOT			= "";
		$PRICEKE	= "";			//--	ราคาสินค้า ตามหน่วยเงินที่คีย์  กรณีมี Option Currency
		$DISCAMTIK	= "";		//--	ราคาสินค้า ตามหน่วยเงินที่คีย์
		$QCSECTI	= $QCSECT;			//--	รหัสแผนก ที่รายการสินค้า (กรณีมี Feature แผนกที่ Item)
		$QCJOBI		= "";			//--	รหัส JOB  ที่รายการสินค้า (กรณีมี Feature Job ที่ Item)
		$REMARKH1	= "";
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

		$path			= getConfig('EXPORT_SO_PATH');
		$file_name = $path.$reference.'-'.date('YmdHis').".xls";
		$workbook	= new Spreadsheet_Excel_Writer($file_name);
		$excel =& $workbook->addWorksheet('SO');

		//------- SET Header Row
		$excel->write(0, 0,'ERRMSG');
		$excel->write(0, 1,'REFTYPE');
		$excel->write(0, 2,'QCCORP');
		$excel->write(0, 3,'QCBRANCH');
		$excel->write(0, 4,'QCSECT');
		$excel->write(0, 5,'QCJOB');
		$excel->write(0, 6,'STAT');
		$excel->write(0, 7,'QCBOOK');
		$excel->write(0, 8,'CODE');
		$excel->write(0, 9,'REFNO');
		$excel->write(0, 10,'DATE');
		$excel->write(0, 11,'RECEDATE');
		$excel->write(0, 12,'DUEDATE');
		$excel->write(0, 13,'QCCOOR');
		$excel->write(0, 14,'QCEMPL');
		$excel->write(0, 15,'CREDTERM');
		$excel->write(0, 16,'VATISOUT');
		$excel->write(0, 17,'VATTYPE');
		$excel->write(0, 18,'HDISCSTR');
		$excel->write(0, 19,'DISCAMT1');
		$excel->write(0, 20,'AMT');
		$excel->write(0, 21,'VATAMT');
		$excel->write(0, 22,'QCCURRENCY');
		$excel->write(0, 23,'XRATE');
		$excel->write(0, 24,'DISCAMTK');
		$excel->write(0, 25,'AMTKE');
		$excel->write(0, 26,'VATAMTKE');
		$excel->write(0, 27,'QCPROD');
		$excel->write(0, 28,'QCWHOUSE');
		$excel->write(0, 29,'LOT');
		$excel->write(0, 30,'QTY');
		$excel->write(0, 31,'QCUM');
		$excel->write(0, 32,'UMQTY');
		$excel->write(0, 33,'PRICE');
		$excel->write(0, 34,'DISCSTR');
		$excel->write(0, 35,'PRICEKE');
		$excel->write(0, 36,'DISCAMTIK');
		$excel->write(0, 37,'QCSECTI');
		$excel->write(0, 38,'QCJOBI');
		$excel->write(0, 39,'REMARKH1');
		$excel->write(0, 40,'REMARKH2');
		$excel->write(0, 41,'REMARKH3');
		$excel->write(0, 42,'REMARKH4');
		$excel->write(0, 43,'REMARKH5');
		$excel->write(0, 44,'REMARKH6');
		$excel->write(0, 45,'REMARKH7');
		$excel->write(0, 46,'REMARKH8');
		$excel->write(0, 47,'REMARKH9');
		$excel->write(0, 48,'REMARKH10');
		$excel->write(0, 49,'REMARKI1');
		$excel->write(0, 50,'REMARKI2');
		$excel->write(0, 51,'REMARKI3');
		$excel->write(0, 52,'REMARKI4');
		$excel->write(0, 53,'REMARKI5');
		$excel->write(0, 54,'REMARKI6');
		$excel->write(0, 55,'REMARKI7');
		$excel->write(0, 56,'REMARKI8');
		$excel->write(0, 57,'REMARKI9');
		$excel->write(0, 58,'REMARKI10');

		//------ End Header Row

		$date_format =& $workbook->addFormat();
		$date_format->setNumFormat('DD-MMM-YYYY');

		//---- Start
		$row = 1;  //--- start on row 2
		$qs = $cs->getDetails($reference);

		if( dbNumRows($qs) > 0 )
		{
			while( $rs = dbFetchObject($qs))
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
				$excel->writeString($row, 31, tis($rs->unit_code));
				$excel->write($row, 32, $rs->umqty);
				$excel->write($row, 33, $rs->price );
				$excel->writeString($row, 34, tis($rs->discount));
				$excel->write($row, 35, $rs->price);
				$excel->writeString($row, 36, tis($rs->discount_amount));
				$excel->writeString($row, 37, tis($QCSECTI));
				$excel->writeString($row, 38, tis($QCJOBI));
				$excel->writeString($row, 39, $REMARKH1);
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
			$order->exported($order->id);
		}
		catch (Exception $e)
		{
			$sc =  "ERROR : ".$e->getMessage();
		}

		return $sc;

	}


?>
