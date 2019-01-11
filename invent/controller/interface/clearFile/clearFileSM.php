<?php

	$sc = TRUE;

	//---	Path ของไฟล์ที่จะย้ายไปเก็บเมื่อ import เสร็จแล้ว
	$move	= getConfig('MOVE_SM_PATH');

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

	echo $sc === TRUE ? 'success' : $message;
?>
