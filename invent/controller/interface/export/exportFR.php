<?php
	//---	$id = id_receive_transform
	function exportFR($id)
	{

		$sc 	= FALSE;
		$cs 	= new receive_transform($id);
		$tr   = new transform($cs->id_order);
		$zone = new zone();

		$wh		= new warehouse();
		$emp	= new employee();

		//---	Export path
		$path		  = getConfig('EXPORT_FR_PATH');

		//---	เว้นว่างไว้
		$ERRMSG		= "";

		//--	ประเภทเอกสาร  FR = รับสินค้าจากการผลิต
		$REFTYPE	= 'FR';

		//-- รหัสบริษัท
		$QCCORP		= getConfig('COMPANY_CODE');

		//-- รหัสสาขา
		$QCBRANCH	= getConfig('BRANCH_CODE');

		//-- รหัสแผนก
		$QCSECT		= tis($emp->getDivisionCode($cs->id_employee));

		//--  รหัสโครงการ (ถ้ามี)
		$QCJOB		= "";

		//-- 	สถานะของเอกสาร ปกติว่างไว้,  C = CANCEL
		$STAT		  = "";

		//--	รหัสเล่มเอกสาร
		$QCBOOK 	= $cs->bookcode;

		//--	เลขที่เอกสาร ถ้าว่างไว้ระบบจะ gen ให้เอง
		$CODE			= "";

		//--	เลขที่อ้างอิง (เลขที่เอกสารใน Smart Invent)
		$REFNO	 	= $cs->reference;

		//--	วันที่ของเอกสาร
		$DATE			= thaiDate($cs->date_add, '/');

		//---	รหัสคลังต้นทาง (ประเภทคลังระหว่างทำ)
		$QCFRWHOUSE = tis($wh->getCode( $zone->getWarehouseId($tr->id_zone)));

		//--- LOT สินค้า
		$LOT 		= "";

		//--- หมายเหตุที่หัวเอกสาร
		$REMARKH1	= tis($cs->remark);

		//---	หมายเหตุที่รายการสินค้า
		$REMARKI1	= "";

		$file_name = $path.$cs->reference.'-'.date('YmdHis').".xls";
		$workbook	= new Spreadsheet_Excel_Writer($file_name);
		$excel =& $workbook->addWorksheet('FR');


		//------- SET Header Row
		$row = 0;
		$excel->writeString($row, 0, 'ERRMSG');
		$excel->writeString($row, 1, 'REFTYPE');
		$excel->writeString($row, 2, 'QCCORP');
		$excel->writeString($row, 3, 'QCBRANCH');
		$excel->writeString($row, 4, 'STAT');
		$excel->writeString($row, 5, 'QCBOOK');
		$excel->writeString($row, 6, 'CODE');
		$excel->writeString($row, 7, 'REFNO');
		$excel->writeString($row, 8, 'DATE');
		$excel->writeString($row, 9, 'QCFRWHOUSE');
		$excel->writeString($row, 10, 'QCTOWHOUSE');
		$excel->writeString($row, 11, 'QCSECT');
		$excel->writeString($row, 12, 'QCJOB');
		$excel->writeString($row, 13, 'REMARKH1');
		$excel->writeString($row, 14, 'QCPROD');
		$excel->writeString($row, 15, 'LOT');
		$excel->writeString($row, 16, 'QTY');
		$excel->writeString($row, 17, 'QCUM');
		$excel->writeString($row, 18, 'UMQTY');
		$excel->writeString($row, 19, 'COST');
		$excel->writeString($row, 20, 'REMARKI1');

		//------ End Header Row

		//------------------------- Start
		//--- start @ row 2
		$row = 1;

		//---	รายการที่รับเข้า
		$qs = $cs->getDetail($cs->id);

		if( dbNumRows($qs) > 0 )
		{
			while( $rs = dbFetchObject($qs) )
			{
				$pd		= new product($rs->id_product);

				$excel->writeString($row, 0, $ERRMSG);

				//---	ประเภทเอกสาร FR = รับสินค้าจากการผลิต
				$excel->writeString($row, 1, $REFTYPE);

				//---	รหัสบริษัท
				$excel->writeString($row, 2, $QCCORP);

				//---	รหัสสาขา
				$excel->writeString($row, 3, $QCBRANCH);

				//---	สถานะของเอกสาร ปกติว่างไว้,  C = CANCEL
				$excel->writeString($row, 4, $STAT);

				//---	รหัสเล่มเอกสาร
				$excel->writeString($row, 5, $QCBOOK);

				//--- รหัสเอกสาร(เว้นว่างไว้ ไป gen ที่ formula)
				$excel->writeString($row, 6, $CODE);

				//---	เลขที่เอกสารจาก smart invent
				$excel->writeString($row, 7, $REFNO);

				//---	วันที่เอกสาร
				$excel->write($row, 8, $DATE);

				//---	รหัสคลังต้นทาง (คลังระหว่างทำ)
				$excel->writeString($row, 9, $QCFRWHOUSE);

				//---	รหัสคลังปลายทาง (คลังซื้อขาย)
				$excel->writeString($row, 10, tis($wh->getCode($rs->id_warehouse)));

				//---	รหัสแผนก
				$excel->writeString($row, 11, tis($QCSECT));

				//---	รหัสโครงการ(ถ้ามี)
				$excel->writeString($row, 12, tis($QCJOB));

				//---	หมายเหตุที่หัวเอกสาร
				$excel->writeString($row, 13, $REMARKH1);

				//---	รหัสสินค้า
				$excel->writeString($row, 14, tis($pd->code));

				//---	LOT สินค้า
				$excel->writeString($row, 15, $LOT);

				//---	จำนวนสินค้า
				$excel->write($row, 16, $rs->qty);

				//---	รหัสหน่วยนับ
				$excel->writeString($row, 17, tis($pd->getUnitCode($rs->id_product)));

				//---	ตัวแปลงหน่วยนับ
				$excel->write($row, 18, 1);

				//---	ต้นทุน/หน่วย
				$excel->write($row, 19, $pd->cost);

				//---	หมายเหตุที่ตัวสินค้า
				$excel->writeString($row, 20, $REMARKI1);

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
