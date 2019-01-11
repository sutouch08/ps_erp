<?php
function showTransferStatus(array $status = array())
{
  $sc = '';
  if( ! empty($status))
  {
    if( $status['isExport'] == 0)
    {
      $sc = '<span class="red">NE</span>';
    }

    if( $status['isSaved'] == 0)
    {
      $sc = '<span class="red">NC</span>';
    }

    if( $status['isCancle'] == 1)
    {
      $sc = '<span class="red">CN</span>';
    }
  }

  return $sc;
}
 ?>
