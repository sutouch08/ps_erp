<?php
  $id_order = $_GET['id_order'];
  $id_box		= $_GET['id_box'];
  $qc 			= new qc();
  $qs 			= $qc->getBoxList($id_order);

  if( dbNumRows($qs) > 0)
  {
    $ds = array();
    while( $rs = dbFetchObject($qs))
    {
      $arr = array(
            "no" 			=> $rs->box_no,
            "id_box" 	=> $rs->id_box,
            "qty"			=> number($rs->qty),
            "class" 	=> $rs->id_box == $id_box ? 'btn-success' : 'btn-default'
        );
      array_push($ds, $arr);
    }

    $sc = json_encode($ds);
  }
  else
  {
    $sc = "no box";
  }
  echo $sc;

 ?>
