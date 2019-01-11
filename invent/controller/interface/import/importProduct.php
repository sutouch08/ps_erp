<?php

	$sc = TRUE;
	$import = 0;
	$update = 0;
	$error  = 0;
	$path		= getConfig('IMPORT_PRODUCT_PATH');
	$move	= getConfig('MOVE_PRODUCT_PATH');

	$dr	= opendir($path);
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
			$reader		= new PHPExcel_Reader_Excel5();
			$excel		= $reader->load($fileName);
			$collection	= $excel->getActiveSheet()->toArray(NULL, TRUE, TRUE, TRUE);

			$cs 	= new product();
			$pg 	= new product_group();
			$co 	= new color();
			$si 	= new size();
			$bd		= new brand();
			$st 	= new style();
			$un		= new unit();

			$i 	= 1;
			foreach ( $collection as $rs )
			{
				if( $i != 1 )
				{
					$id 		= $rs['A'];
					$count_stock	= $rs['G'] == 3 ? 0 : 1;
					$active	= $rs['E'] == 'I' ? 0 : 1;
					if( $cs->isExists($id) === FALSE )
					{
						$arr = array(
											"id"				=> $id,
											"code"			=> $rs['B'],
											"name"			=> addslashes($rs['C']),
											"id_style"	=> $st->getStyleId( $rs['J'] ),
											"id_color"	=> $co->getColorId( $rs['L'] ),
											"id_size"		=> $si->getSizeId( $rs['K'] ),
											"id_group"	=> $pg->getProductGroupId( $rs['F'] ),
											"id_brand"	=> $bd->getBrandId( $rs['I'] ),
											"cost"			=> $rs['D'],
											"price"			=> $rs['M'],
											"id_unit"		=> $un->getUnitId( $rs['H'] ),
											"count_stock"	=> $count_stock,
											"active"		=> $active
										);

						$import++;
						if($cs->add($arr) === FALSE)
						{
							$sc = FALSE;
							$message = 'เพิ่มข้อมูลไม่สำเร็จ';
							$error++;
							writeErrorLogs('Product', $cs->error);
						}
					}
					else
					{
						$arr = array(
											"code"			=> $rs['B'],
											"name"			=> addslashes($rs['C']),
											"id_style"	=> $st->getStyleId( $rs['J'] ),
											"id_color"	=> $co->getColorId( $rs['L'] ),
											"id_size"		=> $si->getSizeId( $rs['K'] ),
											"id_group"	=> $pg->getProductGroupId( $rs['F'] ),
											"id_brand"	=> $bd->getBrandId( $rs['I'] ),
											"cost"			=> $rs['D'],
											"price"			=> $rs['M'],
											"id_unit"		=> $un->getUnitId( $rs['H'] ),
											"count_stock"	=> $count_stock,
											"active"		=> $active
										);

						$update++;
						if($cs->update($id, $arr) === FALSE)
						{
							$sc = FALSE;
							$message = 'ปรับปรุงข้อมูลไม่สำเร็จ';
							$error++;
							writeErrorLogs('Product', $cs->error);
						}

					}//---- end if exists
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

	writeImportLogs('สินค้า', $import, $update, $error);

	echo $sc === TRUE ? 'success' : $message;

?>
