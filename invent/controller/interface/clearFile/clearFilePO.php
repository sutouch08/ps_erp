<?php

	$sc = TRUE;

	$move	= getConfig('MOVE_PO_PATH');

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
