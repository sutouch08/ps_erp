<?php
	$sc = TRUE;

	//---	Path ของเอกสาร (tbl_config)
	$path	= getConfig('EXPORT_TR_PATH');

	$arr = array(
		'complete' => $path . 'COMPLETE/',
		'error'		 => $path . 'ERROR/',
		'imported' => $path . 'IMPORTED/'
	);

	foreach($arr as $move)
	{
		$dr	= opendir($move);
		if( $dr !== FALSE )
		{
			while( $file = readdir($dr) )
			{
				if( $file == '.' OR $file == '..' )
				{
					continue;
				}
				$fileName	= $move . $file;
				$moveName	= $move . $file;
				try
				{
					unlink($fileName); //---- move each file to another folder
				}
				catch(Exception $e)
				{
					$sc = FALSE;
					$message = $e->getMessage();
				}

			}//--- end while
		} //--- end if
	}


	echo $sc === TRUE ? 'success' : $message;

?>
