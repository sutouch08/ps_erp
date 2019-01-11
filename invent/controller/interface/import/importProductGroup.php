<?php

	$sc = TRUE;
	$import = 0;
	$update = 0;
	$error  = 0;

	$path		= getConfig('IMPORT_PRODUCT_GROUP_PATH');
	$move		= getConfig('MOVE_PRODUCT_GROUP_PATH');

	$dr 	= opendir($path);
	if( $dr )
	{
		while( $file = readdir($dr) )
		{
			if( $file == '.' OR $file == '..' )
			{
				continue;
			}//--- end if

			$fileName 	= $path . $file;
			$moveName	= $move . $file;
			$reader		= new PHPExcel_Reader_Excel5();
			$excel		= $reader->load($fileName);
			$collection	= $excel->getActiveSheet()->toArray(NULL, TRUE, TRUE, TRUE);
			$i = 1;

			$cs = new product_group();
			foreach( $collection as $rs )
			{
				if( $i > 1 )  //--- Skip first row
				{
					$id = $rs['A'];

					if( $cs->isExists($id) === FALSE )
					{
						//---- If not exists do insert
						$arr = array(
									"id"			=> $id,
									"code"	=> $rs['B'],
									"name"	=> addslashes($rs['C'])
									);
						if( $cs->add($arr) === FALSE )
						{
							$sc = FALSE;
							$message = 'เพิ่มข้อมูลไม่สำเร็จ';
							$error++;
							writeErrorLogs('Product Group', $cs->error);
						}

					}
					else
					{
						//--- if exists do update
						$arr = array(
									"code"	=> $rs['B'],
									"name"	=> addslashes($rs['C'])
									);

						if( $cs->update($id, $arr) === FALSE )
						{
							$sc = FALSE;
							$message = 'ปรับปรุงข้อมูลไม่สำเร็จ';
							$error++;
							writeErrorLogs('Product Group', $cs->error);
						}


					}//-- end if;
				}//--- end if;
				$i++;
			}//-- end foreach

			rename($fileName, $moveName); //-- move each file to another folder
		} //-- end while

	}//-- end if
	else
	{
		$sc = FALSE;
		$message = "Can not open folder please check connection";
	}

	writeImportLogs('พนักงานขาย', $import, $update, $error);

	echo $sc === TRUE ? 'success' : $message;

?>
