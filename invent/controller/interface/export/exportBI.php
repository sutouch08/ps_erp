<?php
	function exportBI($id_receive_product)
	{
		$sc 	= FALSE;
		$cs 	= new receive_product($id_receive_product);
		$sp	= new supplier();
		$po	= new po($cs->po);
		$pd	= new product();
		$wh	= new warehouse();
		$emp	= new employee();


		$path	= getConfig('EXPORT_BI_PATH');
		$ERRMSG		= "";
		$REFTYPE	= 'BI';
		$QCCORP		= getConfig('COMPANY_CODE'); 	//-- รหัสบริษัท
		$QCBRANCH	= getConfig('BRANCH_CODE'); 		//-- รหัสสาขา
		$QCSECT		= tis($emp->getDivisionCode($cs->id_employee)); 	//-- รหัสแผนก
		$QCJOB		= ""; 	//--  รหัสโครงการ (ถ้ามี)
		$STAT			= "";	//-- 	สถานะของเอกสาร ปกติว่างไว้,  C = CANCEL
		$QCBOOK 	= tis($cs->bookcode);		//--	รหัสเล่มเอกสาร
		$CODE			= $cs->reference;	//--	เลขที่เอกสาร ถ้าว่างไว้ระบบจะ gen ให้เอง
		$REFNO	 	= $cs->reference;		//--	เลขที่อ้างอิง (เลขที่เอกสารใน Smart Invent)
		$DATE			= thaiDate($cs->date_add, '/');	//--	วันที่ของเอกสาร
		$RECEDATE	= thaiDate($cs->date_add, '/');;	//--	วันที่ยื่นภาษี
		$credit 	= '+ '.$po->credit_term.' day '.$cs->date_add;
		$DUEDATE	= date('d/m/Y', strtotime($credit)); //--	วันที่ครบกำหนด
		$QCCOOR		= tis($sp->getCode($cs->id_supplier));	//--	รหัสผู้จำหน่าย
		$CREDTERM	= $po->credit_term;	//--	เครดิตเทอม (จำนวนวัน)
		$HASRET		= "Y"; 	//--	เป็นภาษีขอคืนได้ [ Y = ขอคืนได้,  N = ขอคืนไม่ได้ ]
		$DETAIL		= "";	//--	รายละเอียดที่พิมพ์ในรายงานภาษีซื้อ - ขาย
		$VATISOUT	= $po->vat_is_out;	//--	การคิดภาษี  Y = ภาษีแยกนอก,  N = ภาษีรวมใน
		$VATTYPE	= $po->vat_type;		//--	ประเภทภาษี  1 = 7%, 2 = 1.5%, 3 = 0%, 4 = ไม่มี VAT,  5 = 10%
		$HDISCSTR	= "";	//--	ข้อความ ส่วนลดท้ายบิลเป็น % หรือยอดเงิน เช่น 10%+5% , 200+3%
		$DISCAMT1	= tis($po->bill_discount);		//--	มูลค่าส่วนลดท้ายบิล เป็นจำนวนเงินบาท
		$AMT			= $VATISOUT == 'Y' ? round( $cs->getTotalAmount($cs->id, $cs->po), 2) : round( removeVat( $cs->getTotalAmount($cs->id, $cs->po), getVatRate($VATTYPE) ), 2 );	//--	มูลค่าสินค้าไม่รวม VAT (เป็นยอดรวมทั้งบิล) decmal 15,2
		$VATAMT		= $VATISOUT == 'Y' ? round(getVatAmount($AMT, getVatRate( $VATTYPE ), TRUE ), 2) : round(getVatAmount($AMT, getVatRate( $VATTYPE ), FALSE ), 2);	//--	มูลค่าภาษี (เป็นยอดรวมทั้งบิล) decmal 14,2
		$QCCURRENCY	= "";	//--	รหัสหน่วยเงิน
		$XRATE		= 1;	//--	อัตราแลกเปลี่ยนหน่วยเงิน
		$DISCAMTK	= $po->bill_discount;	//--	มูลค่าส่วนลดท้ายบิล เป็นจำนวนเงิน ตามหน่วยเงินที่คีย์
		$AMTKE		= $AMT;	//--	มูลค่าสินค้าไม่รวม VAT (เป็นยอดรวมทั้งบิล) ตามหน่วยเงินที่คีย์
		$VATAMTKE	= $VATAMT;	//-- 	มูลค่าภาษี (เป็นยอดรวมทั้งบิล) ตามหน่วยเงินที่คีย์
		$LOT			= "";
		$QCSECTI		= "";	//--	รหัสแผนก ที่รายการสินค้า (กรณีมี Feature แผนกที่ Item)
		$QCJOBI		= "";	//--	รหัส JOB  ที่รายการสินค้า (กรณีมี Feature Job ที่ Item)
		$REMARKH1	= tis($cs->remark);
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
		$excel =& $workbook->addWorksheet('BI');
		//------- SET Header Row
		$row = 0;
		$excel->writeString($row, 0, 'ERRMSG');
		$excel->writeString($row, 1, 'REFTYPE');
		$excel->writeString($row, 2, 'QCCORP');
		$excel->writeString($row, 3, 'QCBRANCH');
		$excel->writeString($row, 4, 'QCSECT');
		$excel->writeString($row, 5, 'QCJOB');
		$excel->writeString($row, 6, 'STAT');
		$excel->writeString($row, 7, 'QCBOOK');
		$excel->writeString($row, 8, 'CODE');
		$excel->writeString($row, 9, 'REFNO');
		$excel->writeString($row, 10, 'DATE');
		$excel->writeString($row, 11, 'RECEDATE');
		$excel->writeString($row, 12, 'DUEDATE');
		$excel->writeString($row, 13, 'QCCOOR');
		$excel->writeString($row, 14, 'CREDTERM');
		$excel->writeString($row, 15, 'HASRET');
		$excel->writeString($row, 16, 'DETAIL');
		$excel->writeString($row, 17, 'VATISOUT');
		$excel->writeString($row, 18, 'VATTYPE');
		$excel->writeString($row, 19, 'HDISCSTR');
		$excel->writeString($row, 20, 'DISCAMT1');
		$excel->writeString($row, 21, 'AMT');
		$excel->writeString($row, 22, 'VATAMT');
		$excel->writeString($row, 23, 'QCCURRENCY');
		$excel->writeString($row, 24, 'XRATE');
		$excel->writeString($row, 25, 'DISCAMTK');
		$excel->writeString($row, 26, 'AMTKE');
		$excel->writeString($row, 27, 'VATAMTKE');
		$excel->writeString($row, 28, 'QCPROD');
		$excel->writeString($row, 29, 'QCWHOUSE');
		$excel->writeString($row, 30, 'LOT');
		$excel->writeString($row, 31, 'QTY');
		$excel->writeString($row, 32, 'QCUM');
		$excel->writeString($row, 33, 'UMQTY');
		$excel->writeString($row, 34, 'PRICE');
		$excel->writeString($row, 35, 'DISCSTR');
		$excel->writeString($row, 36, 'Priceke');
		$excel->writeString($row, 37, 'DiscAmtIk');
		$excel->writeString($row, 38, 'QCSECTI');
		$excel->writeString($row, 39, 'QCJOBI');
		$excel->writeString($row, 40, 'REMARKH1');
		$excel->writeString($row, 41, 'REMARKH2');
		$excel->writeString($row, 42, 'REMARKH3');
		$excel->writeString($row, 43, 'REMARKH4');
		$excel->writeString($row, 44, 'REMARKH5');
		$excel->writeString($row, 45, 'REMARKH6');
		$excel->writeString($row, 46, 'REMARKH7');
		$excel->writeString($row, 47, 'REMARKH8');
		$excel->writeString($row, 48, 'REMARKH9');
		$excel->writeString($row, 49, 'REMARKH10');
		$excel->writeString($row, 50, 'REMARKI1');
		$excel->writeString($row, 51, 'REMARKI2');
		$excel->writeString($row, 52, 'REMARKI3');
		$excel->writeString($row, 53, 'REMARKI4');
		$excel->writeString($row, 54, 'REMARKI5');
		$excel->writeString($row, 55, 'REMARKI6');
		$excel->writeString($row, 56, 'REMARKI7');
		$excel->writeString($row, 57, 'REMARKI8');
		$excel->writeString($row, 58, 'REMARKI9');
		$excel->writeString($row, 59, 'REMARKI10');
		//------ End Header Row

		//---- Start
		$row++;  //--- start on row 2
		$qs = $cs->getDetail($cs->id);
		if( dbNumRows($qs) > 0 )
		{
			while( $rs = dbFetchObject($qs) )
			{
				$excel->writeString($row, 0, $ERRMSG);
				$excel->writeString($row, 1, $REFTYPE);
				$excel->writeString($row, 2, $QCCORP);
				$excel->writeString($row, 3, $QCBRANCH);
				$excel->writeString($row, 4, $QCSECT);
				$excel->writeString($row, 5, $QCJOB);
				$excel->writeString($row, 6, $STAT);
				$excel->writeString($row, 7, $QCBOOK);
				$excel->writeString($row, 8, $CODE);
				$excel->writeString($row, 9, $REFNO);
				$excel->write($row, 10, $DATE);
				$excel->write($row, 11, $RECEDATE);
				$excel->write($row, 12, $DUEDATE);
				$excel->write($row, 13, $QCCOOR);
				$excel->write($row, 14, $CREDTERM);
				$excel->writeString($row, 15, $HASRET);
				$excel->writeString($row, 16, $DETAIL);
				$excel->writeString($row, 17, $VATISOUT);
				$excel->writeString($row, 18, $VATTYPE);
				$excel->writeString($row, 19, $HDISCSTR);
				$excel->write($row, 20, $DISCAMT1);
				$excel->write($row, 21, $AMT);
				$excel->write($row, 22, $VATAMT);
				$excel->writeString($row, 23, $QCCURRENCY);
				$excel->write($row, 24, $XRATE);
				$excel->write($row, 25, $DISCAMTK);
				$excel->write($row, 26, $AMTKE);
				$excel->write($row, 27, $VATAMTKE);
				$excel->writeString($row, 28, tis($pd->getCode($rs->id_product) ));
				$excel->writeString($row, 29, tis($wh->getCode($rs->id_warehouse)));
				$excel->writeString($row, 30, $LOT);
				$excel->write($row, 31, $rs->qty);
				$excel->writeString($row, 32, tis($pd->getUnitCode($rs->id_product) ));
				$excel->write($row, 33,  1);
				$excel->write($row, 34, $po->getPrice($cs->po, $rs->id_product));
				$excel->writeString($row, 35, tis($po->getDiscount($cs->po, $rs->id_product)));
				$excel->write($row, 36, $po->getPrice($cs->po, $rs->id_product));
				$excel->write($row, 37, $po->getDiscount($cs->po, $rs->id_product));
				$excel->writeString($row, 38, $QCSECTI);
				$excel->writeString($row, 39, $QCJOBI);
				$excel->writeString($row, 40, $REMARKH1);
				$excel->writeString($row, 41, $REMARKH2);
				$excel->writeString($row, 42, $REMARKH3);
				$excel->writeString($row, 43, $REMARKH4);
				$excel->writeString($row, 44, $REMARKH5);
				$excel->writeString($row, 45, $REMARKH6);
				$excel->writeString($row, 46, $REMARKH7);
				$excel->writeString($row, 47, $REMARKH8);
				$excel->writeString($row, 48, $REMARKH9);
				$excel->writeString($row, 49, $REMARKH10);
				$excel->writeString($row, 50, $REMARKI1);
				$excel->writeString($row, 51, $REMARKI2);
				$excel->writeString($row, 52, $REMARKI3);
				$excel->writeString($row, 53, $REMARKI4);
				$excel->writeString($row, 54, $REMARKI5);
				$excel->writeString($row, 55, $REMARKI6);
				$excel->writeString($row, 56, $REMARKI7);
				$excel->writeString($row, 57, $REMARKI8);
				$excel->writeString($row, 58, $REMARKI9);
				$excel->writeString($row, 59, $REMARKI10);
				$row++;
			}
		}

		try
		{
			$workbook->close();
			$sc = TRUE;
			$cs->exported($cs->id);
		}
		catch (Exception $e)
		{
			$sc =  "ERROR : ".$e->getMessage();
		}

		return $sc;
	}


?>
