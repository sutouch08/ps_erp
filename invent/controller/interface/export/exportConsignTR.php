<?php
	function exportConsignTR($id_order)
	{
		$sc 	= FALSE;
		$order 	= new order($id_order);
		$cs     = new order_consign($order->id);
		$zone 	= new zone($cs->id_zone);
		$pd			= new product();
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
		$QCSECT		= tis($emp->getDivisionCode($order->id_employee));

		//--  รหัสโครงการ (ถ้ามี)
		$QCJOB		= "";

		//-- 	สถานะของเอกสาร ปกติว่างไว้,  C = CANCEL
		$STAT			= "";

		//--	รหัสเล่มเอกสาร
		$QCBOOK 	= $order->bookcode;

		//--	เลขที่เอกสาร ถ้าว่างไว้ระบบจะ gen ให้เอง
		$CODE			= "";

		//--	เลขที่อ้างอิง (เลขที่เอกสารใน Smart Invent)
		$REFNO	 	= $order->reference;

		//---	วันที่ของเอกสาร ต้องเป็นปี ค.ศ. Format DD/MM/YYYY เช่น 20/6/2016
		$DATE			= thaiDate($order->date_add, '/');

		//---	รหัสคลังปลายทาง
		$QCTOWHOUSE = tis($wh->getCode($zone->id_warehouse));

		//---	LOT สินค้า(ถ้ามี)
		$LOT = '';

		//---	หมายเหตุที่หัวเอกสาร
		$REMARKH1	= tis($order->remark);

		//------------------------ จบกำหนดค่าตัวแปรที่ต้องใส่ในทุกบรรทัด-----------------//
		$file_name = $path.$order->reference.'-'.date('YmdHis').".xls";
		$workbook	= new Spreadsheet_Excel_Writer($file_name);
		$excel =& $workbook->addWorksheet('TR');

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

		//---- Start
		$row = 1;  //--- start on row 2
		$qs = $order->getSoldDetails($order->id);
		if( dbNumRows($qs) > 0 )
		{
			while( $rs = dbFetchObject($qs) )
			{
				$excel->writeString($row, 0, $ERRMSG);
				$excel->writeString($row, 1, $REFTYPE);
				$excel->writeString($row, 2, $QCCORP);
				$excel->writeString($row, 3, $QCBRANCH);
				$excel->writeString($row, 4, $STAT);
				$excel->writeString($row, 5, $QCBOOK);
				$excel->writeString($row, 6, $CODE);
				$excel->writeString($row, 7, $REFNO);
				$excel->write($row, 8, $DATE);
				$excel->writeString($row, 9, tis($wh->getCode($rs->id_warehouse)));
				$excel->writeString($row, 10, $QCTOWHOUSE);
				$excel->writeString($row, 11, $QCSECT);
				$excel->writeString($row, 12, $QCJOB);
				$excel->writeString($row, 13, $REMARKH1);
				$excel->writeString($row, 14, tis($rs->product_code));
				$excel->writeString($row, 15, $LOT);
				$excel->write($row, 16, $rs->qty);
				$excel->writeString($row, 17, ($pd->getUnitCode($rs->id_product)));
				$excel->write($row, 18, 1);
				$excel->writeString($row, 19, $rs->cost_inc );
				$excel->writeString($row, 20, '');
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
