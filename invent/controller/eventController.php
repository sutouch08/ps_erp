<?php
require '../../library/config.php';
require '../../library/functions.php';
require '../function/tools.php';



if(isset($_GET['getEvent']))
{
  $sc = array();
  $cs = new event();
  $qs = $cs->search($_REQUEST['term']);
  if( dbNumRows($qs) > 0)
  {
    while($rs = dbFetchObject($qs))
    {
      $sc[] = $rs->code.' | '.$rs->name.' | '.$rs->id;
    }
  }
  else
  {
    $sc[] = 'ไม่พบงานขาย';
  }

  echo json_encode($sc);
}

 ?>
