<?php

function writeImportLogs($name, $import = 0, $update = 0, $error = 0, $last = '')
{
  $content  = date('Y-m-d  H:i:s').'  :  '. 'นำเข้า '. $name;
  $content .= ' จาก  FORMULA';
  $content .= '  >> นำเข้า  :  '.$import.'  รายการ';
  $content .= '  >> ปรับปรุง  :  '.$update.'  รายการ';
  $content .= '  >> ผิดพลาด  :  '.$error. '  รายการ';
  $content .= PHP_EOL;
  if( $last == 'last')
  {
    $content .= '###################################################################################################'. PHP_EOL;
  }
  $path = getConfig('IMPORT_LOG_PATH');
  $fileName = $path.'ImportLogs-'.date('Ymd').'.LOG';
  file_put_contents($fileName, $content, FILE_APPEND);
}



function writeErrorLogs($name, $error)
{
  $content  = date('Y / m / d  H : i : s').'  :  '. 'นำเข้า '. $name;
  $content .= ' จาก  FORMULA';
  $content .= '  >> ผิดพลาด  :  '.$error;
  $content .= PHP_EOL;

  $path = getConfig('IMPORT_LOG_PATH');
  $fileName = $path.'ErrorLogs-'.date('Ymd').'.LOG';
  file_put_contents($fileName, $content, FILE_APPEND);
}

?>
