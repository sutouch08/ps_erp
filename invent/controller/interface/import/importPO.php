<?php

	$sc = TRUE;
	$import = 0;
	$update = 0;
	$error  = 0;
	$path		= getConfig('IMPORT_PO_PATH');
	$move	= getConfig('MOVE_PO_PATH');

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
			$es 			= new PHPExcel();
			$reader		= new PHPExcel_Reader_Excel5();

			$excel		= $reader->load($fileName);
			$collection	= $excel->getActiveSheet()->toArray(NULL, TRUE, TRUE, TRUE);

			$cs 	= new po();
			$sp	= new supplier();
			$wh	= new warehouse();
			$pd	= new product();
			$i 	= 1;
			foreach ( $collection as $rs )
			{
				if( $i != 1 )
				{
					$bookcode 	= $rs['G'];
					$reference	= $rs['I'];
					$product		= $rs['AA'];
					$qty 				= $rs['AD'];
					$vat_amount = $rs['U'];
					$amount_ex  = $rs['T'];
					$id_pd		= $pd->getId($product);
					$id_style		= $pd->getStyleId($id_pd);
					$isCancle	= $rs['F'] == "C" ? 1 : 0;
					if( $cs->isExists($bookcode, $reference, $id_pd) === FALSE )
					{
						$arr = array(
											"bookcode"			=> $bookcode,
											"code"					=> $rs['H'],
											"reference"			=> $reference,
											"id_supplier"		=> $sp->getId( $rs['M'] ),
											"id_warehouse"	=> $wh->getId( $rs['AB'] ),
											"credit_term"		=> $rs['O'],
											"vat_type"			=> $rs['Q'],
											"vat_is_out"		=> $rs['P'],
											"vat_amount"		=> $rs['U'],
											"amount_ex"			=> $rs['T'],
											"bill_discount"	=> $rs['S'],
											"date_add"			=> fmDate($rs['J']),
											"date_need"			=> fmDate($rs['K']),
											"due_date"			=> fmDate($rs['L']),
											"id_style"			=> $id_style,
											"id_product"		=> $id_pd,
											"pd_code"       => $product,
											"price"					=> $rs['AG'],
											"discount"			=> $rs['AH'],
											"qty"						=> $rs['AD'],
											"unit_code"			=> $rs['AE'],
											"unit_qty"			=> $rs['AF'],
											"isCancle"			=> $isCancle
										);
								$import++;
								if($cs->add($arr) === FALSE)
								{
									$sc = FALSE;
									$message = 'เพิ่มข้อมูลไม่สำเร็จ';
									$error++;
									writeErrorLogs('PO', $cs->error);
								}
					}
					else
					{
						if($cs->isChanged($bookcode, $reference, $id_pd, $qty, $vat_amount, $amount_ex) === TRUE )
						{
							$arr = array(
												"id_supplier"		=> $sp->getId( $rs['M'] ),
												"id_warehouse"	=> $wh->getId( $rs['AB'] ),
												"credit_term"		=> $rs['O'],
												"vat_type"			=> $rs['Q'],
												"vat_is_out"		=> $rs['P'],
												"vat_amount"		=> $rs['U'],
												"amount_ex"			=> $rs['T'],
												"bill_discount"	=> $rs['S'],
												"date_add"			=> fmDate($rs['J']),
												"date_need"			=> fmDate($rs['K']),
												"due_date"			=> fmDate($rs['L']),
												"id_style"			=> $id_style,
												"id_product"    => $id_pd,
												"pd_code"       => $product,
												"price"					=> $rs['AG'],
												"discount"			=> $rs['AH'],
												"qty"						=> $rs['AD'],
												"unit_code"			=> $rs['AE'],
												"unit_qty"			=> $rs['AF'],
												"isCancle"			=> $isCancle
											);
								$update++;
								if($cs->update($bookcode, $reference, $id_pd, $arr) === FALSE)
								{
									$sc = FALSE;
									$message = 'เพิ่มข้อมูลไม่สำเร็จ';
									$error++;
									writeErrorLogs('PO', $cs->error);
								}
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

	writeImportLogs('ใบสั่งซื้อ', $import, $update, $error);

	echo $sc === TRUE ? 'success' : $message;

?>
