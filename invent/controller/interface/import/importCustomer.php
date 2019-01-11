<?php

	$sc = TRUE;
	$import = 0;
	$update = 0;
	$error  = 0;

	$path		= getConfig('IMPORT_CUSTOMER_PATH');
	$move		= getConfig('MOVE_CUSTOMER_PATH');

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

			$cs	= new customer();
			$cg			= new customer_group();
			$ca			= new customer_area();
			$sale			= new sale();

			$i 	= 1;
			foreach ( $collection as $rs )
			{
				if( $i != 1 ) //---- Skip first row
				{
					$id 		= $rs['A'];
					$active 	= $rs['J'] == '' ? 1 : 0;

					$arr = array(
							'id'					=> $id,
							'code'				=> $rs['B'],
							'name'				=> addslashes($rs['C']),
							'address1'		=> addslashes($rs['D']),
							'address2'		=> addslashes($rs['E']),
							'address3'		=> addslashes($rs['F']),
							'tel'					=> $rs['H'],
							'fax'					=> $rs['I'],
							'm_id'				=> $rs['L'],
							'tax_id'			=> $rs['M'],
							'contact'			=> addslashes($rs['AF']),
							'email'				=> $rs['X'],
							'id_group'		=> $cg->getGroupId( $rs['Y'] ),
							'id_area'			=> $ca->getAreaId( $rs['AA'] ),
							'id_sale'			=> $sale->getSaleId( $rs['AT'] ),
							'credit'			=> $rs['AC'],
							'term'				=> $rs['AE'],
							'address_no'	=> addslashes($rs['AG']),
							'room_no'			=> addslashes($rs['AI']),
							'floor_no'		=> addslashes($rs['AH']),
							'building'		=> addslashes($rs['AJ']),
							'village_no'	=> addslashes($rs['AK']),
							'soi'				  => addslashes($rs['AL']),
							'road'				=> addslashes($rs['AM']),
							'tambon'			=> addslashes($rs['AN']),
							'amphur'			=> addslashes($rs['AO']),
							'province'		=> addslashes($rs['AP']),
							'zip'				  => $rs['G'],
							'active'			=> $active
							);
					if( $cs->isExists( $id ) === FALSE )
					{
						//-- If not exists do insert
						$import++;
						if($cs->add($arr) === FALSE)
						{
							$sc = FALSE;
							$message = 'เพิ่มข้อมูลไม่สำเร็จ';
							$error++;
							writeErrorLogs('Customer', $cs->error);
						}
					}
					else
					{
						//--- If exists do update
						unset($arr['id']);
						$update++;
						if($cs->update($id, $arr) === FALSE)
						{
							$sc = FALSE;
							$message = 'ปรับปรุงข้อมูลไม่สำเร็จ';
							$error++;
							writeErrorLogs('Customer', $cs->error);
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

	writeImportLogs('ลูกค้า', $import, $update, $error);

	echo $sc === TRUE ? 'success' : $message;

?>
