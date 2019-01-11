<?php

function selectBranch($se = '')
{
  $sc = '';
  $qs = dbQuery("SELECT * FROM tbl_branch");
  while($rs = dbFetchObject($qs))
  {
    $sc .= '<option value="'.$rs->id.'" '.isSelected($se, $rs->id).' >'.$rs->name.'</option>';
  }

  $sc .= '<option value="0" '.($se === '0' ? 'selected': '').'>ไม่ระบุ</option>';

  return $sc;
}


function getBranchCode($id)
{
  $sc = '';
  $qs = dbQuery("SELECT code FROM tbl_branch WHERE id = '".$id."'");
  if(dbNumRows($qs) == 1)
  {
    list($sc) = dbFetchArray($qs);
  }

  return $sc;
}



function getBranchName($id)
{
  $sc = 'ไม่ระบุ';
  $qs = dbQuery("SELECT name FROM tbl_branch WHERE id = '".$id."'");
  if(dbNumRows($qs) == 1)
  {
    list($sc) = dbFetchArray($qs);
  }

  return $sc;
}

 ?>
