<?php
	function exportReturnLendTR($id)
	{
		$sc 	  = FALSE;

		$cs = new return_lend($id);

		//--- ตรวจสอบก่อนว่าบันทึเอกสารแล้วหรือยัง
		if( $cs->isCancle == 0)
		{
			//---	 ถ้าบันทึกแล้ว

			$pd			= new product();
			$zone   = new zone();
			$wh			= new warehouse();
			$emp		= new employee();

			//--------------------  กำหนดค่าตัวแปรที่ต้องมีทุกๆ บรรทัด	-----------------//

			//---	Path ของเอกสาร (tbl_config)
			$path			= getConfig('EXPORT_TR_PATH');

			//---	เว้นว่างไว้เพื่อเติมข้อมูลกรณีที่ formula importing error
			$ERRMSG		= "";

			//---	รหัสประเภทเอกสาร TR = โอนสินค้าข้ามคลัง
			$REFTYPE	= 'TR';

			//---	รหัสบริษัท
			$QCCORP		= getConfig('COMPANY_CODE');

			//---	รหัสสาขา
			$QCBRANCH	= getConfig('BRANCH_CODE');

			//---	รหัสแผนก
			$QCSECT		= tis($emp->getDivisionCode($cs->id_employee));

			//--  รหัสโครงการ (ถ้ามี)
			$QCJOB		= "-";

			//-- 	สถานะของเอกสาร ปกติว่างไว้,  C = CANCEL
			$STAT			= "";

			//--	รหัสเล่มเอกสาร
			$QCBOOK 	= $cs->bookcode;

			//--	เลขที่เอกสาร ถ้าว่างไว้ระบบจะ gen ให้เอง
			$CODE			= "";

			//--	เลขที่อ้างอิง (เลขที่เอกสารใน Smart Invent)
			$REFNO	 	= $cs->reference;

			//---	วันที่ของเอกสาร ต้องเป็นปี ค.ศ. Format DD/MM/YYYY เช่น 20/6/2016
			$DATE			= thaiDate($cs->date_add, '/');


			//---	LOT สินค้า(ถ้ามี)
			$LOT = '';

			//---	หมายเหตุที่หัวเอกสาร
			$REMARKH1	= tis($cs->remark);

			//------------------------ จบกำหนดค่าตัวแปรที่ต้องใส่ในทุกบรรทัด-----------------//

			$file_name = $path.$cs->reference.'-'.date('YmdHis').'.xls';
			$workbook = new Spreadsheet_Excel_Writer($file_name);
			$excel =& $workbook->addWorksheet('TR');

			//------- SET Header Row
			$row = 0;
			$excel->writeString($row, 0, 'ERRMSG');

			//---	รหัสประเภทเอกสาร TR = โอนสินค้าข้ามคลัง
			$excel->writeString($row, 1, 'REFTYPE');

			//---	รหัสบริษัท
			$excel->writeString($row, 2, 'QCCORP');

			//---	รหัสสาขา
			$excel->writeString($row, 3, 'QCBRANCH');

			//---	สถานะของเอกสาร ปกติว่างไว้,  C = CANCEL
			$excel->writeString($row, 4, 'STAT');

			//---	รหัสเล่มเอกสาร
			$excel->writeString($row, 5, 'QCBOOK');

			//---	เลขที่เอกสาร ว่างไว้ formula จะ Gen ให้เอง
			$excel->writeString($row, 6, 'CODE');

			//---	เลขเอกสารใน Smart Invent (เลขที่อ้างอิงฝั่ง formula)
			$excel->writeString($row, 7, 'REFNO');

			//---	วันที่ของเอกสาร ต้องเป็นปี ค.ศ. Format DD/MM/YYYY เช่น 20/6/2016
			$excel->writeString($row, 8, 'DATE');

			//---	รหัสคลังต้นทาง
			$excel->writeString($row, 9, 'QCFRWHOUSE');

			//---	รหัสคลังปลายทาง
			$excel->writeString($row, 10, 'QCTOWHOUSE');

			//---	รหัสแผนก
			$excel->writeString($row, 11, 'QCSECT');

			//---	รหัสโครงการ(ถ้ามี ไม่มีเว้นว่างไว้)
			$excel->writeString($row, 12, 'QCJOB');

			//---	หมายเหตุที่หัวเอกสาร
			$excel->writeString($row, 13, 'REMARKH1');

			//---	รหัสสินค้า
			$excel->writeString($row, 14, 'QCPROD');

			//---	LOT ของสินค้า (ไม่มีเว้นว่างไว้)
			$excel->writeString($row, 15, 'LOT');

			//---	จำนวนสินค้า
			$excel->writeString($row, 16, 'QTY');

			//---	รหัสหน่วยนับ
			$excel->writeString($row, 17, 'QCUM');

			//---	อัตราส่วน หน่วยนับ(QCUM) / หน่วยมาตรฐานในฐานข้อมูลสินค้า
			$excel->writeString($row, 18, 'UMQTY');

			//---	ต้นทุน/หน่วย
			$excel->writeString($row, 19, 'COST');

			//---	หมายเหตุที่ตัวสินค้า
			$excel->writeString($row, 20,  'REMARKI1');


			//------ End Header Row

			//---- Start
			$row = 1;  //--- start on row 2
			$qs = $cs->getDetails($cs->id);
			if( dbNumRows($qs) > 0 )
			{
				while( $rs = dbFetchObject($qs) )
				{
					//--- id warehouse ต้นทาง
					$fWarehouse = $zone->getWarehouseId($rs->from_zone);

					//--- id warehouse ปลายทาง
					$tWarehouse = $zone->getWarehouseId($rs->to_zone);

					//---	เว้นว่างเพื่อให้ formula ใส่ข้อมูลการนำเข้าที่ผิดพลาด
					$excel->writeString($row, 0, $ERRMSG);

					//---	รหัสประเภทเอกสาร
					$excel->writeString($row, 1, $REFTYPE);

					//---	รหัสบริษัท
					$excel->writeString($row, 2, $QCCORP);

					//---	รหัสสาขา
					$excel->writeString($row, 3, $QCBRANCH);

					//---	สถานะเอกสาร ปกติ = ว่างไว้ , C = CANCLE
					$excel->writeString($row, 4, $STAT);

					//---	รหัสเล่มเอกสาร
					$excel->writeString($row, 5, $QCBOOK);

					//---	เลขที่เอกสารใน formula ว่างไว้ formula จะ GEN ให้เอง
					$excel->writeString($row, 6, $CODE);

					//---	เลขที่เอกสารใน Smart Invent
					$excel->writeString($row, 7, $REFNO);

					//---	วันที่เอกสาร
					$excel->write($row, 8, $DATE);

					//---	รหัสคลังต้นทาง
					$excel->writeString($row, 9, tis($wh->getCode($fWarehouse)));

					//---	รหัสคลังปลายทาง
					$excel->writeString($row, 10, tis($wh->getCode($tWarehouse)));

					//---	รหัสแผนก
					$excel->writeString($row, 11, $QCSECT);

					//---	รหัสโครงการ(ถ้ามี)
					$excel->writeString($row, 12, $QCJOB);

					//---	หมายเหตุที่หัวเอกสาร
					$excel->writeString($row, 13, $REMARKH1);

					//---	รหัสสินค้า(SKU)
					$excel->writeString($row, 14, tis($rs->product_code));

					//---	LOT สินค้า(ถ้ามี)
					$excel->writeString($row, 15, $LOT);

					//---	จำนวนสินค้า
					$excel->write($row, 16, $rs->qty);

					//---	รหัสหน่วยนับ
					$excel->writeString($row, 17, tis($pd->getUnitCode($rs->id_product)));

					//---	อัตราส่วนหน่ายนับ
					$excel->write($row, 18, 1);

					//---	ต้นทุน/หน่วย
					$excel->write($row, 19, $pd->getCost($rs->id_product));

					//---	หมายเหตุที่ตัวสินค้า
					$excel->writeString($row, 20, '');

					$row++;

				}
			}


			try
			{
				$workbook->close();
				$sc = TRUE;
			}
			catch (Exception $e)
			{
				$sc = "ERROR : ".$e->getMessage();
			}
		}
		else
		{
			$sc = 'ยังไม่ได้บันทึกเอกสาร';
		}

		return $sc;
	}


?>
