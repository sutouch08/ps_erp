<?php

	$sc = TRUE;
	$import = 0;
	$update = 0;
	$error  = 0;
	$path		= getConfig('IMPORT_BRAND_PATH');
	$move		= getConfig('MOVE_BRAND_PATH');

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

			$cs	= new brand();

			$i 	= 1;
			foreach ( $collection as $rs )
			{
				if( $i != 1 ) //---- Skip first row
				{
					$id 		= $rs['A'];
					if( $cs->isExists( $id ) === FALSE )
					{
						$import++;
						//-- If not exists do insert
						$arr = array(
								'id'					=> $id,
								'code'				=> $rs['B'],
								'name'				=> $rs['C']
								);
						if($cs->add($arr) === FALSE)
						{
							$sc = FALSE;
							$message = 'เพิ่มข้อมูลไม่สำเร็จ';
							$error++;
							writeErrorLogs('Brand', $cs->error);
						}
					}
					else
					{
						$update++;
						//--- If exists do update
						$arr = array(
								'code'				=> $rs['B'],
								'name'				=> $rs['C']
								);

						if($cs->update($id, $arr) === FALSE)
						{
							$sc = FALSE;
							$message = 'ปรับปรุงข้อมูลไม่สำเร็จ';
							$error++;
							writeErrorLogs('Brand', $cs->error);
						}
					}	/// end if
				}//-- end if not first row
				$i++;
			}//---- end foreach
			rename($fileName, $moveName); //---- move each file to another folder
		}//--- end while
	} //--- end if
	else
	{
		$sc = FALSE;
		$message = "Can not open folder please check connection";
	}


	writeImportLogs('ยี่ห้อสินค้า', $import, $update, $error);

	echo $sc === TRUE ? 'success' : $message;

?>
